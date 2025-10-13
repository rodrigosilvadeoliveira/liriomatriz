<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Igreja Lírio Matriz</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Slick Carousel -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --text-dark: #2c3e50;
            --text-light: #ffffff;
            --transition: all 0.3s ease;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            padding-top: 90px; /* Espaço para o header fixo */
        }

        /* Header Styles */
        header {
            background: var(--text-light);
            box-shadow: var(--shadow);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            transition: var(--transition);
        }

        .header-container {
            display: flex;
            align-items: center;
            padding: 15px 10%;
        }

        .logo-container {
            flex: 0 0 auto;
        }

        #logotestecabecalhoSite {
            height: 60px;
            transition: var(--transition);
        }

        /* Desktop Navigation */
        .desktop-nav {
            display: flex;
            align-items: center;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            font-size: 21px;
        }

        .nav-item {
            position: relative;
            margin: 0 5px;
        }

        .nav-link {
            color: var(--text-dark);
            text-decoration: none;
            padding: 10px 15px;
            font-weight: 500;
            border-radius: 4px;
            transition: var(--transition);
            display: block;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.1);
        }

        .nav-item.dropdown .nav-link::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: 5px;
            font-size: 0.8rem;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 200px;
            box-shadow: var(--shadow);
            border-radius: 4px;
            padding: 10px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: var(--transition);
            z-index: 1000;
        }

        .nav-item.dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 10px 20px;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }

        .nav-cta {
            background: var(--secondary-color);
            color: white !important;
            border-radius: 30px;
            padding: 10px 20px !important;
            margin-left: 15px;
            font-weight: 600 !important;
        }

        .nav-cta:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        /* Mobile Navigation */
        .mobile-nav-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .mobile-nav-toggle span {
            height: 3px;
            width: 100%;
            background: var(--primary-color);
            margin: 3px 0;
            transition: var(--transition);
            border-radius: 2px;
        }

        .mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            padding: 80px 30px 30px;
            overflow-y: auto;
            transition: right 0.4s ease;
            z-index: 999;
        }

        .mobile-nav.active {
            right: 0;
        }

        .mobile-nav-header {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        #logotestecabecalhoMobile {
            height: 50px;
        }

        .mobile-nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .mobile-nav-item {
            border-bottom: 1px solid #eee;
        }

        .mobile-nav-link {
            display: block;
            padding: 15px 10px;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .mobile-nav-link:hover {
            color: var(--secondary-color);
            padding-left: 15px;
        }

        .mobile-nav-cta {
            background: var(--secondary-color);
            color: white !important;
            text-align: center;
            border-radius: 30px;
            margin-top: 20px;
            font-weight: 600;
        }

        .mobile-nav-cta:hover {
            background: #2980b9;
        }

        .mobile-nav-close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1.5rem;
            color: var(--text-dark);
            cursor: pointer;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 998;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .desktop-nav {
                display: none;
            }

            .mobile-nav-toggle {
                display: flex;
            }

            .header-container {
                padding: 15px 34px;
                justify-content: space-between;
            }

            #logotestecabecalhoSite {
                height: 50px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding-top: 70px;
            }

            #logotestecabecalhoSite {
                height: 40px;
            }
        }

        /* Animation for mobile menu toggle */
        .mobile-nav-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-nav-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-nav-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* Header scroll effect */
        header.scrolled {
            padding: 5px 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        header.scrolled #logotestecabecalhoSite {
            height: 50px;
        }

        @media (max-width: 992px) {
            header.scrolled #logotestecabecalhoSite {
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="mainHeader">
        <div class="header-container">
            <div class="logo-container">
                <img id="logotestecabecalhoSite" src="LÍRIO MATRIZ (PRETO)_menor.png" alt="Igreja Lírio Matriz">
            </div>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a class="nav-link active" href="index">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="novoComecoSite">Novo Começo</a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link" href="sitevoluntariado">Voluntariado</a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link" href="sitekids">Kids</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="siteprogramacao">Programação</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sitemergulhar">Mergulhar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inscricoes">Inscrições</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="lojalirio">Loja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="doacoes">Doar</a>
                    </li>
                </ul>
            </nav>

            <!-- Mobile Navigation Toggle -->
            <div class="mobile-nav-toggle" id="mobileNavToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="mobile-nav" id="mobileNav">
            <div class="mobile-nav-close" id="mobileNavClose">
                <i class="fas fa-times"></i>
            </div>
            <div class="mobile-nav-header">
                <img id="logotestecabecalhoMobile" src="LÍRIO MATRIZ (PRETO)_menor.png" alt="Igreja Lírio Matriz">
            </div>
            <ul class="mobile-nav-menu">
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link active" href="index">Início</a>
                </li>
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link" href="novoComecoSite">Novo Começo</a>
                </li>
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link" href="sitevoluntariado">Voluntariado</a>
                </li>
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link" href="sitekids">Kids</a>
                </li>
                
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link" href="siteprogramacao">Programação</a>
                </li>
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link" href="sitemergulhar">Mergulhar</a>
                </li>
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link" href="inscricoes">Inscrições</a>
                </li>
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link" href="lojalirio">Loja</a>
                </li>
                <li class="mobile-nav-item">
                    <a class="mobile-nav-link mobile-nav-cta" href="doacoes">Doar</a>
                </li>
            </ul>
        </div>

        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>
    </header>

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Slick Carousel -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script>
        // Mobile Navigation Toggle
        const mobileNavToggle = document.getElementById('mobileNavToggle');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavClose = document.getElementById('mobileNavClose');
        const overlay = document.getElementById('overlay');

        function toggleMobileNav() {
            mobileNavToggle.classList.toggle('active');
            mobileNav.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
        }

        mobileNavToggle.addEventListener('click', toggleMobileNav);
        mobileNavClose.addEventListener('click', toggleMobileNav);
        overlay.addEventListener('click', toggleMobileNav);

        // Header scroll effect
        const header = document.getElementById('mainHeader');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Close mobile menu when clicking on a link
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                toggleMobileNav();
            });
        });

        // Dropdown menu for touch devices
        if ('ontouchstart' in window) {
            const dropdowns = document.querySelectorAll('.nav-item.dropdown');

            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    if (window.innerWidth > 992) {
                        const link = this.querySelector('.nav-link');
                        if (e.target === link || link.contains(e.target)) {
                            e.preventDefault();
                            this.classList.toggle('open');
                        }
                    }
                });
            });
        }
    </script>
</body>
</html>