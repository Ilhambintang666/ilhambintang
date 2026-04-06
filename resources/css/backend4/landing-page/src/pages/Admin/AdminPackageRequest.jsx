import React, { useEffect, useState } from "react";
import AdminLayout from "../../components/AdminLayout";
import AdminSidebar from "../../components/AdminSidebar";
import AdminNavbar from "../../components/AdminNavbar";
import API from "../../utils/api";
import Swal from "sweetalert2";
import { useAuth } from "../../context/AuthContext";

const AdminPackageRequest = () => {
  const { token } = useAuth();
  const [requests, setRequests] = useState([]);
  const [loading, setLoading] = useState(false);

  // ================= FETCH PENDING REQUESTS =================
  const fetchRequests = async () => {
    setLoading(true);
    try {
      const res = await API.get("/admin/package-requests", {
        headers: { Authorization: `Bearer ${token}` },
      });

      setRequests(res.data.requests);
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: err.response?.data?.message || "Failed to load requests",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (token) fetchRequests();
  }, [token]);

  // ================= APPROVE REQUEST =================
  const handleApprove = async (id) => {
    const confirm = await Swal.fire({
      title: "Approve Package Change?",
      text: "User's package will be updated.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Approve",
      background: "#1E293B",
      color: "#E2E8F0",
    });

    if (!confirm.isConfirmed) return;

    try {
      await API.put(
        `/admin/package-requests/${id}/approve`,
        {},
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      Swal.fire({
        icon: "success",
        title: "Request Approved",
        background: "#1E293B",
        color: "#E2E8F0",
      });

      fetchRequests();
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Failed to approve",
        text: err.response?.data?.message,
        background: "#1E293B",
        color: "#E2E8F0",
      });
    }
  };

  // ================= REJECT REQUEST =================
  const handleReject = async (id) => {
    const confirm = await Swal.fire({
      title: "Reject Request?",
      text: "The pending request will be cancelled.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Reject",
      background: "#1E293B",
      color: "#E2E8F0",
    });

    if (!confirm.isConfirmed) return;

    try {
      await API.put(
        `/admin/package-requests/${id}/reject`,
        {},
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      Swal.fire({
        icon: "success",
        title: "Request Rejected",
        background: "#1E293B",
        color: "#E2E8F0",
      });

      fetchRequests();
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Failed to reject",
        text: err.response?.data?.message,
        background: "#1E293B",
        color: "#E2E8F0",
      });
    }
  };

  return (
    <AdminLayout>
      <div className="absolute top-16 md:top-0 left-0 w-full">
        <main className="p-6 md:ml-64 bg-[#0F172A] min-h-screen">
          <h2 className="text-xl text-white font-semibold mb-4">
            Package Change Requests
          </h2>

          <div className="w-full overflow-x-auto rounded-xl bg-[#1E293B] shadow p-4">
            <table className="min-w-max w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-gray-700 text-white">
                  <th className="py-2 px-4">Name</th>
                  <th className="py-2 px-4">Email</th>
                  <th className="py-2 px-4">Current Package</th>
                  <th className="py-2 px-4">Requested Package</th>
                  <th className="py-2 px-4">Action</th>
                </tr>
              </thead>

              <tbody>
                {loading ? (
                  <tr>
                    <td colSpan={5} className="text-center text-white py-4">
                      Loading...
                    </td>
                  </tr>
                ) : requests.length === 0 ? (
                  <tr>
                    <td colSpan={5} className="text-center text-white py-4">
                      No pending requests
                    </td>
                  </tr>
                ) : (
                  requests.map((user) => (
                    <tr
                      key={user._id}
                      className="border-b border-gray-700 text-white"
                    >
                      <td className="py-2 px-4">{user.name}</td>
                      <td className="py-2 px-4">{user.email}</td>
                      <td className="py-2 px-4">{user.package}</td>
                      <td className="py-2 px-4 font-semibold text-yellow-300">
                        {user.pendingPackage}
                      </td>

                      <td className="py-2 px-4 flex gap-2">
                        <button
                          onClick={() => handleApprove(user._id)}
                          className="px-3 py-1 bg-green-600 rounded hover:bg-green-700 text-white"
                        >
                          Approve
                        </button>

                        <button
                          onClick={() => handleReject(user._id)}
                          className="px-3 py-1 bg-red-600 rounded hover:bg-red-700 text-white"
                        >
                          Reject
                        </button>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </AdminLayout>
  );
};

export default AdminPackageRequest;
