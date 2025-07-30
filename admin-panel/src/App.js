import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, NavLink } from 'react-router-dom';
import './App.css';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import Customers from './pages/Customers';
import Inventory from './pages/Inventory';
import Sales from './pages/Sales';
import Invoices from './pages/Invoices';
import Reports from './pages/Reports';
import Settings from './pages/Settings';

function App() {
  return (
    <Router>
      <div className="app-container">
        <aside className="sidebar">
          <h2>Admin Panel</h2>
          <nav>
            <NavLink to="/dashboard">Dashboard</NavLink>
            <NavLink to="/customers">Customers</NavLink>
            <NavLink to="/inventory">Inventory</NavLink>
            <NavLink to="/sales">Sales</NavLink>
            <NavLink to="/invoices">Invoices</NavLink>
            <NavLink to="/reports">Reports</NavLink>
            <NavLink to="/settings">Settings</NavLink>
            <NavLink to="/login">Logout</NavLink>
          </nav>
        </aside>
        <main className="main-content">
          <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/customers" element={<Customers />} />
            <Route path="/inventory" element={<Inventory />} />
            <Route path="/sales" element={<Sales />} />
            <Route path="/invoices" element={<Invoices />} />
            <Route path="/reports" element={<Reports />} />
            <Route path="/settings" element={<Settings />} />
            <Route path="/" element={<Navigate to="/dashboard" />} />
          </Routes>
        </main>
      </div>
    </Router>
  );
}

export default App;
