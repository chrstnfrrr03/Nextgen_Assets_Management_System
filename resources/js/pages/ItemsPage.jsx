import React, { useEffect, useMemo, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import apiClient from '../api/client';
import { downloadCsv } from '../utils/csv';

export default function ItemsPage() {
    const [searchParams, setSearchParams] = useSearchParams();

    const [items, setItems] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [showForm, setShowForm] = useState(false);
    const [editingId, setEditingId] = useState(null);
    const [categories, setCategories] = useState([]);
    const [suppliers, setSuppliers] = useState([]);
    const [departments, setDepartments] = useState([]);
    const [meta, setMeta] = useState({
        current_page: 1,
        last_page: 1,
        total: 0,
    });

    const filters = useMemo(
        () => ({
            search: searchParams.get('search') ?? '',
            status: searchParams.get('status') ?? '',
            category_id: searchParams.get('category_id') ?? '',
            department_id: searchParams.get('department_id') ?? '',
            page: Number.parseInt(searchParams.get('page') ?? '1', 10),
        }),
        [searchParams]
    );

    const [searchInput, setSearchInput] = useState(filters.search);

    const [form, setForm] = useState({
        name: '',
        sku: '',
        category_id: '',
        supplier_id: '',
        department_id: '',
        status: 'available',
        quantity: 1,
        asset_tag: '',
        serial_number: '',
        location: '',
        purchase_date: '',
    });

    useEffect(() => {
        setSearchInput(filters.search);
    }, [filters.search]);

    useEffect(() => {
        void fetchItems();
    }, [filters]);

    useEffect(() => {
        void fetchCategories();
        void fetchSuppliers();
        void fetchDepartments();
    }, []);

    async function fetchItems() {
        try {
            setLoading(true);

            const response = await apiClient.get('/items', {
                params: {
                    search: filters.search || undefined,
                    status: filters.status || undefined,
                    category_id: filters.category_id || undefined,
                    department_id: filters.department_id || undefined,
                    page: filters.page > 0 ? filters.page : 1,
                    per_page: 10,
                },
            });

            const payload = response.data;

            setItems(payload.data || []);
            setMeta({
                current_page: payload.current_page || 1,
                last_page: payload.last_page || 1,
                total: payload.total || 0,
            });
            setError('');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to load items');
        } finally {
            setLoading(false);
        }
    }

    async function fetchCategories() {
        try {
            const response = await apiClient.get('/categories', { params: { per_page: 100 } });
            setCategories(response.data.data || response.data || []);
        } catch (err) {
            console.error('Failed to load categories', err);
        }
    }

    async function fetchSuppliers() {
        try {
            const response = await apiClient.get('/suppliers', { params: { per_page: 100 } });
            setSuppliers(response.data.data || response.data || []);
        } catch (err) {
            console.error('Failed to load suppliers', err);
        }
    }

    async function fetchDepartments() {
        try {
            const response = await apiClient.get('/departments', { params: { per_page: 100 } });
            setDepartments(response.data.data || response.data || []);
        } catch (err) {
            console.error('Failed to load departments', err);
        }
    }

    async function handleSubmit(event) {
        event.preventDefault();

        try {
            if (editingId) {
                await apiClient.put(`/items/${editingId}`, form);
            } else {
                await apiClient.post('/items', form);
            }

            resetForm();
            await fetchItems();
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to save item');
        }
    }

    async function handleDelete(id) {
        if (!window.confirm('Are you sure you want to delete this item?')) {
            return;
        }

        try {
            await apiClient.delete(`/items/${id}`);
            await fetchItems();
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to delete item');
        }
    }

    function handleEdit(item) {
        setForm({
            name: item.name || '',
            sku: item.sku || '',
            category_id: item.category_id || '',
            supplier_id: item.supplier_id || '',
            department_id: item.department_id || '',
            status: item.status || 'available',
            quantity: item.quantity || 1,
            asset_tag: item.asset_tag || '',
            serial_number: item.serial_number || '',
            location: item.location || '',
            purchase_date: item.purchase_date || '',
        });

        setEditingId(item.id);
        setShowForm(true);
    }

    function resetForm() {
        setForm({
            name: '',
            sku: '',
            category_id: '',
            supplier_id: '',
            department_id: '',
            status: 'available',
            quantity: 1,
            asset_tag: '',
            serial_number: '',
            location: '',
            purchase_date: '',
        });

        setEditingId(null);
        setShowForm(false);
        setError('');
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

     function handleExportCsv() {
    downloadCsv(
        'assets.csv',
        items.map((item) => ({
            Name: item.name || '',
            SKU: item.sku || '',
            'Asset Tag': item.asset_tag || '',
            'Serial Number': item.serial_number || '',
            Category: item.category?.name || '',
            Supplier: item.supplier?.name || '',
            Department: item.department?.name || '',
            Quantity: item.quantity ?? 0,
            Status: item.status || '',
            Location: item.location || '',
            'Purchase Date': item.purchase_date || '',
        }))
    );
}

    function handleFilterChange(key, value) {
        updateQuery({ [key]: value, page: 1 });
    }

    function goToPage(page) {
        updateQuery({ page });
    }

    if (loading) {
        return <div className="text-slate-500">Loading assets...</div>;
    }

    return (
        <div className="space-y-6">
            <div className="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Assets</h1>
                    <p className="mt-1 text-sm text-slate-500">Manage your organization&apos;s assets</p>
                </div>

                <div className="flex flex-col gap-3 sm:flex-row">
                    <form onSubmit={handleSearchSubmit} className="flex items-center gap-2">
                        <input
                            type="text"
                            value={searchInput}
                            onChange={(e) => setSearchInput(e.target.value)}
                            placeholder="Search assets..."
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
                        {showForm ? 'Cancel' : 'Add Asset'}
                    </button>
                    <button
    type="button"
    onClick={handleExportCsv}
    className="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
>
    Export CSV
</button>
                </div>
            </div>

            <div className="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-4">
                <select
                    value={filters.status}
                    onChange={(e) => handleFilterChange('status', e.target.value)}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All statuses</option>
                    <option value="available">Available</option>
                    <option value="assigned">Assigned</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="lost">Lost</option>
                    <option value="retired">Retired</option>
                </select>

                <select
                    value={filters.category_id}
                    onChange={(e) => handleFilterChange('category_id', e.target.value)}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All categories</option>
                    {categories.map((category) => (
                        <option key={category.id} value={category.id}>
                            {category.name}
                        </option>
                    ))}
                </select>

                <select
                    value={filters.department_id}
                    onChange={(e) => handleFilterChange('department_id', e.target.value)}
                    className="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">All departments</option>
                    {departments.map((department) => (
                        <option key={department.id} value={department.id}>
                            {department.name}
                        </option>
                    ))}
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
                    <h2 className="mb-4 text-lg font-semibold">
                        {editingId ? 'Edit Asset' : 'Add New Asset'}
                    </h2>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Name</label>
                                <input
                                    type="text"
                                    value={form.name}
                                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">SKU</label>
                                <input
                                    type="text"
                                    value={form.sku}
                                    onChange={(e) => setForm({ ...form, sku: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Asset Tag</label>
                                <input
                                    type="text"
                                    value={form.asset_tag}
                                    onChange={(e) => setForm({ ...form, asset_tag: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Serial Number</label>
                                <input
                                    type="text"
                                    value={form.serial_number}
                                    onChange={(e) => setForm({ ...form, serial_number: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Quantity</label>
                                <input
                                    type="number"
                                    min="0"
                                    value={form.quantity}
                                    onChange={(e) => setForm({ ...form, quantity: Number.parseInt(e.target.value || '0', 10) })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Status</label>
                                <select
                                    value={form.status}
                                    onChange={(e) => setForm({ ...form, status: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="available">Available</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="lost">Lost</option>
                                    <option value="retired">Retired</option>
                                </select>
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Category</label>
                                <select
                                    value={form.category_id}
                                    onChange={(e) => setForm({ ...form, category_id: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Category</option>
                                    {categories.map((category) => (
                                        <option key={category.id} value={category.id}>
                                            {category.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Supplier</label>
                                <select
                                    value={form.supplier_id}
                                    onChange={(e) => setForm({ ...form, supplier_id: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Supplier</option>
                                    {suppliers.map((supplier) => (
                                        <option key={supplier.id} value={supplier.id}>
                                            {supplier.name}
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
                                    {departments.map((department) => (
                                        <option key={department.id} value={department.id}>
                                            {department.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Location</label>
                                <input
                                    type="text"
                                    value={form.location}
                                    onChange={(e) => setForm({ ...form, location: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Purchase Date</label>
                                <input
                                    type="date"
                                    value={form.purchase_date}
                                    onChange={(e) => setForm({ ...form, purchase_date: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div className="flex gap-3">
                            <button
                                type="submit"
                                className="rounded-xl bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700"
                            >
                                {editingId ? 'Update' : 'Create'} Asset
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
                                <th className="px-6 py-4 text-left font-semibold">SKU</th>
                                <th className="px-6 py-4 text-left font-semibold">Department</th>
                                <th className="px-6 py-4 text-left font-semibold">Quantity</th>
                                <th className="px-6 py-4 text-left font-semibold">Status</th>
                                <th className="px-6 py-4 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>

                        <tbody className="divide-y divide-slate-100">
                            {items.length > 0 ? (
                                items.map((item) => (
                                    <tr key={item.id} className="hover:bg-slate-50">
                                        <td className="px-6 py-4 font-medium text-slate-900">{item.name}</td>
                                        <td className="px-6 py-4 text-slate-700">{item.sku || item.asset_tag || '-'}</td>
                                        <td className="px-6 py-4 text-slate-700">{item.department?.name || '-'}</td>
                                        <td className="px-6 py-4 text-slate-700">{item.quantity}</td>
                                        <td className="px-6 py-4">
                                            <span className={`inline-flex rounded-full px-3 py-1 text-xs font-semibold ${
                                                item.status === 'available'
                                                    ? 'bg-emerald-100 text-emerald-700'
                                                    : item.status === 'assigned'
                                                      ? 'bg-blue-100 text-blue-700'
                                                      : item.status === 'maintenance'
                                                        ? 'bg-orange-100 text-orange-700'
                                                        : 'bg-slate-100 text-slate-700'
                                            }`}>
                                                {item.status}
                                            </span>
                                        </td>
                                        <td className="space-x-2 px-6 py-4">
                                            <button
                                                type="button"
                                                onClick={() => handleEdit(item)}
                                                className="text-blue-600 hover:underline"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                type="button"
                                                onClick={() => handleDelete(item.id)}
                                                className="text-red-600 hover:underline"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="6" className="px-6 py-10 text-center text-slate-500">
                                        No assets found.
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