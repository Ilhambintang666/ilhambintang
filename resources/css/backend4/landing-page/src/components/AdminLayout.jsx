import React, { useState } from "react";
import AdminSidebar from "./AdminSidebar";
import AdminNavbar from "./AdminNavbar";

const AdminLayout = ({ children }) => {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const toggleSidebar = () => setIsSidebarOpen(!isSidebarOpen);

  return (
    <div className="flex bg-[#0F172A] min-h-screen text-white">
      <AdminSidebar isOpen={isSidebarOpen} toggleSidebar={toggleSidebar} />

      <div className="flex flex-col flex-1">
        <AdminNavbar toggleSidebar={toggleSidebar} />

        <main className="flex-1 p-1 md:p-10">{children}</main>
      </div>
    </div>
  );
};

export default AdminLayout;
