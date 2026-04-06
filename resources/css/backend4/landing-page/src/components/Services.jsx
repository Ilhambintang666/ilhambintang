import React from "react";
import { motion } from "framer-motion";

const packages = [
  {
    name: "RAMBO XS",
    price: "Rp 150k",
    speed: "Up to 12 Mbps",
    bandwidth: "UNLIMITED",
    popular: true,
  },
  {
    name: "RAMBO S",
    price: "Rp 165k",
    speed: "Up to 16 Mbps",
    bandwidth: "UNLIMITED",
  },
  {
    name: "RAMBO M",
    price: "Rp 200k",
    speed: "Up to 20 Mbps",
    bandwidth: "UNLIMITED",
  },
  {
    name: "RAMBO L",
    price: "Rp 215k",
    speed: "Up to 25 Mbps",
    bandwidth: "UNLIMITED",
  },
];

export default function ServicesSection() {
  return (
    <section
      id="services"
      className="relative py-20 mt-16 text-white"
      style={{
        backgroundImage:
          "url('https://i.postimg.cc/pLQ7MW6R/ai-generated-8259059-1280.jpg')",
        backgroundSize: "cover",
        backgroundPosition: "center",
      }}
    >
      {/* Overlay Gradient */}
      <div className="absolute inset-0 bg-gradient-to-b from-black/80 via-black/70 to-black/80"></div>

      <div className="relative max-w-6xl mx-auto px-4">
        <h2 className="text-3xl md:text-4xl font-bold text-center mb-12">
          Paket Internet
        </h2>

        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {packages.map((pkg, idx) => (
            <motion.div
              key={idx}
              initial={{ opacity: 0, y: 50 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: idx * 0.1 }}
              className="relative rounded-2xl p-6 shadow-xl border border-white/20 bg-white/10 backdrop-blur-md hover:scale-105 transition-transform duration-300"
            >
              {/* Label Populer */}
              {pkg.popular && (
                <span className="absolute top-3 right-3 bg-red-500 text-gray-300 px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                  Populer
                </span>
              )}

              <h3 className="text-xl font-bold mb-2">{pkg.name}</h3>
              <p className="text-3xl font-bold mb-4">{pkg.price}</p>
              <p className="mb-2">Speed {pkg.speed}</p>
              <p className="mb-6">Bandwidth {pkg.bandwidth}</p>
              <a
                href="https://api.whatsapp.com/send/?phone=6289510811261&text=Halo+admin%2CSaya+ingin+berlanggan+Rambo+wifi&type=phone_number&app_absent=0"
                target="_blank"
                rel="noopener noreferrer"
              >
                <button className="w-full py-2 rounded-lg bg-yellow-400 text-gray-300 font-semibold hover:bg-yellow-300 transition-colors">
                  Pilih Paket
                </button>
              </a>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
