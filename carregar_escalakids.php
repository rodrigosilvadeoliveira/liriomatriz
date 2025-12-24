<?php
include_once('config.php');

// Pega id enviado
$id = intval($_GET['id'] ?? 0);

$sql = "SELECT dados_escala FROM escalas_kids WHERE id = $id";
$res = $conexao->query($sql);

if(!$res || $res->num_rows == 0){
    echo "<p>Escala não encontrada.</p>";
    exit;
}

$row = $res->fetch_assoc();
$dados = json_decode($row['dados_escala'], true);

if(!$dados || !isset($dados['datas']) || !isset($dados['escalas'])){
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

function diaSemana($dataIso) {
    $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    $time = strtotime($dataIso);
    return $dias[date('w', $time)];
}

// Monta saída
foreach ($dados['datas'] as $dataInfo) {
    $dataIso = $dataInfo['iso'];
    $dataFormatada = date('d/m', strtotime($dataIso));
    $diaSemana = diaSemana($dataIso);

    echo "<div class=''>";
    echo "<div class='escala-data'>{$dataFormatada} - {$diaSemana}</div>";

    foreach ($dados['escalas'] as $funcao => $dias) {
    if (isset($dias[$dataIso])) {
        $musico = $dias[$dataIso];
        
        // ADICIONE ESTA VERIFICAÇÃO
        if (!empty(trim($musico))) {  // Verifica se não está vazio/nulo
            $foto = isset($musicos[$musico]) && $musicos[$musico] != '' 
                    ? $musicos[$musico] 
                    : 'uploads/default.png';
            
            echo "<div class='escala-funcao'>
                    <img src='$foto' alt='$musico' class='me-3'>
                    <div>
                        <strong class='text-primary'>$funcao:</strong> 
                        <span class='ms-1'>$musico</span>
                    </div>
                  </div>";
        }
    }
}

    echo "</div>";
}
?>
