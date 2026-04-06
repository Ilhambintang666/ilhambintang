import React, { useState, useEffect } from "react";
import AdminLayout from "../../components/AdminLayout";
import API from "../../utils/api";
import Swal from "sweetalert2";
import { useAuth } from "../../context/AuthContext";

const AdminDashboard = () => {
  const { token } = useAuth();
  const [stats, setStats] = useState({
    totalUsers: 0,
    expiredUsers: 0,
    profit: 0,
  });
  const [lastPayments, setLastPayments] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchDashboardData = async () => {
    setLoading(true);
    try {
      const res = await API.get("/admin/dashboard", {
        headers: { Authorization: `Bearer ${token}` },
      });

      const membersData = res.data.data.members || [];
      const totalUsers = res.data.data.totalUsers || membersData.length;
      const profit = res.data.data.totalRevenue || 0;
      const expiredUsers = res.data.data.expiredUsers || 0;

      // Filter Here
      const formattedPayments = membersData
        .filter((u) => u.status === "success")
        .map((u) => ({
          name: u.name,
          email: u.email,
          package: u.package,
          amount: u.amount || 0,
          status: u.status,
          paidAt: u.paidAt || null,
        }));

      setStats({ totalUsers, expiredUsers, profit });
      setLastPayments(formattedPayments);
    } catch (err) {
      console.error("Dashboard fetch error:", err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: err.response?.data?.message || "Failed to load dashboard data",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (token) fetchDashboardData();
  }, [token]);

  const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleString("id-ID", {
      day: "2-digit",
      month: "short",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  };

  return (
    <AdminLayout>
      <div className="flex">
        <div className="flex-1 min-h-screen bg-[#0F172A]">
          <main className="p-6 md:ml-64">
            {/* Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
              <div className="bg-[#1E293B] p-6 rounded-xl shadow text-white">
                <h3 className="font-semibold mb-2">Total Members</h3>
                <p className="text-2xl">{stats.totalUsers}</p>
              </div>
              <div className="bg-[#1E293B] p-6 rounded-xl shadow text-white">
                <h3 className="font-semibold mb-2">Belum dibayarkan</h3>
                <p className="text-2xl">{stats.expiredUsers}</p>
              </div>
              <div className="bg-[#1E293B] p-6 rounded-xl shadow text-white">
                <h3 className="font-semibold mb-2">Keuntungan</h3>
                <p className="text-2xl">Rp {stats.profit.toLocaleString()}</p>
              </div>
            </div>

            {/* Last Payment List */}
            <div className="bg-[#1E293B] p-6 rounded-xl shadow text-white">
              <div className="flex justify-between items-center mb-4">
                <h3 className="font-semibold text-lg">Last Payment</h3>
              </div>

              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="border-b border-gray-700">
                    <th className="py-2 px-4">Name</th>
                    <th className="py-2 px-4">Email</th>
                    <th className="py-2 px-4">Package</th>
                    <th className="py-2 px-4">Amount</th>
                    <th className="py-2 px-4">Paid At</th>
                  </tr>
                </thead>
                <tbody>
                  {loading ? (
                    <tr>
                      <td colSpan={5} className="text-center py-4 text-white">
                        Loading...
                      </td>
                    </tr>
                  ) : lastPayments.length === 0 ? (
                    <tr>
                      <td colSpan={5} className="text-center py-4 text-white">
                        No successful payments found
                      </td>
                    </tr>
                  ) : (
                    lastPayments.map((p, idx) => (
                      <tr key={idx} className="border-b border-gray-700">
                        <td className="py-2 px-4">{p.name}</td>
                        <td className="py-2 px-4">{p.email}</td>
                        <td className="py-2 px-4">{p.package}</td>
                        <td className="py-2 px-4">
                          Rp {p.amount.toLocaleString()}
                        </td>
                        <td className="py-2 px-4">{formatDate(p.paidAt)}</td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </main>
        </div>
      </div>
    </AdminLayout>
  );
};

export default AdminDashboard;
