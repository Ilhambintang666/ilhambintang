import HeroSection from "../components/HeroSection";
import Navbar from "../components/Navbar";
import AboutSection from "../components/AboutSection";
import Services from "../components/Services";
import WhyChooseUs from "../components/WhyChooseUs";
import Footer from "../components/Footer";

export default function LandingPage() {
  return (
    <div className="bg-gray-900 min-h-screen">
      <Navbar />
      <HeroSection />
      <AboutSection />
      <Services />
      <WhyChooseUs />
      <Footer />
    </div>
  );
}
