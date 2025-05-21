import { useState, useCallback, useEffect } from "react";

const API_URL = import.meta.env.VITE_API_URL;

interface FetchParams<T = unknown> {
  url: string;
  method?: string;
  body?: T;
}

function useFetch<TResponse = unknown, TBody = undefined>(
  autoUrl?: string,
  autoOptions?: Omit<FetchParams<TBody>, "url">
) {
  const [data, setData] = useState<TResponse | null>(null);
  const [error, setError] = useState<Error | null>(null);
  const [loading, setLoading] = useState(false);

  const fetchData = useCallback(
    async (params: FetchParams<TBody>): Promise<TResponse> => {
      const { url, method = "GET", body } = params;
      setLoading(true);
      setError(null);
      try {
        const response = await fetch(`${API_URL}${url}`, {
          method,
          headers: body ? { "Content-Type": "application/json" } : undefined,
          body: body ? JSON.stringify(body) : undefined,
          credentials: "include"
        });
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const text = await response.text();
        const data = text && text.trim() !== "" ? JSON.parse(text) : null;
        setData(data);
        return data as TResponse;
      } catch (err: unknown) {
        if (err instanceof Error) {
          setError(err);
        } else {
          setError(new Error(String(err)));
        }
        throw err;
      } finally {
        setLoading(false);
      }
    },
    []
  );

  useEffect(() => {
    if (autoUrl) {
      fetchData({ url: autoUrl, ...autoOptions } as FetchParams<TBody>);
    }
  }, [autoUrl, autoOptions, fetchData]);

  return { data, error, loading, fetchData };
}
export default useFetch;
