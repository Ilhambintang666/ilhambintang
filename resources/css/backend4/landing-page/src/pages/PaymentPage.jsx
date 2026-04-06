import React, { useEffect, useState } from "react";
import DashboardLayout from "../components/DashboardLayout";
import { useAuth } from "../context/AuthContext";
import API from "../utils/api";
import Swal from "sweetalert2";

const PaymentPage = () => {
  const { user, token } = useAuth();
  const [payment, setPayment] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchPayment = async () => {
      try {
        const res = await API.get("/auth/tagihan", {
          headers: { Authorization: `Bearer ${token}` },
        });
        if (res.data.success) {
          setPayment(res.data.payment);
        }
      } catch (err) {
        Swal.fire({
          icon: "error",
          title: "Failed to load payment",
          text: err.response?.data?.message || "Server error",
          background: "#1E293B",
          color: "#E2E8F0",
        });
      } finally {
        setLoading(false);
      }
    };

    if (token) fetchPayment();
  }, [token]);

  if (loading)
    return (
      <DashboardLayout>
        <p className="text-white text-center mt-10">Loading...</p>
      </DashboardLayout>
    );

  if (!payment)
    return (
      <DashboardLayout>
        <p className="text-white text-center mt-10">
          No payment found for you.
        </p>
      </DashboardLayout>
    );

  return (
    <DashboardLayout>
      <div className="max-w-xl mx-auto bg-[#1E293B] p-8 rounded-xl shadow-lg mt-6">
        <h1 className="text-3xl font-bold text-[#3B82F6] mb-6">
          Informasi Pembayaran
        </h1>

        <div className="bg-[#0F172A] p-6 rounded-lg shadow text-white space-y-3">
          <p>
            <span className="font-semibold">Transaction ID:</span>{" "}
            {payment.trxId}
          </p>
          <p>
            <span className="font-semibold">Jumlah:</span> Rp{" "}
            {payment.amount.toLocaleString("ID-id")}
          </p>
          <p>
            <span className="font-semibold">Metode:</span> {payment.method}
          </p>
          <p>
            <span className="font-semibold">Ditagih pada:</span>{" "}
            {new Date(payment.dueDate).toLocaleDateString()}
          </p>
          <p>
            <span className="font-semibold">Status:</span>{" "}
            {payment.status === "success" ? (
              <span className="text-green-400 font-bold">Verified</span>
            ) : (
              <span className="text-yellow-400 font-bold">
                {payment.status}
              </span>
            )}
          </p>
        </div>

        {payment.status !== "success" && (
          <div className="mt-6 p-4 bg-[#1E293B]/80 rounded border border-gray-700 text-gray-300">
            Mohon transfer ke rekening berikut: <br />
            <span className="font-semibold text-white">
              Bank ABC 123456789 a.n MTCNA
            </span>
          </div>
        )}
      </div>
    </DashboardLayout>
  );
};

export default PaymentPage;
