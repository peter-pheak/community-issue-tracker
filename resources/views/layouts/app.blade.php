<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Community Issue Tracker') &mdash; CitizenConnect</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')

  <style>
    :root {
      --primary: #0B4F6C;
      --accent: #E07A5F;
      --success: #2A9D8F;
      --bg: #F4F1EA;
      --text: #1E293B;
      --border: #D6CCC2;
      --white: #ffffff;
    }
    * { box-sizing: border-box; }
    body {
      background-color: var(--bg); color: var(--text);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      margin: 0; padding: 0; overflow-x: hidden;
    }
    .layer-background {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      z-index: 0; pointer-events: none;
    }
    .atmospheric-glow {
      position: absolute; top: -20%; left: 50%; transform: translateX(-50%);
      width: 120%; height: 80%;
      background: radial-gradient(ellipse at center, rgba(11,79,108,0.08) 0%, transparent 70%);
      pointer-events: none;
    }
    .layer-midground {
      position: fixed; bottom: 0; left: 0; width: 100%; height: 300px;
      z-index: 1; pointer-events: none; overflow: hidden;
    }
    .skyline { position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; }
    .skyline-1 { fill: rgba(11,79,108,0.12); }
    .skyline-2 { fill: rgba(224,122,95,0.10); }
    .skyline-3 { fill: rgba(180,160,140,0.15); }
    .layer-foreground { position: relative; z-index: 2; }

    .site-header {
      position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .site-header:not(.scrolled) {
      background-color: rgba(255,255,255,0.85);
      backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .site-header.scrolled {
      background-color: var(--white); box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    .navbar-brand-custom {
      font-weight: 600; font-size: 1.25rem; color: var(--primary) !important;
      display: flex; align-items: center; gap: 0.5rem; text-decoration: none;
    }
    .navbar-brand-custom:hover { color: var(--primary) !important; }
    .nav-link-custom {
      color: var(--text) !important; position: relative; padding: 0.5rem 1rem;
      transition: color 0.3s ease;
    }
    .nav-link-custom::after {
      content: ''; position: absolute; bottom: 2px; left: 1rem; right: 1rem;
      height: 2px; background-color: var(--accent);
      transform: scaleX(0); transition: transform 0.3s ease;
    }
    .nav-link-custom:hover::after, .nav-link-custom.active::after { transform: scaleX(1); }
    .nav-link-custom:hover { color: var(--primary) !important; }
    .nav-link-custom.active { color: var(--primary) !important; font-weight: 500; }
    .btn-report-nav {
      background-color: var(--accent); color: var(--white) !important;
      border-radius: 50px; padding: 0.4rem 1.2rem !important;
      transition: background-color 0.3s ease;
    }
    .btn-report-nav:hover { background-color: #c96a52; }

    :target { scroll-margin-top: 80px; }

    .alert-area { padding-top: 80px; position: relative; z-index: 2; }
    .main-content { position: relative; z-index: 2; min-height: 60vh; }

    footer {
      position: relative; z-index: 2; margin-top: 4rem;
    }
    footer svg { display: block; width: 100%; height: auto; }
    .footer-content {
      background: linear-gradient(180deg, var(--primary) 0%, #08384e 100%);
      color: rgba(255,255,255,0.9); padding: 3rem 0 2rem;
    }
    .footer-brand-text {
      color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.6;
    }
    .footer-heading {
      color: var(--white); font-weight: 600; margin-bottom: 1rem;
      font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-link {
      color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.95rem;
      display: block; padding: 0.25rem 0; transition: color 0.3s;
    }
    .footer-link:hover { color: var(--accent); }
    .footer-contact-item {
      display: block; color: rgba(255,255,255,0.7); text-decoration: none;
      padding: 0.25rem 0; font-size: 0.95rem; transition: color 0.3s;
    }
    .footer-contact-item:hover { color: var(--accent); }
    .footer-contact-item i { margin-right: 0.5rem; }
    .back-to-top {
      width: 40px; height: 40px; border-radius: 50%;
      background-color: rgba(255,255,255,0.15); border: none;
      color: var(--white); cursor: pointer;
      transition: background-color 0.3s;
    }
    .back-to-top:hover { background-color: var(--accent); }
    .footer-divider {
      border-color: rgba(255,255,255,0.15); margin: 2rem 0 1.5rem;
    }
    .footer-bottom-text {
      color: rgba(255,255,255,0.5); font-size: 0.85rem;
    }

    .card-issue {
      border: 1px solid var(--border); border-radius: 12px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      border-left: 4px solid transparent;
    }
    .card-issue:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.08);
      border-left-color: var(--accent);
    }
    .skeleton {
      background: linear-gradient(90deg, #eee 25%, #f5f5f5 50%, #eee 75%);
      background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 12px;
    }
    @keyframes shimmer {
      0% { background-position: -200% 0; }
      100% { background-position: 200% 0; }
    }
    .reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .badge-Road { background-color: #6c757d; }
    .badge-Lighting { background-color: #ffc107; color: #000; }
    .badge-Waste { background-color: #198754; }
    .badge-Other { background-color: #0d6efd; }
    .status-Open { background-color: #dc3545; }
    .status-In-Progress { background-color: #fd7e14; }
    .status-Resolved { background-color: #198754; }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
    .shake { animation: shake 0.3s ease-in-out; }
    @media (prefers-reduced-motion: reduce) {
      *,*::before,*::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
    }
    #map { height: 420px; border-radius: 12px; margin-bottom: 1.5rem; }
    #detail-map { height: 250px; border-radius: 8px; margin: 1rem 0; }
    #picker-map { height: 300px; border-radius: 8px; margin-bottom: 1rem; }
    @media (max-width: 576px) { #map { height: 250px; } }
  </style>
</head>
<body>

  {{-- Background layers --}}
  <div class="layer-background" aria-hidden="true">
    <div class="atmospheric-glow"></div>
  </div>
  <div class="layer-midground" aria-hidden="true">
    <svg class="skyline" viewBox="0 0 1440 300" preserveAspectRatio="none">
      <path class="skyline-1" d="M0,180 L30,170 L60,185 L90,165 L120,175 L150,155 L180,170 L210,145 L240,160 L270,140 L300,150 L330,130 L360,145 L390,125 L420,135 L450,110 L480,130 L510,105 L540,120 L570,100 L600,115 L630,95 L660,110 L690,85 L720,100 L750,75 L780,95 L810,70 L840,85 L870,60 L900,75 L930,50 L960,65 L990,40 L1020,55 L1050,30 L1080,45 L1110,20 L1140,40 L1170,15 L1200,30 L1230,10 L1260,25 L1290,5 L1320,20 L1350,10 L1380,25 L1410,15 L1440,30 L1440,300 L0,300 Z"/>
      <path class="skyline-2" d="M0,200 L40,190 L80,205 L120,185 L160,195 L200,175 L240,190 L280,165 L320,180 L360,160 L400,170 L440,150 L480,165 L520,140 L560,155 L600,135 L640,150 L680,125 L720,140 L760,115 L800,130 L840,105 L880,120 L920,95 L960,110 L1000,85 L1040,100 L1080,75 L1120,90 L1160,65 L1200,80 L1240,55 L1280,70 L1320,45 L1360,60 L1400,35 L1440,50 L1440,300 L0,300 Z"/>
      <path class="skyline-3" d="M0,220 L50,210 L100,225 L150,210 L200,220 L250,205 L300,215 L350,195 L400,210 L450,190 L500,205 L550,185 L600,200 L650,180 L700,195 L750,175 L800,190 L850,170 L900,185 L950,165 L1000,180 L1050,160 L1100,175 L1150,155 L1200,170 L1250,150 L1300,165 L1350,145 L1400,160 L1440,145 L1440,300 L0,300 Z"/>
    </svg>
  </div>

  {{-- Navbar --}}
  <header class="site-header" id="siteHeader">
    <nav class="container py-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
      <a class="navbar-brand-custom" href="{{ route('home') }}">
        <i class="bi bi-building"></i>CitizenConnect
      </a>
      <div class="d-flex align-items-center gap-1 flex-wrap">
        <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
        <a class="nav-link-custom" href="{{ route('home') }}#map">Map</a>
        <a class="nav-link-custom btn-report-nav" href="{{ route('report.create') }}"><i class="bi bi-pencil-square me-1"></i>Report an Issue</a>
        @auth('admin')
          <a class="nav-link-custom" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock"></i> Dashboard</a>
          <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="nav-link-custom btn btn-link text-decoration-none p-0" style="color:var(--text)!important;">
              <i class="bi bi-box-arrow-right"></i> Logout
            </button>
          </form>
        @endauth
      </div>
    </nav>
  </header>

  {{-- Alert area --}}
  <div class="alert-area container">
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  {{-- Main content --}}
  <div class="main-content container my-4">
    @yield('content')
  </div>

  {{-- Footer --}}
  <footer>
    <svg viewBox="0 0 1440 80" preserveAspectRatio="none" style="display:block;width:100%;height:auto;">
      <path d="M0,40 Q120,20 240,40 T480,40 T720,40 T960,40 T1200,40 T1440,40 L1440,80 L0,80 Z" fill="#0B4F6C"/>
    </svg>
    <div class="footer-content">
      <div class="container">
        <div class="row g-4">
          <div class="col-md-4">
            <a class="navbar-brand-custom mb-3 d-inline-block" href="{{ route('home') }}" style="color:var(--white)!important;">
              <i class="bi bi-building me-2"></i>CitizenConnect
            </a>
            <p class="footer-brand-text">A community-powered platform for reporting and tracking local issues. Together we build a better neighbourhood.</p>
          </div>
          <div class="col-md-2">
            <h6 class="footer-heading">Explore</h6>
            <ul class="footer-links">
              <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
              <li><a href="{{ route('home') }}#map" class="footer-link">Map</a></li>
              <li><a href="{{ route('report.create') }}" class="footer-link">Report Issue</a></li>
            </ul>
          </div>
          <div class="col-md-3">
            <h6 class="footer-heading">Contact</h6>
            <a href="mailto:hello@citizenconnect.local" class="footer-contact-item"><i class="bi bi-envelope"></i> hello@citizenconnect.local</a>
            <a href="#" class="footer-contact-item"><i class="bi bi-github"></i> GitHub</a>
          </div>
          <div class="col-md-3 text-md-end">
            <button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="bi bi-arrow-up"></i></button>
          </div>
        </div>
        <hr class="footer-divider">
        <p class="footer-bottom-text text-center">&copy; {{ date('Y') }} CitizenConnect &mdash; Made with care for the community</p>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    (function() {
      const header = document.getElementById('siteHeader');
      window.addEventListener('scroll', function() {
        header.classList.toggle('scrolled', window.scrollY > 20);
      });
      document.getElementById('backToTop').addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    })();
  </script>
  @stack('scripts')
</body>
</html>
