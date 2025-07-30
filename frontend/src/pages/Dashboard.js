import React from 'react';
import { useQuery } from 'react-query';
import { reportsService } from '../services/api';
import { format } from 'date-fns';
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  PieChart,
  Pie,
  Cell,
  LineChart,
  Line,
} from 'recharts';
import {
  DollarSign,
  ShoppingCart,
  Users,
  Package,
  TrendingUp,
  TrendingDown,
  AlertTriangle,
} from 'lucide-react';

const Dashboard = () => {
  const today = format(new Date(), 'yyyy-MM-dd');
  
  const { data: reportData, isLoading } = useQuery(
    ['daily-sales', today],
    () => reportsService.getDailySales({ 
      date_from: today, 
      date_to: today 
    }),
    {
      refetchInterval: 5 * 60 * 1000, // Refetch every 5 minutes
    }
  );

  const report = reportData?.data;

  if (isLoading) {
    return (
      <div className="px-4 sm:px-6 lg:px-8">
        <div className="animate-pulse">
          <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            {[...Array(4)].map((_, i) => (
              <div key={i} className="card p-5">
                <div className="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                <div className="h-8 bg-gray-200 rounded w-1/2"></div>
              </div>
            ))}
          </div>
        </div>
      </div>
    );
  }

  const kpiCards = [
    {
      title: 'Total Revenue',
      value: `$${report?.summary?.total_revenue || 0}`,
      change: '+12.5%',
      changeType: 'positive',
      icon: DollarSign,
    },
    {
      title: 'Total Sales',
      value: report?.summary?.total_transactions || 0,
      change: '+8.2%',
      changeType: 'positive',
      icon: ShoppingCart,
    },
    {
      title: 'Unique Customers',
      value: report?.summary?.unique_customers || 0,
      change: '+3.1%',
      changeType: 'positive',
      icon: Users,
    },
    {
      title: 'Low Stock Items',
      value: report?.low_stock_alerts?.length || 0,
      change: '-2',
      changeType: 'negative',
      icon: Package,
    },
  ];

  const COLORS = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];

  return (
    <div className="px-4 sm:px-6 lg:px-8">
      <div className="mb-8">
        <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p className="mt-1 text-sm text-gray-500">
          Welcome back! Here's what's happening with your business today.
        </p>
      </div>

      {/* KPI Cards */}
      <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        {kpiCards.map((card) => {
          const Icon = card.icon;
          return (
            <div key={card.title} className="card p-5">
              <div className="flex items-center">
                <div className="flex-shrink-0">
                  <Icon className="h-6 w-6 text-gray-400" />
                </div>
                <div className="ml-5 w-0 flex-1">
                  <dl>
                    <dt className="text-sm font-medium text-gray-500 truncate">
                      {card.title}
                    </dt>
                    <dd className="flex items-baseline">
                      <div className="text-2xl font-semibold text-gray-900">
                        {card.value}
                      </div>
                      <div className={`ml-2 flex items-baseline text-sm font-semibold ${
                        card.changeType === 'positive' ? 'text-green-600' : 'text-red-600'
                      }`}>
                        {card.changeType === 'positive' ? (
                          <TrendingUp className="h-4 w-4 mr-1" />
                        ) : (
                          <TrendingDown className="h-4 w-4 mr-1" />
                        )}
                        {card.change}
                      </div>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          );
        })}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {/* Sales by Hour */}
        <div className="card p-6">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Sales by Hour</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={report?.hourly_sales || []}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="hour" />
              <YAxis />
              <Tooltip 
                formatter={(value) => [`$${value}`, 'Revenue']}
                labelFormatter={(label) => `${label}:00`}
              />
              <Bar dataKey="total_amount" fill="#3B82F6" />
            </BarChart>
          </ResponsiveContainer>
        </div>

        {/* Payment Methods */}
        <div className="card p-6">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Payment Methods</h3>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie
                data={report?.payment_methods || []}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={({ payment_method, percent }) => 
                  `${payment_method} ${(percent * 100).toFixed(0)}%`
                }
                outerRadius={80}
                fill="#8884d8"
                dataKey="total_amount"
              >
                {(report?.payment_methods || []).map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip formatter={(value) => [`$${value}`, 'Amount']} />
            </PieChart>
          </ResponsiveContainer>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Top Products */}
        <div className="card p-6">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Top Selling Products</h3>
          <div className="space-y-4">
            {(report?.top_products || []).slice(0, 5).map((product, index) => (
              <div key={product.product_code} className="flex items-center justify-between">
                <div className="flex items-center">
                  <div className={`w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-medium ${
                    index === 0 ? 'bg-yellow-500' : 
                    index === 1 ? 'bg-gray-400' : 
                    index === 2 ? 'bg-orange-600' : 'bg-primary-500'
                  }`}>
                    {index + 1}
                  </div>
                  <div className="ml-3">
                    <p className="text-sm font-medium text-gray-900">
                      {product.product_name}
                    </p>
                    <p className="text-xs text-gray-500">
                      {product.total_quantity} units sold
                    </p>
                  </div>
                </div>
                <div className="text-sm font-medium text-gray-900">
                  ${product.total_revenue}
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Low Stock Alerts */}
        <div className="card p-6">
          <div className="flex items-center mb-4">
            <AlertTriangle className="h-5 w-5 text-orange-500 mr-2" />
            <h3 className="text-lg font-medium text-gray-900">Low Stock Alerts</h3>
          </div>
          <div className="space-y-4">
            {(report?.low_stock_alerts || []).slice(0, 5).map((item) => (
              <div key={item.product_code} className="flex items-center justify-between">
                <div>
                  <p className="text-sm font-medium text-gray-900">
                    {item.name}
                  </p>
                  <p className="text-xs text-gray-500">
                    Code: {item.product_code}
                  </p>
                </div>
                <div className="text-right">
                  <p className="text-sm font-medium text-red-600">
                    {item.quantity_in_stock} left
                  </p>
                  <p className="text-xs text-gray-500">
                    Min: {item.min_stock_level}
                  </p>
                </div>
              </div>
            ))}
            {(!report?.low_stock_alerts || report.low_stock_alerts.length === 0) && (
              <p className="text-sm text-gray-500 text-center py-4">
                All items are well stocked! 🎉
              </p>
            )}
          </div>
        </div>
      </div>

      {/* Recent Transactions */}
      <div className="mt-8">
        <div className="card p-6">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h3>
          <div className="overflow-x-auto">
            <table className="table">
              <thead>
                <tr>
                  <th>Sale #</th>
                  <th>Customer</th>
                  <th>Amount</th>
                  <th>Payment</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {(report?.recent_transactions || []).map((transaction) => (
                  <tr key={transaction.id}>
                    <td className="font-medium text-primary-600">
                      {transaction.sale_number}
                    </td>
                    <td>
                      {transaction.customer_name || 'Walk-in Customer'}
                    </td>
                    <td className="font-medium">
                      ${transaction.total_amount}
                    </td>
                    <td>
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize">
                        {transaction.payment_method}
                      </span>
                    </td>
                    <td className="text-gray-500">
                      {format(new Date(transaction.sale_date), 'HH:mm')}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;