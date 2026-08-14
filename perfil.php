<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if ((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}

$logado = $_SESSION['usuario'];
$resultlist = null;
$perfil = $_SESSION['nivel_acesso'] ?? 'consulta';

$foto_perfil = "uploads/foto_67feba4ab0f0a.jpg";// imagem padrão

// =============================
// 2️⃣ Buscar foto com JOIN (mais eficiente)
// =============================
$sql = "
SELECT m.foto
FROM cadastroadm c
LEFT JOIN musicos m ON m.cadastroadm_id = c.cadastroadm_id
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

$stmtId = $conexao->prepare("SELECT cadastroadm_id FROM cadastroadm WHERE usuario = ? LIMIT 1");
$stmtId->bind_param("s", $logado);
$stmtId->execute();
$resultId = $stmtId->get_result();

if ($rowId = $resultId->fetch_assoc()) {
    $id_usuario = $rowId['cadastroadm_id'];
}

$stmtId->close();
// =============================
// Buscar ID do membro na tabela correta
// =============================
$id_membro = null;

// Primeiro tenta buscar na tabela membros pelo nome de usuário
$stmtMembro = $conexao->prepare("SELECT id FROM membros WHERE nome = ? LIMIT 1");
$stmtMembro->bind_param("s", $logado);
$stmtMembro->execute();
$resultMembro = $stmtMembro->get_result();

if ($rowMembro = $resultMembro->fetch_assoc()) {
    $id_membro = $rowMembro['id'];
}

$stmtMembro->close();

// Se não encontrou, usa o ID da tabela cadastroadm como fallback
if (!$id_membro) {
    $id_membro = $id_usuario; // ou o ID que você já tem
}

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
// =============================
// Alterar a senha
// =============================
$stmtId = $conexao->prepare("SELECT id FROM cadastroadm WHERE usuario = ? LIMIT 1");
$stmtId->bind_param("s", $logado);
$stmtId->execute();
$resultId = $stmtId->get_result();

if ($rowId = $resultId->fetch_assoc()) {
    $id_usuario = $rowId['id'];
}

$stmtId->close();

// =============================
// Alterar a foto
// =============================

$stmtId = $conexao->prepare("SELECT cadastroadm_id FROM cadastroadm WHERE usuario = ? LIMIT 1");
$stmtId->bind_param("s", $logado);
$stmtId->execute();
$resultId = $stmtId->get_result();

if ($rowId = $resultId->fetch_assoc()) {
    $id_acesso = $rowId['cadastroadm_id'];
}

$stmtId->close();

$pageTitle = "Sistema Igreja - Home";


$data_login = date('Y-m-d'); // formato: 2025-04-21
$hora_login = date('H:i:s'); // formato: 14:30:05
$pagina = basename($_SERVER['PHP_SELF']); // pega o nome do arquivo atual, ex: membros.php

// Busca o nome e o nível de acesso do usuário atual
$usuario = $_SESSION['usuario'];
$nivel_acesso = $_SESSION['nivel_acesso'] ?? 'desconhecido';

$resultlist = null;


$icones_funcoes = [

'bateria' => 'fa-drum',
'violao' => 'fa-guitar',
'teclado' => 'fa-keyboard',
'baixo' => 'fa-guitar',
'ministro' => 'fa-microphone',
'vocal1' => 'fa-microphone',
'vocal2' => 'fa-microphone',
'vocal3' => 'fa-microphone',
'talckback' => 'fa-headset',

'igreja' => 'fa-volume-up',
'live' => 'fa-broadcast-tower',
'somkids' => 'fa-volume-up',
'igreja_noite' => 'fa-volume-up',

'ct' => 'fa-video',
'c1' => 'fa-camera',
'c2' => 'fa-camera',
'lt' => 'fa-lightbulb',
'lz' => 'fa-lightbulb',
'ph' => 'fa-photo-video',

'danca_manhã' => 'fa-child',
'danca_noite' => 'fa-child',

'real_time' => 'fa-clock',
'recap' => 'fa-film',
'real_time_manhã' => 'fa-clock',
'real_time_noite' => 'fa-clock',

'staff1' => 'fa-user-cog',
'staff2' => 'fa-user-cog',

'prof1_manhã' => 'fa-chalkboard-teacher',
'prof2_manhã' => 'fa-chalkboard-teacher',
'prof1_noite' => 'fa-chalkboard-teacher',
'prof2_noite' => 'fa-chalkboard-teacher'

];


if ($resultlist === null && $id_membro) {
    $stmt = $conexao->prepare("SELECT * FROM membros WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id_membro);
    $stmt->execute();
    $resultlist = $stmt->get_result();
    $stmt->close();
}



include('registroslog.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Membros</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --success-color: #1cc88a;
            --light-bg: #f8f9fc;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* .navbar-custom {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        } */

        .sidebar {
            min-height: calc(100vh - 73px);
            background: linear-gradient(180deg, var(--primary-color) 10%, var(--secondary-color) 100%);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            margin: 5px 0;
            border-radius: 5px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .card-dashboard {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
        }

        .card-dashboard .card-body {
            padding: 1.5rem;
        }

        .stats-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-primary-light {
            background-color: #e8eefa;
            color: var(--primary-color);
        }

        .bg-success-light {
            background-color: #e6faf3;
            color: var(--success-color);
        }

        .search-container {
            position: relative;
        }

        .search-container .search-input {
            padding-left: 40px;
            border-radius: 20px;
        }

        .search-container i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #6c757d;
        }

        .filter-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .member-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }

        .badge-status {
            padding: 0.5em 0.8em;
            border-radius: 20px;
            font-size: 0.75em;
        }

        .table-responsive {
            border-radius: 10px;
    overflow-x: auto;
    overflow-y: auto;
    max-height: 400px;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #6e707e;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .export-btn {
            background-color: var(--success-color);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
        }

        .export-btn:hover {
            background-color: #17a673;
        }
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

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .stats-number {
                font-size: 1.5rem;
            }

            .search-form {
                margin-top: 15px;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .member-img {
                width: 35px;
                height: 35px;
            }
            .quick-access-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <?php include("navegacao.php") ?>
    </nav>

    <div class="container-fluid" style="margin-top: 73px;">
     <div class="welcome-message">
             <div class="profile-icon-container">
       <a href="#" class="profile-link view-member" data-id="<?php echo $id_membro; ?>">
    <img src="<?php echo $foto_perfil; ?>" alt="Perfil" class="profile-icon">
</a>
    </div>
            <h1><?php echo __('bem_vindo') ?> <?php echo $logado; ?>!</h1>
            <p><?php echo __('seu_login') ?> <?php echo $perfil; ?></p>
            <p><?php echo __('hoje') ?> <?php echo date('d/m/Y'); ?></p>
        </div>

       <div class="card card-dashboard">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Dados Pessoais</h5>
                </div>
                <div class="card-body">
    <div class="table-responsive" style="max-height:400px; overflow-y:auto;">
        <table id="tabelaMembros" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?php echo __('foto') ?></th>
                                    <th><?php echo __('nome') ?></th>
                                    <th>Email</th>
                                    <th><?php echo __('telefone') ?></th>
                                    <th><?php echo __('membrodesde') ?></th>
                                    <th><?php echo __('status') ?></th>
                                    <th><?php echo __('acoes') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($resultlist && $resultlist->num_rows > 0) {
                                    while ($user_data = mysqli_fetch_assoc($resultlist)) {
                                        // Garantir valores padrão para evitar colunas quebradas
                                        $foto = !empty($user_data['foto']) ? $user_data['foto'] : '';
                                        $nome = $user_data['nome'] ?? '';
                                        $sobrenome = $user_data['sobrenome'] ?? '';
                                        $email = $user_data['email'] ?? '-';
                                        $telefone = $user_data['telefone'] ?? '-';
                                        $datas = !empty($user_data['datas']) ? date('d/m/Y', strtotime($user_data['datas'])) : '-';
                                        $status = $user_data['status'] ?? 'indefinido';
                                        $status_class = ($status === 'ativo') ? 'bg-success' : 'bg-secondary';

                                        echo "<tr>";
                                        $foto = !empty($user_data['foto']) ? $user_data['foto'] : './img/fotoescala/semfoto.png';

                                        echo "<td>
                                         <img class='member-img' src='{$foto}' 
                                             onerror=\"this.src='./img/fotoescala/semfoto.png'\">
                                            </td>";
                                        echo "<td>" . trim($nome . " " . $sobrenome) . "</td>";
                                        echo "<td>{$email}</td>";
                                        echo "<td>{$telefone}</td>";
                                        echo "<td>{$datas}</td>";
                                        echo "<td><span class='badge {$status_class} badge-status'>{$status}</span></td>";
                                        echo "<td>
                <div class='btn-group'>
                    <a class='btn btn-sm btn-primary action-btn' href='edit_formularioVol.php?id={$user_data['id']}' title='Editar'>
                        <i class='fas fa-edit'></i>
                    </a>
                    <button class='btn btn-sm btn-info action-btn view-member' data-id='{$user_data['id']}' title='Visualizar'>
                        <i class='fas fa-eye'></i>
                    </button>
                </div>
              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    // Colspan deve ser 7 porque existem 7 colunas no thead
                                    echo "<tr><td colspan='7' class='text-center py-4'>Nenhum membro encontrado. Tente alterar os filtros de pesquisa.</td></tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        
    </div>
    </div>

    <!-- Member Detail Modal -->
    <div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Membro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="memberDetails">
                    <!-- Details will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="#" id="editMemberBtn" class="btn btn-primary">Editar</a>
                </div>
            </div>
        </div>
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
        <a href="alterarfoto.php?id=<?php echo $id_acesso; ?>" class="quick-access-item">
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
<?php
$icone = $icones_funcoes[$funcao] ?? 'fa-user';
?>

<i class="fas <?php echo $icone; ?>"></i>
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
          <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            

            // View member details
            $(document).on('click', '.view-member', function () {
                var memberId = $(this).data('id');

                // Show loading spinner
                $('#memberDetails').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-2">Carregando informações do membro...</p>
                    </div>
                `);

                // Show the modal
                $('#memberModal').modal('show');

                // AJAX call to get member details
                $.ajax({
                    url: 'getMemberDetailsVol.php',
                    type: 'GET',
                    data: { id: memberId },
                    success: function (response) {
                        $('#memberDetails').html(response);
                        $('#editMemberBtn').attr('href', 'edit_formularioVol.php?id=' + memberId);
                    },
                    error: function () {
                        $('#memberDetails').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Erro ao carregar os dados do membro. Tente novamente.
                            </div>
                        `);
                    }
                });
            });

            // Auto logout after 1 hour
            setTimeout(() => {
                window.location.href = "sistema.php?timeout=1";
            }, 3600000);
        });
    </script>
            
</body>

</html>