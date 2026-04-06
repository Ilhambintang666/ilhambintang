import React from "react";
import DashboardLayout from "../../components/DashboardLayout";

const MemberDashboard = () => {
  return (
    <DashboardLayout>
      <h1 className="text-3xl font-bold mb-4 text-[#3B82F6]">
        Member Dashboard
      </h1>
      <p className="text-gray-300">
        Selamat datang di area member. Nikmati fitur eksklusif Anda di sini!
      </p>
    </DashboardLayout>
  );
};

export default MemberDashboard;
