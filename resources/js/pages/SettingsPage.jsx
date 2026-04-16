import React, { useEffect, useMemo, useState } from 'react';
import apiClient from '../api/client';
import { useSettings } from '../context/SettingsContext';
import { useAuth } from '../context/AuthContext';

const settingGroups = [
    {
        title: 'Branding',
        keys: ['system_name', 'system_tagline', 'company_name', 'support_email'],
    },
    {
        title: 'Operations',
        keys: ['low_stock_threshold', 'assignment_overdue_days', 'items_per_page'],
    },
    {
        title: 'Automation',
        keys: ['email_notifications_enabled', 'maintenance_alerts_enabled', 'allow_user_impersonation'],
    },
];

const labels = {
    system_name: 'System Name',
    system_tagline: 'System Tagline',
    company_name: 'Company Name',
    support_email: 'Support Email',
    low_stock_threshold: 'Low Stock Threshold',
    assignment_overdue_days: 'Assignment Overdue Days',
    items_per_page: 'Items Per Page',
    email_notifications_enabled: 'Email Notifications Enabled',
    maintenance_alerts_enabled: 'Maintenance Alerts Enabled',
    allow_user_impersonation: 'Allow Switch User',
};

function isToggle(key) {
    return [
        'email_notifications_enabled',
        'maintenance_alerts_enabled',
        'allow_user_impersonation',
    ].includes(key);
}

function initials(name) {
    if (!name) {
        return 'SA';
    }

    return name
        .split(' ')
        .map((part) => part[0])
        .join('')
        .slice(0, 2)
        .toUpperCase();
}

function AvatarPreview({ user, previewUrl }) {
    const imageUrl = previewUrl || user?.profile_photo_url || '';

    if (imageUrl) {
        return (
            <img
                src={imageUrl}
                alt={user?.name || 'Profile'}
                className="h-24 w-24 rounded-2xl object-cover ring-4 ring-slate-100"
            />
        );
    }

    return (
        <div className="flex h-24 w-24 items-center justify-center rounded-2xl bg-slate-900 text-xl font-bold text-white ring-4 ring-slate-100">
            {initials(user?.name)}
        </div>
    );
}

