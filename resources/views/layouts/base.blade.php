<!doctype html>
<html lang="en" class="theme-light">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mobile Banking, Credit Cards, Mortgages, Auto Loan">
    <meta property="og:image" content="{{ branding_url($settings->favicon) }}">

    <!-- Links of CSS files -->
    <link rel="stylesheet" href="temp/custom/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/animate.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/flaticon.css">
    <link rel="stylesheet" href="temp/custom/assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/nice-select.css">
    <link rel="stylesheet" href="temp/custom/assets/css/slick.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/meanmenu.css">
    <link rel="stylesheet" href="temp/custom/assets/css/odometer.min.css">
    <link rel="stylesheet" href="temp/custom/assets/css/style.css">
    <link rel="stylesheet" href="temp/custom/assets/css/responsive.css">
    <link rel="stylesheet" href="temp/custom/assets/css/dark-style.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>{{ $settings->site_name }}</title>

    <link rel="icon" type="image/png" href="{{ branding_url($settings->favicon) }}">
    
    @if($settings->pwa_enabled ?? true)
    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $settings->pwa_short_name ?? $settings->site_name }}">
    <meta name="theme-color" content="{{ $settings->pwa_theme_color ?? '#2d8659' }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ $settings->pwa_short_name ?? $settings->site_name }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('manifest') }}">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="{{ branding_url($settings->logo) }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ branding_url($settings->logo) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ branding_url($settings->logo) }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ branding_url($settings->logo) }}">
    @endif
    
    <style>
        :root {
            --forest-green: #2d8659;
            --champagne: #e0a84e;
            --dark-green: #1e5a3d;
            --light-green: #3fa572;
            --cream: #fdfbf7;
            --charcoal: #2a2a2a;
        }
        
        body {
            font-family: 'DM Sans', sans-serif;
        }
        
        /* Modern Navigation Styles */
        .modern-navbar {
            background: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .modern-navbar.scrolled {
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.12);
        }
        
        .modern-navbar .logo-link {
            display: flex;
            align-items: center;
        }
        
        .modern-navbar .logo-img {
            height: 45px;
            width: auto;
        }
        
        .modern-navbar .nav-menu {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .modern-navbar .nav-link-modern {
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            color: #2a2a2a;
            text-decoration: none;
            font-size: 0.95rem;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .modern-navbar .nav-link-modern:hover {
            color: #2d8659;
        }
        
        .modern-navbar .nav-link-modern::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #2d8659 0%, #3fa572 100%);
            transition: width 0.3s ease;
        }
        
        .modern-navbar .nav-link-modern:hover::after,
        .modern-navbar .nav-link-modern.active::after {
            width: 100%;
        }
        
        .modern-navbar .dropdown-toggle-modern {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        
        .modern-navbar .dropdown-menu-modern {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            padding: 0.75rem;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border: 1px solid rgba(45, 134, 89, 0.1);
        }
        
        .modern-navbar .dropdown-wrapper:hover .dropdown-menu-modern {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .modern-navbar .dropdown-item-modern {
            display: block;
            padding: 0.75rem 1rem;
            color: #2a2a2a;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        
        .modern-navbar .dropdown-item-modern:hover {
            background: linear-gradient(135deg, rgba(45, 134, 89, 0.08) 0%, rgba(63, 165, 114, 0.08) 100%);
            color: #2d8659;
            transform: translateX(5px);
        }
        
        .modern-navbar .ebanking-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            background: linear-gradient(135deg, #2d8659 0%, #3fa572 100%);
            color: white;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(45, 134, 89, 0.25);
            font-size: 0.9rem;
        }
        
        .modern-navbar .ebanking-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 134, 89, 0.35);
            color: white;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2a2a2a;
            cursor: pointer;
        }
        
        @media (max-width: 991px) {
            .modern-navbar .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                gap: 0;
                padding: 1.5rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                border-radius: 0 0 16px 16px;
            }
            
            .modern-navbar .nav-menu.active {
                display: flex;
            }
            
            .modern-navbar .nav-menu li {
                width: 100%;
                padding: 0.75rem 0;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .modern-navbar .nav-menu li:last-child {
                border-bottom: none;
            }
            
            .modern-navbar .dropdown-menu-modern {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                border: none;
                margin-top: 0.5rem;
                background: #f8f9fa;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .modern-navbar .ebanking-btn {
                width: 100%;
                justify-content: center;
                margin-top: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body>


<!-- Modern Navigation -->
<nav class="modern-navbar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="/" class="logo-link">
                <img src="{{ branding_url($settings->logo) }}" alt="{{ $settings->site_name }}" class="logo-img">
            </a>
            
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-menu" id="navMenu">
                <li>
                    <a href="/" class="nav-link-modern active">Home</a>
                </li>
                <li>
                    <a href="about" class="nav-link-modern">About Us</a>
                </li>
                <li class="dropdown-wrapper" style="position: relative;">
                    <a href="#" class="nav-link-modern dropdown-toggle-modern">
                        Services
                        <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                    </a>
                    <div class="dropdown-menu-modern">
                        <a href="services" class="dropdown-item-modern">Investment Management</a>
                        <a href="loan" class="dropdown-item-modern">Business Loan</a>
                    </div>
                </li>
                <li>
                    <a href="contact" class="nav-link-modern">Contact</a>
                </li>
              
                <li>
                    <a href="login" class="ebanking-btn">
                        <i class="flaticon-user"></i>
                        E-Banking
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Modern Navigation -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navMenu = document.getElementById('navMenu');
        
        if (mobileMenuToggle && navMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                const icon = this.querySelector('i');
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        }
        
        // Navbar scroll effect
        const navbar = document.querySelector('.modern-navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    });
</script>


@yield('content')

<!-- Careers Section -->
<section style="padding: 6rem 0; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.03; background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, #2d8659 35px, #2d8659 70px);"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 mb-5 mb-lg-0">
                <div style="position: relative; padding: 2rem;">
                    <div class="main-image" style="position: relative; z-index: 3;">
                        <img src="temp/custom/assets/img/mobile-app1.png"
                            class="wow animate__animated animate__fadeInLeft" 
                            alt="Mobile Banking App"
                            style="position: absolute; top: 10%; left: -5%; width: 45%; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15); z-index: 2;">
                        <img src="temp/custom/assets/img/mobile-app2.png"
                            class="wow animate__animated animate__fadeInUp" 
                            alt="Banking Features"
                            style="position: absolute; bottom: 15%; right: -8%; width: 40%; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15); z-index: 4;">
                    </div>

                    <div style="position: relative; z-index: 3; margin: 0 auto; max-width: 350px;">
                        <img src="temp/custom/assets/img/main-mobile.png"
                            class="wow animate__animated animate__fadeInUp" 
                            alt="Main Mobile Banking"
                            style="width: 100%; filter: drop-shadow(0 20px 50px rgba(0, 0, 0, 0.2));">
                    </div>

                    <div style="position: absolute; top: 20%; right: 10%; width: 120px; height: 120px; opacity: 0.4; animation: spin 25s linear infinite; z-index: 1;">
                        <img src="temp/custom/assets/img/circle.png" alt="decoration" style="width: 100%;">
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div style="padding-left: 2rem;">
                    <div style="display: inline-block; padding: 0.5rem 1.25rem; background: linear-gradient(135deg, rgba(45, 134, 89, 0.1) 0%, rgba(63, 165, 114, 0.1) 100%); border-radius: 50px; margin-bottom: 1.5rem;">
                        <span style="font-family: 'DM Sans', sans-serif; font-weight: 600; color: #2d8659; font-size: 0.9rem; letter-spacing: 0.05em;">JOIN OUR TEAM</span>
                    </div>
                    
                    <h2 style="font-family: 'Crimson Text', serif; font-size: 3.2rem; font-weight: 700; color: #2a2a2a; margin-bottom: 1.5rem; line-height: 1.2;">Careers</h2>
                    
                    <div style="width: 80px; height: 5px; background: linear-gradient(90deg, #e0a84e 0%, #ebb860 100%); border-radius: 10px; margin-bottom: 2rem;"></div>
                    
                    <p style="font-family: 'DM Sans', sans-serif; font-size: 1.15rem; color: #555; line-height: 1.8; margin-bottom: 2.5rem;">{{ $settings->site_name }} welcomes talented professionals to join our young and dynamic team. We are looking for highly qualified professionals, graduates and financial specialists who share our values of integrity and excellence to grow with us.</p>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2.5rem;">
                        <div style="flex: 1; min-width: 200px;">
                            <div style="font-family: 'Crimson Text', serif; font-size: 2.5rem; font-weight: 700; color: #2d8659; margin-bottom: 0.5rem;">500+</div>
                            <div style="font-family: 'DM Sans', sans-serif; color: #666; font-weight: 500;">Team Members</div>
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <div style="font-family: 'Crimson Text', serif; font-size: 2.5rem; font-weight: 700; color: #e0a84e; margin-bottom: 0.5rem;">50+</div>
                            <div style="font-family: 'DM Sans', sans-serif; color: #666; font-weight: 500;">Open Positions</div>
                        </div>
                    </div>
                    
                    <a href="contact" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1.1rem 2.75rem; background: linear-gradient(135deg, #2d8659 0%, #3fa572 100%); color: white; font-family: 'DM Sans', sans-serif; font-weight: 600; border-radius: 12px; text-decoration: none; box-shadow: 0 8px 24px rgba(45, 134, 89, 0.3); transition: all 0.3s ease;" 
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 32px rgba(45, 134, 89, 0.4)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 24px rgba(45, 134, 89, 0.3)'">
                        Apply Now
                        <i class="flaticon-right-arrow"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Careers Section -->

<!-- Account CTA Section -->
<section style="padding: 5rem 0; background: linear-gradient(165deg, #1e5a3d 0%, #2d8659 50%, #2a6f4f 100%); position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.05; background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, white 35px, white 70px);"></div>
    
    <div class="container" style="position: relative; z-index: 2;">
        <div style="text-align: center;">
            <h2 style="font-family: 'Crimson Text', serif; font-size: 3rem; font-weight: 700; color: white; margin-bottom: 1.25rem;">Apply for an Account in Minutes</h2>
            <p style="font-family: 'DM Sans', sans-serif; font-size: 1.2rem; color: rgba(255, 255, 255, 0.92); margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">Get your {{ $settings->site_name }} account today and experience banking excellence!</p>
            <a href="login" style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1.2rem 3rem; background: linear-gradient(135deg, #e0a84e 0%, #ebb860 100%); color: #2a2a2a; font-family: 'DM Sans', sans-serif; font-weight: 700; border-radius: 14px; text-decoration: none; box-shadow: 0 10px 30px rgba(224, 168, 78, 0.35); transition: all 0.3s ease; font-size: 1.05rem;" 
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 15px 40px rgba(224, 168, 78, 0.45)'" 
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(224, 168, 78, 0.35)'">
                Get Your {{ $settings->site_name }} Account
                <i class="flaticon-right-arrow" style="font-size: 1.2rem;"></i>
            </a>
        </div>
    </div>
</section>
<!-- End Account CTA Section -->





<!--<script src="//code.tidio.co/bm299phjcxz1daqje007ekystxhmfdc9.js" async></script>-->
<!--<script src="//code.tidio.co/bgwrsunonqzucqybffnwkafe8hxcsqjc.js" async></script>-->
<!--<script src="//code.tidio.co/ezdayqdn4rzkrlujshzl3beo7gbfb841.js" async></script>-->
<!--<script src="//code.tidio.co/yxecarprfgwh6t1cxx6wnqhq2fzctgkg.js" async></script>-->
<!--<script src="//code.tidio.co/5gzxfmq6wbipcunmgojctcudjgszwh3b.js" async></script>-->
<!-- Modern Footer -->
<footer style="background: linear-gradient(to bottom, #1a1a1a 0%, #0d0d0d 100%); color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; opacity: 0.03;">
        <img src="temp/custom/assets/img/map.png" alt="Global Presence" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    
    <div class="container" style="position: relative; z-index: 2; padding: 5rem 0 2rem;">
        <div class="row" style="margin-bottom: 3rem;">
            <div class="col-lg-4 col-md-6 mb-4">
                <div style="margin-bottom: 1.5rem;">
                    <img src="{{ branding_url($settings->logo) }}" alt="{{ $settings->site_name }}" style="height: 50px; width: auto; filter: brightness(0) invert(1);">
                </div>
                <p style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); line-height: 1.8; margin-bottom: 1.5rem; font-size: 0.95rem;">{{ $settings->site_name }} is a bank for premier business, offering financial services and competitive products to Large Corporates and SMEs, International Clients, Institutional Investors, Provident and Pension Funds, Equity Funds as well as High Net Worth Individuals.</p>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h3 style="font-family: 'Crimson Text', serif; font-size: 1.4rem; font-weight: 600; color: white; margin-bottom: 1.5rem; position: relative; padding-bottom: 0.75rem;">
                    Company
                    <span style="position: absolute; bottom: 0; left: 0; width: 40px; height: 3px; background: linear-gradient(90deg, #e0a84e 0%, #ebb860 100%); border-radius: 10px;"></span>
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 0.75rem;"><a href="about" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.color='#e0a84e'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'; this.style.paddingLeft='0'">About Us</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="services" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.color='#e0a84e'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'; this.style.paddingLeft='0'">Services</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="contact" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.color='#e0a84e'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'; this.style.paddingLeft='0'">Contact Us</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h3 style="font-family: 'Crimson Text', serif; font-size: 1.4rem; font-weight: 600; color: white; margin-bottom: 1.5rem; position: relative; padding-bottom: 0.75rem;">
                    Financial Services
                    <span style="position: absolute; bottom: 0; left: 0; width: 40px; height: 3px; background: linear-gradient(90deg, #e0a84e 0%, #ebb860 100%); border-radius: 10px;"></span>
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 0.75rem;"><a href="services" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.color='#e0a84e'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'; this.style.paddingLeft='0'">Corporate Banking</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="services" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.color='#e0a84e'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'; this.style.paddingLeft='0'">Personal Banking</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="services" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.color='#e0a84e'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'; this.style.paddingLeft='0'">International Banking</a></li>
                    <li style="margin-bottom: 0.75rem;"><a href="contact" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.color='#e0a84e'; this.style.paddingLeft='5px'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'; this.style.paddingLeft='0'">Contact Us</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h3 style="font-family: 'Crimson Text', serif; font-size: 1.4rem; font-weight: 600; color: white; margin-bottom: 1.5rem; position: relative; padding-bottom: 0.75rem;">
                    Contact Info
                    <span style="position: absolute; bottom: 0; left: 0; width: 40px; height: 3px; background: linear-gradient(90deg, #e0a84e 0%, #ebb860 100%); border-radius: 10px;"></span>
                </h3>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 1rem; display: flex; gap: 0.75rem;">
                        <i class="flaticon-location" style="color: #e0a84e; font-size: 1.2rem; margin-top: 0.2rem;"></i>
                        <span style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); font-size: 0.95rem; line-height: 1.6;">{{ $settings->address }}</span>
                    </li>
                    <li style="margin-bottom: 1rem; display: flex; gap: 0.75rem;">
                        <i class="flaticon-envelope" style="color: #e0a84e; font-size: 1.2rem; margin-top: 0.2rem;"></i>
                        <a href="mailto:{{ $settings->contact_email }}" style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.3s;" onmouseover="this.style.color='#e0a84e'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">{{ $settings->contact_email }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 2rem; text-align: center;">
            <p style="font-family: 'DM Sans', sans-serif; color: rgba(255, 255, 255, 0.6); margin: 0; font-size: 0.9rem;">
                Copyright © {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.
            </p>
        </div>
    </div>
</footer>
<!-- End Modern Footer --> <!-- Links of JS files -->
<script src="temp/custom/assets/js/jquery.min.js"></script>
<script src="temp/custom/assets/js/bootstrap.bundle.min.js"></script>
<script src="temp/custom/assets/js/meanmenu.js"></script>
<script src="temp/custom/assets/js/nice-select.min.js"></script>
<script src="temp/custom/assets/js/slick.min.js"></script>
<script src="temp/custom/assets/js/magnific-popup.min.js"></script>


<script src="temp/custom/assets/js/odometer.min.js"></script>
<script src="temp/custom/assets/js/owl.carousel.min.js"></script>
<script src="temp/custom/assets/js/parallax.min.js"></script>
<script src="temp/custom/assets/js/wow.min.js"></script>
<script src="temp/custom/assets/js/form-validator.min.js"></script>
<script src="temp/custom/assets/js/contact-form-script.js"></script>
<script src="temp/custom/assets/js/main.js"></script>

@if($settings->pwa_enabled ?? true)
<!-- PWA Service Worker Registration & Install Prompt -->
<script>
    // Service Worker Registration
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('{{ asset("sw.js") }}')
                .then(registration => {
                    console.log('[PWA] Service Worker registered successfully:', registration.scope);
                })
                .catch(error => {
                    console.error('[PWA] Service Worker registration failed:', error);
                });
        });
    }
    
    // PWA Install Prompt
    let deferredPrompt;
    let installPromptShown = sessionStorage.getItem('pwa-install-prompted');
    
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Show install prompt if not shown in this session
        if (!installPromptShown) {
            showInstallPromotion();
        }
    });
    
    function showInstallPromotion() {
        // Create install banner
        const installBanner = document.createElement('div');
        installBanner.id = 'pwa-install-banner';
        installBanner.innerHTML = `
            <div style="position: fixed; bottom: 20px; right: 20px; max-width: 350px; background: linear-gradient(135deg, #2d8659 0%, #1e5a3d 100%); color: white; padding: 20px; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 9999; animation: slideUp 0.5s ease;">
                <div style="display: flex; align-items: start; gap: 15px;">
                    <img src="{{ branding_url($settings->logo) }}" alt="App Icon" style="width: 48px; height: 48px; border-radius: 12px;">
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600;">Install {{ $settings->pwa_short_name ?? $settings->site_name }}</h3>
                        <p style="margin: 0 0 12px 0; font-size: 13px; opacity: 0.9; line-height: 1.4;">Get quick access from your home screen. Works offline!</p>
                        <div style="display: flex; gap: 10px;">
                            <button onclick="installPWA()" style="flex: 1; background: #e0a84e; color: #1e5a3d; border: none; padding: 10px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">
                                Install
                            </button>
                            <button onclick="dismissInstallPrompt()" style="background: rgba(255,255,255,0.2); color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; font-size: 13px;">
                                Not Now
                            </button>
                        </div>
                    </div>
                    <button onclick="dismissInstallPrompt()" style="background: none; border: none; color: white; cursor: pointer; font-size: 20px; line-height: 1; opacity: 0.7; padding: 0;">&times;</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(installBanner);
        
        // Mark as shown in session
        sessionStorage.setItem('pwa-install-prompted', 'true');
    }
    
    function installPWA() {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('[PWA] User accepted the install prompt');
                } else {
                    console.log('[PWA] User dismissed the install prompt');
                }
                deferredPrompt = null;
            });
        }
        dismissInstallPrompt();
    }
    
    function dismissInstallPrompt() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.style.animation = 'slideDown 0.3s ease';
            setTimeout(() => banner.remove(), 300);
        }
    }
    
    // App installed event
    window.addEventListener('appinstalled', () => {
        console.log('[PWA] App was installed successfully');
        dismissInstallPrompt();
    });
    
    // Animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideUp {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideDown {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(100px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
</script>
@endif
<div>
    @include('layouts.lang')
</div>
@include('layouts.session-expired-handler')

</body>





<!-- Mirrored from metroxtrustspay.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 30 Jun 2024 15:19:54 GMT -->

@include('layouts.livechat')
</html>
