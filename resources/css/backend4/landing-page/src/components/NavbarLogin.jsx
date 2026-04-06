import React from "react";
import { Menu } from "lucide-react";

const NavbarLogin = ({ toggleSidebar }) => {
  return (
    <header className="bg-[#1E293B] text-white flex items-center justify-between px-4 py-3 shadow-md md:hidden">
      <h1 className="text-xl font-semibold text-[#3B82F6]">RAMBO</h1>
      <button
        onClick={toggleSidebar}
        className="text-gray-300 hover:text-white"
      >
        <Menu size={24} />
      </button>
    </header>
  );
};

export default NavbarLogin;
