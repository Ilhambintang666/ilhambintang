import { BrowserRouter, Routes, Route } from "react-router-dom";
import LandingPage from "../pages/LandingPage";
import LoginPage from "../pages/LoginPage";
import RegisterPage from "../pages/RegisterPage";
import MemberDashboard from "../pages/Member/MemberDashboard";
import AdminDashboard from "../pages/Admin/AdminDashboard";
import RouteProtect from "../components/RouteProtect";
import ProfilePage from "../pages/ProfilePage";
import AdminUsersPage from "../pages/Admin/AdminUsersPage";
import { Package } from "lucide-react";
import PackagesPage from "../pages/PackagesPage";
import AdminPackageRequest from "../pages/Admin/AdminPackageRequest";
import PaymentPage from "../pages/PaymentPage";
import AdminMutasiPage from "../pages/Admin/AdminMutasiPage";

export default function AppRouter() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<LandingPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />

        <Route
          path="/member"
          element={
            <RouteProtect role="member">
              <MemberDashboard />
            </RouteProtect>
          }
        />

        <Route
          path="/admin/package-requests"
          element={
            <RouteProtect role="admin">
              <AdminPackageRequest />
            </RouteProtect>
          }
        />
        <Route
          path="/admin/users"
          element={
            <RouteProtect role="admin">
              <AdminUsersPage />
            </RouteProtect>
          }
        />
        <Route
          path="/admin/mutasi"
          element={
            <RouteProtect role="admin">
              <AdminMutasiPage />
            </RouteProtect>
          }
        />

        <Route
          path="/admin/dashboard"
          element={
            <RouteProtect role="admin">
              <AdminDashboard />
            </RouteProtect>
          }
        />

        <Route path="/profile" element={<ProfilePage />} />
        <Route path="/tagihan" element={<PaymentPage />} />
        <Route path="/user-packages" element={<PackagesPage />} />
        <Route
          path="*"
          element={<h1 className="text-center mt-10">404 Not Found</h1>}
        />
      </Routes>
    </BrowserRouter>
  );
}
