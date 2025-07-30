import axios from 'axios';

// Create axios instance
const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle auth errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth services
export const authService = {
  login: (credentials) => api.post('/auth/login.php', credentials),
};

// Customer services
export const customerService = {
  getCustomers: (params = {}) => api.get('/customers/', { params }),
  getCustomer: (id) => api.get(`/customers/?id=${id}`),
  createCustomer: (data) => api.post('/customers/', data),
  updateCustomer: (data) => api.put('/customers/', data),
  deleteCustomer: (id) => api.delete(`/customers/?id=${id}`),
};

// Inventory services
export const inventoryService = {
  getItems: (params = {}) => api.get('/inventory/', { params }),
  getItem: (id) => api.get(`/inventory/?id=${id}`),
  createItem: (data) => api.post('/inventory/', data),
  updateItem: (data) => api.put('/inventory/', data),
  deleteItem: (id) => api.delete(`/inventory/?id=${id}`),
};

// Category services
export const categoryService = {
  getCategories: () => api.get('/categories/'),
  getCategory: (id) => api.get(`/categories/?id=${id}`),
  createCategory: (data) => api.post('/categories/', data),
  updateCategory: (data) => api.put('/categories/', data),
  deleteCategory: (id) => api.delete(`/categories/?id=${id}`),
};

// Sales services
export const salesService = {
  getSales: (params = {}) => api.get('/sales/', { params }),
  getSale: (id) => api.get(`/sales/?id=${id}`),
  createSale: (data) => api.post('/sales/', data),
  updateSale: (data) => api.put('/sales/', data),
  deleteSale: (id) => api.delete(`/sales/?id=${id}`),
};

// Reports services
export const reportsService = {
  getDailySales: (params = {}) => api.get('/reports/daily-sales.php', { params }),
};

// Settings services
export const settingsService = {
  getSettings: () => api.get('/settings/'),
  updateSettings: (data) => api.put('/settings/', data),
};

export default api;