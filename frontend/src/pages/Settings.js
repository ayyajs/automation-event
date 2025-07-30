import React from 'react';
import { Settings as SettingsIcon, Save, User, Building, CreditCard, Bell } from 'lucide-react';

const Settings = () => {
  return (
    <div className="px-4 sm:px-6 lg:px-8">
      <div className="sm:flex sm:items-center">
        <div className="sm:flex-auto">
          <h1 className="text-2xl font-bold text-gray-900">Settings</h1>
          <p className="mt-2 text-sm text-gray-700">
            Configure your admin panel settings and preferences.
          </p>
        </div>
      </div>

      <div className="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {/* Navigation */}
        <div className="lg:col-span-1">
          <nav className="space-y-1">
            <a href="#" className="sidebar-link active">
              <User className="mr-3 h-5 w-5" />
              Profile
            </a>
            <a href="#" className="sidebar-link">
              <Building className="mr-3 h-5 w-5" />
              Company
            </a>
            <a href="#" className="sidebar-link">
              <CreditCard className="mr-3 h-5 w-5" />
              Billing
            </a>
            <a href="#" className="sidebar-link">
              <Bell className="mr-3 h-5 w-5" />
              Notifications
            </a>
            <a href="#" className="sidebar-link">
              <SettingsIcon className="mr-3 h-5 w-5" />
              System
            </a>
          </nav>
        </div>

        {/* Content */}
        <div className="lg:col-span-2">
          <div className="card p-6">
            <div className="flex items-center justify-between mb-6">
              <h3 className="text-lg font-medium text-gray-900">Profile Settings</h3>
              <button className="btn btn-primary">
                <Save className="h-4 w-4 mr-2" />
                Save Changes
              </button>
            </div>

            <form className="space-y-6">
              <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    First Name
                  </label>
                  <input
                    type="text"
                    className="input"
                    defaultValue="Admin"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Last Name
                  </label>
                  <input
                    type="text"
                    className="input"
                    defaultValue="User"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Email Address
                </label>
                <input
                  type="email"
                  className="input"
                  defaultValue="admin@adminpanel.com"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Role
                </label>
                <select className="input">
                  <option>Admin</option>
                  <option>Manager</option>
                  <option>Staff</option>
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Current Password
                </label>
                <input
                  type="password"
                  className="input"
                  placeholder="Enter current password"
                />
              </div>

              <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    New Password
                  </label>
                  <input
                    type="password"
                    className="input"
                    placeholder="Enter new password"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Confirm Password
                  </label>
                  <input
                    type="password"
                    className="input"
                    placeholder="Confirm new password"
                  />
                </div>
              </div>

              <div className="border-t pt-6">
                <h4 className="text-md font-medium text-gray-900 mb-4">Preferences</h4>
                <div className="space-y-4">
                  <div className="flex items-center justify-between">
                    <div>
                      <label className="text-sm font-medium text-gray-700">
                        Email Notifications
                      </label>
                      <p className="text-sm text-gray-500">
                        Receive email notifications for important updates
                      </p>
                    </div>
                    <input
                      type="checkbox"
                      className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                      defaultChecked
                    />
                  </div>
                  <div className="flex items-center justify-between">
                    <div>
                      <label className="text-sm font-medium text-gray-700">
                        Low Stock Alerts
                      </label>
                      <p className="text-sm text-gray-500">
                        Get notified when products are running low
                      </p>
                    </div>
                    <input
                      type="checkbox"
                      className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                      defaultChecked
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Settings;