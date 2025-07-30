import React from 'react';
import { ShoppingCart, Plus, Search, DollarSign } from 'lucide-react';

const Sales = () => {
  return (
    <div className="px-4 sm:px-6 lg:px-8">
      <div className="sm:flex sm:items-center">
        <div className="sm:flex-auto">
          <h1 className="text-2xl font-bold text-gray-900">Sales</h1>
          <p className="mt-2 text-sm text-gray-700">
            Manage sales transactions, process orders, and track sales performance.
          </p>
        </div>
        <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button className="btn btn-primary">
            <Plus className="h-4 w-4 mr-2" />
            New Sale
          </button>
        </div>
      </div>

      {/* Search */}
      <div className="mt-6">
        <div className="relative">
          <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search className="h-5 w-5 text-gray-400" />
          </div>
          <input
            type="text"
            className="input pl-10"
            placeholder="Search sales..."
          />
        </div>
      </div>

      {/* Stats */}
      <div className="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div className="card p-5">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <DollarSign className="h-8 w-8 text-green-600" />
            </div>
            <div className="ml-5 w-0 flex-1">
              <dl>
                <dt className="text-sm font-medium text-gray-500 truncate">
                  Today's Sales
                </dt>
                <dd className="text-2xl font-semibold text-gray-900">
                  $2,345
                </dd>
              </dl>
            </div>
          </div>
        </div>

        <div className="card p-5">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <ShoppingCart className="h-8 w-8 text-blue-600" />
            </div>
            <div className="ml-5 w-0 flex-1">
              <dl>
                <dt className="text-sm font-medium text-gray-500 truncate">
                  Transactions
                </dt>
                <dd className="text-2xl font-semibold text-gray-900">
                  42
                </dd>
              </dl>
            </div>
          </div>
        </div>

        <div className="card p-5">
          <div className="flex items-center">
            <div className="flex-shrink-0">
              <DollarSign className="h-8 w-8 text-purple-600" />
            </div>
            <div className="ml-5 w-0 flex-1">
              <dl>
                <dt className="text-sm font-medium text-gray-500 truncate">
                  Average Sale
                </dt>
                <dd className="text-2xl font-semibold text-gray-900">
                  $55.83
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      {/* Sales Table */}
      <div className="mt-8 card overflow-hidden">
        <div className="overflow-x-auto">
          <table className="table">
            <thead>
              <tr>
                <th>Sale #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody className="bg-white divide-y divide-gray-200">
              <tr>
                <td colSpan={7} className="text-center py-12 text-gray-500">
                  <ShoppingCart className="mx-auto h-12 w-12 text-gray-400 mb-4" />
                  <h3 className="text-sm font-medium text-gray-900 mb-2">No sales found</h3>
                  <p className="text-sm text-gray-500 mb-4">
                    Start processing sales to see them listed here.
                  </p>
                  <button className="btn btn-primary">
                    <Plus className="h-4 w-4 mr-2" />
                    New Sale
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Sales;