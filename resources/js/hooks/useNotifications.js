import { useCallback, useEffect, useState } from 'react';
import apiClient from '../api/client';

export default function useNotifications() {
    const [notifications, setNotifications] = useState([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const [loading, setLoading] = useState(true);

    const loadNotifications = useCallback(async () => {
        try {
            setLoading(true);

            const [notificationsResponse, unreadResponse] = await Promise.all([
                apiClient.get('/notifications?per_page=8'),
                apiClient.get('/notifications/unread-count'),
            ]);

            setNotifications(notificationsResponse.data.data || []);
            setUnreadCount(unreadResponse.data.count || 0);
        } catch (error) {
            console.error('Failed to load notifications', error);
        } finally {
            setLoading(false);
        }
    }, []);

    const markRead = useCallback(async (id) => {
        await apiClient.patch(`/notifications/${id}/read`);

        setNotifications((prev) =>
            prev.map((item) =>
                item.id === id
                    ? { ...item, is_read: true, read_at: new Date().toISOString() }
                    : item
            )
        );

        setUnreadCount((prev) => Math.max(0, prev - 1));
    }, []);

    const markUnread = useCallback(async (id) => {
        await apiClient.patch(`/notifications/${id}/unread`);

        setNotifications((prev) =>
            prev.map((item) =>
                item.id === id
                    ? { ...item, is_read: false, read_at: null }
                    : item
            )
        );

        setUnreadCount((prev) => prev + 1);
    }, []);

    const markAllRead = useCallback(async () => {
        await apiClient.patch('/notifications/read-all');

        setNotifications((prev) =>
            prev.map((item) => ({
                ...item,
                is_read: true,
                read_at: item.read_at || new Date().toISOString(),
            }))
        );

        setUnreadCount(0);
    }, []);

    useEffect(() => {
        void loadNotifications();
    }, [loadNotifications]);

    return {
        notifications,
        unreadCount,
        loading,
        loadNotifications,
        markRead,
        markUnread,
        markAllRead,
    };
}