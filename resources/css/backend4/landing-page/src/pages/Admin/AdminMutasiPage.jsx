import React, { useEffect, useState } from "react";
import AdminLayout from "../../components/AdminLayout";
import API from "../../utils/api";
import { useAuth } from "../../context/AuthContext";
import Swal from "sweetalert2";

const AdminMutasiPage = () => {
  const { token } = useAuth();
  const [mutasi, setMutasi] = useState([]);
  const [loading, setLoading] = useState(false);

  // Fetch Admin Mutasi
  const fetchMutasi = async () => {
    setLoading(true);
    try {
      const res = await API.get("/admin/mutasi", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setMutasi(res.data.data);
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: err.response?.data?.message || "Failed to load mutasi",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (token) fetchMutasi();
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
      <div className="absolute top-16 md:top-0 left-0 w-full">
        <div className="flex-1 min-h-screen bg-[#0F172A]">
          <main className="p-6 md:ml-64">
            <h2 className="text-white text-xl font-semibold mb-4">
              Admin Mutasi
            </h2>

            <div className="w-full overflow-x-auto rounded-xl bg-[#1E293B] shadow p-4">
              <table className="min-w-max w-full text-left border-collapse">
                <thead>
                  <tr className="border-b border-gray-700 text-white">
                    <th className="py-2 px-4">TRX ID</th>
                    <th className="py-2 px-4">User</th>
                    <th className="py-2 px-4">Paket</th>
                    <th className="py-2 px-4">Jumlah</th>
                    <th className="py-2 px-4">Metode</th>
                    <th className="py-2 px-4">Expired</th>
                    <th className="py-2 px-4">Tanggal Pembayaran</th>
                    <th className="py-2 px-4">Status</th>
                  </tr>
                </thead>
                <tbody>
                  {loading ? (
                    <tr>
                      <td colSpan={8} className="text-center text-white py-4">
                        Loading...
                      </td>
                    </tr>
                  ) : mutasi.length === 0 ? (
                    <tr>
                      <td colSpan={8} className="text-center text-white py-4">
                        No transactions found
                      </td>
                    </tr>
                  ) : (
                    mutasi.map((item) => (
                      <tr
                        key={item._id}
                        className="border-b border-gray-700 text-white"
                      >
                        <td className="py-2 px-4">{item.trxId}</td>
                        <td className="py-2 px-4">
                          {item.userId?.name || "-"}
                        </td>
                        <td className="py-2 px-4">
                          {item.userId?.package || "-"}
                        </td>
                        <td className="py-2 px-4">
                          Rp {item.amount.toLocaleString()}
                        </td>
                        <td className="py-2 px-4">{item.method}</td>
                        <td className="py-2 px-4">
                          {formatDate(item.dueDate)}
                        </td>
                        <td className="py-2 px-4">{formatDate(item.paidAt)}</td>
                        <td
                          className={`py-2 px-4 font-semibold ${
                            item.status === "success"
                              ? "text-green-400"
                              : item.status === "pending"
                              ? "text-yellow-400"
                              : "text-red-500"
                          }`}
                        >
                          {item.status.toUpperCase()}
                        </td>
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

export default AdminMutasiPage;
