import { renderHook, act, waitFor } from '@testing-library/react';
import { describe, it, expect, vi, afterEach } from 'vitest';
import useFetch from '../useFetch';

// Mock global fetch
const mockFetch = vi.fn();
(globalThis as typeof globalThis).fetch = mockFetch;

describe('useFetch', () => {
  const API_URL = import.meta.env.VITE_API_URL || '';

  afterEach(() => {
    vi.clearAllMocks();
  });

  it('fetches data successfully (manual)', async () => {
    const mockResponse = { foo: 'bar' };
    mockFetch.mockResolvedValueOnce({
      ok: true,
      text: async () => JSON.stringify(mockResponse),
    });

    const { result } = renderHook(() => useFetch());
    let data;
    await act(async () => {
      data = await result.current.fetchData({ url: '/test' });
    });
    expect(mockFetch).toHaveBeenCalledWith(`${API_URL}/test`, expect.any(Object));
    expect(data).toEqual(mockResponse);
    expect(result.current.data).toEqual(mockResponse);
    expect(result.current.error).toBeNull();
    expect(result.current.loading).toBe(false);
  });

  it('handles fetch error (manual)', async () => {
    mockFetch.mockResolvedValueOnce({
      ok: false,
      status: 500,
      json: async () => ({ message: 'Internal Server Error' }),
    });
    const { result } = renderHook(() => useFetch());
    await expect(result.current.fetchData({ url: '/fail' })).rejects.toThrow('Internal Server Error');
    await act(async () => {
      // Wait for the fetch to complete
    });
    expect(result.current.data).toBeNull();
    expect(result.current.error).not.toBeNull();
    expect(result.current.loading).toBe(false);
  });

  it('auto-fetches data on mount', async () => {
    const mockResponse = { hello: 'world' };
    mockFetch.mockResolvedValueOnce({
      ok: true,
      text: async () => JSON.stringify(mockResponse),
    });
    const { result } = renderHook(() => useFetch('/auto'));
    await waitFor(() => {
      expect(result.current.loading).toBe(false);
      expect(result.current.data).toEqual(mockResponse);
    });
    expect(mockFetch).toHaveBeenCalledWith(`${API_URL}/auto`, expect.any(Object));
    expect(result.current.error).toBeNull();
  });
});