import React, { useState } from 'react';

const initialInventory = [
  { id: 1, name: 'Product A', quantity: 10, price: 100 },
  { id: 2, name: 'Product B', quantity: 5, price: 200 },
];

const Inventory = () => {
  const [inventory, setInventory] = useState(initialInventory);
  const [editing, setEditing] = useState(null);
  const [form, setForm] = useState({ name: '', quantity: '', price: '' });
  const [showForm, setShowForm] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleAdd = () => {
    setForm({ name: '', quantity: '', price: '' });
    setEditing(null);
    setShowForm(true);
  };

  const handleEdit = (item) => {
    setForm(item);
    setEditing(item.id);
    setShowForm(true);
  };

  const handleDelete = (id) => {
    setInventory(inventory.filter((i) => i.id !== id));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (editing) {
      setInventory(inventory.map((i) => (i.id === editing ? { ...form, id: editing } : i)));
    } else {
      setInventory([...inventory, { ...form, id: Date.now() }]);
    }
    setShowForm(false);
    setEditing(null);
  };

  return (
    <div>
      <h1>Inventory</h1>
      <button onClick={handleAdd} style={{ marginBottom: 16 }}>Add Item</button>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead>
          <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {inventory.map((item) => (
            <tr key={item.id}>
              <td>{item.name}</td>
              <td>{item.quantity}</td>
              <td>{item.price}</td>
              <td>
                <button onClick={() => handleEdit(item)}>Edit</button>
                <button onClick={() => handleDelete(item.id)} style={{ marginLeft: 8 }}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {showForm && (
        <div style={{ marginTop: 24, padding: 16, background: '#fff', borderRadius: 8, boxShadow: '0 2px 8px rgba(0,0,0,0.05)', maxWidth: 400 }}>
          <h2>{editing ? 'Edit Item' : 'Add Item'}</h2>
          <form onSubmit={handleSubmit}>
            <div style={{ marginBottom: 12 }}>
              <label>Name:</label>
              <input name="name" value={form.name} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Quantity:</label>
              <input name="quantity" type="number" value={form.quantity} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Price:</label>
              <input name="price" type="number" value={form.price} onChange={handleChange} required style={{ width: '100%' }} />
            </div>
            <button type="submit">{editing ? 'Update' : 'Add'}</button>
            <button type="button" onClick={() => setShowForm(false)} style={{ marginLeft: 8 }}>Cancel</button>
          </form>
        </div>
      )}
    </div>
  );
};

export default Inventory;