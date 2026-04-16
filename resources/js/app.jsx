import '../css/app.css';
import './bootstrap';

import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';

import { AuthProvider, useAuth } from './context/AuthContext';
import { SettingsProvider } from './context/SettingsContext';
import Layout from './components/Layout';

import LoginPage from './pages/LoginPage';
import DashboardPage from './pages/DashboardPage';
import ItemsPage from './pages/ItemsPage';
import AssignmentsPage from './pages/AssignmentsPage';
import InventoryPage from './pages/InventoryPage';
import CategoriesPage from './pages/CategoriesPage';
import DepartmentsPage from './pages/DepartmentsPage';
import SuppliersPage from './pages/SuppliersPage';
import NotificationsPage from './pages/NotificationsPage';
import ProfilePage from './pages/ProfilePage';
import UsersPage from './pages/UsersPage';
import SettingsPage from './pages/SettingsPage';

function LoadingScreen() {
    return (
        <div className="flex min-h-screen items-center justify-center bg-slate-100">
            <div className="rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
                <p className="text-sm text-slate-500">Loading application...</p>
            </div>
        </div>
    );
}

function GuestRoute({ children }) {
    const { loading, isAuthenticated } = useAuth();

    if (loading) {
        return <LoadingScreen />;
    }

    if (isAuthenticated) {
        return <Navigate to="/dashboard" replace />;
    }

    return children;
}

function ProtectedRoute({ children }) {
    const { loading, isAuthenticated } = useAuth();

    if (loading) {
        return <LoadingScreen />;
    }

    if (!isAuthenticated) {
        return <Navigate to="/login" replace />;
    }

    return <Layout>{children}</Layout>;
}

function AppRoutes() {
    return (
        <Routes>
            <Route path="/" element={<Navigate to="/dashboard" replace />} />

            <Route
                path="/login"
                element={
                    <GuestRoute>
                        <LoginPage />
                    </GuestRoute>
                }
            />

            <Route
                path="/dashboard"
                element={
                    <ProtectedRoute>
                        <DashboardPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/items"
                element={
                    <ProtectedRoute>
                        <ItemsPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/assignments"
                element={
                    <ProtectedRoute>
                        <AssignmentsPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/inventory"
                element={
                    <ProtectedRoute>
                        <InventoryPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/categories"
                element={
                    <ProtectedRoute>
                        <CategoriesPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/departments"
                element={
                    <ProtectedRoute>
                        <DepartmentsPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/suppliers"
                element={
                    <ProtectedRoute>
                        <SuppliersPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/notifications"
                element={
                    <ProtectedRoute>
                        <NotificationsPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/profile"
                element={
                    <ProtectedRoute>
                        <ProfilePage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/users"
                element={
                    <ProtectedRoute>
                        <UsersPage />
                    </ProtectedRoute>
                }
            />

            <Route
                path="/settings"
                element={
                    <ProtectedRoute>
                        <SettingsPage />
                    </ProtectedRoute>
                }
            />

            <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
    );
}

function App() {
    return (
        <React.StrictMode>
            <BrowserRouter>
                <AuthProvider>
                    <SettingsProvider>
                        <AppRoutes />
                    </SettingsProvider>
                </AuthProvider>
            </BrowserRouter>
        </React.StrictMode>
    );
}

const rootElement = document.getElementById('app');

if (rootElement) {
    if (!window.__nextgenReactRoot) {
        window.__nextgenReactRoot = ReactDOM.createRoot(rootElement);
    }

    window.__nextgenReactRoot.render(<App />);
}