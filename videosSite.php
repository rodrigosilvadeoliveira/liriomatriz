<<<<<<< HEAD
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>teste Matriz</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Slick Carousel CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
  <!-- CSS Personalizado -->
  <link rel="stylesheet" href="style.css?t=<?=time()?>">
  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">
  
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    /* Estilos personalizados para a página */
:root {
  --primary-color: #0d6efd;
  --secondary-color: #6c757d;
  --light-bg: #f8f9fa;
}

body {
  background-color: var(--light-bg);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Cabeçalho fixo */
header {
  z-index: 1020;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Container do vídeo */
.video-container {
  background: white;
  padding: 0;
  transition: transform 0.3s ease;
}

.video-container:hover {
  transform: translateY(-5px);
}

/* Cards de recursos */
.card {
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Título principal */
#titulonapagina {
  background: linear-gradient(135deg, var(--primary-color), #6610f2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Responsividade para o post do Instagram */
.instagram-media {
  min-width: auto !important;
  width: 100% !important;
}

/* Ajustes para dispositivos móveis */
@media (max-width: 768px) {
  .display-5 {
    font-size: 1.8rem;
  }
  
  .lead {
    font-size: 1rem;
  }
  
  .video-container {
    margin: 0 10px;
  }
}
</style>
</head>

<body class="d-flex flex-column min-vh-100">
  <!-- Cabeçalho -->
  <header class="sticky-top">
    <div id="cabecalhodoSite">
      <?php include('sitecabecalho.php'); ?>
    </div>
  </header>

  <!-- Conteúdo Principal -->
  <main class="container-fluid flex-grow-1 py-4">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 col-xl-8">
        <!-- Título da Página -->
        <div class="text-center mb-4">
          <h1 id="titulonapagina" class="display-5 fw-bold text-primary">Pagina no instagram</h1>
          <p class="lead text-muted">Acompanhe nossas redes sociais e fique por dentro das novidades</p>
        </div>

        <!-- Container do Vídeo/Post -->
        <div class="video-container shadow-lg rounded-3 overflow-hidden mb-5">
          <!-- Post incorporado do Instagram -->
          <blockquote class="instagram-media" 
            data-instgrm-permalink="https://www.instagram.com/lirio.matriz/" 
            data-instgrm-version="14" 
            style="background:#FFF; border:0; margin:0 auto; max-width:100%; width:100%;">
          </blockquote>
        </div>


        <!-- Seção de Conteúdo Adicional (opcional) -->
        <div class="row mt-5">
          <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body text-center">
                <i class="bi bi-people-fill text-primary fs-1 mb-3"></i>
                <h5 class="card-title">Comunidade</h5>
                <p class="card-text">Participe da nossa comunidade e interaja com outros membros.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body text-center">
                <i class="bi bi-camera-video-fill text-primary fs-1 mb-3"></i>
                <h5 class="card-title">Conteúdo Exclusivo</h5>
                <p class="card-text">Acesse conteúdos exclusivos disponíveis apenas para membros.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body text-center">
                <i class="bi bi-bell-fill text-primary fs-1 mb-3"></i>
                <h5 class="card-title">Notificações</h5>
                <p class="card-text">Ative as notificações para não perder nenhuma transmissão.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Rodapé -->
  <footer class="mt-auto">
    <div id="footer">
      <?php include('sitefooter.php'); ?>
    </div>
  </footer>

  <!-- Script para renderizar o post do Instagram -->
  <script async src="//www.instagram.com/embed.js"></script>
  
  <!-- Ícones Bootstrap (se necessário) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
=======

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="style.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    </head>

<body>
    <div class="cabecalho" id="cabecalhodoSite">
    <?php include('sitecabecalho.php');?>
    </div>
<br >
<br >
<h1 id="titulonapagina">Ao Vivo na Liro Matriz</h1>
<div class="video-container">
        <!-- Substitua o URL abaixo pelo link do seu vídeo do YouTube -->
        <iframe id="youtube" src="https://www.youtube.com/embed/K_ADtJ7jnsE?si=z-22cfMGdbSCeTGK" title="YouTube video player" 
            title="YouTube video player" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>

        </iframe>
    </div>

<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>

>>>>>>> 2657b610b256eecd0effa4e3c07a7749882687c8
</body>
</html>