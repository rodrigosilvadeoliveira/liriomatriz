<?php
include_once('config.php');
include_once('config_language.php');

if (empty($_SESSION['usuario']) || empty($_SESSION['senha'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];
$perfil = $_SESSION['nivel_acesso'] ?? 'consulta';

$foto_perfil = "uploads/foto_67feba4ab0f0a.jpg";// imagem padrão

// =============================
// 2️⃣ Buscar foto com JOIN (mais eficiente)
// =============================
$sql = "
SELECT m.foto
FROM cadastroadm c
LEFT JOIN musicos m ON m.cadastroadm_id = c.id
WHERE c.usuario = ?
LIMIT 1
";

$stmt = $conexao->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $logado);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados = $result->fetch_assoc();

    if (!empty($dados['foto'])) {

        // Remove ./ do início se existir
        $caminho = ltrim($dados['foto'], './');

        if (file_exists($caminho)) {
            $foto_perfil = $caminho;
        }
    }

    $stmt->close();
}

$id_usuario = null;

$stmtId = $conexao->prepare("SELECT id FROM cadastroadm WHERE usuario = ? LIMIT 1");
$stmtId->bind_param("s", $logado);
$stmtId->execute();
$resultId = $stmtId->get_result();

if ($rowId = $resultId->fetch_assoc()) {
    $id_usuario = $rowId['id'];
}

$stmtId->close();

$departamentos = [

"Louvor" => [
'bateria','violao','teclado','baixo','ministro','vocal1','vocal2','vocal3','talckback'
],

"Som" => [
'igreja','live','somkids','igreja_noite'
],

"Midias" => [
'ct','c1','c2','lt','lz','ph'
],

"Dança" => [
'danca_manhã','danca_noite'
],

"Criativo" => [
'real_time','recap','real_time_manhã','real_time_noite'
],

"Staff" => [
'staff1','staff2'
],

"Kids" => [
'prof1_manhã','prof2_manhã','prof1_noite','prof2_noite'
]

];

$funcoes_usuario = [];

if ($id_usuario) {

    $sqlMusico = "SELECT * FROM musicos WHERE cadastroadm_id = ?";
    $stmtMusico = $conexao->prepare($sqlMusico);
    $stmtMusico->bind_param("i", $id_usuario);
    $stmtMusico->execute();

    $resultMusico = $stmtMusico->get_result();

    if ($dadosMusico = $resultMusico->fetch_assoc()) {

        foreach ($departamentos as $departamento => $funcoes) {

            foreach ($funcoes as $funcao) {

                if (!empty($dadosMusico[$funcao]) && strtolower($dadosMusico[$funcao]) == strtolower($logado)) {

                    $funcoes_usuario[$departamento][] = $funcao;

                }

            }

        }

    }

    $stmtMusico->close();
}

$stmtId = $conexao->prepare("SELECT id FROM cadastroadm WHERE usuario = ? LIMIT 1");
$stmtId->bind_param("s", $logado);
$stmtId->execute();
$resultId = $stmtId->get_result();

if ($rowId = $resultId->fetch_assoc()) {
    $id_usuario = $rowId['id'];
}

$stmtId->close();

$pageTitle = "Sistema Igreja - Home";
include('navegacao.php');

$data_login = date('Y-m-d'); // formato: 2025-04-21
$hora_login = date('H:i:s'); // formato: 14:30:05
$pagina = basename($_SERVER['PHP_SELF']); // pega o nome do arquivo atual, ex: membros.php

// Busca o nome e o nível de acesso do usuário atual
$usuario = $_SESSION['usuario'];
$nivel_acesso = $_SESSION['nivel_acesso'] ?? 'desconhecido';

$resultlist = null;

include('registroslog.php');
?>
 <!-- Mensagem de boas-vindas -->
