import React, { useEffect, useMemo, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import apiClient from '../api/client';

function initials(name) {
    if (!name) {
        return 'U';
    }

    return name
        .split(' ')
        .map((part) => part[0])
        .join('')
        .slice(0, 2)
        .toUpperCase();
}

export default function ProfilePage() {
    const { user, refreshUser } = useAuth();

    const [form, setForm] = useState({
        name: '',
        email: '',
        current_password: '',
        password: '',
        password_confirmation: '',
    });
    const [photoFile, setPhotoFile] = useState(null);
    const [photoPreview, setPhotoPreview] = useState('');
    const [loading, setLoading] = useState(false);
    const [photoLoading, setPhotoLoading] = useState(false);
    const [success, setSuccess] = useState('');
    const [error, setError] = useState('');

    useEffect(() => {
        if (user) {
            setForm((prev) => ({
                ...prev,
                name: user.name || '',
                email: user.email || '',
            }));
        }
    }, [user]);

    const currentPhoto = useMemo(() => {
        return photoPreview || user?.profile_photo_url || '';
    }, [photoPreview, user]);

    function handlePhotoChange(event) {
        const file = event.target.files?.[0] || null;
        setPhotoFile(file);

        if (file) {
            setPhotoPreview(URL.createObjectURL(file));
        } else {
            setPhotoPreview('');
        }
    }

    async function handleSubmit(event) {
        event.preventDefault();
        setLoading(true);
        setSuccess('');
        setError('');

        try {
            const payload = new FormData();
            payload.append('name', form.name);
            payload.append('email', form.email);

            if (form.current_password) {
                payload.append('current_password', form.current_password);
            }

            if (form.password) {
                payload.append('password', form.password);
                payload.append('password_confirmation', form.password_confirmation);
            }

            if (photoFile) {
                payload.append('profile_photo', photoFile);
            }

            payload.append('_method', 'PUT');

            await apiClient.post('/profile', payload, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            await refreshUser();

            setSuccess('Profile updated successfully.');
            setPhotoFile(null);
            setPhotoPreview('');
            setForm((prev) => ({
                ...prev,
                current_password: '',
                password: '',
                password_confirmation: '',
            }));
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to update profile');
        } finally {
            setLoading(false);
        }
    }

    async function handleDeletePhoto() {
        setPhotoLoading(true);
        setSuccess('');
        setError('');

        try {
            await apiClient.delete('/profile/photo');
            await refreshUser();
            setPhotoFile(null);
            setPhotoPreview('');
            setSuccess('Profile photo deleted successfully.');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to delete profile photo');
        } finally {
            setPhotoLoading(false);
        }
    }

    return (
        <div className="space-y-6">
            <div>
                <h1 className="text-3xl font-bold text-slate-900">Profile</h1>
                <p className="mt-1 text-sm text-slate-500">Manage your account settings</p>
            </div>

            {success ? (
                <div className="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {success}
                </div>
            ) : null}

            {error ? (
                <div className="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {error}
                </div>
            ) : null}

            <div className="grid grid-cols-1 gap-6 xl:grid-cols-3">
                <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-4 text-lg font-semibold">Profile Photo</h2>

                    <div className="flex flex-col items-center gap-4">
                        {currentPhoto ? (
                            <img
                                src={currentPhoto}
                                alt="Profile"
                                className="h-28 w-28 rounded-full object-cover ring-4 ring-slate-100"
                            />
                        ) : (
                            <div className="flex h-28 w-28 items-center justify-center rounded-full bg-slate-900 text-2xl font-bold text-white ring-4 ring-slate-100">
                                {initials(user?.name)}
                            </div>
                        )}

                        <input
                            type="file"
                            accept="image/png,image/jpeg,image/jpg,image/webp"
                            onChange={handlePhotoChange}
                            className="block w-full text-sm text-slate-600"
                        />

                        <button
                            type="button"
                            onClick={handleDeletePhoto}
                            disabled={photoLoading || (!user?.profile_photo_url && !photoPreview)}
                            className="w-full rounded-xl border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {photoLoading ? 'Deleting...' : 'Delete Photo'}
                        </button>
                    </div>
                </div>

                <div className="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 className="mb-6 text-lg font-semibold">Account Information</h2>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                                <input
                                    type="text"
                                    value={form.name}
                                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Email</label>
                                <input
                                    type="email"
                                    value={form.email}
                                    onChange={(e) => setForm({ ...form, email: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <hr className="my-6" />

                        <h3 className="text-base font-semibold text-slate-900">Change Password</h3>

                        <div>
                            <label className="mb-1 block text-sm font-medium text-slate-700">Current Password</label>
                            <input
                                type="password"
                                value={form.current_password}
                                onChange={(e) => setForm({ ...form, current_password: e.target.value })}
                                className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">New Password</label>
                                <input
                                    type="password"
                                    value={form.password}
                                    onChange={(e) => setForm({ ...form, password: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium text-slate-700">Confirm Password</label>
                                <input
                                    type="password"
                                    value={form.password_confirmation}
                                    onChange={(e) => setForm({ ...form, password_confirmation: e.target.value })}
                                    className="w-full rounded-xl border border-slate-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div className="flex gap-3 pt-4">
                            <button
                                type="submit"
                                disabled={loading}
                                className="rounded-xl bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 disabled:opacity-60"
                            >
                                {loading ? 'Saving...' : 'Save Changes'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 className="mb-4 text-lg font-semibold">Account Details</h2>

                <div className="space-y-3 text-sm">
                    <div className="flex justify-between">
                        <span className="text-slate-600">Role:</span>
                        <span className="font-medium text-slate-900">{user?.role || 'N/A'}</span>
                    </div>

                    <div className="flex justify-between">
                        <span className="text-slate-600">Email Verified:</span>
                        <span className="font-medium text-slate-900">{user?.email_verified_at ? 'Yes' : 'No'}</span>
                    </div>

                    <div className="flex justify-between">
                        <span className="text-slate-600">Impersonating:</span>
                        <span className="font-medium text-slate-900">{user?.is_impersonating ? 'Yes' : 'No'}</span>
                    </div>

                    <div className="flex justify-between">
                        <span className="text-slate-600">Member Since:</span>
                        <span className="font-medium text-slate-900">
                            {user?.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A'}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}