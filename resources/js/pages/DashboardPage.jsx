import React from 'react';
import { Link } from 'react-router-dom';
import { useApi } from '../hooks/useApi';

export default function DashboardPage() {
    const { data, loading, error } = useApi('/dashboard');

    if (loading) {
        return (
            <div className="flex items-center justify-center py-12">
                <div className="text-center">
                    <div className="mx-auto h-8 w-8 animate-spin rounded-full border-b-2 border-blue-600"></div>
                    <p className="mt-2 text-sm text-slate-500">Loading dashboard...</p>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="rounded-xl border border-red-200 bg-red-50 p-4">
                <p className="text-sm text-red-700">Failed to load dashboard data: {error}</p>
            </div>
        );
    }

    const stats = [
        { label: 'Total Assets', value: data?.total_assets || 0, gradient: 'from-slate-900 to-slate-700' },
        { label: 'Available', value: data?.available || 0, gradient: 'from-emerald-500 to-emerald-400' },
        { label: 'Assigned', value: data?.assigned || 0, gradient: 'from-amber-500 to-yellow-400' },
        { label: 'Maintenance', value: data?.maintenance || 0, gradient: 'from-rose-500 to-red-400' },
        { label: 'Low Stock', value: data?.low_stock || 0, gradient: 'from-orange-500 to-orange-400' },
        { label: 'Overdue', value: data?.overdue || 0, gradient: 'from-red-600 to-red-500' },
    ];

    return (
        <div className="space-y-8">
            <div className="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div className="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-500 shadow-sm">
                        <span className="h-2 w-2 rounded-full bg-emerald-500"></span>
                        System overview
                    </div>

                    <h1 className="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Admin Dashboard
                    </h1>

                    <p className="mt-2 max-w-2xl text-sm text-slate-500 sm:text-base">
                        Operational control center for assets, assignments, departments, and system activity.
                    </p>

                    <div className="mt-4 flex flex-wrap gap-3">
                        <Link
                            to="/items"
                            className="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            + Add Asset
                        </Link>

                        <Link
                            to="/assignments"
                            className="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
                        >
                            + Assign Asset
                        </Link>
                    </div>
                </div>

                <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:w-[360px]">
                    <div className="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <p className="text-xs font-medium uppercase tracking-wide text-slate-400">Assets</p>
                        <p className="mt-2 text-2xl font-bold text-slate-900">{data?.total_assets || 0}</p>
                    </div>
                    <div className="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <p className="text-xs font-medium uppercase tracking-wide text-slate-400">Active</p>
                        <p className="mt-2 text-2xl font-bold text-slate-900">{data?.assigned || 0}</p>
                    </div>
                    <div className="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <p className="text-xs font-medium uppercase tracking-wide text-slate-400">Notifications</p>
                        <p className="mt-2 text-2xl font-bold text-slate-900">{data?.notifications_count || 0}</p>
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 2xl:grid-cols-6">
                {stats.map((stat) => (
                    <div key={stat.label} className={`rounded-2xl bg-gradient-to-br ${stat.gradient} p-5 text-white shadow-sm`}>
                        <p className="text-xs font-medium uppercase tracking-wide text-white/75">{stat.label}</p>
                        <h2 className="mt-4 text-3xl font-bold">{stat.value}</h2>
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-1 gap-6 xl:grid-cols-4">
                <div className="space-y-6 xl:col-span-3">
                    <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div className="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                            <div>
                                <h2 className="text-lg font-semibold text-slate-900">Recent Assignments</h2>
                                <p className="mt-1 text-sm text-slate-500">Latest asset movement across departments.</p>
                            </div>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="min-w-full text-sm">
                                <thead className="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th className="px-6 py-4 text-left font-semibold">Asset</th>
                                        <th className="px-6 py-4 text-left font-semibold">User</th>
                                        <th className="px-6 py-4 text-left font-semibold">Department</th>
                                        <th className="px-6 py-4 text-left font-semibold">Date</th>
                                        <th className="px-6 py-4 text-left font-semibold">Status</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {data?.recent_assignments?.length ? (
                                        data.recent_assignments.map((assignment) => (
                                            <tr key={assignment.id} className="transition hover:bg-blue-50/60">
                                                <td className="px-6 py-4 font-medium text-slate-900">
                                                    {assignment.item?.name || '-'}
                                                </td>
                                                <td className="px-6 py-4 text-slate-700">
                                                    {assignment.user?.name || '-'}
                                                </td>
                                                <td className="px-6 py-4 text-slate-700">
                                                    {assignment.assigned_department?.name || '-'}
                                                </td>
                                                <td className="px-6 py-4 text-slate-700">
                                                    {assignment.assigned_at ? new Date(assignment.assigned_at).toLocaleDateString() : '-'}
                                                </td>
                                                <td className="px-6 py-4">
                                                    <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${
                                                        assignment.returned_at
                                                            ? 'bg-slate-100 text-slate-700'
                                                            : 'bg-green-100 text-green-800'
                                                    }`}>
                                                        {assignment.returned_at ? 'Returned' : 'Active'}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="5" className="px-6 py-4 text-center text-slate-500">
                                                No recent assignments
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div className="space-y-6">
                    <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 className="text-lg font-semibold text-slate-900">Quick Actions</h3>
                        <p className="mt-1 text-sm text-slate-500">Common tasks and shortcuts.</p>
                        <div className="mt-4 space-y-3">
                            <Link to="/items" className="block rounded-lg px-4 py-2 text-sm text-slate-700 transition hover:bg-slate-50">
                                View All Assets
                            </Link>
                            <Link to="/users" className="block rounded-lg px-4 py-2 text-sm text-slate-700 transition hover:bg-slate-50">
                                Manage Users
                            </Link>
                            <Link to="/settings" className="block rounded-lg px-4 py-2 text-sm text-slate-700 transition hover:bg-slate-50">
                                System Settings
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}