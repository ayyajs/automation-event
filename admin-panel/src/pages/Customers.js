import React, { useState } from 'react';

const initialCustomers = [
  { id: 1, name: 'John Doe', email: 'john@example.com', phone: '1234567890' },
  { id: 2, name: 'Jane Smith', email: 'jane@example.com', phone: '9876543210' },
];

const Customers = () => {
  const [customers, setCustomers] = useState(initialCustomers);
  const [editing, setEditing] = useState(null);
  const [form, setForm] = useState({ name: '', email: '', phone: '' });
  const [showForm, setShowForm] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleAdd = () => {
    setForm({ name: '', email: '', phone: '' });
    setEditing(null);
    setShowForm(true);
  };

  const handleEdit = (customer) => {
    setForm(customer);
    setEditing(customer.id);
    setShowForm(true);
  };

  const handleDelete = (id) => {
    setCustomers(customers.filter((c) => c.id !== id));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (editing) {
      setCustomers(customers.map((c) => (c.id === editing ? { ...form, id: editing } : c)));
    } else {
      setCustomers([...customers, { ...form, id: Date.now() }]);
    }
    setShowForm(false);
    setEditing(null);
  };

  return (
    <div>
      <h1>Customers</h1>
      <button onClick={handleAdd} style={{ marginBottom: 16 }}>Add Customer</button>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {customers.map((customer) => (
            <tr key={customer.id}>
              <td>{customer.name}</td>
              <td>{customer.email}</td>
              <td>{customer.phone}</td>
              <td>
                <button onClick={() => handleEdit(customer)}>Edit</button>
                <button onClick={() => handleDelete(customer.id)} style={{ marginLeft: 8 }}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {showForm && (
        <div style={{ marginTop: 24, padding: 16, background: '#fff', borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.05)', maxWidth: 400 }}>
          <h2>{editing ? 'Edit Customer' : 'Add Customer'}</h2>
          <form onSubmit={handleSubmit}>
            <div style={{ marginBottom: 12 }}>
              <label>Name:</label>
              <input name="name" value={form.name} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Email:</label>
              <input name="email" value={form.email} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Phone:</label>
              <input name="phone" value={form.phone} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <button type="submit">{editing ? 'Update' : 'Add'}</button>
            <button type="button" onClick={() => setShowForm(false)} style={{ marginLeft: 8 }}>Cancel</button>
          </form>
        </div>
      )}
    </div>
  );
};

export default Customers;