import React, { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

const LoginPage = () => {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setLoading(true);

    try {
      await login(email, password);
      const userData = JSON.parse(localStorage.getItem("user"));

      if (userData?.role === "admin") {
        navigate("/admin");
      } else {
        navigate("/member");
      }
    } catch (err) {
      setError(err.response?.data?.message || "Invalid email or password");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-[#0F172A] px-4">
      <div className="w-full max-w-md bg-[#1E293B] rounded-2xl shadow-lg p-8 md:p-10 text-white">
        <div className="mb-8 text-center">
          <h1 className="text-3xl font-semibold mb-2">
            Welcome to <span className="text-[#3B82F6] font-bold">RAMBO</span>
          </h1>
          <p className="text-gray-400">Sign in to continue</p>
        </div>

        <form className="flex flex-col gap-5" onSubmit={handleSubmit}>
          <div className="flex flex-col gap-2">
            <label htmlFor="email" className="font-medium text-gray-200">
              Email address
            </label>
            <input
              className="p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
              type="email"
              id="email"
              placeholder="you@example.com"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              disabled={loading}
            />
          </div>

          <div className="flex flex-col gap-2">
            <label htmlFor="password" className="font-medium text-gray-200">
              Password
            </label>
            <input
              className="p-3 rounded-lg border border-gray-600 bg-[#0F172A] text-white focus:outline-none focus:ring-2 focus:ring-[#3B82F6]"
              type="password"
              id="password"
              placeholder="••••••••"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              disabled={loading}
            />
          </div>

          {error && <p className="text-red-400 text-center">{error}</p>}

          <button
            type="submit"
            disabled={loading}
            className={`bg-[#3B82F6] hover:bg-[#2563EB] transition-colors p-3 rounded-lg text-white font-semibold mt-2 ${
              loading ? "opacity-50 cursor-not-allowed" : ""
            }`}
          >
            {loading ? "Signing in..." : "Sign In"}
          </button>
        </form>

        <div className="mt-6 text-center text-gray-400">
          No account?{" "}
          <Link to="/register" className="text-[#3B82F6] hover:underline">
            Sign up
          </Link>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
