import React, { useEffect, useState } from "react";
import AdminSidebar from "../../components/AdminSidebar";
import AdminNavbar from "../../components/AdminNavbar";
import AdminLayout from "../../components/AdminLayout";
import EditUserAdmin from "../../components/EditUserAdmin";
import API from "../../utils/api";
import { UserPen, UserPlus, Trash } from "lucide-react";
import Swal from "sweetalert2";
import { useAuth } from "../../context/AuthContext";

import L from "leaflet";
import "leaflet/dist/leaflet.css";
import { Edit } from "lucide-react";

const AdminUsersPage = () => {
  const { token } = useAuth();
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);

  const toggleSidebar = () => setSidebarOpen(!sidebarOpen);

  // ======================= FETCH USERS =======================
  const fetchUsers = async () => {
    setLoading(true);
    try {
      const res = await API.get("/admin/users", {
        headers: { Authorization: `Bearer ${token}` },
      });
      setUsers(res.data.users);
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: err.response?.data?.message || "Failed to load users",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (token) fetchUsers();
  }, [token]);

  // ======================= ADD USER =======================
  const handleAddUser = async () => {
    const { value: formValues } = await Swal.fire({
      title: "Add User",
      html: `
        <input id="swal-name" class="swal2-input" placeholder="Name">
        <input id="swal-email" class="swal2-input" placeholder="Email">
        <input id="swal-password" type="password" class="swal2-input" placeholder="Password">
        <select id="swal-role" class="swal2-select">
          <option value="member">Member</option>
          <option value="admin">Admin</option>
        </select>
        <select id="swal-package" class="swal2-select">
          <option value="10MBPS">10MBPS - Rp 210.000</option>
          <option value="20MBPS">20MBPS - Rp 210.000</option>
        </select>
      `,
      focusConfirm: false,
      preConfirm: () => {
        const name = document.getElementById("swal-name").value;
        const email = document.getElementById("swal-email").value;
        const password = document.getElementById("swal-password").value;
        const role = document.getElementById("swal-role").value;
        const pkg = document.getElementById("swal-package").value;

        if (!name || !email || !password) {
          Swal.showValidationMessage("All fields are required");
          return false;
        }

        return { name, email, password, role, package: pkg };
      },
      background: "#1E293B",
      color: "#E2E8F0",
    });

    if (!formValues) return;

    try {
      await API.post("/admin/users", formValues, {
        headers: { Authorization: `Bearer ${token}` },
      });

      Swal.fire({
        icon: "success",
        title: "User Added Successfully",
        background: "#1E293B",
        color: "#E2E8F0",
      });

      fetchUsers();
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: err.response?.data?.message || "Failed to add user",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    }
  };

  const handleEditUser = async (user) => {
    let mapInstance;
    let marker;

    let chosenLat = user.location?.lat || -6.2;
    let chosenLng = user.location?.lng || 106.81;

    await Swal.fire({
      title: `Edit ${user.name}`,
      width: "750px",
      html: `
      <style>
        .swal-grid {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 12px;
          margin-bottom: 10px;
        }
        .swal-grid-full {
          grid-column: span 2;
        }
        .swal-label {
          font-weight: 600;
          margin-bottom: 4px;
          display: block;
          text-align: left;
          color: #cbd5e1;
        }
        .swal-input, .swal-select {
          width: 100%;
          padding: 8px;
          border-radius: 6px;
          background: #0f172a;
          border: 1px solid #334155;
          color: white;
        }
      </style>

      <div class="swal-grid">

        <div>
          <label class="swal-label">Name</label>
          <input id="swal-name" class="swal-input" value="${user.name}" />
        </div>

        <div>
          <label class="swal-label">Email (readonly)</label>
          <input id="swal-email" class="swal-input" value="${
            user.email
          }" readonly />
        </div>

        <div>
          <label class="swal-label">Role</label>
          <select id="swal-role" class="swal-select">
            <option value="member" ${
              user.role === "member" ? "selected" : ""
            }>Member</option>
            <option value="admin" ${
              user.role === "admin" ? "selected" : ""
            }>Admin</option>
          </select>
        </div>

        <div>
          <label class="swal-label">Paket Internet</label>
          <select id="swal-package" class="swal-select">
            <option value="10MBPS" ${
              user.package === "10MBPS" ? "selected" : ""
            }>
              10MBPS - Rp 150.000
            </option>
            <option value="20MBPS" ${
              user.package === "20MBPS" ? "selected" : ""
            }>
              20MBPS - Rp 210.000
            </option>
          </select>
        </div>

        <div class="swal-grid-full">
          <label class="swal-label">Location</label>
          <div id="map-edit" 
               style="height: 260px; margin-top: 6px; border-radius: 8px; border: 1px solid #334155;">
          </div>
        </div>

        <p style="grid-column: span 2; color:#ccc; margin-top:8px; font-size: 12px;">
          Klik pada peta untuk memindahkan marker lokasi
        </p>

      </div>
    `,
      didOpen: () => {
        mapInstance = L.map("map-edit").setView([chosenLat, chosenLng], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(
          mapInstance
        );

        marker = L.marker([chosenLat, chosenLng]).addTo(mapInstance);

        mapInstance.on("click", (e) => {
          chosenLat = e.latlng.lat;
          chosenLng = e.latlng.lng;
          marker.setLatLng([chosenLat, chosenLng]);
        });
      },

      preConfirm: () => {
        const name = document.getElementById("swal-name").value;
        const role = document.getElementById("swal-role").value;
        const pkg = document.getElementById("swal-package").value;

        if (!name) {
          Swal.showValidationMessage("Name required");
          return false;
        }

        return {
          name,
          role,
          package: pkg,
          location: { lat: chosenLat, lng: chosenLng },
        };
      },

      background: "#1E293B",
      color: "#E2E8F0",
      showCancelButton: true,
    }).then(async (result) => {
      if (!result.isConfirmed) return;

      try {
        await API.put(`/admin/users/${user._id}`, result.value, {
          headers: { Authorization: `Bearer ${token}` },
        });

        Swal.fire({
          icon: "success",
          title: "User Updated!",
          background: "#1E293B",
          color: "#E2E8F0",
        });

        fetchUsers();
      } catch (err) {
        console.error(err);
        Swal.fire({
          icon: "error",
          title: "Update Failed",
          text: err.response?.data?.message || "Could not update user",
          background: "#1E293B",
          color: "#E2E8F0",
        });
      }
    });
  };

  // ======================= DELETE USER =======================
  const handleDeleteUser = async (id) => {
    const result = await Swal.fire({
      title: "Delete User?",
      text: "This action cannot be undone",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#EF4444",
      cancelButtonColor: "#3B82F6",
      confirmButtonText: "Yes, delete",
      background: "#1E293B",
      color: "#E2E8F0",
    });

    if (!result.isConfirmed) return;

    try {
      await API.delete(`/admin/users/${id}`, {
        headers: { Authorization: `Bearer ${token}` },
      });

      Swal.fire({
        icon: "success",
        title: "User Deleted",
        background: "#1E293B",
        color: "#E2E8F0",
      });

      fetchUsers();
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: err.response?.data?.message || "Failed to delete user",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    }
  };

  return (
    <AdminLayout>
      <div className="absolute top-16 md:top-0 left-0 w-full">
        <div className="flex-1 min-h-screen bg-[#0F172A]">
          <main className="p-6 md:ml-64">
            <div className="flex justify-between mb-4">
              <h2 className="text-white text-xl font-semibold">Users</h2>

              <button
                onClick={handleAddUser}
                className="px-4 py-2 bg-[#3B82F6] rounded hover:bg-[#2563EB] text-white"
              >
                <UserPlus />
              </button>
            </div>

            <div className="w-full overflow-x-auto rounded-xl bg-[#1E293B] shadow p-4">
              <table className="min-w-max w-full text-left border-collapse">
                <thead>
                  <tr className="border-b border-gray-700 text-white">
                    <th className="py-2 px-4">Name</th>
                    <th className="py-2 px-4 hidden sm:table-cell">Email</th>
                    <th className="py-2 px-4">Role</th>
                    <th className="py-2 px-4">Action</th>
                  </tr>
                </thead>

                <tbody>
                  {loading ? (
                    <tr>
                      <td colSpan={4} className="text-center text-white py-4">
                        Loading...
                      </td>
                    </tr>
                  ) : users.length === 0 ? (
                    <tr>
                      <td colSpan={4} className="text-center text-white py-4">
                        No users found
                      </td>
                    </tr>
                  ) : (
                    users.map((user) => (
                      <tr
                        key={user._id}
                        className="border-b border-gray-700 text-white"
                      >
                        <td className="py-2 px-4">{user.name}</td>
                        <td className="py-2 px-4 hidden sm:table-cell">
                          {user.email}
                        </td>
                        <td className="py-2 px-4">{user.role}</td>
                        <td className="py-2 px-4 flex gap-2">
                          {/* ✏️ EDIT BUTTON */}
                          <button
                            onClick={() => handleEditUser(user)}
                            className="px-3 py-1 bg-yellow-500 rounded hover:bg-yellow-600"
                          >
                            <UserPen />
                          </button>

                          {/* 🗑 DELETE BUTTON */}
                          <button
                            onClick={() => handleDeleteUser(user._id)}
                            className="px-3 py-1 bg-red-600 rounded hover:bg-red-700"
                          >
                            <Trash />
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
      </div>
    </AdminLayout>
  );
};

export default AdminUsersPage;
