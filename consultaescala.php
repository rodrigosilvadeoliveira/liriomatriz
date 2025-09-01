<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];

// Pega escala salva
$sql = "SELECT dados_escala FROM escalas_salvas WHERE id = 1";
$res = $conexao->query($sql);
if (!$res || $res->num_rows === 0) {
    echo "<p>Nenhuma escala encontrada.</p>";
    exit;
}
$row = $res->fetch_assoc();
$dados = json_decode($row['dados_escala'], true);

if (!$dados || !isset($dados['datas']) || !isset($dados['escalas'])) {
    echo "<p>Erro ao interpretar os dados da escala.</p>";
    exit;
}

// Pega lista de músicos com fotos
$sqlMusicos = "SELECT nome, foto FROM musicos";
$resMusicos = $conexao->query($sqlMusicos);
$musicos = [];
while($m = $resMusicos->fetch_assoc()){
    $musicos[$m['nome']] = $m['foto'];
}

// Função para traduzir dia da semana
function diaSemana($dataIso) {
    $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    $time = strtotime($dataIso);
    return $dias[date('w', $time)];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta Escala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        .escala-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .escala-data {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 12px;
        }
        .escala-funcao {
            display: flex;
            align-items: center;
            margin: 6px 0;
            padding: 8px;
            background: #ecf0f1;
            border-radius: 8px;
        }
        .escala-funcao img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .escala-funcao strong {
            color: #2980b9;
            margin-right: 5px;
        }
    </style>
</head>
<body class="container py-4">

    <div class="navegacao">
        <?php include("navegacao.php") ?>
    </div>

    <h2 class="mb-4">Consulta de Escalas</h2>

    <?php 
    // Percorrer todas as datas salvas
    foreach ($dados['datas'] as $dataInfo) {
        $dataIso = $dataInfo['iso']; 
        $dataFormatada = date('d/m', strtotime($dataIso));
        $diaSemana = diaSemana($dataIso);
        ?>
        
        <div class="escala-card">
            <div class="escala-data">
                <?php echo "$dataFormatada - $diaSemana"; ?>
            </div>
            <div>
                <?php 
                // Mostrar funções e músicos daquela data
                foreach ($dados['escalas'] as $funcao => $dias) {
                    if (isset($dias[$dataIso])) {
                        $musico = $dias[$dataIso];
                        $foto = isset($musicos[$musico]) && $musicos[$musico] != '' 
                                ? $musicos[$musico] 
                                : 'uploads/default.png';
                        echo "
                        <div class='escala-funcao'>
                            <img src='$foto' alt='$musico'>
                            <div><strong>$funcao:</strong> $musico</div>
                        </div>";
                    }
                }
                ?>
            </div>
        </div>
        
    <?php } ?>

</body>
</html>
