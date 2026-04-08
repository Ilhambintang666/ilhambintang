<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris - PMI Semarang</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #000;
        }
        .hero-bg {
            background: linear-gradient(
                rgba(0, 0, 0, 0.4), 
                rgba(0, 0, 0, 0.8)
            ), url("{{ asset('images/pmi.jpeg') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            padding: 0 20px;
        }
        .pmi-logo-wrapper {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 25px;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            animation: fadeInDown 1s ease-out;
            border: 2px solid rgba(255,255,255,0.8);
        }
        .pmi-logo-wrapper img {
            max-width: 280px;
            height: auto;
            display: block;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 10px rgba(0,0,0,0.6);
            animation: fadeInUp 1s ease-out 0.3s backwards;
            letter-spacing: -0.5px;
        }
        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 400;
            margin-bottom: 3.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 5px rgba(0,0,0,0.6);
            animation: fadeInUp 1s ease-out 0.5s backwards;
        }
        .btn-enter {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 12px 45px;
            border: 2px solid rgba(255,255,255,0.8);
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(5px);
            color: white;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-transform: none;
            letter-spacing: 0.5px;
            animation: fadeInUp 1s ease-out 0.7s backwards;
            text-decoration: none;
        }
        .btn-enter:hover {
            background-color: white;
            color: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            border-color: white;
        }
        
        .hero-footer {
            position: absolute;
            bottom: 30px;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
            animation: fadeIn 2s ease-out 1s backwards;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.2rem; }
            .hero-subtitle { font-size: 1.1rem; }
            .pmi-logo-wrapper img { max-width: 200px; }
            .btn-enter { padding: 12px 35px; font-size: 1rem; }
        }
    </style>
</head>
<body>

    <div class="hero-bg">
        <div class="pmi-logo-wrapper">
            <img src="{{ asset('images/pmi.png') }}" alt="Logo PMI Semarang">
        </div>
        
        <h1 class="hero-title">Sistem Inventaris PMI</h1>
        <h2 class="hero-subtitle">Palang Merah Indonesia Kota Semarang</h2>
        
        <a href="{{ route('login') }}" class="btn-enter">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </a>

        <div class="hero-footer text-center" style="position: relative; margin-top: 50px; bottom: 0;">
            <i class="fas fa-chevron-down mb-2" style="animation: bounce 2s infinite;"></i>
            <p class="mb-0">Scroll untuk info alamat</p>
        </div>
    </div>

    <!-- Info Footer Section -->
    <div class="footer-section py-5 position-relative" style="background: linear-gradient(to bottom, #111, #000); color: white; border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="container">
            <div class="row gx-5">
                <!-- Kolom Alamat -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h4 class="fw-bold mb-4" style="color: #f8f9fa;">Alamat</h4>
                    <p class="mb-1" style="color: #ccc; font-size: 1.05rem;">Jl. Mgr Sugiyopranoto No.35, Pendrikan Kidul,</p>
                    <p class="mb-4" style="color: #ccc; font-size: 1.05rem;">Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50131</p>
                    
                    <p class="mb-2" style="color: #ccc;">
                        <strong class="text-white">Telepon:</strong> 0243541237
                    </p>
                    <p class="mb-0" style="color: #ccc;">
                        <strong class="text-white">Website:</strong> <a href="http://www.pmikotasemarang.or.id/" target="_blank" style="color: #e60000; text-decoration: none;">http://www.pmikotasemarang.or.id/</a>
                    </p>
                </div>
                
                <!-- Kolom Peta -->
                <div class="col-lg-6">
                    <h4 class="fw-bold mb-4" style="color: #f8f9fa;">Lokasi Peta</h4>
                    <a href="https://maps.app.goo.gl/zRpJXX99Sw1nEhNP9" target="_blank" class="d-block overflow-hidden position-relative" style="border-radius: 12px; border: 2px solid rgba(255,255,255,0.1); transition: all 0.3s;" onmouseover="this.style.borderColor='rgba(230,0,0,0.5)'; this.style.transform='scale(1.02)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.transform='scale(1)';">
                        <!-- We use an iframe mapped exactly to PMI Kota Semarang -->
                        <div style="width: 100%; position: relative; pointer-events: none;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15840.407147774218!2d110.4005527!3d-6.9805995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708cafc286e1cd%3A0xe6719ab489cb0e18!2sPMI%20Kota%20Semarang!5e0!3m2!1sid!2sid!4v1712581622345!5m2!1sid!2sid" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                            <span class="btn btn-danger rounded-pill px-4"><i class="fas fa-map-marker-alt me-2"></i>Buka di Gmaps</span>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="mb-0 small" style="color: #6c757d;">&copy; {{ date('Y') }} Palang Merah Indonesia Kota Semarang. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);} 
            40% {transform: translateY(-10px);} 
            60% {transform: translateY(-5px);} 
        }
    </style>

</body>
</html>
