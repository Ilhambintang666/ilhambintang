import React, { useState, useContext } from "react";
import { Link, useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import AuthContext from "../context/AuthContext";
import { MapContainer, TileLayer, Marker, useMapEvents } from "react-leaflet";

const RegisterPage = () => {
  const { register } = useContext(AuthContext);
  const navigate = useNavigate();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [selectedPackage, setSelectedPackage] = useState("10MBPS");
  const [location, setLocation] = useState({ lat: -6.2, lng: 106.816666 }); // default Jakarta
  const [loading, setLoading] = useState(false);

  // Component untuk memilih titik lokasi
  function LocationSelector() {
    useMapEvents({
      click(e) {
        setLocation({
          lat: e.latlng.lat,
          lng: e.latlng.lng,
        });
      },
    });
    return null;
  }

  const handleRegister = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      await register(email, password, selectedPackage, location);

      await Swal.fire({
        title: "Registration Successful!",
        text: "Your account has been created.",
        icon: "success",
        confirmButtonColor: "#3B82F6",
        background: "#1E293B",
        color: "#E2E8F0",
      });

      navigate("/login");
    } catch (error) {
      Swal.fire({
        title: "Registration Failed!",
        text: error.response?.data?.message || "Please try again later.",
        icon: "error",
        confirmButtonColor: "#EF4444",
        background: "#1E293B",
        color: "#E2E8F0",
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="w-full min-h-screen flex items-center justify-center bg-[#0F172A]">
      <div className="w-[90%] max-w-md bg-[#1E293B] rounded-2xl shadow-2xl p-8 sm:p-10 text-white flex flex-col">
        <div className="text-center mb-8">
          <h1 className="text-3xl sm:text-4xl font-semibold mb-2">
            Ingin Berlangganan{" "}
            <span className="text-[#3B82F6] font-bold">Rambo Wifi</span>
            <p className="text-gray-400 text-sm">
              Silahkan Daftar Isi Form Dibawah
            </p>
          </h1>
        </div>

        <form className="flex flex-col gap-5" onSubmit={handleRegister}>
          {/* EMAIL */}
          <div className="flex flex-col gap-2">
            <label htmlFor="email" className="font-medium text-gray-200">
              Email
            </label>
            <input
              type="email"
              id="email"
              placeholder="you@example.com"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              disabled={loading}
              className="p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
            />
          </div>

          {/* PASSWORD */}
          <div className="flex flex-col gap-2">
            <label htmlFor="password" className="font-medium text-gray-200">
              Password
            </label>
            <input
              type="password"
              id="password"
              placeholder="••••••••"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              disabled={loading}
              className="p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
            />
          </div>

          {/* INTERNET PACKAGE */}
          <div className="flex flex-col gap-2">
            <label className="font-medium text-gray-200">
              Pilih Paket Internet
            </label>
            <select
              value={selectedPackage}
              onChange={(e) => setSelectedPackage(e.target.value)}
              disabled={loading}
              className="p-3 rounded-lg border border-gray-600 bg-[#0F172A] focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
            >
              <option value="10MBPS">10MBPS - Rp 150.000</option>
              <option value="20MBPS">20MBPS - Rp 210.000</option>
            </select>
          </div>

          {/* LOCATION PICKER */}
          <div className="flex flex-col gap-2">
            <label className="font-medium text-gray-200">
              Pilih Lokasi (klik pada peta)
            </label>

            <div className="rounded-lg overflow-hidden border border-gray-700">
              <MapContainer
                center={[location.lat, location.lng]}
                zoom={13}
                style={{ height: "250px", width: "100%" }}
              >
                <TileLayer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" />

                <Marker position={[location.lat, location.lng]} />
                <LocationSelector />
              </MapContainer>
            </div>

            <p className="text-sm text-gray-400">
              Lokasi dipilih: {location.lat.toFixed(5)},{" "}
              {location.lng.toFixed(5)}
            </p>
          </div>

          {/* SUBMIT */}
          <button
            type="submit"
            disabled={loading}
            className={`w-full py-3 rounded-lg text-white font-semibold transition-all ${
              loading
                ? "bg-gray-600 cursor-not-allowed"
                : "bg-[#3B82F6] hover:bg-[#2563EB]"
            }`}
          >
            {loading ? "Registering..." : "Register"}
          </button>
        </form>

        <p className="mt-6 text-center text-gray-400 text-sm sm:text-base">
          Already have an account?{" "}
          <Link
            to="/login"
            className="text-[#3B82F6] hover:underline hover:text-[#60A5FA]"
          >
            Sign in
          </Link>
        </p>
      </div>
    </div>
  );
};

export default RegisterPage;
