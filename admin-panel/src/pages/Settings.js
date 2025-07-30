import React, { useState } from 'react';

const Settings = () => {
  const [form, setForm] = useState({ email: 'admin@example.com', password: '' });
  const [message, setMessage] = useState('');

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setMessage('Settings updated (not saved, demo only)');
  };

  return (
    <div>
      <h1>Settings</h1>
      <form onSubmit={handleSubmit} style={{ maxWidth: 400, background: '#fff', padding: 16, borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.05)' }}>
        <div style={{ marginBottom: 12 }}>
          <label>Email:</label>
          <input name="email" value={form.email} onChange={handleChange} required style={{ width: '100%' }} />
        </div>
        <div style={{ marginBottom: 12 }}>
          <label>New Password:</label>
          <input name="password" type="password" value={form.password} onChange={handleChange} style={{ width: '100%' }} />
        </div>
        <button type="submit">Update Settings</button>
      </form>
      {message && <div style={{ marginTop: 16, color: 'green' }}>{message}</div>}
    </div>
  );
};

export default Settings;