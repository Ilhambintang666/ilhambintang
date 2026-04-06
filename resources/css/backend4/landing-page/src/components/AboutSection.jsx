import { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";

export default function AboutSection() {
  useEffect(() => {
    AOS.init({ duration: 1000 });
  }, []);

  const stats = [
    { title: "Jumlah Kota", value: "7+" },
    { title: "Jumlah Customer", value: "2800+" },
    { title: "Tenaga Ahli", value: "13+" },
    { title: "Support", value: "24/7" },
  ];

  return (
    <section
      id="about"
      className="relative bg-fixed bg-cover bg-center mt-20 py-16"
      style={{
        backgroundImage:
          "url('https://i.postimg.cc/t4YgTcF1/earth-3537401-1920.jpg')",
      }}
    >
      {/* Overlay gelap */}
      <div className="absolute inset-0 bg-black/50"></div>

      {/* Konten */}
      <div className="relative max-w-6xl mx-auto px-6 text-white">
        <h2
          className="text-3xl md:text-4xl font-bold text-center mb-6"
          data-aos="fade-up"
        >
          Tentang Kami
        </h2>
        <p
          className="text-lg md:text-xl text-center max-w-3xl mx-auto mb-12 leading-relaxed"
          data-aos="fade-up"
          data-aos-delay="200"
        >
          PT. Rambo Network adalah penyedia layanan internet (ISP) yang
          berkomitmen untuk menghadirkan koneksi internet cepat, stabil, dan
          andal bagi masyarakat Indonesia. Berdiri sejak 2022, Rambo telah
          menjadi mitra utama bagi ribuan rumah tangga, bisnis, dan organisasi
          di seluruh negeri, dengan jaringan yang menjangkau area perkotaan
          hingga pedesaan.
          <br />
          <br />
          Kami memahami bahwa internet adalah jantung dari aktivitas
          sehari-hari, mulai dari hiburan, komunikasi, hingga bisnis. Oleh
          karena itu, kami fokus memberikan layanan berkualitas dengan teknologi
          terbaru dan dukungan pelanggan yang profesional, agar Anda selalu
          terhubung kapan saja dan di mana saja.
          <br />
          <br />
          Di Rambo, kami percaya bahwa setiap orang berhak mendapatkan akses
          internet yang mudah dan terjangkau. Dengan berbagai pilihan paket
          internet yang fleksibel, kami berusaha memastikan semua lapisan
          masyarakat dapat menikmati kecepatan internet terbaik tanpa kompromi.
        </p>

        {/* Cards */}
        <div
          className="grid grid-cols-2 md:grid-cols-4 gap-6"
          data-aos="fade-up"
          data-aos-delay="400"
        >
          {stats.map((stat, idx) => (
            <div
              key={idx}
              className="bg-white/10 backdrop-blur-md rounded-xl p-6 text-center border border-white/20 shadow-lg hover:scale-105 transform transition duration-300"
            >
              <h3 className="text-3xl font-bold text-yellow-400">
                {stat.value}
              </h3>
              <p className="mt-2 text-sm md:text-base">{stat.title}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
