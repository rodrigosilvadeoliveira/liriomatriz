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
      header('Location: login.php');
      
    }$logado = $_SESSION['usuario'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM membros WHERE id LIKE '%$data%' or nome LIKE '%$data%' or sobrenome LIKE '%$data%' ORDER BY id DESC";
    }

?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Consultar Membros</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<br><br><br>
   
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
<fieldset>
<br>
<form id="dataRelatorio" method="POST" action="consulta_membros_busca.php">
    
    <label for="nomep"><b>Nome do membro(a):</b></label>
    <input type="varchar" name="nome" id="nome" />
        <input type="submit" value="Consultar" id="Exportar"/>
</form>
</fieldset>
<div>
<table class="table" id="tabelaLista">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Foto</th>
      <th scope="col">Nome</th>
      <th scope="col">Sobrenome</th>
      <th scope="col">Nascimento</th>
      <th scope="col">Batizado</th>
      <th scope="col">Membro desde</th>
      <th scope="col">Telefone</th>
      <th scope="col">Email</th>
      <th scope="col">Voluntario</th>
      <th scope="col">Lider</th>
      <th scope="col">Departamentos</th>
      <th scope="col">Status</th>
      <th scope="col">......</th>
      <th scope="col">Idade</th>
      <th scope="col">Tempo membro</th>
      <th scope="col">Responsavel</th>
    </tr>
  </thead>
  <tbody>
  <?php
  
include_once('config.php');

// Estabelecer a conexão com o banco de dados
$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if (isset($_POST['nome'])){
  $nome = $_POST['nome'];

$sql = "SELECT * FROM membros WHERE nome = '$nome' or id = '$nome' ORDER BY id DESC";


$result = $conexao->query($sql);

      while($user_data = mysqli_fetch_assoc($result))
   {
            echo "<tr>";
            echo "<td>" .$user_data['id']. "</td>";
            
            echo "<td><img class='imagensMembros' src='uploads/" . $user_data['foto'] . "'></td>";


            echo "<td>" .$user_data['nome']. "</td>";

            echo "<td>" .$user_data['sobrenome']. "</td>";
                        
            echo "<td>" .$user_data['nascimento']. "</td>";

            echo "<td>" .$user_data['batizado']. "</td>";
            
            echo "<td>" .$user_data['datas']. "</td>";

            echo "<td>" .$user_data['telefone']. "</td>";

            echo "<td>" .$user_data['email']. "</td>";

            echo "<td>" .$user_data['voluntario']. "</td>";

            echo "<td>" .$user_data['lider']. "</td>";

            echo "<td>" .$user_data['departamentos']. "</td>";

            echo "<td>" .$user_data['status']. "</td>";
            
                       echo "<td> 
            <a class='btn btn-sm btn-primary' href='edit_formularioMembros.php?id=$user_data[id]' title='Editar'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
            </svg>
            </a> 
            
</td>";


echo "<td>" .$user_data['idade']. "</td>";


date_default_timezone_set('America/Sao_Paulo');
$dataAtual = new DateTime();

// Obtém o status (exemplo vindo de $user_data['status'])
$status = $user_data['status'];

// Obtém a data cadastrada (exemplo vindo de $user_data['data'])
$dataCadastrada = new DateTime($user_data['datas']);

// Verifica o status
if ($status === 'ativo') {
    // Calcula a diferença entre as datas
    $diferenca = $dataAtual->diff($dataCadastrada);
    
    // Mostra a data e a diferença
    
     $dataAtual->format('d/m/Y') . " - " . $dataCadastrada->format('d/m/Y');
     echo "<td>" .
     " (" . $diferenca->y . " anos, " . $diferenca->m . " meses e " . $diferenca->d . " dias)"
        . "</td>";
} else {
    // Mostra uma mensagem se não estiver ativo
    echo "<td>Status inativo.</td>";
}
 
echo "<td>" .$user_data['responsavel']. "</td>";
echo "</tr>";

        }

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