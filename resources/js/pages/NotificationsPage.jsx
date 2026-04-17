import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import apiClient from '../api/client';

function formatDate(value) {
    if (!value) {
        return 'N/A';
    }

    return new Date(value).toLocaleString();
}

export default function NotificationsPage() {
    const navigate = useNavigate();
    const [items, setItems] = useState([]);
    const [loading, setLoading] = useState(true);
    const [busyId, setBusyId] = useState(null);

    async function loadNotifications() {
        try {
            setLoading(true);
            const response = await apiClient.get('/notifications?per_page=50');
            setItems(response.data.data || []);
        } catch (error) {
            console.error('Failed to load notifications', error);
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        void loadNotifications();
    }, []);

    async function handleOpen(notification) {
        try {
            setBusyId(notification.id);

            if (!notification.is_read) {
                await apiClient.patch(`/notifications/${notification.id}/read`);
            }

            navigate(notification.url || '/notifications');
        } catch (error) {
            console.error('Failed to open notification', error);
        } finally {
            setBusyId(null);
        }
    }

    async function handleToggleRead(notification) {
        try {
            setBusyId(notification.id);

            if (notification.is_read) {
                await apiClient.patch(`/notifications/${notification.id}/unread`);
            } else {
                await apiClient.patch(`/notifications/${notification.id}/read`);
            }

            setItems((prev) =>
                prev.map((item) =>
                    item.id === notification.id
                        ? {
                              ...item,
                              is_read: !notification.is_read,
                              read_at: notification.is_read ? null : new Date().toISOString(),
                          }
                        : item
                )
            );
        } catch (error) {
            console.error('Failed to update notification', error);
        } finally {
            setBusyId(null);
        }
    }

    async function handleMarkAllRead() {
        try {
            await apiClient.patch('/notifications/read-all');

            setItems((prev) =>
                prev.map((item) => ({
                    ...item,
                    is_read: true,
                    read_at: item.read_at || new Date().toISOString(),
                }))
            );
        } catch (error) {
            console.error('Failed to mark all notifications as read', error);
        }
    }

    return (
        <div className="space-y-6">
            <div className="flex items-end justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Notifications</h1>
                    <p className="mt-1 text-sm text-slate-500">View and manage your system alerts.</p>
                </div>

                <button
                    type="button"
                    onClick={handleMarkAllRead}
                    className="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Mark All Read
                </button>
            </div>

            <div className="rounded-2xl border border-slate-200 bg-white shadow-sm">
                {loading ? (
                    <div className="p-6 text-sm text-slate-500">Loading notifications...</div>
                ) : items.length === 0 ? (
                    <div className="p-6 text-sm text-slate-500">No notifications found.</div>
                ) : (
                    <div className="divide-y divide-slate-200">
                        {items.map((notification) => (
                            <div
                                key={notification.id}
                                className={[
                                    'flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between',
                                    notification.is_read ? 'bg-white' : 'bg-blue-50/40',
                                ].join(' ')}
                            >
                                <div className="min-w-0 flex-1">
                                    <div className="flex items-center gap-2">
                                        <h3 className="font-semibold text-slate-900">{notification.title}</h3>
                                        {!notification.is_read ? (
                                            <span className="rounded-full bg-blue-600 px-2 py-0.5 text-[10px] font-bold uppercase text-white">
                                                New
                                            </span>
                                        ) : null}
                                    </div>

                                    <p className="mt-1 text-sm text-slate-600">{notification.message}</p>

                                    <p className="mt-2 text-xs text-slate-400">
                                        {formatDate(notification.created_at)}
                                    </p>
                                </div>

                                <div className="flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        onClick={() => handleOpen(notification)}
                                        disabled={busyId === notification.id}
                                        className="rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60"
                                    >
                                        Open
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => handleToggleRead(notification)}
                                        disabled={busyId === notification.id}
                                        className="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-60"
                                    >
                                        {notification.is_read ? 'Mark Unread' : 'Mark Read'}
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}