import React from 'react';
import { BarChart3, Calendar, Download, TrendingUp } from 'lucide-react';

const Reports = () => {
  return (
    <div className="px-4 sm:px-6 lg:px-8">
      <div className="sm:flex sm:items-center">
        <div className="sm:flex-auto">
          <h1 className="text-2xl font-bold text-gray-900">Reports</h1>
          <p className="mt-2 text-sm text-gray-700">
            Analyze your business performance with detailed reports and insights.
          </p>
        </div>
        <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button className="btn btn-primary">
            <Download className="h-4 w-4 mr-2" />
            Export Report
          </button>
        </div>
      </div>

      {/* Date Range Selector */}
      <div className="mt-6 card p-4">
        <div className="flex items-center space-x-4">
          <div className="flex items-center">
            <Calendar className="h-5 w-5 text-gray-400 mr-2" />
            <span className="text-sm font-medium text-gray-700">Date Range:</span>
          </div>
          <input
            type="date"
            className="input w-auto"
            defaultValue="2024-01-01"
          />
          <span className="text-gray-500">to</span>
          <input
            type="date"
            className="input w-auto"
            defaultValue="2024-01-31"
          />
          <button className="btn btn-secondary">
            Apply
          </button>
        </div>
      </div>

      {/* Report Cards */}
      <div className="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div className="card p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-medium text-gray-900">Sales Overview</h3>
            <BarChart3 className="h-5 w-5 text-gray-400" />
          </div>
          <div className="space-y-4">
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-600">Total Revenue</span>
              <span className="text-lg font-semibold text-gray-900">$12,345</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-600">Total Transactions</span>
              <span className="text-lg font-semibold text-gray-900">456</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-600">Average Transaction</span>
              <span className="text-lg font-semibold text-gray-900">$27.06</span>
            </div>
          </div>
        </div>

        <div className="card p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-medium text-gray-900">Growth Metrics</h3>
            <TrendingUp className="h-5 w-5 text-green-500" />
          </div>
          <div className="space-y-4">
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-600">Revenue Growth</span>
              <span className="text-lg font-semibold text-green-600">+15.2%</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-600">Customer Growth</span>
              <span className="text-lg font-semibold text-green-600">+8.7%</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-600">Order Growth</span>
              <span className="text-lg font-semibold text-green-600">+12.3%</span>
            </div>
          </div>
        </div>
      </div>

      {/* Charts Placeholder */}
      <div className="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div className="card p-6">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Daily Sales Trend</h3>
          <div className="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <div className="text-center">
              <BarChart3 className="mx-auto h-12 w-12 text-gray-400 mb-2" />
              <p className="text-sm text-gray-500">Chart will be displayed here</p>
            </div>
          </div>
        </div>

        <div className="card p-6">
          <h3 className="text-lg font-medium text-gray-900 mb-4">Top Products</h3>
          <div className="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
            <div className="text-center">
              <BarChart3 className="mx-auto h-12 w-12 text-gray-400 mb-2" />
              <p className="text-sm text-gray-500">Chart will be displayed here</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Reports;