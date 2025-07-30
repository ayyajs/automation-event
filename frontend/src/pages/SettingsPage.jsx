import React, { useState, useEffect } from 'react';
import { Typography, TextField, Button } from '@mui/material';

function SettingsPage() {
  const [settings, setSettings] = useState({});

  useEffect(() => {
    fetch('/api/settings')
      .then((res) => res.json())
      .then(setSettings);
  }, []);

  const handleChange = (e) => setSettings({ ...settings, [e.target.name]: e.target.value });

  const handleSave = async () => {
    await fetch('/api/settings', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(settings),
    });
    alert('Settings saved');
  };

  return (
    <div>
      <Typography variant="h4" gutterBottom>Settings</Typography>
      <TextField
        label="Site Title"
        name="site_title"
        value={settings.site_title || ''}
        onChange={handleChange}
        fullWidth
        sx={{ mb: 2 }}
      />
      <Button variant="contained" onClick={handleSave}>Save</Button>
    </div>
  );
}

export default SettingsPage;