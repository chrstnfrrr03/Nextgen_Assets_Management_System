import React, { useEffect, useMemo, useRef, useState } from 'react';
import { NavLink, Outlet, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useSettings } from '../context/SettingsContext';
import { navigationItems } from '../config/navigation';
import useNotifications from '../hooks/useNotifications';

function SearchIcon() {
    return (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            className="h-4 w-4"
            aria-hidden="true"
        >
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3.5-3.5" />
        </svg>
    );
}

function MenuIcon() {
    return (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            className="h-5 w-5"
            aria-hidden="true"
        >
            <path d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    );
}

function CloseIcon() {
    return (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            className="h-5 w-5"
            aria-hidden="true"
        >
            <path d="M18 6 6 18M6 6l12 12" />
        </svg>
    );
}

function DashboardIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M3 13h8V3H3zM13 21h8v-6h-8zM13 11h8V3h-8zM3 21h8v-6H3z" />
        </svg>
    );
}

function BoxIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="m3 7 9-4 9 4-9 4-9-4Z" />
            <path d="m3 7 9 4 9-4" />
            <path d="M12 11v10" />
            <path d="M3 7v10l9 4 9-4V7" />
        </svg>
    );
}

function ClipboardIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M9 5h6" />
            <path d="M8 3h8v4H8z" />
            <path d="M6 7h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z" />
        </svg>
    );
}

function LayersIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="m12 3 9 4.5-9 4.5L3 7.5 12 3Z" />
            <path d="m3 12.5 9 4.5 9-4.5" />
            <path d="m3 17 9 4 9-4" />
        </svg>
    );
}

function TruckIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M10 17h4V5H2v12h3" />
            <path d="M14 8h4l4 4v5h-3" />
            <circle cx="7.5" cy="17.5" r="2.5" />
            <circle cx="17.5" cy="17.5" r="2.5" />
        </svg>
    );
}

function TagIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M20 10 10 20l-7-7L13 3h7v7Z" />
            <circle cx="17" cy="7" r="1" />
        </svg>
    );
}

function BuildingIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M3 21h18" />
            <path d="M5 21V7l7-4 7 4v14" />
            <path d="M9 10h.01M15 10h.01M9 14h.01M15 14h.01" />
        </svg>
    );
}

function BellIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M15 17H5a2 2 0 0 1-2-2c0-1.2.5-2.3 1.4-3.1L6 10.5V8a6 6 0 1 1 12 0v2.5l1.6 1.4A4.2 4.2 0 0 1 21 15a2 2 0 0 1-2 2h-4" />
            <path d="M9 17a3 3 0 0 0 6 0" />
        </svg>
    );
}

function UserIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <circle cx="12" cy="8" r="4" />
            <path d="M6 20a6 6 0 0 1 12 0" />
        </svg>
    );
}

function SettingsIcon() {
    return (
        <svg className="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true">
            <path d="M12 3v2.2M12 18.8V21M4.9 4.9l1.6 1.6M17.5 17.5l1.6 1.6M3 12h2.2M18.8 12H21M4.9 19.1l1.6-1.6M17.5 6.5l1.6-1.6" />
            <circle cx="12" cy="12" r="4" />
        </svg>
    );
}

