import React, { useState } from "react";
import DashboardLayout from "../components/DashboardLayout";
import { useAuth } from "../context/AuthContext";
import Swal from "sweetalert2";
import API from "../utils/api";

const PackagesPage = () => {
  const { user, token, setUser } = useAuth();
  const [selectedPackage, setSelectedPackage] = useState(
    user?.pendingPackage || user?.package || ""
  );
  const [loading, setLoading] = useState(false);

  const handleUpdatePackage = async () => {
    setLoading(true);

    try {
      const res = await API.put(
        "/auth/user-package",
        { package: selectedPackage },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      if (res.data.success) {
        const updatedUser = res.data.user;

        // Simpan perubahan (include pendingPackage)
        localStorage.setItem("user", JSON.stringify(updatedUser));
        setUser(updatedUser);

        Swal.fire({
          icon: "success",
          title: "Request Submitted",
          text: "Package change request submitted. Awaiting admin approval.",
          background: "#1E293B",
          color: "#E2E8F0",
          confirmButtonColor: "#3B82F6",
        });
      }
    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Request Failed",
        text: err.response?.data?.message || "Something went wrong",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <DashboardLayout>
      <div className="max-w-xl mx-auto bg-[#1E293B] p-8 rounded-xl shadow-lg">
        <h1 className="text-3xl font-bold text-[#3B82F6] mb-6">
          Upgrade Paket
        </h1>

        {/* Current & Pending Info */}
        <div className="mb-6 text-gray-300 space-y-2">
          <p className="text-lg">
            <span className="font-semibold">Paket saat ini: </span>
            {user.package || "-"}
          </p>

          {user?.pendingPackage && (
            <p className="text-lg text-yellow-400">
              <span className="font-semibold">Pending Change:</span>{" "}
              {user.pendingPackage} (waiting for admin)
            </p>
          )}
        </div>

        {/* Package Selection */}
        <div className="space-y-4">
          {/* 10MBPS */}
          <div
            className={`p-4 rounded-lg border cursor-pointer transition ${
              selectedPackage === "10MBPS"
                ? "border-[#3B82F6] bg-[#0F172A]"
                : "border-gray-700 bg-[#0F172A]/50 hover:bg-[#0F172A]"
            }`}
            onClick={() => setSelectedPackage("10MBPS")}
          >
            <h2 className="text-xl font-semibold text-white">10 MBPS</h2>
            <p className="text-gray-400">Rp 150.000 / month</p>
          </div>

          {/* 20MBPS */}
          <div
            className={`p-4 rounded-lg border cursor-pointer transition ${
              selectedPackage === "20MBPS"
                ? "border-[#3B82F6] bg-[#0F172A]"
                : "border-gray-700 bg-[#0F172A]/50 hover:bg-[#0F172A]"
            }`}
            onClick={() => setSelectedPackage("20MBPS")}
          >
            <h2 className="text-xl font-semibold text-white">20 MBPS</h2>
            <p className="text-gray-400">Rp 210.000 / month</p>
          </div>
        </div>

        {/* Button */}
        <button
          onClick={handleUpdatePackage}
          disabled={loading}
          className={`w-full mt-6 py-3 rounded-lg font-semibold transition ${
            loading
              ? "bg-gray-600 cursor-not-allowed"
              : "bg-[#3B82F6] hover:bg-[#2563EB]"
          }`}
        >
          {loading ? "Submitting..." : "Request Package Change"}
        </button>
      </div>
    </DashboardLayout>
  );
};

export default PackagesPage;
