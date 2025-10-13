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
      header('Location: login.php');
      
    }$logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM log_login WHERE id LIKE '%$data%' or usuario LIKE '%$data%' or nivel_acesso LIKE '%$data%' ORDER BY id DESC";
    }
    else
    {
        $sql = "SELECT * FROM log_login ORDER BY id DESC";
    }
    $result = $conexao->query($sql);

    
    
    // **Reexecuta a consulta para garantir que os dados atualizados sejam refletidos**
    $resultlist = $conexao->query($sql);
 
    
?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Consultar Membros</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

</head>
<body>

   
<?php
    echo "<h1 id='BemVindo'>Bem vindo <U>$logado</u><p>Consulta Lista de Membros</p></h1>";
?>
<br>
 <!-- <div  class="diaHoje">
        <p type="hidden" class="date">
            <?php
                // Configura o fuso horário
                date_default_timezone_set('America/Sao_Paulo');
                
                // Obtém a data atual no formato desejado
                echo date('d/m/Y');
            ?>
        </p>
    </div> -->
   <br><br>
  <div class="navegacao">
   <?php include("navegacao.php")?>
   </div>

<br>
<fieldset class="boxexportarMembros">
<form id="dataRelatorio" method="POST" action="relatorio_membros.php">
    <label for="data_inicio"><b>Exportar em planilha Membros da Lirio Matriz:</b></label>
    <input type="submit" value="Exportar" id="Exportar"/>
</form>
</fieldset>

<div>
<div class="table-container">
<table class="table" id="tabelaLista">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Usuario</th>
      <th scope="col">Nome</th>
      <th scope="col">Nivel Acesso</th>
      <th scope="col">Data</th>
      <th scope="col">Hora</th>
      
    </tr>
  </thead>
  <tbody>
  <?php
        while($user_data = mysqli_fetch_assoc($resultlist))
        {
            echo "<tr>";
            echo "<td>" .$user_data['id']. "</td>";

            echo "<td>" .$user_data['usuario']. "</td>";
            
            echo "<td>" .$user_data['nome']. "</td>";

            echo "<td>" .$user_data['nivel_acesso']. "</td>";
                        
            echo "<td>" .$user_data['data_login']. "</td>";

            echo "<td>" .$user_data['hora_login']. "</td>";
            
                       
                       echo "<td>  

            <a href='delete_log.php?id=".$user_data['id']."' class='btn btn-sm btn-danger' onclick=\"return confirm('Excluir log?')\">
            <i class='fas fa-trash'></i>
        </a>
            
</td>";




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