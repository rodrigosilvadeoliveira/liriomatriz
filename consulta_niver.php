<?php include("cabecalhoIgreja.php")?>
<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include('verificarLogin.php');
verificarLogin();
//session_start();
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
    echo "<h1 id='BemVindo'><p>Consulta Lista de Aniversariantes</p></h1>";
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
<br>
<fieldset class="boxexportarMembros">
<form method="GET" action="relatorio_aniverariantes.php">
  <label for="mes">Informe o mês (1 a 12):</label>
  <input type="number" name="mes" id="mes" min="1" max="12" required>
  <button type="submit">Exportar Excel</button>
</form>
</fieldset>
<br>
<fieldset class="boxexportarMembros">
<form method="GET" action="">
  <label for="mes">Informe o mês (1 a 12):</label>
  <input type="number" name="mes" id="mes" min="1" max="12" required>
  <button type="submit">Consulta em tela</button>
</form>
</fieldset>

<div class="table-container">
<table class="table" id="tabelaLista">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Foto</th>
      <th scope="col">Nome</th>
      <th scope="col">Sobrenome</th>
      <th scope="col">Nascimento</th>
      <th scope="col">Membro desde</th>
      <th scope="col">Telefone</th>
      
    </tr>
  </thead>
  <tbody>
  <?php
// Conexão 
include_once('config.php');
// Verifica se houve erro de conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

if (isset($_GET['mes'])) {
    $mes = (int) $_GET['mes']; // sanitiza o input para garantir que é número inteiro

    // Consulta os aniversariantes do mês informado
    $sql = "SELECT nome, nascimento, sobrenome, foto, datas, telefone FROM membros WHERE MONTH(nascimento) = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $mes);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $contador = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<th scope='row'>" . $contador++ . "</th>";
            //echo "<td><img src='fotos/" . $row['foto'] . "' alt='Foto' width='50' height='60'></td>";
            echo "<td><img class='imagensMembros' src='uploads/" . $row['foto'] . "'></td>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sobrenome']) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['nascimento'])) . "</td>";
            echo "<td>" . date('d/m/Y', strtotime($row['datas'])) . "</td>";
            echo "<td>" . htmlspecialchars($row['telefone']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Nenhum aniversariante encontrado para este mês.</td></tr>";
    }


    $stmt->close();
}

$conexao->close();
?>   
    
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