function getNavIcon(path) {
    switch (path) {
        case '/dashboard':
            return <DashboardIcon />;
        case '/items':
            return <BoxIcon />;
        case '/assignments':
            return <ClipboardIcon />;
        case '/inventory':
            return <LayersIcon />;
        case '/suppliers':
            return <TruckIcon />;
        case '/categories':
            return <TagIcon />;
        case '/departments':
            return <BuildingIcon />;
        case '/notifications':
            return <BellIcon />;
        case '/users':
        case '/profile':
            return <UserIcon />;
        case '/settings':
            return <SettingsIcon />;
        default:
            return <BoxIcon />;
    }
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

function Avatar({ user }) {
    const [broken, setBroken] = useState(false);

    if (user?.profile_photo_url && !broken) {
        return (
            <img
                src={user.profile_photo_url}
                alt={user.name || 'User'}
                className="h-10 w-10 rounded-full object-cover ring-2 ring-white/10"
                onError={() => setBroken(true)}
            />
        );
    }

    return (
        <div className="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-xs font-bold text-white">
            {initials(user?.name)}
        </div>
    );
}

function BrandBlock({ systemName, systemTagline }) {
    return (
        <div className="flex items-center gap-3">
            <img
                src="/images/nextgen-logo.png"
                alt="NextGen Technology"
                className="h-11 w-auto max-w-[140px] object-contain"
                onError={(event) => {
                    event.currentTarget.style.display = 'none';
                }}
            />

            <div className="min-w-0">
                <h1 className="truncate text-base font-bold tracking-wide text-white">{systemName}</h1>
                <p className="truncate text-xs text-slate-400">{systemTagline}</p>
            </div>
        </div>
    );
}

function SidebarNavItem({ item, onClick, unreadCount }) {
    return (
        <NavLink
            to={item.to}
            onClick={onClick}
            className={({ isActive }) =>
                [
                    'group flex items-center justify-between gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                    isActive ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-200 hover:bg-slate-800 hover:text-white',
                ].join(' ')
            }
        >
            <span className="flex items-center gap-3">
                <span className="shrink-0">{getNavIcon(item.to)}</span>
                <span>{item.label}</span>
            </span>

            {item.to === '/notifications' && unreadCount > 0 ? (
                <span className="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-bold text-white">
                    {unreadCount}
                </span>
            ) : null}
        </NavLink>
    );
}

function SidebarContent({
    systemName,
    systemTagline,
    companyWebsite,
    displayName,
    user,
    onLogout,
    onNavigate,
    unreadCount,
}) {
    return (
        <>
            <div className="border-b border-slate-800 px-5 py-5">
                <BrandBlock systemName={systemName} systemTagline={systemTagline} />

                <a
                    href={companyWebsite}
                    target="_blank"
                    rel="noreferrer"
                    className="mt-3 inline-flex text-xs font-medium text-blue-300 transition hover:text-blue-200"
                >
                    nextgenpng.net
                </a>
            </div>

            <nav className="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                {navigationItems.map((item) => (
                    <SidebarNavItem key={item.to} item={item} onClick={onNavigate} unreadCount={unreadCount} />
                ))}
            </nav>

            <div className="border-t border-slate-800 px-4 py-4">
                <div className="mb-3 rounded-2xl bg-slate-900/80 px-4 py-3">
                    <div className="flex items-center gap-3">
                        <Avatar user={user} />
                        <div className="min-w-0">
                            <p className="text-xs text-slate-400">Signed in as</p>
                            <p className="truncate text-sm font-medium text-white">{displayName}</p>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    onClick={onLogout}
                    className="w-full rounded-xl border border-slate-700 px-4 py-3 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white"
                >
                    Logout
                </button>
            </div>
        </>
    );
}