export default function SettingsPage() {
    const { settings, refreshSettings } = useSettings();
    const { user, refreshUser } = useAuth();

    const [form, setForm] = useState(settings);
    const [saving, setSaving] = useState(false);
    const [photoSaving, setPhotoSaving] = useState(false);
    const [photoFile, setPhotoFile] = useState(null);
    const [photoPreview, setPhotoPreview] = useState('');
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [photoError, setPhotoError] = useState('');
    const [photoSuccess, setPhotoSuccess] = useState('');

    useEffect(() => {
        setForm(settings);
    }, [settings]);

    useEffect(() => {
        return () => {
            if (photoPreview) {
                URL.revokeObjectURL(photoPreview);
            }
        };
    }, [photoPreview]);

    async function handleSave() {
        try {
            setSaving(true);
            setError('');
            setSuccess('');

            const entries = Object.entries(form);

            await Promise.all(
                entries.map(([key, value]) =>
                    apiClient.put(`/settings/${key}`, { value: String(value ?? '') })
                )
            );

            await refreshSettings();
            setSuccess('Settings saved successfully.');
        } catch (err) {
            setError(err?.response?.data?.message || 'Failed to save settings');
        } finally {
            setSaving(false);
        }
    }

    function handlePhotoChange(event) {
        const file = event.target.files?.[0] || null;

        setPhotoError('');
        setPhotoSuccess('');
        setPhotoFile(file);

        if (photoPreview) {
            URL.revokeObjectURL(photoPreview);
        }

        if (file) {
            setPhotoPreview(URL.createObjectURL(file));
        } else {
            setPhotoPreview('');
        }
    }

    async function handlePhotoUpload() {
        if (!user) {
            setPhotoError('You must be logged in.');
            return;
        }

        if (!photoFile) {
            setPhotoError('Please choose an image first.');
            return;
        }

        try {
            setPhotoSaving(true);
            setPhotoError('');
            setPhotoSuccess('');

            const payload = new FormData();
            payload.append('name', user.name || '');
            payload.append('email', user.email || '');
            payload.append('profile_photo', photoFile);
            payload.append('_method', 'PUT');

            await apiClient.post('/profile', payload, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            await refreshUser();

            setPhotoSuccess('Profile image updated successfully.');
            setPhotoFile(null);

            if (photoPreview) {
                URL.revokeObjectURL(photoPreview);
            }

            setPhotoPreview('');
        } catch (err) {
            setPhotoError(err?.response?.data?.message || 'Failed to update profile image');
        } finally {
            setPhotoSaving(false);
        }
    }

    async function handlePhotoDelete() {
        try {
            setPhotoSaving(true);
            setPhotoError('');
            setPhotoSuccess('');

            await apiClient.delete('/profile/photo');
            await refreshUser();

            setPhotoFile(null);

            if (photoPreview) {
                URL.revokeObjectURL(photoPreview);
            }

            setPhotoPreview('');
            setPhotoSuccess('Profile image removed successfully.');
        } catch (err) {
            setPhotoError(err?.response?.data?.message || 'Failed to remove profile image');
        } finally {
            setPhotoSaving(false);
        }
    }

    const groups = useMemo(
        () =>
            settingGroups.map((group) => ({
                ...group,
                entries: group.keys.map((key) => ({
                    key,
                    label: labels[key] || key,
                    value: form[key] ?? '',
                })),
            })),
        [form]
    );

    return (
        <div className="space-y-6">
            <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900">Settings</h1>
                    <p className="mt-1 text-sm text-slate-500">Configure system settings</p>
                </div>

                <button
                    type="button"
                    onClick={handleSave}
                    disabled={saving}
                    className="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                >
                    {saving ? 'Saving...' : 'Save All Changes'}
                </button>
            </div>

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

            <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div className="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 className="text-lg font-semibold text-slate-900">Profile Image</h2>
                        <p className="mt-1 text-sm text-slate-500">
                            Update the avatar shown in the sidebar and header.
                        </p>
                    </div>

                    <div className="flex w-full max-w-3xl flex-col gap-6 sm:flex-row sm:items-center">
                        <AvatarPreview user={user} previewUrl={photoPreview} />

                        <div className="flex-1 space-y-4">
                            <div>
                                <label className="mb-2 block text-sm font-medium text-slate-700">
                                    Upload Image
                                </label>
                                <input
                                    type="file"
                                    accept="image/png,image/jpeg,image/jpg,image/webp"
                                    onChange={handlePhotoChange}
                                    className="block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200"
                                />
                                <p className="mt-2 text-xs text-slate-500">
                                    Accepted: JPG, PNG, WEBP. Max size: 2MB.
                                </p>
                            </div>

                            {photoError ? (
                                <div className="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    {photoError}
                                </div>
                            ) : null}

                            {photoSuccess ? (
                                <div className="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                                    {photoSuccess}
                                </div>
                            ) : null}

                            <div className="flex flex-wrap gap-3">
                                <button
                                    type="button"
                                    onClick={handlePhotoUpload}
                                    disabled={photoSaving || !photoFile}
                                    className="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    {photoSaving ? 'Updating...' : 'Update Profile Image'}
                                </button>

                                <button
                                    type="button"
                                    onClick={handlePhotoDelete}
                                    disabled={photoSaving || !user?.profile_photo_url}
                                    className="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    Remove Image
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="grid grid-cols-1 gap-6 xl:grid-cols-3">
                {groups.map((group) => (
                    <div key={group.title} className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 className="text-lg font-semibold text-slate-900">{group.title}</h2>

                        <div className="mt-4 space-y-4">
                            {group.entries.map((entry) => (
                                <div key={entry.key}>
                                    <label className="mb-2 block text-sm font-medium text-slate-700">
                                        {entry.label}
                                    </label>

                                    {isToggle(entry.key) ? (
                                        <label className="inline-flex cursor-pointer items-center gap-3">
                                            <input
                                                type="checkbox"
                                                checked={String(entry.value) === '1'}
                                                onChange={(event) =>
                                                    setForm((prev) => ({
                                                        ...prev,
                                                        [entry.key]: event.target.checked ? '1' : '0',
                                                    }))
                                                }
                                                className="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                            />
                                            <span className="text-sm text-slate-600">
                                                {String(entry.value) === '1' ? 'Enabled' : 'Disabled'}
                                            </span>
                                        </label>
                                    ) : (
                                        <input
                                            type={entry.key.includes('email') ? 'email' : 'text'}
                                            value={entry.value}
                                            onChange={(event) =>
                                                setForm((prev) => ({
                                                    ...prev,
                                                    [entry.key]: event.target.value,
                                                }))
                                            }
                                            className="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        />
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}