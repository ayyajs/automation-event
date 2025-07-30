import React, { useState, useEffect } from 'react';
import { Typography } from '@mui/material';

function ReportsPage() {
  const [report, setReport] = useState(null);

  useEffect(() => {
    fetch('/api/reports/daily_sales.php')
      .then((res) => res.json())
      .then(setReport);
  }, []);

  return (
    <div>
      <Typography variant="h4" gutterBottom>Daily Sales Report</Typography>
      {report ? (
        <Typography variant="body1">
          Date: {report.date} | Orders: {report.orders} | Total Sales: ${report.total_sales}
        </Typography>
      ) : (
        <Typography>Loading...</Typography>
      )}
    </div>
  );
}

export default ReportsPage;