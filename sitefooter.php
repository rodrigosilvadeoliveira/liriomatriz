<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Matriz - Footer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    
    <style>
        :root {
            /* Cores do Design System */
            --primary: #4a2c82;
            --primary-light: #6a4ca3;
            --primary-dark: #3a2068;
            --secondary: #ff6b6b;
            --neutral-dark: #212529;
            --neutral-gray: #6c757d;
            --neutral-light: #f8f9fa;
            --white: #ffffff;
            
            /* Espaçamentos */
            --spacing-xs: 0.5rem;
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --spacing-lg: 2.5rem;
            --spacing-xl: 4rem;
            
            /* Bordas */
            --border-radius: 8px;
            --border-radius-lg: 12px;
            
            /* Sombras */
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            
            /* Transições */
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--neutral-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
            /* padding: var(--spacing-xl) 0; */
        }
        
        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: var(--spacing-xl) 0 var(--spacing-md);
            margin-top: auto;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-md);
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: var(--spacing-lg);
        }
        
        .footer-brand {
            grid-column: span 12;
            text-align: center;
            margin-bottom: var(--spacing-lg);
        }
        
        @media (min-width: 768px) {
            .footer-brand {
                grid-column: span 4;
                text-align: left;
            }
        }
        
        .footer-logo {
            max-width: 180px;
            height: auto;
            margin-bottom: var(--spacing-sm);
            filter: brightness(0) invert(1);
        }
        
        .footer-heading {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: var(--spacing-md);
            position: relative;
            display: inline-block;
        }
        
        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 40px;
            height: 3px;
            background-color: var(--secondary);
            border-radius: 2px;
        }
        
        .footer-links {
            grid-column: span 12;
            margin-bottom: var(--spacing-lg);
        }
        
        @media (min-width: 768px) {
            .footer-links {
                grid-column: span 4;
            }
        }
        
        .footer-links-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: var(--spacing-xs);
        }
        
        .footer-link {
            display: flex;
            align-items: center;
            color: var(--white);
            text-decoration: none;
            padding: var(--spacing-xs) 0;
            transition: var(--transition-normal);
            font-weight: 500;
        }
        
        .footer-link:hover {
            color: var(--secondary);
            transform: translateX(5px);
        }
        
        .footer-icon {
            width: 24px;
            height: 24px;
            margin-right: var(--spacing-xs);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .footer-contact {
            grid-column: span 12;
        }
        
        @media (min-width: 768px) {
            .footer-contact {
                grid-column: span 4;
            }
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-sm);
        }
        
        .contact-icon {
            font-size: 1.25rem;
            color: var(--secondary);
            flex-shrink: 0;
        }
        
        .social-links {
            display: flex;
            gap: var(--spacing-sm);
            margin-top: var(--spacing-md);
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var(--white);
            text-decoration: none;
            transition: var(--transition-normal);
        }
        
        .social-link:hover {
            background-color: var(--secondary);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            grid-column: span 12;
            text-align: center;
            padding-top: var(--spacing-md);
            margin-top: var(--spacing-lg);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Responsividade */
        @media (max-width: 767px) {
            .footer-container {
                gap: var(--spacing-md);
            }
            
            .footer-heading {
                font-size: 1.1rem;
            }
            
            .footer-heading::after {
                width: 30px;
            }
        }
        
        /* Utilitários */
        .text-center {
            text-align: center;
        }
        
        .mt-auto {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <main>
        <!-- <div class="container text-center">
            <h1>Conteúdo Principal</h1>
            <p>Esta é uma demonstração do footer moderno e responsivo.</p>
        </div>
    </main> -->
    
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <img src="LÍRIO MATRIZ (PRETO)_menor.png" alt="Lírio Matriz" class="footer-logo">
                <p>Transformando vidas através do amor e fé.</p>
            </div>
            
            <div class="footer-links">
                <h3 class="footer-heading">Links Úteis</h3>
                <ul class="footer-links-list">
                    <li>
                        <a href="sobre.php" class="footer-link">
                            <span class="footer-icon">
                                <i class="fas fa-church"></i>
                            </span>
                            Sobre a Lírio
                        </a>
                    </li>
                    <li>
                        <a href="contatoSite.php" class="footer-link">
                            <span class="footer-icon">
                                <i class="fas fa-phone"></i>
                            </span>
                            Fale Conosco
                        </a>
                    </li>
                    <li>
                        <a href="siteprogramacao.php" class="footer-link">
                            <span class="footer-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            Programação
                        </a>
                    </li>
                    <li>
                        <a href="voluntariadoSite.php" class="footer-link">
                            <span class="footer-icon">
                                <i class="fas fa-hands-helping"></i>
                            </span>
                            Voluntariado
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="footer-contact">
                <h3 class="footer-heading">Contato</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <span class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <span>2025 Rua Amanari, 629 - Vila Santa Teresinha, São Paulo - SP, 08247-060 - Brasil

+55 (11) 2071-3218 - liriomatrizorganizacao@gmail.com WhatssApp: 11 - 94860-4083</span>
                    </div>
                    
                    <div class="contact-item">
                        <span class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </span>
                        <span>Horários de culto:<br>Quarta 20h | Domingo 9h</span>
                    </div>
                    
                    <div class="social-links">
                        <a href="https://www.instagram.com/lirio.matriz?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@lirio.igreja" class="social-link" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://www.facebook.com/lirioigreja?locale=pt_BR" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <!-- <a href="https://web.whatsapp.com" class="social-link" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i> -->
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 Igreja Lírio Matriz. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Script para exemplo de funcionalidade
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona ano atual no copyright
            const yearElement = document.querySelector('.footer-bottom p');
            if (yearElement) {
                const currentYear = new Date().getFullYear();
                yearElement.innerHTML = yearElement.innerHTML.replace('2023', currentYear);
            }
        });
    </script>
</body>
</html>