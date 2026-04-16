import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function LoginPage() {
    const navigate = useNavigate();
    const { login } = useAuth();

    const [form, setForm] = useState({
        email: 'admin@nextgen.local',
        password: 'password',
    });
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    async function handleSubmit(event) {
        event.preventDefault();
        setLoading(true);
        setError('');

        const result = await login(form.email, form.password);
        setLoading(false);

        if (result.success) {
            navigate('/dashboard', { replace: true });
        } else {
            setError(result.error);
        }
    }

    return (
        <div className="min-h-screen bg-slate-100">
            <div className="grid min-h-screen lg:grid-cols-2">
                <div className="hidden bg-slate-950 p-12 text-white lg:flex lg:flex-col lg:justify-between">
                    <div>
                        <div className="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-slate-200">
                            NextGen Assets
                        </div>

                        <h1 className="mt-8 text-4xl font-bold leading-tight">
                            Smart assets control for modern operations.
                        </h1>

                        <p className="mt-4 max-w-xl text-sm text-slate-300">
                            Track assets, assignments, departments, inventory, notifications, and operational activity in one secure platform.
                        </p>
                    </div>

                    <div className="grid grid-cols-3 gap-4">
                        <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p className="text-xs text-slate-300">Assets</p>
                            <p className="mt-2 text-2xl font-bold">24/7</p>
                        </div>
                        <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p className="text-xs text-slate-300">Security</p>
                            <p className="mt-2 text-2xl font-bold">Role-based</p>
                        </div>
                        <div className="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p className="text-xs text-slate-300">Experience</p>
                            <p className="mt-2 text-2xl font-bold">React SPA</p>
                        </div>
                    </div>
                </div>

                <div className="flex items-center justify-center px-4 py-10">
                    <div className="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div className="mb-8">
                            <h2 className="text-3xl font-bold text-slate-900">Sign in</h2>
                            <p className="mt-2 text-sm text-slate-500">
                                Login to NextGen Assets Management System
                            </p>
                        </div>

                        {error ? (
                            <div className="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {error}
                            </div>
                        ) : null}

                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    value={form.email}
                                    onChange={(e) =>
                                        setForm((prev) => ({ ...prev, email: e.target.value }))
                                    }
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                    required
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    value={form.password}
                                    onChange={(e) =>
                                        setForm((prev) => ({ ...prev, password: e.target.value }))
                                    }
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                    required
                                />
                            </div>

                            <button
                                type="submit"
                                disabled={loading}
                                className="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:opacity-60"
                            >
                                {loading ? 'Signing in...' : 'Login'}
                            </button>
                        </form>

                        <div className="mt-6 rounded-2xl bg-slate-50 px-4 py-3 text-xs text-slate-500">
                            Demo credentials: admin@nextgen.local / password
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
