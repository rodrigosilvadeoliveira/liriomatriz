<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Loja Lirio Matriz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
   
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary" id="cabecalho">
    <img id="logoLiriocabecalho" src ="LÍRIO MATRIZ (PRETO)_menor.png">
  <div class="container-fluid">
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!--
      <li class="nav-item">
          <a class="nav-link" aria-current="page" href="catalogo">Catalogo</a>
        </li>
-->
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="consulta_voluntarios">Voluntários</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="consultaProdutosAdm">Produtos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="vendas">Atendimento</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="vendasrealizadas">Vendas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="relatoriosAdm">Relatórios</a>
          
        </li>
        <li class="nav-item">
          <a class="nav-link" id="sairmenu" href="sair">Sair</a>
          
        </li>
        <!--
        <li class="nav-item dropdown">
         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Catalogo
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="cadastroProduto">Action</a></li>
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
    <a href="sair" class="btn btn-danger me5" id="sair">Sair</a>
</div>
</nav>
<div id="tabelaMobile">
<img id="logoLiriocabecalhoMobile" src ="LÍRIO MATRIZ (PRETO)_menor.png">
<div class="produtos-container">
    <a id="menuHeader" href="consulta_voluntarios">Voluntários</a>
<a id="menuHeader" href="consultaProdutosAdm">Produtos</a>
<a id="menuHeader" href="vendas">Atendimento</a>
<a id="menuHeader" href="vendasrealizadas">Vendas</a>
<a id="menuHeader" href="relatoriosAdm">Relatórios</a>
<a id="menuHeader" id="sairmenu" href="sair">Sair</a>
</div>
</div>
</header>


</body>
</html>