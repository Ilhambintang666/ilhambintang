import { Navigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

export default function RouteProtect({ children, role }) {
  const { user } = useAuth();

  if (!user) {
    // belum login
    return <Navigate to="/login" replace />;
  }

  if (role && user.role !== role) {
    // role tidak sesuai (misalnya member akses halaman admin)
    return <Navigate to="/" replace />;
  }

  return children;
}
