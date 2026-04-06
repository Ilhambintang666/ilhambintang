import React, { useState } from "react";
import Sidebar from "./Sidebar";
import NavbarLogin from "./NavbarLogin";

const DashboardLayout = ({ children }) => {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const toggleSidebar = () => setIsSidebarOpen(!isSidebarOpen);

  return (
    <div className="flex bg-[#0F172A] min-h-screen text-white">
      <Sidebar isOpen={isSidebarOpen} toggleSidebar={toggleSidebar} />

      <div className="flex flex-col flex-1">
        <NavbarLogin toggleSidebar={toggleSidebar} />

        <main className="flex-1 p-6 md:p-10">{children}</main>
      </div>
    </div>
  );
};

export default DashboardLayout;
