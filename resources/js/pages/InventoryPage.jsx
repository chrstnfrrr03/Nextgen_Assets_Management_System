import React, { useEffect, useMemo, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import apiClient from '../api/client';

function StatCard({ label, value, helper }) {
    return (
        <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 className="text-sm font-medium text-slate-600">{label}</h3>
            <p className="mt-2 text-3xl font-bold text-slate-900">{value}</p>
            <p className="mt-1 text-xs text-slate-500">{helper}</p>
        </div>
    );
}

export default function InventoryPage() {
    const [searchParams, setSearchParams] = useSearchParams();

    const filters = useMemo(
        () => ({
            search: searchParams.get('search') ?? '',
            stock: searchParams.get('stock') ?? '',
            page: Number.parseInt(searchParams.get('page') ?? '1', 10),
        }),
        [searchParams]
    );

    const [searchInput, setSearchInput] = useState(filters.search);
    const [inventory, setInventory] = useState([]);
    const [summary, setSummary] = useState({
        totalItems: 0,
        lowStockCount: 0,
        outOfStockCount: 0,
    });
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
        void fetchInventory();
    }, [filters]);

    async function fetchInventory() {
        try {
            setLoading(true);

            const response = await apiClient.get('/inventory', {
                params: {
                    search: filters.search || undefined,
                    stock: filters.stock || undefined,
                    page: filters.page > 0 ? filters.page : 1,
                    per_page: 10,
                },
            });

            const payload = response.data;
            const rows = payload.data || payload.items || [];
            const stats = payload.summary || payload;

            setInventory(rows);
            setSummary({
                totalItems: stats.totalItems || stats.total_items || rows.length || 0,
                lowStockCount: stats.lowStockCount || stats.low_stock_count || 0,
                outOfStockCount: stats.outOfStockCount || stats.out_of_stock_count || 0,
            });
            setMeta({
                current_page: payload.current_page || 1,
                last_page: payload.last_page || 1,
                total: payload.total || rows.length || 0,
            });
            setError('');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to load inventory');
        } finally {
            setLoading(false);
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
        return <div className="text-slate-500">Loading inventory...</div>;
    }

    if (error) {
        return (
            <div className="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {error}
            </div>
        );
    }

    return (
        <div className="space-y-6">
            <div className="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Inventory</h1>
                    <p className="mt-1 text-sm text-slate-500">
                        View asset inventory and stock levels
                    </p>
                </div>

                <form onSubmit={handleSearchSubmit} className="flex items-center gap-2">
                    <input
                        type="text"
                        value={searchInput}
                        onChange={(e) => setSearchInput(e.target.value)}
                        placeholder="Search inventory..."
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

            <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                <StatCard
                    label="Low Stock Items"
                    value={summary.lowStockCount}
                    helper="Items need restocking"
                />
                <StatCard
                    label="Out of Stock"
                    value={summary.outOfStockCount}
                    helper="No inventory available"
                />
                <StatCard
                    label="Total Items"
                    value={summary.totalItems}
                    helper="Across all categories"
                />
            </div>

            <div className="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-3">
                <select
                    value={filters.stock}
                    onChange={(e) => updateQuery({ stock: e.target.value, page: 1 })}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All stock levels</option>
                    <option value="low">Low stock</option>
                    <option value="out">Out of stock</option>
                    <option value="available">Available stock</option>
                </select>

                <button
                    type="button"
                    onClick={() => setSearchParams({})}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Clear Filters
                </button>
            </div>

            <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div className="overflow-x-auto">
                    <table className="min-w-full text-sm">
                        <thead className="border-b border-slate-200 bg-slate-50 text-slate-600">
                            <tr>
                                <th className="px-6 py-4 text-left font-semibold">Item</th>
                                <th className="px-6 py-4 text-left font-semibold">SKU</th>
                                <th className="px-6 py-4 text-left font-semibold">Category</th>
                                <th className="px-6 py-4 text-left font-semibold">Department</th>
                                <th className="px-6 py-4 text-left font-semibold">Quantity</th>
                                <th className="px-6 py-4 text-left font-semibold">Status</th>
                            </tr>
                        </thead>

                        <tbody className="divide-y divide-slate-100">
                            {inventory.length ? (
                                inventory.map((item) => (
                                    <tr key={item.id} className="hover:bg-slate-50">
                                        <td className="px-6 py-4 font-medium text-slate-900">
                                            {item.name || '-'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {item.sku || item.asset_tag || '-'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {item.category?.name || '-'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {item.department?.name || '-'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {item.quantity ?? 0}
                                        </td>
                                        <td className="px-6 py-4">
                                            <span
                                                className={`inline-flex rounded-full px-3 py-1 text-xs font-semibold ${
                                                    Number(item.quantity) === 0
                                                        ? 'bg-red-100 text-red-700'
                                                        : Number(item.quantity) < 5
                                                          ? 'bg-amber-100 text-amber-700'
                                                          : 'bg-emerald-100 text-emerald-700'
                                                }`}
                                            >
                                                {Number(item.quantity) === 0
                                                    ? 'Out of Stock'
                                                    : Number(item.quantity) < 5
                                                      ? 'Low Stock'
                                                      : 'Healthy'}
                                            </span>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="6" className="px-6 py-10 text-center text-slate-500">
                                        No inventory records found.
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