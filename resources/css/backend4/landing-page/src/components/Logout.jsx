import React from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import Swal from "sweetalert2";

const Logout = () => {
  const navigate = useNavigate();
  const { setUser, setToken } = useAuth();

  const handleLogout = async () => {
    const result = await Swal.fire({
      title: "Logout?",
      text: "Are you sure you want to log out?",
      icon: "question",
      background: "#1E293B",
      color: "#E2E8F0",
      showCancelButton: true,
      confirmButtonColor: "#EF4444",
      cancelButtonColor: "#3B82F6",
      confirmButtonText: "Yes, logout",
    });

    if (result.isConfirmed) {
      // 🧹 Bersihkan localStorage dan context
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      setUser(null);
      setToken(null);

      await Swal.fire({
        icon: "success",
        title: "Logged Out",
        text: "You have been logged out successfully.",
        background: "#1E293B",
        color: "#E2E8F0",
        confirmButtonColor: "#3B82F6",
      });

      navigate("/login");
    }
  };

  return (
    <button
      onClick={handleLogout}
      className="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
    >
      Logout
    </button>
  );
};

export default Logout;
