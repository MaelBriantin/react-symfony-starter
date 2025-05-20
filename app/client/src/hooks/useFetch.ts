const API_URL = import.meta.env.VITE_API_URL;

interface FetchParams<T = unknown> {
  url: string;
  method?: string;
  body?: T;
}

/**
 * Custom React hook for performing HTTP requests with typed request and response bodies.
 *
 * @template TBody - The type of the request body. Defaults to `undefined`.
 * @template TResponse - The type of the response data. Defaults to `unknown`.
 *
 * @returns An object containing the `fetchData` function.
 *
 * @function fetchData
 * @param params - The parameters for the fetch request.
 * @param params.url - The endpoint URL (relative to `API_URL`).
 * @param params.method - The HTTP method (e.g., "GET", "POST"). Defaults to "GET".
 * @param params.body - The request body, if applicable.
 * @returns A promise that resolves to the response data of type `TResponse`.
 *
 * @throws Will throw an error if the HTTP response is not OK or if the fetch fails.
 *
 * @example
 * const { fetchData } = useFetch<MyRequestBody, MyResponse>();
 * const data = await fetchData({ url: '/api/data', method: 'POST', body: { foo: 'bar' } });
 */
const useFetch = <TBody = undefined, TResponse = unknown>() => {
  const fetchData = async (params: FetchParams<TBody>): Promise<TResponse> => {
    const { url, method = "GET", body } = params;
    try {
      const response = await fetch(`${API_URL}${url}`, {
        method,
        headers: body ? { "Content-Type": "application/json" } : undefined,
        body: body ? JSON.stringify(body) : undefined,
      });
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const data = await response.json();
      return data as TResponse;
    } catch (error) {
      console.error("Error fetching data:", error);
      throw error;
    }
  };
  return { fetchData };
};
export default useFetch;
