<?php
session_start();
include('config.php');

$sql = "SELECT * FROM evento WHERE cartaz= 'live' ORDER BY id DESC";
$result = $conexao->query($sql);
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vídeos Ao Vivo - Matriz</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css?t=<?=time()?>">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2d3436;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-text);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }
        
        #titulonapagina {
            text-align: center;
            margin: 30px 0;
            color: var(--secondary-color);
            position: relative;
            padding-bottom: 15px;
        }
        
        #titulonapagina:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }
        
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .video-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        
        .video-thumb {
            position: relative;
            padding-top: 56.25%; /* Proporção 16:9 */
            background-color: #000;
            cursor: pointer;
            overflow: hidden;
        }
        
        .video-thumb img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .video-card:hover .video-thumb img {
            transform: scale(1.05);
        }
        
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70px;
            height: 70px;
            background: rgba(231, 76, 60, 0.85);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            transition: all 0.3s ease;
            opacity: 0.9;
        }
        
        .video-card:hover .play-button {
            background: var(--accent-color);
            opacity: 1;
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        .video-info {
            padding: 15px;
        }
        
        .video-title {
            font-weight: 600;
            margin: 0;
            font-size: 16px;
            line-height: 1.4;
            color: var(--secondary-color);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .modal-content {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .modal-header {
            background: var(--secondary-color);
            color: white;
            border: none;
        }
        
        .modal-body {
            padding: 0;
            background: #000;
        }
        
        .btn-close {
            filter: invert(1);
        }
        
        @media (max-width: 768px) {
            .video-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
                padding: 15px;
            }
            
            .play-button {
                width: 50px;
                height: 50px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="cabecalho" id="cabecalhodoSite">
        <?php include('sitecabecalho.php');?>
    </div>

    <h1 id="titulonapagina">Ao Vivo na Liro Matriz</h1>
    
    <div class="video-grid">
        <?php
        while ($video = mysqli_fetch_assoc($result)) {
            // Extrair o ID do vídeo do YouTube a partir da URL
            $url = $video['links'];
            $videoId = '';
            
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                $videoId = $matches[1];
            }
            
            // Gerar a miniatura do YouTube
            $thumbnail = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : "https://via.placeholder.com/800x450/2c3e50/ecf0f1?text=Thumbnail+Indisponível";
            
            // Título do vídeo
            $title = isset($video['nomeevento']) ? htmlspecialchars($video['nomeevento']) : 'Vídeo Sem Título';
            
            echo '
            <div class="video-card">
                <div class="video-thumb" data-bs-toggle="modal" data-bs-target="#videoModal' . $video['id'] . '">
                    <img src="' . $thumbnail . '" alt="' . $title . '">
                    <div class="play-button">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <div class="video-info">
                    <h3 class="video-title">' . $title . '</h3>
                </div>
            </div>
            
            <!-- Modal -->
            <div class="modal fade" id="videoModal' . $video['id'] . '" tabindex="-1" aria-labelledby="videoModalLabel' . $video['id'] . '" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="videoModalLabel' . $video['id'] . '">' . $title . '</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ratio ratio-16x9">
                                <iframe src="' . $video['links'] . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>

    <div class="footer" id="footer">
        <?php include('sitefooter.php');?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <script>
        // Adicionar efeito de carregamento suave
        document.addEventListener('DOMContentLoaded', function() {
            const videoCards = document.querySelectorAll('.video-card');
            
            videoCards.forEach((card, index) => {
                // Adicionar delay progressivo para animação
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });
            
            // Pré-carregar imagens para evitar flickering
            const images = document.querySelectorAll('.video-thumb img');
            images.forEach(img => {
                const src = img.getAttribute('src');
                const newImage = new Image();
                newImage.src = src;
                
                // Se a imagem do YouTube não existir, usar fallback
                newImage.onerror = function() {
                    if (src.includes('youtube.com')) {
                        const videoId = src.split('/vi/')[1].split('/')[0];
                        img.src = 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg';
                    }
                };
            });
        });
    </script>
</body>
</html>