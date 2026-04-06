import React from "react";
import { Link } from "react-router-dom";
import { Home, User, Radio, NotebookPen } from "lucide-react";
import Logout from "./Logout";

const Sidebar = ({ isOpen, toggleSidebar }) => {
  return (
    <>
      <div
        onClick={toggleSidebar}
        className={`fixed inset-0 bg-black bg-opacity-40 z-30 md:hidden ${
          isOpen ? "block" : "hidden"
        }`}
      ></div>

      <aside
        className={`fixed md:static top-0 left-0 h-full w-64 bg-[#1E293B] text-white shadow-lg z-40 transform transition-transform duration-300 ${
          isOpen ? "translate-x-0" : "-translate-x-full md:translate-x-0"
        }`}
      >
        <div className="flex items-center justify-between md:justify-center p-4 border-b border-gray-700">
          <h2 className="text-2xl font-bold text-[#3B82F6]">RAMBO</h2>
          <button
            className="md:hidden text-gray-300 hover:text-white"
            onClick={toggleSidebar}
          >
            ✕
          </button>
        </div>

        <nav className="mt-6 flex flex-col gap-2 px-4">
          <Link
            to="/admin/dashboard"
            className="flex items-center gap-3 p-3 rounded-lg hover:bg-[#0F172A] transition"
          >
            <Home size={25} /> Dashboard
          </Link>

          <Link
            to="/admin/users"
            className="flex items-center gap-3 p-3 rounded-lg hover:bg-[#0F172A] transition"
          >
            <User size={25} /> Profile
          </Link>
          <Link
            to="/admin/package-requests"
            className="flex items-center gap-3 p-3 rounded-lg hover:bg-[#0F172A] transition"
          >
            <Radio size={25} /> Upgrade Paket List
          </Link>
          <Link
            to="/admin/mutasi"
            className="flex items-center gap-3 p-3 rounded-lg hover:bg-[#0F172A] transition"
          >
            <NotebookPen size={25} /> Mutasi
          </Link>

          {/* 🔹 Gunakan komponen LogoutButton */}
          <div className="mt-4">
            <Logout />
          </div>
        </nav>
      </aside>
    </>
  );
};

export default Sidebar;
