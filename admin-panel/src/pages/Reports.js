import React, { useState } from 'react';

const initialReports = [
  { id: 1, date: '2024-07-30', customer: 'John Doe', item: 'Product A', quantity: 2, total: 200 },
  { id: 2, date: '2024-07-30', customer: 'Jane Smith', item: 'Product B', quantity: 1, total: 200 },
  { id: 3, date: '2024-07-29', customer: 'John Doe', item: 'Product B', quantity: 1, total: 200 },
];

const Reports = () => {
  const [date, setDate] = useState('2024-07-30');
  const [reports] = useState(initialReports);

  const filtered = reports.filter((r) => r.date === date);

  return (
    <div>
      <h1>Daily Sales Report</h1>
      <div style={{ marginBottom: 16 }}>
        <label>Date: </label>
        <input type="date" value={date} onChange={e => setDate(e.target.value)} />
      </div>
      <table style={{ width: '100%', borderCollapse: 'collapse' }}>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          {filtered.length === 0 ? (
            <tr><td colSpan={4} style={{ textAlign: 'center' }}>No sales for this date.</td></tr>
          ) : (
            filtered.map((r) => (
              <tr key={r.id}>
                <td>{r.customer}</td>
                <td>{r.item}</td>
                <td>{r.quantity}</td>
                <td>{r.total}</td>
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
};

export default Reports;