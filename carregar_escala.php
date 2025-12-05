<?php
include_once('config.php');

// Pega parâmetros
$id = intval($_GET['id'] ?? 0);
$tabela = $_GET['tabela'] ?? 'escalas_louvor';

// Validar tabela permitida
$tabelas_permitidas = ['escalas_louvor', 'escalas_homens', 'escalas_louvorkids'];
if (!in_array($tabela, $tabelas_permitidas)) {
    echo "<div class='alert alert-danger'>Tabela não permitida.</div>";
    exit;
}

// Verificar estrutura da tabela
$sql_check = "SHOW COLUMNS FROM $tabela LIKE 'dados_escala'";
$res_check = $conexao->query($sql_check);

if ($res_check->num_rows == 0) {
    // Tabela não tem campo dados_escala - mostrar dados básicos
    $sql = "SELECT * FROM $tabela WHERE id = $id";
    $res = $conexao->query($sql);
    
    if(!$res || $res->num_rows == 0){
        echo "<div class='alert alert-warning'>Escala não encontrada.</div>";
        exit;
    }
    
    $row = $res->fetch_assoc();
    echo "<div class='p-3'>";
    echo "<h5>Informações da Escala</h5>";
    
    // Mostrar campos disponíveis
    foreach($row as $campo => $valor) {
        if($campo != 'id' && $campo != 'pdf_path' && $valor) {
            echo "<p><strong>" . ucfirst(str_replace('_', ' ', $campo)) . ":</strong> " . htmlspecialchars($valor) . "</p>";
        }
    }
    
    echo "</div>";
    exit;
}

// Tabela tem campo dados_escala (compatível com o antigo)
$sql = "SELECT dados_escala FROM $tabela WHERE id = $id";
$res = $conexao->query($sql);

if(!$res || $res->num_rows == 0){
    echo "<div class='alert alert-warning'>Escala não encontrada.</div>";
    exit;
}

$row = $res->fetch_assoc();
$dados = json_decode($row['dados_escala'], true);

if(!$dados || !isset($dados['datas']) || !isset($dados['escalas'])){
    echo "<div class='alert alert-warning'>Erro ao interpretar os dados da escala.</div>";
    exit;
}

// Pega lista de músicos com fotos (se a tabela musicos existir)
$musicos = [];
$sqlMusicos = "SHOW TABLES LIKE 'musicos'";
if($conexao->query($sqlMusicos)->num_rows > 0) {
    $resMusicos = $conexao->query("SELECT nome, foto FROM musicos");
    while($m = $resMusicos->fetch_assoc()){
        $musicos[$m['nome']] = $m['foto'];
    }
}

function diaSemana($dataIso) {
    $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    $time = strtotime($dataIso);
    return $dias[date('w', $time)];
}

// Monta saída
echo "<div class='container-fluid p-0'>";
foreach ($dados['datas'] as $dataInfo) {
    $dataIso = $dataInfo['iso'];
    $dataFormatada = date('d/m', strtotime($dataIso));
    $diaSemana = diaSemana($dataIso);

    echo "<div class='escala-card mb-3'>";
    echo "<div class='escala-data p-3 border-bottom'><i class='bi bi-calendar'></i> {$dataFormatada} - {$diaSemana}</div>";
    

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
echo "</div>";
?>