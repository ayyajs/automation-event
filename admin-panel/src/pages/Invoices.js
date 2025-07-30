import React, { useState } from 'react';

const initialInvoices = [
  { id: 1, number: 'INV-001', customer: 'John Doe', amount: 200, date: '2024-07-30' },
  { id: 2, number: 'INV-002', customer: 'Jane Smith', amount: 400, date: '2024-07-29' },
];

const Invoices = () => {
  const [invoices, setInvoices] = useState(initialInvoices);
  const [editing, setEditing] = useState(null);
  const [form, setForm] = useState({ number: '', customer: '', amount: '', date: '' });
  const [showForm, setShowForm] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleAdd = () => {
    setForm({ number: '', customer: '', amount: '', date: '' });
    setEditing(null);
    setShowForm(true);
  };

  const handleEdit = (invoice) => {
    setForm(invoice);
    setEditing(invoice.id);
    setShowForm(true);
  };

  const handleDelete = (id) => {
    setInvoices(invoices.filter((i) => i.id !== id));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (editing) {
      setInvoices(invoices.map((i) => (i.id === editing ? { ...form, id: editing } : i)));
    } else {
      setInvoices([...invoices, { ...form, id: Date.now() }]);
    }
    setShowForm(false);
    setEditing(null);
  };

  return (
    <div>
      <h1>Invoices</h1>
      <button onClick={handleAdd} style={{ marginBottom: 16 }}>Add Invoice</button>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead>
          <tr>
            <th>Number</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {invoices.map((invoice) => (
            <tr key={invoice.id}>
              <td>{invoice.number}</td>
              <td>{invoice.customer}</td>
              <td>{invoice.amount}</td>
              <td>{invoice.date}</td>
              <td>
                <button onClick={() => handleEdit(invoice)}>Edit</button>
                <button onClick={() => handleDelete(invoice.id)} style={{ marginLeft: 8 }}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {showForm && (
        <div style={{ marginTop: 24, padding: 16, background: '#fff', borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.05)', maxWidth: 400 }}>
          <h2>{editing ? 'Edit Invoice' : 'Add Invoice'}</h2>
          <form onSubmit={handleSubmit}>
            <div style={{ marginBottom: 12 }}>
              <label>Number:</label>
              <input name="number" value={form.number} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Customer:</label>
              <input name="customer" value={form.customer} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Amount:</label>
              <input name="amount" type="number" value={form.amount} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Date:</label>
              <input name="date" type="date" value={form.date} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <button type="submit">{editing ? 'Update' : 'Add'}</button>
            <button type="button" onClick={() => setShowForm(false)} style={{ marginLeft: 8 }}>Cancel</button>
          </form>
        </div>
      )}
    </div>
  );
};

export default Invoices;