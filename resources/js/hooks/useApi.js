import { useState, useEffect } from 'react';
import apiClient from '../api/client';

export function useApi(endpoint, options = {}) {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                setError(null);
                const response = await apiClient.get(endpoint, options);
                setData(response.data);
            } catch (err) {
                setError(err?.response?.data?.message || err.message || 'An error occurred');
                setData(null);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [endpoint, options.skip]);

    return { data, loading, error };
}

export default useApi;
