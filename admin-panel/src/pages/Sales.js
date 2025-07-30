import React, { useState } from 'react';

const initialSales = [
  { id: 1, customer: 'John Doe', item: 'Product A', quantity: 2, total: 200 },
  { id: 2, customer: 'Jane Smith', item: 'Product B', quantity: 1, total: 200 },
];

const Sales = () => {
  const [sales, setSales] = useState(initialSales);
  const [editing, setEditing] = useState(null);
  const [form, setForm] = useState({ customer: '', item: '', quantity: '', total: '' });
  const [showForm, setShowForm] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleAdd = () => {
    setForm({ customer: '', item: '', quantity: '', total: '' });
    setEditing(null);
    setShowForm(true);
  };

  const handleEdit = (sale) => {
    setForm(sale);
    setEditing(sale.id);
    setShowForm(true);
  };

  const handleDelete = (id) => {
    setSales(sales.filter((s) => s.id !== id));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (editing) {
      setSales(sales.map((s) => (s.id === editing ? { ...form, id: editing } : s)));
    } else {
      setSales([...sales, { ...form, id: Date.now() }]);
    }
    setShowForm(false);
    setEditing(null);
  };

  return (
    <div>
      <h1>Sales</h1>
      <button onClick={handleAdd} style={{ marginBottom: 16 }}>Add Sale</button>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {sales.map((sale) => (
            <tr key={sale.id}>
              <td>{sale.customer}</td>
              <td>{sale.item}</td>
              <td>{sale.quantity}</td>
              <td>{sale.total}</td>
              <td>
                <button onClick={() => handleEdit(sale)}>Edit</button>
                <button onClick={() => handleDelete(sale.id)} style={{ marginLeft: 8 }}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {showForm && (
        <div style={{ marginTop: 24, padding: 16, background: '#fff', borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.05)', maxWidth: 400 }}>
          <h2>{editing ? 'Edit Sale' : 'Add Sale'}</h2>
          <form onSubmit={handleSubmit}>
            <div style={{ marginBottom: 12 }}>
              <label>Customer:</label>
              <input name="customer" value={form.customer} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Item:</label>
              <input name="item" value={form.item} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Quantity:</label>
              <input name="quantity" type="number" value={form.quantity} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Total:</label>
              <input name="total" type="number" value={form.total} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <button type="submit">{editing ? 'Update' : 'Add'}</button>
            <button type="button" onClick={() => setShowForm(false)} style={{ marginLeft: 8 }}>Cancel</button>
          </form>
        </div>
      )}
    </div>
  );
};

export default Sales;