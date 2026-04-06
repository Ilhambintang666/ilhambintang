// src/components/AdminNavbar.jsx
import React from "react";
import { Menu } from "lucide-react";

const AdminNavbar = ({ toggleSidebar }) => {
  return (
    <header className="bg-[#1E293B] text-white flex items-center justify-between p-4 shadow-md md:ml-64">
      <button className="md:hidden text-white" onClick={toggleSidebar}>
        <Menu size={24} />
      </button>
      <h1 className="text-xl font-semibold">Admin Dashboard</h1>
    </header>
  );
};

export default AdminNavbar;
