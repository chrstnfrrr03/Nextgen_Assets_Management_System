import React, { useEffect, useMemo, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import apiClient from '../api/client';

function formatTypeBadge(type) {
    const value = String(type || 'info').toLowerCase();

    if (value === 'success') {
        return 'bg-emerald-100 text-emerald-700';
    }

    if (value === 'warning') {
        return 'bg-amber-100 text-amber-700';
    }

    if (value === 'error' || value === 'danger') {
        return 'bg-red-100 text-red-700';
    }

    return 'bg-blue-100 text-blue-700';
}

export default function NotificationsPage() {
    const [searchParams, setSearchParams] = useSearchParams();

    const filters = useMemo(
        () => ({
            search: searchParams.get('search') ?? '',
            status: searchParams.get('status') ?? '',
            page: Number.parseInt(searchParams.get('page') ?? '1', 10),
        }),
        [searchParams]
    );

    const [searchInput, setSearchInput] = useState(filters.search);
    const [notifications, setNotifications] = useState([]);
    const [meta, setMeta] = useState({
        current_page: 1,
        last_page: 1,
        total: 0,
    });
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        setSearchInput(filters.search);
    }, [filters.search]);

    useEffect(() => {
        void fetchNotifications();
    }, [filters]);

    async function fetchNotifications() {
        try {
            setLoading(true);

            const response = await apiClient.get('/notifications', {
                params: {
                    search: filters.search || undefined,
                    status: filters.status || undefined,
                    page: filters.page > 0 ? filters.page : 1,
                    per_page: 10,
                },
            });

            const payload = response.data;

            setNotifications(payload.data || payload || []);
            setMeta({
                current_page: payload.current_page || 1,
                last_page: payload.last_page || 1,
                total: payload.total || (payload.data || payload || []).length || 0,
            });
            setError('');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to load notifications');
        } finally {
            setLoading(false);
        }
    }

    async function markAsRead(id) {
        try {
            await apiClient.put(`/notifications/${id}/read`, {});
            await fetchNotifications();
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to update notification');
        }
    }

    function updateQuery(nextValues) {
        const next = new URLSearchParams(searchParams);

        Object.entries(nextValues).forEach(([key, value]) => {
            if (value === '' || value === null || value === undefined) {
                next.delete(key);
            } else {
                next.set(key, String(value));
            }
        });

        if (!next.get('page')) {
            next.set('page', '1');
        }

        setSearchParams(next);
    }

    function handleSearchSubmit(event) {
        event.preventDefault();
        updateQuery({ search: searchInput.trim(), page: 1 });
    }

    function goToPage(page) {
        updateQuery({ page });
    }

    if (loading) {
        return <div className="text-slate-500">Loading notifications...</div>;
    }

    return (
        <div className="space-y-6">
            <div className="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Notifications</h1>
                    <p className="mt-1 text-sm text-slate-500">System alerts and updates</p>
                </div>

                <form onSubmit={handleSearchSubmit} className="flex items-center gap-2">
                    <input
                        type="text"
                        value={searchInput}
                        onChange={(e) => setSearchInput(e.target.value)}
                        placeholder="Search notifications..."
                        className="w-72 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    />
                    <button
                        type="submit"
                        className="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Find
                    </button>
                </form>
            </div>

            <div className="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-3">
                <select
                    value={filters.status}
                    onChange={(e) => updateQuery({ status: e.target.value, page: 1 })}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All notifications</option>
                    <option value="unread">Unread only</option>
                    <option value="read">Read only</option>
                </select>

                <button
                    type="button"
                    onClick={() => setSearchParams({})}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Clear Filters
                </button>
            </div>

            {error ? (
                <div className="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {error}
                </div>
            ) : null}

            {notifications.length > 0 ? (
                <div className="space-y-4">
                    {notifications.map((notif) => (
                        <div
                            key={notif.id}
                            className="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md"
                        >
                            <div className="flex items-start justify-between gap-4">
                                <div className="flex-1">
                                    <div className="flex flex-wrap items-center gap-2">
                                        <h3 className="font-semibold text-slate-900">
                                            {notif.title || 'Notification'}
                                        </h3>
                                        <span
                                            className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${formatTypeBadge(
                                                notif.type
                                            )}`}
                                        >
                                            {notif.type || 'info'}
                                        </span>
                                        {!notif.read_at ? (
                                            <span className="inline-flex rounded-full bg-slate-900 px-2.5 py-1 text-xs font-semibold text-white">
                                                Unread
                                            </span>
                                        ) : null}
                                    </div>

                                    <p className="mt-2 text-sm text-slate-600">
                                        {notif.message || '-'}
                                    </p>

                                    <p className="mt-3 text-xs text-slate-400">
                                        {notif.created_at
                                            ? new Date(notif.created_at).toLocaleString()
                                            : '-'}
                                    </p>
                                </div>

                                {!notif.read_at ? (
                                    <button
                                        type="button"
                                        onClick={() => markAsRead(notif.id)}
                                        className="rounded-lg px-3 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50"
                                    >
                                        Mark as read
                                    </button>
                                ) : null}
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
                    <p className="text-slate-500">No notifications at this time.</p>
                </div>
            )}

            <div className="flex flex-col gap-3 border border-slate-200 bg-white px-6 py-4 text-sm text-slate-600 shadow-sm sm:flex-row sm:items-center sm:justify-between rounded-2xl">
                <p>
                    Showing page {meta.current_page} of {meta.last_page} · {meta.total} total
                </p>

                <div className="flex items-center gap-2">
                    <button
                        type="button"
                        disabled={meta.current_page <= 1}
                        onClick={() => goToPage(meta.current_page - 1)}
                        className="rounded-lg border border-slate-300 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Previous
                    </button>

                    <button
                        type="button"
                        disabled={meta.current_page >= meta.last_page}
                        onClick={() => goToPage(meta.current_page + 1)}
                        className="rounded-lg border border-slate-300 px-3 py-1.5 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    );
}
