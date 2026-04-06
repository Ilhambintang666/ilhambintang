// Footer.jsx
export default function Footer() {
  return (
    <footer id="contact" className="bg-gray-900 text-gray-300 py-10 mt-10">
      <div className="container mx-auto px-4 grid md:grid-cols-3 gap-8">
        {/* Brand */}
        <div>
          <h2 className="text-2xl font-bold text-indigo-400">
            PT Rambon Network Telekomunikasi Indonesia
          </h2>
          <p className="mt-3 text-sm">
            Layanan internet cepat, stabil, dan terpercaya untuk kebutuhan rumah
            dan bisnis Anda.
          </p>
        </div>

        {/* Contact Info */}
        <div>
          <h3 className="text-lg font-semibold mb-3 text-indigo-400">
            Kontak Kami
          </h3>
          <p>
            📍 Jl. Untung Suropati, Plendungan, Kuripan, Kec. Purwodadi,
            Kabupaten Grobogan, Jawa Tengah 58112
          </p>
          <p>📞 +62 812-3456-7890</p>
          <p>✉️ support@rambonetwork.id</p>
          <p>✉️ noc@rambonetwork.id</p>
        </div>

        {/* Social Media */}
        <div>
          <h3 className="text-lg font-semibold mb-3 text-indigo-400">
            Ikuti Kami
          </h3>
          <div className="flex space-x-4">
            <a href="#" className="hover:text-yellow-300">
              Facebook
            </a>
            <a href="#" className="hover:text-yellow-300">
              Instagram
            </a>
            <a href="#" className="hover:text-yellow-300">
              WhatsApp
            </a>
          </div>
        </div>
      </div>

      {/* Bottom */}
      <div className="text-center text-sm text-gray-500 mt-8 border-t border-gray-700 pt-4">
        © {new Date().getFullYear()} PT Rambo Network Telekomunikasi Indonesia.
        All Rights Reserved.
      </div>
    </footer>
  );
}
