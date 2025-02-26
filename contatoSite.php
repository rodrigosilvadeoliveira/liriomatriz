<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $tpcontato = $_POST['tpcontato'];
    $pedido = $_POST['pedido'];
    $assunto = $_POST['assunto'];
    $mensagem = $_POST['mensagem'];
    $status = 'pendente';

    $result = mysqli_query($conexao, "INSERT INTO contato (nome, sobrenome, telefone, email, tpcontato, pedido, assunto, mensagem, status) 
    VALUES ('$nome', '$sobrenome', '$telefone', '$email', '$tpcontato', '$pedido', '$assunto', '$mensagem', '$status')");

    header('Location: contato.php');
}

?>
<!-- Adicione este script antes do fechamento da tag </body> -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Seu formulário
    var form = document.querySelector('form');

    // Adiciona um listener para o evento submit do formulário
    form.addEventListener('submit', function (event) {
      // Previne o envio padrão do formulário
      event.preventDefault();

      // Exibe o modal de confirmação
      $('#confirmacaoModal').modal('show');
    });
  });
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

</head>
<body>
<header>
    <div class="cabecalho" id="cabecalho">
    <?php include('cabecalhoSite.php');?>
    </div>    

</header>

<form action="contato.php" method="post">

<h1 id="titulonapagina">Contato com a Lirio Matriz</h1>

<h3 class="fraseentrega">Tem dúvidas gostaria de saber mais sobre a igreja, envie uma mensagem através do nosso firmulario.</h3>

<div class="dadoscliente">    
<div class="col-md-9">
    <label for="nome" class="form-label">*Nome:</label>
    <input type="text" name="nome" id="nome" class="form-control" required>
  </div><br>
  
  <div class="col-md-9">
    <label for="nome" class="form-label">*Sobrenome:</label>
    <input type="text" name="sobrenome" id="sobrenome" class="form-control" required>
  </div><br>

  <div class="col-6">
    <label for="telefone" class="form-label">*Telefone:</label>
    <input type="tel" class="form-control" name="telefone" id="telefone" placeholder="dd numero" required>
  </div><br>
  
  <div class="col-md-9">
    <label for="email" class="form-label">Email:</label>
    <input type="email"  name="email" id="email" class="form-control">
  </div><br>

  <div class="col-md-9">
    <label for="inputState" class="form-label">*Forma de contato:</label>
    <select id="tpcontato" class="form-select" name="tpcontato">
        <option value="">Selecione</option>
        <option value="email">Email</option>
        <option value="whatsapp">WhatsApp</option>
    </select>
</div>
<br>

  
  <div class="col-md-9">
    <label for="nome" class="form-label">*Titulo para Assunto:</label>
    <input type="text" name="assunto" id="assunto" class="form-control" required>
  </div><br>
  
  <div class="col-md-9">
    <label for="mensagem" class="form-label">*Deixe aqui sua mensagem:</label>
    <textarea name="mensagem" id="mensagem" class="form-control" rows="8" required></textarea>
</div><br>

  
  <br>
   </label>
   
   
   <button type="submit"  class="confirmarpedido" name="confirmar_pedido" value="Confirmar Pedido" data-toggle="modal" data-target="#pedidoSucessoModal" data-id-pedido="<?= $id_pedido ?>">
    Enviar
   </button>

</div>
</div>
</div>
</form>
<br>

<!-- Modal de confirmação -->
<div class="modal fade" id="confirmacaoModal" tabindex="-1" aria-labelledby="confirmacaoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmacaoModalLabel">Confirmação de Envio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Seu formulário foi enviado com sucesso! Entraremos em contato em breve.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  action="contato.php">Fechar</button>
      </div>
    </div>
  </div>
</div>

<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>
</body>
</html>
