<?php
date_default_timezone_set('America/Sao_Paulo');
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
$resultlist = null; // só cria a variável, ainda sem resultado

// Se o usuário usou a barra de pesquisa
if(!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM membros 
            WHERE id LIKE '%$data%' 
            OR nome LIKE '%$data%' 
            OR email LIKE '%$data%' 
            ORDER BY nome ASC";
    $resultlist = $conexao->query($sql);

} else if(isset($_GET['filtro'])) {
    // Se o usuário selecionou um filtro no select
    $filtro = $_GET['filtro'];

    if($filtro == "ate1ano") {
        $sql = "SELECT * FROM membros 
                WHERE DATEDIFF(NOW(), datas) <= 365 
                ORDER BY nome ASC";

    } elseif($filtro == "ate5anos") {
        $sql = "SELECT * FROM membros 
                WHERE DATEDIFF(NOW(), datas) <= 365*5 
                ORDER BY nome ASC";

    } elseif($filtro == "mais5anos") {
        $sql = "SELECT * FROM membros 
                WHERE DATEDIFF(NOW(), datas) > 365*5 
                ORDER BY nome ASC";

    } elseif($filtro == "nomeAZ") {
        $sql = "SELECT * FROM membros 
                ORDER BY nome ASC";  // ordena alfabeticamente A → Z

    } elseif($filtro == "nomeZA") {
        $sql = "SELECT * FROM membros 
                ORDER BY nome DESC"; // ordena alfabeticamente Z → A

    } else {
        $sql = "SELECT * FROM membros ORDER BY nome ASC";
    }

    $resultlist = $conexao->query($sql);

}    include('calculoMembros.php');

    $sqlmembros = "SELECT * FROM totalmembros ORDER BY id DESC";
    $resultmembros = $conexao->query($sqlmembros);
    
?>
     
<!DOCTYPE html>
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
<fieldset class="boxexportarMembros">
<form id="filtroMembros" method="GET" action="">
    <label for="filtro"><b>Filtrar membros:</b></label>
    <select name="filtro" id="filtro" onchange="document.getElementById('filtroMembros').submit()">
    <option value="" disabled selected>-- Selecione um filtro --</option>    
    <option value="todos" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="todos") echo "selected"; ?>>Todos membros</option>
        <option value="ate1ano" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="ate1ano") echo "selected"; ?>>Membros até 1 ano</option>
        <option value="ate5anos" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="ate5anos") echo "selected"; ?>>Membros até 5 anos</option>
        <option value="mais5anos" <?php if(isset($_GET['filtro']) && $_GET['filtro']=="mais5anos") echo "selected"; ?>>Membros acima de 5 anos</option>
    </select>
</form>
<form method="GET" action="relatorio_membros.php" style="margin-top:10px;">
    <!-- mantém o filtro selecionado -->
    <input type="hidden" name="filtro" value="<?php echo isset($_GET['filtro']) ? $_GET['filtro'] : ''; ?>">
    <button type="submit">📊 Exportar Excel</button>
</form>
</fieldset>
<div>
    <?php
// Pega filtro selecionado
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

// Base da query
$sqlMembros = "
    SELECT
        SUM(CASE WHEN idade <= 12 THEN 1 ELSE 0 END) AS idademenor,
        SUM(CASE WHEN idade >= 13 THEN 1 ELSE 0 END) AS idademaior
    FROM membros
    WHERE status = 'ativo'
";

// Ajusta conforme filtro
if ($filtro == "ate1ano") {
    $sqlMembros .= " AND DATEDIFF(NOW(), datas) <= 365";
} elseif ($filtro == "ate5anos") {
    $sqlMembros .= " AND DATEDIFF(NOW(), datas) <= 1825";
} elseif ($filtro == "mais5anos") {
    $sqlMembros .= " AND DATEDIFF(NOW(), datas) > 1825";
}

// Ordenação opcional (se listar nomes, mas aqui é só contagem não precisa ORDER BY)

$resultmembros = $conexao->query($sqlMembros);
?>

<table class="table" id="tabelaTotal">
  <thead>
    <tr>
      <th scope="col">Membros até 12 anos</th>
      <th scope="col">Membros a partir de 13 anos</th>
    </tr>
  </thead>
  <tbody>
  <?php
        while($user_membros = mysqli_fetch_assoc($resultmembros))
        {
            echo "<tr>";
            echo "<td>" .$user_membros['idademenor']. "</td>";
            echo "<td>" .$user_membros['idademaior']. "</td>";
            echo "</tr>";
        }
  ?>
  </tbody>
</table>

</div>

<div class="table-container">
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
if ($resultlist && $resultlist->num_rows > 0) {
    while($user_data = mysqli_fetch_assoc($resultlist)) {
        echo "<tr>";
        echo "<td>".$user_data['id']."</td>";
        echo "<td><img class='imagensMembros' src='uploads/".$user_data['foto']."'></td>";
        echo "<td>".$user_data['nome']."</td>";
        echo "<td>".$user_data['sobrenome']."</td>";
        echo "<td>".date('d/m/Y', strtotime($user_data['nascimento']))."</td>";
        echo "<td>".$user_data['batizado']."</td>";
        echo "<td>".date('d/m/Y', strtotime($user_data['datas']))."</td>";
        echo "<td>".$user_data['telefone']."</td>";
        echo "<td>".$user_data['email']."</td>";
        echo "<td>".$user_data['voluntario']."</td>";
        echo "<td>".$user_data['lider']."</td>";
        echo "<td>".$user_data['departamentos']."</td>";
        echo "<td>".$user_data['status']."</td>";
        echo "<td><a class='btn btn-sm btn-primary' href='edit_formularioMembros.php?id=$user_data[id]'>Editar</a></td>";
        echo "<td>".$user_data['idade']."</td>";

        // cálculo do tempo de membro
        $dataAtual = new DateTime();
        $dataCadastrada = new DateTime($user_data['datas']);
        $diferenca = $dataAtual->diff($dataCadastrada);
        echo "<td> (" . $diferenca->y . " anos, " . $diferenca->m . " meses e " . $diferenca->d . " dias)</td>";

        echo "<td>".$user_data['responsavel']."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='16' class='text-center'>Selecione um filtro para exibir os membros.</td></tr>";
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