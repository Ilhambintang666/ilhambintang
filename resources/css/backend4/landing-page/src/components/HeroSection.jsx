// src/components/HeroSection.jsx
import React, { useState, useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
export default function HeroSection() {
  const text = "PT Rambo Network Telekomunikasi Indonesia";
  const [displayedText, setDisplayedText] = useState("");
  const [index, setIndex] = useState(0);
  // AOS INIT
  useEffect(() => {
    AOS.init({ duration: 1000, once: true });
  }, []);
  //  typing
  useEffect(() => {
    if (index < text.length) {
      const timeout = setTimeout(() => {
        setDisplayedText((prev) => prev + text.charAt(index));
        setIndex(index + 1);
      }, 30); // kecepatan mengetik (ms)
      return () => clearTimeout(timeout);
    }
  }, [index, text]);
  // Button init
  const [isScrolled, setIsScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 200); // aktif setelah scroll 200px
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <section
      id="home"
      className="bg-gray-900 text-white min-h-screen flex items-center px-6"
      style={{
        backgroundImage:
          "url('https://i.postimg.cc/4xGtqsc6/frequency-wave-7776034-1920.jpg')",
        backgroundSize: "cover",
        backgroundPosition: "center",
      }}
    >
      <div className="max-w-6xl mx-auto flex flex-col-reverse md:flex-row items-center justify-between gap-10">
        {/* Teks */}
        <div className="text-center md:text-left max-w-xl">
          <h1 className="text-4xl sm:text-5xl font-bold leading-tight">
            {displayedText}
          </h1>
          <p
            className="mt-6 text-lg text-gray-300"
            data-aos="fade-left"
            data-aos-delay="2000"
          >
            Layanan internet cepat, murah, dan stabil. Solusi terbaik untuk
            bisnis Anda dengan dukungan penuh 24/7.
          </p>
          <div
            className={
              isScrolled
                ? "fixed bottom-6 right-6 z-50"
                : "mt-8 flex gap-4 justify-center md:justify-start"
            }
          >
            <a
              href="https://wa.me/6281234567890"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-2 bg-green-500 px-6 py-3 rounded-lg text-gray-300 hover:bg-green-600 transition shadow-lg"
              data-aos="fade-left"
              data-aos-delay="2500"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="30"
                height="22"
                fill="currentColor"
                viewBox="0 0 24 24"
              >
                <path d="M12.001 2.002c-5.523 0-10 4.478-10 10.001 0 1.768.467 3.497 1.355 5.018l-1.433 5.239 5.377-1.41a9.953 9.953 0 004.701 1.2h.001c5.523 0 10.001-4.478 10.001-10.001 0-2.671-1.041-5.179-2.929-7.071a9.951 9.951 0 00-7.072-2.976zm0 18.181a8.18 8.18 0 01-4.168-1.143l-.299-.177-3.191.838.853-3.111-.195-.319a8.182 8.182 0 01-1.264-4.369c0-4.521 3.678-8.199 8.199-8.199 2.19 0 4.251.854 5.8 2.402a8.183 8.183 0 012.399 5.797c0 4.521-3.678 8.199-8.199 8.199zm4.495-6.14c-.246-.123-1.454-.718-1.679-.8-.225-.083-.389-.123-.554.123-.163.246-.634.8-.777.964-.143.164-.286.184-.532.062-.246-.123-1.038-.382-1.977-1.218-.73-.65-1.224-1.451-1.367-1.697-.143-.246-.015-.379.108-.502.111-.11.246-.287.369-.43.123-.144.164-.246.246-.41.082-.164.041-.308-.021-.43-.062-.123-.554-1.338-.759-1.836-.2-.48-.404-.414-.554-.422l-.471-.009c-.164 0-.43.062-.655.308s-.86.84-.86 2.048 1.102 2.377 1.255 2.54c.154.164 2.172 3.309 5.261 4.637.736.318 1.31.508 1.756.65.738.235 1.41.202 1.94.123.592-.088 1.454-.594 1.659-1.168.205-.574.205-1.065.143-1.168-.062-.103-.225-.164-.471-.287z" />
              </svg>
              Pesan Sekarang
            </a>
          </div>
        </div>

        {/* Gambar */}
        <div
          className="flex justify-center md:justify-end w-full mt-10 md:mt-0"
          data-aos="fade-left"
          data-aos-delay="3000"
        >
          <img
            src="/src/assets/Net.png"
            alt="Hero Illustration"
            className="w-64 sm:w-80 md:w-96 h-auto object-contain animate-bounce rounded-2xl"
          />
        </div>
      </div>
    </section>
  );
}
