<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Loja Lirio Matriz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="loja.js" async></script>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" id="cabecalho">
    <img id="logoLiriocabecalho" src ="LÍRIO MATRIZ (PRETO)_menor.png">
  <div class="container-fluid">
    <!--<a class="navbar-brand" href="#" id="menu">Menu</a>-->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!--
      <li class="nav-item">
          <a class="nav-link" aria-current="page" href="catalogo.php">Catalogo</a>
        </li>
-->
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="consulta_voluntarios.php">Voluntários</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="consultaProdutosAdm.php">Produtos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="vendas.php">Atendimento</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="vendasrealizadas.php">Vendas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="relatoriosAdm.php">Relatórios</a>
          
        </li>
        <!--
        <li class="nav-item dropdown">
         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Catalogo
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="cadastroProduto.php">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        -->
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search" id="cpesquisa">
        <button class="btn btn-outline-success" type="submit" id="bpesquisa">Search</button>
      </form>
    </div>
  </div>
  <div class="d-flex">
    <a href="sair.php" class="btn btn-danger me5" id="sair">Sair</a>
</div>
</nav>
</header>


</body>
</html>