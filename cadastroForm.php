<?php include("cabecalhoIgreja.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
//session_start();
include_once('config.php');
   // print_r($_SESSION);
    if((!isset($_SESSION['usuario'])== true) and ($_SESSION['senha']) == true)
    {
      unset($_SESSION['usuario']);
      unset($_SESSION['senha']);
      header('Location: login.php');
      
    }$logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM evento WHERE imagem LIKE '%$data%' or produto LIKE '%$data%' or modelo LIKE '%$data%' or categoria LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
      $sql = "SELECT * FROM evento WHERE cartaz = 'formulario' ORDER BY id DESC";
    }
    $result = $conexao->query($sql);

    if(isset($_POST['submitAdm']))
{
include_once("config.php");

// Insira as informações da compra no banco de dados
// Data e hora atual

if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"])){
  $imagem = "./img/".$_FILES["imagem"]["name"];
  move_uploaded_file($_FILES["imagem"]["tmp_name"] ,$imagem);
}else{
  $imagem = "";
}
$result = mysqli_query($conexao, "INSERT INTO evento(imagem) 
VALUES ('$imagem')");

header('Location: cadastroForm.php');
}

if(isset($_POST['submitEvento']))
{
include_once("config.php");

// Insira as informações da compra no banco de dados
// Data e hora atual
// $cartaz = isset($_POST['cartaz']) ? $_POST['cartaz'] : null;
$cartaz = $_POST['cartaz'];
$links = $_POST['links'];
if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"])){
  $imagem = "./img/".$_FILES["imagem"]["name"];
  move_uploaded_file($_FILES["imagem"]["tmp_name"] ,$imagem);
}else{
  $imagem = "";
}
$result = mysqli_query($conexao, "INSERT INTO evento(imagem,cartaz,links) 
VALUES ('$imagem','$cartaz','$links')");

header('Location: cadastroForm.php');
}
?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Inscrição</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <br><br><br>
<?php
    echo "<h1 id='BemVindo'>Cadastrar Fornulário no Site</h1>";
?>
<a id="incluirCadastro" value="Novo Volutario" href="cadastroMembrosAdm.php">Novo Membro(a)</a>
<a id="cons_Adm" value="Novo Volutario" href="consulta_membros.php">Consultar Membros</a>
<a id="incluirCadastro" href="cadastroEvento.php" value="Novo Cadastro">Eventos</a>
<a id="incluirCadastro" href="cadastroForm.php" value="Novo Cadastro">Inscrições</a>
<fieldset class="boxformularioAdm">
    <form id="insert_form" class="row g-3" name="cadastrodeevento" action="cadastroEvento.php" method="POST" enctype="multipart/form-data">
    
      <h1>Cadastro de Imagens</h1>
    
  
      <!-- <label class="nomedoCampo">Imagem: *</label> -->
      
      <div class="col-md-5">
    <label for="inputState" class="form-label">*Tipo Cadastro</label>
    <br>
    <select id="cartaz" class="form-select" name="cartaz" required>
        <option value="formulario">Formulario</option>
      
    </select>
</div>
  
     <div class="col-md-5">
<label class="form-label">Informe Link do google Form:</label>
       <input type="text" class="form-control" name="links" placeholder="" id="links" maxlength="300">
     </div> <br>

  <div class="col-md-5">
    <button type="submit" name="submitEvento" id="submitEvento" class="btn btn-primary">Enviar</button>
  </div>
  
</form>
</fieldset>
<table class="table" id="tabelaLista" style="width: 99%;">
  <thead>
    <tr>
    <th scope="col">#</th>
      <th scope="col">Tp.Divulgação</th>
      <th scope="col">Links</th>
      
      <th scope="col">......</th>
    </tr>
  </thead>
  <tbody>
  <?php
        while($user_data = mysqli_fetch_assoc($result))
        {
            echo "<tr>";
            echo "<td>" .$user_data['id']. "</td>";
            echo "<td>".$user_data['cartaz']."</td>";
            echo "<td>".$user_data['links']."</td>";
            
            echo "<td> 
            
            <a class='btn btn-sm btn-danger' href='deleteForm.php?id=$user_data[id]' title='Deletar'>
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

</body>


</html>