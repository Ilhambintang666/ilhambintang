import React from "react";
import { motion } from "framer-motion";
import {
  Headset,
  Tag,
  Gauge,
  BarChart3,
  ThumbsUp,
  MapPin,
  Tv,
} from "lucide-react";

const features = [
  {
    icon: <Headset className="w-10 h-10 text-yellow-400" />,
    title: "SUPPORT 24/7",
    desc: "Tim teknis NOC dan Field Operations kami akan terus menjaga performa jaringan, sehingga Anda dapat fokus pada bisnis utama.",
  },
  {
    icon: <Tag className="w-10 h-10 text-yellow-400" />,
    title: "INSTALLASI CEPAT",
    desc: "Proses pemasangan internet cepat dan rapi, langsung siap digunakan tanpa menunggu lama.",
  },
  {
    icon: <Gauge className="w-10 h-10 text-yellow-400" />,
    title: "UNLIMITED ACCESS!",
    desc: "Akses internet penuh tanpa batasan penggunaan (FUP) atau biaya tambahan tersembunyi.",
  },
  {
    icon: <Tv className="w-10 h-10 text-yellow-400" />,
    title: "FREE IPTV",
    desc: "Nikmati layanan IPTV gratis dengan berbagai channel pilihan untuk pelanggan kami.",
  },
  {
    icon: <ThumbsUp className="w-10 h-10 text-yellow-400" />,
    title: "SLA 96.5%",
    desc: "Jaminan uptime terbaik dengan jaringan yang redundant untuk ketersediaan hingga 96.5%.",
  },
  {
    icon: <MapPin className="w-10 h-10 text-yellow-400" />,
    title: "GET CLOSER TO YOU!",
    desc: "Respons lebih cepat dengan kantor cabang di berbagai kota di Grobogan.",
  },
];

export default function WhyChooseUs() {
  return (
    <section className="relative py-20 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-white">
      <div className="max-w-6xl mx-auto px-4">
        <h2 className="text-3xl md:text-4xl font-bold text-center mb-12">
          Why Choose Us
        </h2>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
          {features.map((feature, idx) => (
            <motion.div
              key={idx}
              initial={{ opacity: 0, y: 50 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: idx * 0.1 }}
              className="p-6 bg-gradient-to-br from-blue-700/90 to-blue-900/90 rounded-xl shadow-lg border border-white/10 hover:scale-105 transition-transform duration-300"
            >
              <div className="mb-4">{feature.icon}</div>
              <h3 className="text-lg font-bold mb-2">{feature.title}</h3>
              <p className="text-gray-300 text-sm">{feature.desc}</p>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