function NotificationDropdown({ notifications, unreadCount, onOpen, onMarkAllRead, onClose }) {
    return (
        <div className="absolute right-0 top-12 z-50 w-[360px] rounded-2xl border border-slate-200 bg-white shadow-xl">
            <div className="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <div>
                    <h3 className="text-sm font-semibold text-slate-900">Notifications</h3>
                    <p className="text-xs text-slate-500">{unreadCount} unread</p>
                </div>

                <button
                    type="button"
                    onClick={onMarkAllRead}
                    className="text-xs font-semibold text-blue-600 hover:text-blue-700"
                >
                    Mark all read
                </button>
            </div>

            <div className="max-h-96 overflow-y-auto">
                {notifications.length === 0 ? (
                    <div className="px-4 py-6 text-sm text-slate-500">No notifications available.</div>
                ) : (
                    notifications.map((notification) => (
                        <button
                            key={notification.id}
                            type="button"
                            onClick={() => onOpen(notification)}
                            className={[
                                'block w-full border-b border-slate-100 px-4 py-3 text-left transition hover:bg-slate-50',
                                notification.is_read ? 'bg-white' : 'bg-blue-50/40',
                            ].join(' ')}
                        >
                            <div className="flex items-start justify-between gap-3">
                                <div className="min-w-0">
                                    <p className="text-sm font-semibold text-slate-900">{notification.title}</p>
                                    <p className="mt-1 line-clamp-2 text-xs text-slate-600">{notification.message}</p>
                                    <p className="mt-2 text-[11px] text-slate-400">
                                        {new Date(notification.created_at).toLocaleString()}
                                    </p>
                                </div>

                                {!notification.is_read ? (
                                    <span className="mt-1 h-2.5 w-2.5 rounded-full bg-blue-600" />
                                ) : null}
                            </div>
                        </button>
                    ))
                )}
            </div>

            <div className="border-t border-slate-200 px-4 py-3">
                <NavLink
                    to="/notifications"
                    onClick={onClose}
                    className="text-sm font-semibold text-blue-600 hover:text-blue-700"
                >
                    View all notifications
                </NavLink>
            </div>
        </div>
    );
}

