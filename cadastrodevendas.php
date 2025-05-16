<?php include("cabecalhoIgreja.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
//session_start();
include('verifica_permissao.php');
include_once('config.php');
   // print_r($_SESSION);
    if((!isset($_SESSION['usuario'])== true) and ($_SESSION['senha']) == true)
    {
      unset($_SESSION['usuario']);
      unset($_SESSION['senha']);
      header('Location: sistema.php');
      
    }$logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM vendasprodutos WHERE imagem LIKE '%$data%' or produto LIKE '%$data%' or modelo LIKE '%$data%' or categoria LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
      $sql = "SELECT * FROM vendasprodutos WHERE estoque IN ('sim','não') ORDER BY id ASC";


    }
    $result = $conexao->query($sql);

    if(isset($_POST['submitAdm']))
{
include_once("config.php");

// Insira as informações da compra no banco de dados
// Data e hora atual

if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"])) {
    $nome_original = $_FILES["imagem"]["name"];

    // Substitui espaços por underlines e converte para minúsculas
    $nome_limpo = strtolower(str_replace(' ', '_', $nome_original));

    // Adiciona um timestamp para evitar nomes repetidos
    $nome_final = time() . '_' . $nome_limpo;

    $caminho_destino = "./img/" . $nome_final;

    move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho_destino);

    $imagem = $caminho_destino;
} else {
    $imagem = "";
}

$result = mysqli_query($conexao, "INSERT INTO vendasprodutos(imagem) 
VALUES ('$imagem')");

header('Location: cadastroEvento.php');
}

if(isset($_POST['submitEvento']))
{
include_once("config.php");

// Insira as informações da compra no banco de dados
// Data e hora atual
// $cartaz = isset($_POST['cartaz']) ? $_POST['cartaz'] : null;
$produto = $_POST['produto'];
$modelo = $_POST['modelo'];
$valordevenda = $_POST['valordevenda'];
$estoque = $_POST['estoque'];
if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"])){
  $imagem = "./img/".$_FILES["imagem"]["name"];
  move_uploaded_file($_FILES["imagem"]["tmp_name"] ,$imagem);
  
}else{
  $imagem = "";
}
$result = mysqli_query($conexao, "INSERT INTO vendasprodutos(imagem,produto,modelo,valordevenda,estoque) 
VALUES ('$imagem','$produto','$modelo','$valordevenda','$estoque')");

header('Location: cadastroEvento.php');
}
?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Cadastro Eventos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <br><br><br>
<?php
    echo "<h1 id='BemVindo'>Cadastrar Imagens no Site</h1>";
?>
<div  class="diaHoje">
        <p type="hidden" class="date">
            <?php
                // Configura o fuso horário
                date_default_timezone_set('America/Sao_Paulo');
                
                // Obtém a data atual no formato desejado
                echo date('d/m/Y');
            ?>
<div class="navegacao">
   <?php include("navegacao.php")?>
   </div>
<fieldset class="boxformulariodoSite">
    <form id="insert_form" class="row g-3" name="cadastrodevendas" action="cadastrodevendas.php" method="POST" enctype="multipart/form-data">
    
      <h1>Cadastro de Imagens</h1>
    
  
      <!-- <label class="nomedoCampo">Imagem: *</label> -->
      
      <div class="col-md-5">
    <label for="inputState" class="form-label">*Produtos para Vendas</label>
    <br>
    <select id="produto" class="form-select" name="produto" required>
        <option value="">Selecione</option>
        <option value="panquecadecarne">Panqueca de Carne</option>
        <option value="panquecadefrango">Panqueca de Frango</option>
        
    </select>
</div>
<div class="col-md-5">
<label for="inputState" class="form-label">*Selecione arquivo:</label>
       <input type="file" name="imagem" class="form-control" accept="image/*">
     </div><br>
  
     <div class="col-md-5">
<label class="form-label">Cracteristica:</label>
       <input type="text" class="form-control" name="modelo" placeholder="" id="modelo" maxlength="30">
     </div> <br>

     <div class="col-md-5">
<label class="form-label">valor de venda:</label>
       <input type="text" class="form-control" name="valordevenda" placeholder="Exemplo 10.50" id="valordevenda" maxlength="50">
     </div> <br>

  
  <div class="col-md-5">
    <label for="inputState" class="form-label">*Estoque</label>
    <br>
    <select id="estoque" class="form-select" name="estoque" required>
        <option value="">Selecione</option>
        <option value="sim">Sim</option>
        <option value="não">Não</option>
        
    </select>
</div>

  <div class="col-md-5">
    <button type="submit" name="submitEvento" id="submitEvento" class="btn btn-primary">Enviar</button>
  </div>
</form>
</fieldset>
<table class="table" id="tabelaLista" style="width: 99%;">
  <thead>
    <tr>
    <th scope="col">#</th>
      <th scope="col">Imagem</th>
      <th scope="col">Produto</th>
      <th scope="col">Cracteristica</th>
      <th scope="col">Valor</th>
      <th scope="col">Em estoque</th>
      
      <th scope="col">......</th>
    </tr>
  </thead>
  <tbody>
  <?php
        while($user_data = mysqli_fetch_assoc($result))
        {
            echo "<tr>";
            echo "<td>" .$user_data['id']. "</td>";
            $imagem_url = str_replace(' ', '%20', $user_data['imagem']);
    echo "<td><img class='imagensevento' src='$imagem_url'></td>";
            echo "<td>".$user_data['produto']."</td>";
            echo "<td>".$user_data['modelo']."</td>";
            echo "<td>".$user_data['valordevenda']."</td>";
            
            echo "<td> 
            
            <a class='btn btn-sm btn-danger' href='deleteEvento.php?id=$user_data[id]' title='Deletar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                    <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                </svg>
            </a>
</td>";
            echo "</tr>";

        }



  ?>
    
    </tr>
  </tbody>
</table>
</div>

<script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>

</body>


</html>