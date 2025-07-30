import React, { useState, useEffect } from 'react';
import { Typography, Button, Table, TableHead, TableRow, TableCell, TableBody, TextField, Dialog, DialogTitle, DialogContent, DialogActions } from '@mui/material';

function CustomersPage() {
  const [customers, setCustomers] = useState([]);
  const [open, setOpen] = useState(false);
  const [form, setForm] = useState({ name: '', email: '', phone: '' });
  const [editingId, setEditingId] = useState(null);

  const fetchCustomers = async () => {
    const res = await fetch('/api/customers');
    setCustomers(await res.json());
  };

  useEffect(() => {
    fetchCustomers();
  }, []);

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async () => {
    const method = editingId ? 'PUT' : 'POST';
    const url = '/api/customers' + (editingId ? `?id=${editingId}` : '');
    await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(form) });
    setOpen(false);
    setForm({ name: '', email: '', phone: '' });
    setEditingId(null);
    fetchCustomers();
  };

  const handleEdit = (customer) => {
    setForm({ name: customer.name, email: customer.email, phone: customer.phone });
    setEditingId(customer.id);
    setOpen(true);
  };

  const handleDelete = async (id) => {
    if (confirm('Delete this customer?')) {
      await fetch(`/api/customers?id=${id}`, { method: 'DELETE' });
      fetchCustomers();
    }
  };

  return (
    <div>
      <Typography variant="h4" gutterBottom>Customers</Typography>
      <Button variant="contained" onClick={() => setOpen(true)}>Add Customer</Button>
      <Table sx={{ mt: 2 }}>
        <TableHead>
          <TableRow>
            <TableCell>Name</TableCell>
            <TableCell>Email</TableCell>
            <TableCell>Phone</TableCell>
            <TableCell>Actions</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {customers.map((c) => (
            <TableRow key={c.id}>
              <TableCell>{c.name}</TableCell>
              <TableCell>{c.email}</TableCell>
              <TableCell>{c.phone}</TableCell>
              <TableCell>
                <Button size="small" onClick={() => handleEdit(c)}>Edit</Button>
                <Button size="small" color="error" onClick={() => handleDelete(c.id)}>Delete</Button>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>

      <Dialog open={open} onClose={() => setOpen(false)}>
        <DialogTitle>{editingId ? 'Edit Customer' : 'Add Customer'}</DialogTitle>
        <DialogContent sx={{ display: 'flex', flexDirection: 'column', gap: 2, mt: 1 }}>
          <TextField label="Name" name="name" value={form.name} onChange={handleChange} required />
          <TextField label="Email" name="email" value={form.email} onChange={handleChange} />
          <TextField label="Phone" name="phone" value={form.phone} onChange={handleChange} />
        </DialogContent>
        <DialogActions>
          <Button onClick={() => setOpen(false)}>Cancel</Button>
          <Button onClick={handleSubmit}>{editingId ? 'Update' : 'Create'}</Button>
        </DialogActions>
      </Dialog>
    </div>
  );
}

export default CustomersPage;