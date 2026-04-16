import React, { useEffect, useMemo, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import apiClient from '../api/client';
import { useAuth } from '../context/AuthContext';
import { useSettings } from '../context/SettingsContext';

export default function UsersPage() {
    const [searchParams, setSearchParams] = useSearchParams();
    const { user: currentUser, impersonate, stopImpersonation, refreshUser } = useAuth();
    const { settings} = useSettings();
    const canSwitchUser = String(settings.allow_user_impersonation ?? '1') === '1';

    const filters = useMemo(
        () => ({
            search: searchParams.get('search') ?? '',
            page: Number.parseInt(searchParams.get('page') ?? '1', 10),
        }),
        [searchParams]
    );

    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [showForm, setShowForm] = useState(false);
    const [editingId, setEditingId] = useState(null);
    const [searchInput, setSearchInput] = useState(filters.search);
    const [meta, setMeta] = useState({
        current_page: 1,
        last_page: 1,
        total: 0,
    });

    const [form, setForm] = useState({
        name: '',
        email: '',
        role: 'staff',
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        setSearchInput(filters.search);
    }, [filters.search]);

    useEffect(() => {
        void fetchUsers();
    }, [filters]);

    async function fetchUsers() {
        try {
            setLoading(true);

            const response = await apiClient.get('/users', {
                params: {
                    search: filters.search || undefined,
                    page: filters.page > 0 ? filters.page : 1,
                    per_page: 10,
                },
            });

            const payload = response.data;

            setUsers(payload.data || []);
            setMeta({
                current_page: payload.current_page || 1,
                last_page: payload.last_page || 1,
                total: payload.total || 0,
            });
            setError('');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to load users');
        } finally {
            setLoading(false);
        }
    }

    async function handleSubmit(event) {
        event.preventDefault();

        try {
            const payload = {
                name: form.name,
                email: form.email,
                role: form.role,
            };

            if (form.password) {
                payload.password = form.password;
                payload.password_confirmation = form.password_confirmation;
            }

            if (editingId) {
                await apiClient.put(`/users/${editingId}`, payload);
            } else {
                await apiClient.post('/users', payload);
            }

            setSuccess(editingId ? 'User updated successfully.' : 'User created successfully.');
            resetForm();
            await fetchUsers();
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to save user');
        }
    }

    async function handleDelete(id) {
        if (!window.confirm('Delete this user?')) {
            return;
        }

        try {
            await apiClient.delete(`/users/${id}`);
            await fetchUsers();
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to delete user');
        }
    }

    async function handleImpersonate(id) {
        try {
            await impersonate(id);
            await refreshUser();
            setSuccess('Switched user successfully.');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to switch user');
        }
    }

    async function handleStopImpersonation() {
        try {
            await stopImpersonation();
            await refreshUser();
            setSuccess('Returned to administrator account.');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to stop impersonation');
        }
    }

    function handleEdit(user) {
        setForm({
            name: user.name || '',
            email: user.email || '',
            role: user.role || 'staff',
            password: '',
            password_confirmation: '',
        });
        setEditingId(user.id);
        setShowForm(true);
    }

    function resetForm() {
        setForm({
            name: '',
            email: '',
            role: 'staff',
            password: '',
            password_confirmation: '',
        });
        setEditingId(null);
        setShowForm(false);
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
        return <div className="text-slate-500">Loading users...</div>;
    }

    return (
        <div className="space-y-6">
            <div className="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Users</h1>
                    <p className="mt-1 text-sm text-slate-500">
                        Manage system users and switch-user access
                    </p>
                </div>

                <div className="flex flex-col gap-3 sm:flex-row">
                    <form onSubmit={handleSearchSubmit} className="flex items-center gap-2">
                        <input
                            type="text"
                            value={searchInput}
                            onChange={(e) => setSearchInput(e.target.value)}
                            placeholder="Search users..."
                            className="w-72 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        />
                        <button
                            type="submit"
                            className="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        >
                            Find
                        </button>
                    </form>

                    <button
                        type="button"
                        onClick={() => setShowForm((prev) => !prev)}
                        className="rounded-xl bg-blue-600 px-4 py-2.5 font-medium text-white hover:bg-blue-700"
                    >
                        {showForm ? 'Cancel' : 'Add User'}
                    </button>
                </div>
            </div>

            {currentUser?.is_impersonating ? (
                <div className="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    You are in switch-user mode.
                    <button
                        type="button"
                        onClick={handleStopImpersonation}
                        className="ml-3 font-semibold underline"
                    >
                        Return to admin
                    </button>
                </div>
            ) : null}

            {error ? (
                <div className="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {error}
                </div>
            ) : null}

            {success ? (
                <div className="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {success}
                </div>
            ) : null}

            {showForm ? (
                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-4 text-lg font-semibold">
                        {editingId ? 'Edit User' : 'Add User'}
                    </h2>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">
                                    Name
                                </label>
                                <input
                                    type="text"
                                    value={form.name}
                                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    value={form.email}
                                    onChange={(e) => setForm({ ...form, email: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">
                                    Role
                                </label>
                                <select
                                    value={form.role}
                                    onChange={(e) => setForm({ ...form, role: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="asset_officer">Asset Officer</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    value={form.password}
                                    onChange={(e) => setForm({ ...form, password: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder={
                                        editingId
                                            ? 'Leave blank to keep current password'
                                            : 'Default is password if blank'
                                    }
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">
                                    Confirm Password
                                </label>
                                <input
                                    type="password"
                                    value={form.password_confirmation}
                                    onChange={(e) =>
                                        setForm({ ...form, password_confirmation: e.target.value })
                                    }
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div className="flex gap-3">
                            <button
                                type="submit"
                                className="rounded-xl bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700"
                            >
                                {editingId ? 'Update' : 'Create'} User
                            </button>

                            <button
                                type="button"
                                onClick={resetForm}
                                className="rounded-xl bg-slate-200 px-4 py-2 font-medium text-slate-700 hover:bg-slate-300"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            ) : null}

            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div className="overflow-x-auto">
                    <table className="min-w-full text-sm">
                        <thead className="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <tr>
                                <th className="px-6 py-4 text-left font-semibold">Name</th>
                                <th className="px-6 py-4 text-left font-semibold">Email</th>
                                <th className="px-6 py-4 text-left font-semibold">Role</th>
                                <th className="px-6 py-4 text-left font-semibold">Assignments</th>
                                <th className="px-6 py-4 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>

                        <tbody className="divide-y divide-slate-100">
                            {users.length ? (
                                users.map((user) => (
                                    <tr key={user.id} className="hover:bg-slate-50">
                                        <td className="px-6 py-4 font-medium text-slate-900">
                                            {user.name}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {user.email}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {user.role}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {user.active_assignments_count ?? 0}
                                        </td>
                                        <td className="space-x-2 px-6 py-4">
                                            <button
                                                type="button"
                                                onClick={() => handleEdit(user)}
                                                className="text-blue-600 hover:underline"
                                            >
                                                Edit
                                            </button>

                                            <button
                                                type="button"
                                                onClick={() => handleDelete(user.id)}
                                                className="text-red-600 hover:underline"
                                            >
                                                Delete
                                            </button>

                                            {canSwitchUser && currentUser?.role === 'admin' && !currentUser?.is_impersonating && Number(currentUser?.id) !== Number(user.id) ? (
                                                <button
                                                    type="button"
                                                    onClick={() => handleImpersonate(user.id)}
                                                    className="text-amber-600 hover:underline"
                                                >
                                                    Switch User
                                                </button>
                                            ) : null}
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="5" className="px-6 py-10 text-center text-slate-500">
                                        No users found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="flex flex-col gap-3 border-t border-slate-200 px-6 py-4 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
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
        </div>
    );
}
