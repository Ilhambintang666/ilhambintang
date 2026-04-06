import React, { useState } from "react";
import DashboardLayout from "../components/DashboardLayout";
import { useAuth } from "../context/AuthContext";
import Swal from "sweetalert2";
import API from "../utils/api";

const ProfilePage = () => {
  const { user, token, setUser } = useAuth();
  const [name, setName] = useState(user?.name || "");
  const [email, setEmail] = useState(user?.email || "");
  const [currentPassword, setCurrentPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [loading, setLoading] = useState(false);

  // 🔹 Update profile (name/email/password)
  const handleProfileUpdate = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      const res = await API.put(
        "/auth/update",
        { name, email, currentPassword, newPassword },
        { headers: { Authorization: `Bearer ${token}` } }
      );

      if (res.data.success === true) {
        const updatedUser = res.data.user;
        localStorage.setItem("user", JSON.stringify(updatedUser));
        setUser(updatedUser);

        await Swal.fire({
          icon: "success",
          title: "Profile Updated",
          text: res.data.message || "Profile saved successfully.",
          background: "#1E293B",
          color: "#E2E8F0",
          confirmButtonColor: "#3B82F6",
        });

        setCurrentPassword("");
        setNewPassword("");
      } else {
        console.warn("Profile update failed:", res.data.message);
      }
    } catch (err) {
      console.error("Profile update error:", err.response?.data || err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <DashboardLayout>
      <div className="max-w-2xl mx-auto bg-[#1E293B] p-8 rounded-2xl shadow-lg">
        <h1 className="text-3xl font-bold text-[#3B82F6] mb-6">
          Profile Settings
        </h1>

        <form onSubmit={handleProfileUpdate} className="space-y-5">
          {/* Name */}
          <div>
            <label className="block mb-2 font-medium text-gray-200">Name</label>
            <input
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              placeholder={user?.name || "Your name"}
              className="w-full p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
              required
            />
          </div>

          {/* Email */}
          <div>
            <label className="block mb-2 font-medium text-gray-200">
              Email
            </label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder={user?.email || "you@example.com"}
              className="w-full p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
              required
            />
          </div>

          <div className="my-8 border-t border-gray-700"></div>

          {/* Current Password */}
          <div>
            <label className="block mb-2 font-medium text-gray-200">
              Current Password (optional)
            </label>
            <input
              type="password"
              value={currentPassword}
              onChange={(e) => setCurrentPassword(e.target.value)}
              placeholder="Enter current password to change password"
              className="w-full p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
            />
          </div>

          {/* New Password */}
          <div>
            <label className="block mb-2 font-medium text-gray-200">
              New Password (optional)
            </label>
            <input
              type="password"
              value={newPassword}
              onChange={(e) => setNewPassword(e.target.value)}
              placeholder="New password"
              className="w-full p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
            />
          </div>

          {/* Save Button */}
          <button
            type="submit"
            disabled={loading}
            className={`w-full py-3 rounded-lg font-semibold transition ${
              loading
                ? "bg-gray-600 cursor-not-allowed"
                : "bg-[#3B82F6] hover:bg-[#2563EB]"
            }`}
          >
            {loading ? "Saving..." : "Save Changes"}
          </button>
        </form>
      </div>
    </DashboardLayout>
  );
};

export default ProfilePage;
