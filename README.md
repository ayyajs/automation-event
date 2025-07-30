# Modern Admin Panel

A comprehensive admin panel built with React.js frontend, PHP backend, and MySQL database. Features a modern, responsive design with complete CRUD operations, authentication, reporting, and analytics.

## 🚀 Features

### Frontend (React.js)
- **Modern UI Design** - Clean, responsive interface with Tailwind CSS
- **Authentication** - Secure login/logout with JWT tokens
- **Dashboard** - Real-time analytics and KPI cards with charts
- **Customer Management** - Complete CRUD operations with search and pagination
- **Inventory Management** - Product management with stock tracking
- **Sales Management** - Transaction processing and management
- **Reports** - Daily sales reports with interactive charts
- **Settings** - User profile and system configuration

### Backend (PHP)
- **RESTful API** - Clean API architecture with proper HTTP methods
- **JWT Authentication** - Secure token-based authentication
- **Database Integration** - PDO-based MySQL integration
- **CORS Support** - Cross-origin resource sharing enabled
- **Error Handling** - Comprehensive error handling and validation
- **Stock Management** - Automatic inventory updates with sales

### Database (MySQL)
- **Comprehensive Schema** - Well-designed relational database
- **Data Integrity** - Foreign key constraints and indexes
- **Audit Trail** - Stock movements and transaction logging
- **Sample Data** - Pre-populated with demo data

## 📋 Requirements

- Docker & Docker Compose
- Node.js 18+ (for local development)
- PHP 8.1+
- MySQL 8.0+

## 🛠️ Installation & Setup

### Using Docker (Recommended)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd admin-panel
   ```

2. **Build and start services**
   ```bash
   docker-compose up --build
   ```

3. **Access the applications**
   - **Frontend (React)**: http://localhost:3000
   - **Backend (PHP API)**: http://localhost:9000
   - **phpMyAdmin**: http://localhost:9001
   - **MySQL**: localhost:3306

### Manual Setup

#### Backend Setup
1. **Navigate to PHP directory**
   ```bash
   cd php
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure database**
   - Import `mysql/admin_panel_schema.sql` to your MySQL database
   - Update database credentials in `config/database.php`

4. **Start PHP server**
   ```bash
   php -S localhost:8000
   ```

#### Frontend Setup
1. **Navigate to frontend directory**
   ```bash
   cd frontend
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Start development server**
   ```bash
   npm start
   ```

## 🔐 Default Login Credentials

- **Username**: `admin`
- **Password**: `admin123`

## 📁 Project Structure

```
admin-panel/
├── frontend/                 # React.js frontend
│   ├── public/              # Public assets
│   ├── src/
│   │   ├── components/      # Reusable components
│   │   ├── contexts/        # React contexts (Auth)
│   │   ├── pages/           # Page components
│   │   ├── services/        # API services
│   │   └── App.js           # Main app component
│   ├── package.json
│   └── tailwind.config.js
├── php/                     # PHP backend
│   ├── api/                 # API endpoints
│   │   ├── auth/           # Authentication
│   │   ├── customers/      # Customer CRUD
│   │   ├── inventory/      # Inventory CRUD
│   │   ├── sales/          # Sales CRUD
│   │   └── reports/        # Reports & Analytics
│   ├── config/             # Configuration files
│   └── composer.json
├── mysql/                   # Database
│   ├── admin_panel_schema.sql
│   └── Dockerfile
└── docker-compose.yml
```

## 🔌 API Endpoints

### Authentication
- `POST /api/auth/login.php` - User login

### Customers
- `GET /api/customers/` - List customers
- `GET /api/customers/?id={id}` - Get customer
- `POST /api/customers/` - Create customer
- `PUT /api/customers/` - Update customer
- `DELETE /api/customers/?id={id}` - Delete customer

### Inventory
- `GET /api/inventory/` - List products
- `GET /api/inventory/?id={id}` - Get product
- `POST /api/inventory/` - Create product
- `PUT /api/inventory/` - Update product
- `DELETE /api/inventory/?id={id}` - Delete product

### Sales
- `GET /api/sales/` - List sales
- `GET /api/sales/?id={id}` - Get sale
- `POST /api/sales/` - Create sale
- `PUT /api/sales/` - Update sale
- `DELETE /api/sales/?id={id}` - Delete sale

### Reports
- `GET /api/reports/daily-sales.php` - Daily sales report

## 🎨 UI Components

### Dashboard
- KPI cards with trend indicators
- Interactive charts (Bar, Pie, Line)
- Recent transactions table
- Low stock alerts
- Top selling products

### Data Tables
- Search and filtering
- Pagination
- Sorting capabilities
- Responsive design
- Action buttons (Edit, Delete)

### Forms
- Validation with error messages
- Modern input styling
- Modal dialogs
- File uploads (ready for implementation)

## 🔧 Configuration

### Environment Variables
- `DB_HOST` - Database host
- `DB_NAME` - Database name
- `DB_USER` - Database username
- `DB_PASS` - Database password
- `REACT_APP_API_URL` - Backend API URL

### Customization
- **Colors**: Update `tailwind.config.js` for theme colors
- **Logo**: Replace logo in `frontend/src/components/Layout.js`
- **Company Info**: Update settings in database or settings page

## 📊 Database Schema

### Main Tables
- `users` - Admin users and authentication
- `customers` - Customer information
- `categories` - Product categories
- `inventory` - Products and stock levels
- `sales` - Sales transactions
- `sale_items` - Individual sale line items
- `invoices` - Invoice management
- `invoice_items` - Invoice line items
- `stock_movements` - Inventory movement tracking
- `settings` - System configuration

## 🚀 Deployment

### Production Build
1. **Build React app**
   ```bash
   cd frontend
   npm run build
   ```

2. **Configure production environment**
   - Update API URLs
   - Set up SSL certificates
   - Configure production database

3. **Deploy using Docker**
   ```bash
   docker-compose -f docker-compose.prod.yml up -d
   ```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions:
- Check the documentation
- Review existing issues
- Create a new issue with detailed information

## 🔮 Future Enhancements

- [ ] Invoice PDF generation
- [ ] Email notifications
- [ ] Advanced reporting with date ranges
- [ ] User role management
- [ ] Multi-language support
- [ ] Dark mode theme
- [ ] Mobile app (React Native)
- [ ] Real-time notifications
- [ ] Backup and restore functionality
- [ ] Advanced search and filters

---

**Built with ❤️ using React.js, PHP, and MySQL**