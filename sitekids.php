 <?php include('sitecabecalho.php'); ?>
        
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz - Voluntariado</title>
    <link rel="stylesheet" href="style.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    
    <style>
        /* Reset e estilos gerais */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        /* Título */
        #titulohome {
            text-align: center;
            margin: 2rem 0;
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.5rem;
        }
        
        /* Container do vídeo */
        .video-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            margin: 0 auto 3rem;
            padding: 0 1rem;
        }
        
        /* Vídeo responsivo */
        .video-voluntarios {
            width: 100%;
            height: auto;
            aspect-ratio: 16/9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: block;
        }
        
        /* Container das imagens em coluna */
        .imagens-container {
            max-width: 1000px;
            margin: 0 auto 3rem;
            padding: 0 1rem;
        }
        
        .img-voluntariado {
            width: 100%;
            max-width: 800px;
            height: auto;
            margin: 0 auto 2rem;
            display: block;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            #titulohome {
                font-size: 2rem;
                margin: 1.5rem 0;
            }
            
            .video-container {
                margin-bottom: 2rem;
            }
            
            .imagens-container {
                margin-bottom: 2rem;
            }
            
            .img-voluntariado {
                margin-bottom: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            #titulohome {
                font-size: 1.8rem;
                margin: 1rem 0;
            }
            
            .img-voluntariado {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="cabecalho" id="cabecalho">
            <?php include('sitecabecalho.php'); ?>
        </div>    
    </header>

    <main>
        <!-- <h1 id="titulohome">Voluntariado</h1>

         Container do vídeo responsivo -->
        <!--<div class="video-container">
            <video class="video-voluntarios" controls loop muted playsinline autoplay>
                <source src="voluntariado.mp4" type="video/mp4">
                <source src="voluntariado.webm" type="video/webm">
                Seu navegador não suporta a tag de vídeo.
            </video>
        </div> -->

        <!-- Container das imagens em coluna -->
        <div class="imagens-container">
            <?php
            $slides = glob("img/kids/*.jpg");
            if (count($slides) > 0) {
                foreach ($slides as $slide) {
                    echo '<img class="img-voluntariado" src="'.$slide.'?t='.time().'" />';
                }
            } else {
                echo '<p style="text-align: center; padding: 2rem;">Nenhuma imagem disponível no momento.</p>';
            }
            ?>
        </div>
    </main>

    <div class="footer" id="footer">
        <?php include('sitefooter.php'); ?>
    </div>

    <script>
        $(document).ready(function(){
            // Controle do vídeo - pausa quando clicado
            $('.video-voluntarios').on('click', function() {
                if (this.paused) {
                    this.play();
                } else {
                    this.pause();
                }
            });
        });
    </script>
</body>
</html>