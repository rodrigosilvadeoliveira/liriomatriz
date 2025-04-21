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
    <div class="produtos-container">
<a id="incluirCadastro" value="Novo Volutario" href="cadastroMembrosAdm.php">Novo Membro(a)</a>
<a id="incluirCadastro" value="Novo Volutario" href="consulta_membros.php">Consultar Membros</a>
<a id="incluirCadastro" value="Novo Volutario" href="consulta_membros_busca.php">Pesquisa Membro(a)</a>
<a id="incluirCadastro" href="cadastroForm.php" value="Novo Cadastro">Inscrições</a>
<a id="incluirCadastro" href="consulta_logs.php" value="Novo Cadastro">Log</a>
<a id="incluirCadastro" href="formularioMaster.php" value="Novo Cadastro">Cadastro Acesso</a>
</div>

<br>
<fieldset class="boxexportarMembros">
<form id="dataRelatorio" method="POST" action="relatorio_membros.php">
    <label for="data_inicio"><b>Exportar em planilha Membros da Lirio Matriz:</b></label>
    <input type="submit" value="Exportar" id="Exportar"/>
</form>
</fieldset>

<div>
<table class="table" id="tabelaLista">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Usuari0</th>
      <th scope="col">Nivel Acessp</th>
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

            echo "<td>" .$user_data['nivel_acesso']. "</td>";
                        
            echo "<td>" .$user_data['data_login']. "</td>";

            echo "<td>" .$user_data['hora_login']. "</td>";
            
                       
                       echo "<td> 
            <a class='btn btn-sm btn-primary' href='edit_formularioMembros.php?id=$user_data[id]' title='Editar'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
            </svg>
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