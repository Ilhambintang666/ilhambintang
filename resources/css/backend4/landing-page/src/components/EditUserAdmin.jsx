import Swal from "sweetalert2";
const EditUserAdmin = async (user) => {
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
export default EditUserAdmin;