<style>
    /* Welcome */
    .welcome-message {
        margin-bottom: 30px;
    }

    .profile-icon-container {
        margin-bottom: 15px;
    }

    .profile-link {
        display: inline-block;
    }

    .profile-icon {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #ddd;
        transition: 0.3s;
    }

    .profile-icon:hover {
        transform: scale(1.05);
    }

    /* Quick Access */
    .quick-access {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .section-title {
        font-size: 1.5rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .quick-access-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .quick-access-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        transition: 0.3s;
    }

    .quick-access-item:hover {
        background: #4e73df;
        color: white;
        transform: translateY(-3px);
    }

    .quick-access-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .quick-access-text {
        text-align: center;
        font-weight: 500;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .quick-access-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
        <div class="welcome-message">
             <div class="profile-icon-container">
        <a href="perfil.php" class="profile-link">
     <img src="<?php echo $foto_perfil; ?>" alt="Perfil" class="profile-icon">
        </a>
    </div>
            <h1><?php echo __('bem_vindo') ?> <?php echo $logado; ?>!</h1>
            <p><?php echo __('seu_login') ?> <?php echo $perfil; ?></p>
            <p><?php echo __('hoje') ?> <?php echo date('d/m/Y'); ?></p>
        </div>

       

        <!-- Acesso Rápido -->
     <div class="quick-access">
    <h2 class="section-title">Acesso Rápido</h2>
    <div class="quick-access-grid">
        <a href="alterarsenha.php?id=<?php echo $id_usuario; ?>" class="quick-access-item">
            <div class="quick-access-icon">
                <i class="fas fa-arrow-right"></i>
            </div>
            <div class="quick-access-text"><?php echo __('alterar_senha') ?></div>
        </a>
        <a href="alterarfoto.php?id=<?php echo $id_usuario; ?>" class="quick-access-item">
            <div class="quick-access-icon">
                <i class="fas fa-arrow-right"></i>
            </div>
            <div class="quick-access-text"><?php echo __('alterar_foto') ?></div>
        </a>
        </div>
    </div>

    <?php if (!empty($funcoes_usuario)) { ?>

<div class="quick-access">

<h2 class="section-title">Suas Funções nos Departamentos</h2>

<?php foreach ($funcoes_usuario as $departamento => $funcoes) { ?>

<h3 style="margin-top:20px;"><?php echo $departamento; ?></h3>

<div class="quick-access-grid">

<?php foreach ($funcoes as $funcao) { ?>

<div class="quick-access-item">

<div class="quick-access-icon">
<i class="fas fa-music"></i>
</div>

<div class="quick-access-text">
<?php echo ucwords(str_replace('_',' ',$funcao)); ?>
</div>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

<?php } ?>

        <!-- Atividade Recente -->
        <!-- <div class="recent-activity">
            <h2 class="section-title">Atividade Recente</h2>
            <ul class="activity-list">
                <?php if (count($atividadesRecentes) > 0): ?>
                    <?php foreach ($atividadesRecentes as $atividade): 
                        $info = obterInfoAtividade($atividade['tipo']);
                    ?>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas <?php echo $info['icon']; ?>"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?php echo $info['title']; ?></div>
                            <div class="activity-desc">
                                <?php 
                                if ($atividade['tipo'] == 'novo_membro' && !empty($atividade['nome'])) {
                                    echo $atividade['nome'] . ' foi cadastrado no sistema';
                                } elseif ($atividade['tipo'] == 'escala_atualizada' && !empty($atividade['descricao'])) {
                                    echo $atividade['descricao'] . ' foi publicada';
                                } elseif ($atividade['tipo'] == 'relatorio_gerado' && !empty($atividade['nome'])) {
                                    echo 'Relatório do ' . $atividade['nome'] . ' foi enviado';
                                } else {
                                    echo 'Atividade realizada no sistema';
                                }
                                ?>
                            </div>
                            <div class="activity-time"><?php echo tempoDecorrido($atividade['data']); ?></div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Nenhuma atividade recente</div>
                            <div class="activity-desc">As atividades serão exibidas aqui quando disponíveis</div>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div> -->

  <?php
include('footer_sistema.php');
?>