export default function Layout({ children }) {
    const location = useLocation();
    const navigate = useNavigate();
    const { user, logout, stopImpersonation } = useAuth();
    const { settings } = useSettings();
    const { notifications, unreadCount, markRead, markAllRead } = useNotifications();

    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [globalSearch, setGlobalSearch] = useState('');
    const [notificationsOpen, setNotificationsOpen] = useState(false);
    const notificationsRef = useRef(null);

    useEffect(() => {
        const params = new URLSearchParams(location.search);
        setGlobalSearch(params.get('search') ?? '');
    }, [location.search]);

    useEffect(() => {
        function handleClickOutside(event) {
            if (notificationsRef.current && !notificationsRef.current.contains(event.target)) {
                setNotificationsOpen(false);
            }
        }

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    const displayName = useMemo(() => {
        if (!user) {
            return 'System Administrator';
        }

        return user.name || user.email || 'System Administrator';
    }, [user]);

    const systemName = settings.system_name || 'NextGen Technology';
    const systemTagline = settings.system_tagline || 'Asset Management System';
    const companyWebsite = settings.company_website || 'https://nextgenpng.net/';

    async function handleLogout() {
        try {
            await logout();
        } finally {
            navigate('/login', { replace: true });
        }
    }

    async function handleStopImpersonation() {
        try {
            await stopImpersonation();
            navigate('/users');
        } catch (error) {
            console.error('Failed to stop impersonation', error);
        }
    }

    function handleGlobalSearchSubmit(event) {
        event.preventDefault();

        const query = globalSearch.trim();

        if (!query) {
            navigate('/items');
            return;
        }

        navigate(`/items?search=${encodeURIComponent(query)}`);
    }

    function closeSidebar() {
        setSidebarOpen(false);
    }

    async function handleOpenNotification(notification) {
        try {
            if (!notification.is_read) {
                await markRead(notification.id);
            }

            setNotificationsOpen(false);
            navigate(notification.url || '/notifications');
        } catch (error) {
            console.error('Failed to open notification', error);
        }
    }

    return (
        <div className="flex min-h-screen bg-slate-100 text-slate-800">
            <aside className="hidden w-72 flex-col bg-slate-950 text-white lg:flex">
                <SidebarContent
                    systemName={systemName}
                    systemTagline={systemTagline}
                    companyWebsite={companyWebsite}
                    displayName={displayName}
                    user={user}
                    onLogout={handleLogout}
                    onNavigate={closeSidebar}
                    unreadCount={unreadCount}
                />
            </aside>

            {sidebarOpen ? (
                <div className="fixed inset-0 z-40 lg:hidden">
                    <button
                        type="button"
                        aria-label="Close menu"
                        className="absolute inset-0 bg-slate-950/60"
                        onClick={closeSidebar}
                    />
                    <aside className="relative z-10 flex h-full w-72 flex-col bg-slate-950 text-white shadow-2xl">
                        <div className="flex items-center justify-between border-b border-slate-800 px-5 py-5">
                            <BrandBlock systemName={systemName} systemTagline={systemTagline} />

                            <button
                                type="button"
                                onClick={closeSidebar}
                                className="inline-flex items-center justify-center rounded-xl border border-slate-700 p-2 text-slate-300 transition hover:bg-slate-800 hover:text-white"
                                aria-label="Close menu"
                            >
                                <CloseIcon />
                            </button>
                        </div>

                        <div className="flex min-h-0 flex-1 flex-col">
                            <SidebarContent
                                systemName={systemName}
                                systemTagline={systemTagline}
                                companyWebsite={companyWebsite}
                                displayName={displayName}
                                user={user}
                                onLogout={handleLogout}
                                onNavigate={closeSidebar}
                                unreadCount={unreadCount}
                            />
                        </div>
                    </aside>
                </div>
            ) : null}

            <div className="flex min-w-0 flex-1 flex-col">
                {user?.is_impersonating ? (
                    <div className="border-b border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 sm:px-6">
                        <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <span>You are currently switched into another user account.</span>

                            <button
                                type="button"
                                onClick={handleStopImpersonation}
                                className="rounded-lg border border-amber-300 px-3 py-1.5 font-medium transition hover:bg-amber-100"
                            >
                                Return to Admin
                            </button>
                        </div>
                    </div>
                ) : null}

                <header className="border-b border-slate-200 bg-white">
                    <div className="flex flex-col gap-4 px-4 py-4 sm:px-6 xl:flex-row xl:items-center xl:justify-between">
                        <div className="flex items-center gap-3">
                            <button
                                type="button"
                                onClick={() => setSidebarOpen(true)}
                                className="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 lg:hidden"
                                aria-label="Open menu"
                            >
                                <MenuIcon />
                            </button>

                            <div>
                                <h2 className="text-lg font-semibold text-slate-900">{systemName}</h2>
                                <p className="text-xs text-slate-500">{systemTagline}</p>
                            </div>
                        </div>

                        <form onSubmit={handleGlobalSearchSubmit} className="flex w-full max-w-xl items-center gap-2">
                            <div className="relative flex-1">
                                <input
                                    type="text"
                                    value={globalSearch}
                                    onChange={(event) => setGlobalSearch(event.target.value)}
                                    placeholder="Search assets by name, asset tag or serial number..."
                                    className="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 pr-10 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                />
                                <span className="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                    <SearchIcon />
                                </span>
                            </div>

                            <button
                                type="submit"
                                className="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                            >
                                Search
                            </button>
                        </form>

                        <div className="flex items-center gap-3">
                            <div className="relative" ref={notificationsRef}>
                                <button
                                    type="button"
                                    onClick={() => setNotificationsOpen((prev) => !prev)}
                                    className="relative inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2.5 text-slate-700 transition hover:bg-slate-50"
                                >
                                    <BellIcon />
                                    {unreadCount > 0 ? (
                                        <span className="absolute -right-1 -top-1 rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white">
                                            {unreadCount}
                                        </span>
                                    ) : null}
                                </button>

                                {notificationsOpen ? (
                                    <NotificationDropdown
                                        notifications={notifications}
                                        unreadCount={unreadCount}
                                        onOpen={handleOpenNotification}
                                        onMarkAllRead={markAllRead}
                                        onClose={() => setNotificationsOpen(false)}
                                    />
                                ) : null}
                            </div>

                            <div className="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                                <Avatar user={user} />
                                <span>{displayName}</span>
                            </div>

                            <button
                                type="button"
                                onClick={handleLogout}
                                className="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            >
                                Logout
                            </button>
                        </div>
                    </div>
                </header>

                <main className="flex-1 p-4 sm:p-6">
                    {children ?? <Outlet />}
                </main>
            </div>
        </div>
    );
}