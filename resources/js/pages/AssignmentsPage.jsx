import React, { useEffect, useMemo, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import apiClient from '../api/client';
import { downloadCsv } from '../utils/csv';

export default function AssignmentsPage() {
    const [searchParams, setSearchParams] = useSearchParams();

    const filters = useMemo(
        () => ({
            search: searchParams.get('search') ?? '',
            status: searchParams.get('status') ?? '',
            page: Number.parseInt(searchParams.get('page') ?? '1', 10),
        }),
        [searchParams]
    );

    const [assignments, setAssignments] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [showForm, setShowForm] = useState(false);
    const [searchInput, setSearchInput] = useState(filters.search);
    const [meta, setMeta] = useState({
        current_page: 1,
        last_page: 1,
        total: 0,
    });

    const [form, setForm] = useState({
        item_id: '',
        user_id: '',
        department_id: '',
    });

    const [itemsList, setItemsList] = useState([]);
    const [usersList, setUsersList] = useState([]);
    const [departmentsList, setDepartmentsList] = useState([]);

    useEffect(() => {
        setSearchInput(filters.search);
    }, [filters.search]);

    useEffect(() => {
        void fetchAssignments();
    }, [filters]);

    useEffect(() => {
        void fetchOptions();
    }, []);

    async function fetchAssignments() {
        try {
            setLoading(true);

            const response = await apiClient.get('/assignments', {
                params: {
                    search: filters.search || undefined,
                    status: filters.status || undefined,
                    page: filters.page > 0 ? filters.page : 1,
                    per_page: 10,
                },
            });

            const payload = response.data;

            setAssignments(payload.data || []);
            setMeta({
                current_page: payload.current_page || 1,
                last_page: payload.last_page || 1,
                total: payload.total || 0,
            });
            setError('');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to load assignments');
        } finally {
            setLoading(false);
        }
    }

    async function fetchOptions() {
        try {
            const [items, users, departments] = await Promise.all([
                apiClient.get('/items', { params: { per_page: 100 } }),
                apiClient.get('/users', { params: { per_page: 100 } }),
                apiClient.get('/departments', { params: { per_page: 100 } }),
            ]);

            setItemsList(items.data.data || items.data || []);
            setUsersList(users.data.data || users.data || []);
            setDepartmentsList(departments.data.data || departments.data || []);
        } catch (err) {
            console.error('Failed to load assignment options', err);
        }
    }

    async function handleSubmit(event) {
        event.preventDefault();

        try {
            await apiClient.post('/assignments', form);
            setForm({ item_id: '', user_id: '', department_id: '' });
            setShowForm(false);
            await fetchAssignments();
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to create assignment');
        }
    }

    async function handleReturnAsset(id) {
        if (!window.confirm('Mark this assignment as returned?')) {
            return;
        }

        try {
            await apiClient.put(`/assignments/${id}/return`);
            await fetchAssignments();
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to return asset');
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
        return <div className="text-slate-500">Loading assignments...</div>;
    }

    return (
        <div className="space-y-6">
            <div className="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Assignments</h1>
                    <p className="mt-1 text-sm text-slate-500">Manage asset assignments to users and departments</p>
                </div>

                <div className="flex flex-col gap-3 sm:flex-row">
                    <form onSubmit={handleSearchSubmit} className="flex items-center gap-2">
                        <input
                            type="text"
                            value={searchInput}
                            onChange={(e) => setSearchInput(e.target.value)}
                            placeholder="Search assignments..."
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
                        {showForm ? 'Cancel' : 'New Assignment'}
                    </button>
                </div>
            </div>

            <div className="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-3">
                <select
                    value={filters.status}
                    onChange={(e) => updateQuery({ status: e.target.value, page: 1 })}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All statuses</option>
                    <option value="active">Active</option>
                    <option value="returned">Returned</option>
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

            {showForm ? (
                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-4 text-lg font-semibold">Create New Assignment</h2>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="mb-1 block text-sm font-medium text-slate-700">Asset</label>
                            <select
                                value={form.item_id}
                                onChange={(e) => setForm({ ...form, item_id: e.target.value })}
                                className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            >
                                <option value="">Select Asset</option>
                                {itemsList.map((item) => (
                                    <option key={item.id} value={item.id}>
                                        {item.name} {item.sku ? `(${item.sku})` : ''}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">User</label>
                                <select
                                    value={form.user_id}
                                    onChange={(e) => setForm({ ...form, user_id: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select User</option>
                                    {usersList.map((user) => (
                                        <option key={user.id} value={user.id}>
                                            {user.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Department</label>
                                <select
                                    value={form.department_id}
                                    onChange={(e) => setForm({ ...form, department_id: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Department</option>
                                    {departmentsList.map((department) => (
                                        <option key={department.id} value={department.id}>
                                            {department.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>

                        <div className="flex gap-3">
                            <button
                                type="submit"
                                className="rounded-xl bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700"
                            >
                                Create Assignment
                            </button>
                            <button
                                type="button"
                                onClick={() => setShowForm(false)}
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
                                <th className="px-6 py-4 text-left font-semibold">Asset</th>
                                <th className="px-6 py-4 text-left font-semibold">User</th>
                                <th className="px-6 py-4 text-left font-semibold">Department</th>
                                <th className="px-6 py-4 text-left font-semibold">Assigned On</th>
                                <th className="px-6 py-4 text-left font-semibold">Status</th>
                                <th className="px-6 py-4 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>

                        <tbody className="divide-y divide-slate-100">
                            {assignments.length > 0 ? (
                                assignments.map((assignment) => (
                                    <tr key={assignment.id} className="hover:bg-slate-50">
                                        <td className="px-6 py-4 font-medium text-slate-900">
                                            {assignment.item?.name || '-'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {assignment.user?.name || '-'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {assignment.assigned_department?.name || '-'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">
                                            {assignment.assigned_at
                                                ? new Date(assignment.assigned_at).toLocaleDateString()
                                                : '-'}
                                        </td>
                                        <td className="px-6 py-4">
                                            <span
                                                className={`inline-flex rounded-full px-3 py-1 text-xs font-semibold ${
                                                    !assignment.returned_at
                                                        ? 'bg-blue-100 text-blue-700'
                                                        : 'bg-slate-100 text-slate-700'
                                                }`}
                                            >
                                                {assignment.returned_at ? 'Returned' : 'Active'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            {!assignment.returned_at ? (
                                                <button
                                                    type="button"
                                                    onClick={() => handleReturnAsset(assignment.id)}
                                                    className="text-orange-600 hover:underline"
                                                >
                                                    Return
                                                </button>
                                            ) : (
                                                <span className="text-slate-400">-</span>
                                            )}
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="6" className="px-6 py-10 text-center text-slate-500">
                                        No assignments yet.
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