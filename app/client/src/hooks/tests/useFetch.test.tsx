import { renderHook, act } from '@testing-library/react';
import { describe, it, expect, vi, afterEach, Mock } from 'vitest';
import useFetch from '../useFetch';

// Mock global fetch
global.fetch = vi.fn();

describe('useFetch', () => {
  const API_URL = import.meta.env.VITE_API_URL || '';

  afterEach(() => {
    vi.clearAllMocks();
  });

  it('should fetch data successfully', async () => {
    const mockResponse = { foo: 'bar' };
    (fetch as Mock).mockResolvedValueOnce({
      ok: true,
      json: async () => mockResponse,
    });

    const { result } = renderHook(() => useFetch());
    let data;
    await act(async () => {
      data = await result.current.fetchData({ url: '/test' });
    });
    expect(fetch).toHaveBeenCalledWith(`${API_URL}/test`, expect.any(Object));
    expect(data).toEqual(mockResponse);
  });

  it('should throw error on failed fetch', async () => {
    (fetch as Mock).mockResolvedValueOnce({
      ok: false,
      status: 500,
      json: async () => ({}),
    });
    const { result } = renderHook(() => useFetch());
    await expect(result.current.fetchData({ url: '/fail' })).rejects.toThrow('HTTP error! status: 500');
  });
});
