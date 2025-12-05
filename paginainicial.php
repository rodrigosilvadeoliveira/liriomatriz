<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
ini_set('display_errors', 1);
include_once('config.php');

if(!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}

$logado = $_SESSION['usuario'];
$perfil = $_SESSION['nivel_acesso'];
$resultlist = null;

// VERIFICAR SE A CONEXÃO FUNCIONA
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

// Funções para contar (adaptadas para mysqli)
function contarEscalasAtivas($conexao) {
    try {
        $sql = "SELECT COUNT(id) as total FROM escalas_louvor";
        $result = $conexao->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Erro ao contar escalas: " . $e->getMessage());
        return 0;
    }
}

function contarEscalasAtivasSom($conexao) {
    try {
        $sql = "SELECT COUNT(id) as total FROM escalas_som";
        $result = $conexao->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Erro ao contar escalas som: " . $e->getMessage());
        return 0;
    }
}

function contarEscalasAtivasMidias($conexao) {
    try {
        $sql = "SELECT COUNT(id) as total FROM escalas_midias";
        $result = $conexao->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Erro ao contar escalas midias: " . $e->getMessage());
        return 0;
    }
}

function contarAniversariantesMes($conexao) {
    try {
        $mesAtual = date('m');
        $sql = "SELECT COUNT(id) as total FROM membros WHERE MONTH(nascimento) = '$mesAtual'";
        $result = $conexao->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Erro ao contar aniversariantes: " . $e->getMessage());
        return 0;
    }
}

function contarNovosMembros($conexao) {
    try {
        $dataLimite = date('Y-m-d', strtotime('-30 days'));
        $sql = "SELECT COUNT(id) as total FROM membros WHERE data_cadastro >= '$dataLimite'";
        $result = $conexao->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Erro ao contar novos membros: " . $e->getMessage());
        return 0;
    }
}

// Consulta total de membros
try {
    $sqlTotal = "SELECT COUNT(*) as total FROM membros";
    $resultTotal = $conexao->query($sqlTotal);
    if ($resultTotal) {
        $rowTotal = $resultTotal->fetch_assoc();
    } else {
        $rowTotal = ['total' => 0];
    }
} catch (Exception $e) {
    $rowTotal = ['total' => 0];
}

// Definir cards do dashboard baseados no perfil
$dashboardCards = [];

if ($perfil === 'master') {
    $dashboardCards = [
        [
            'icon' => 'fa-users',
            'title' => 'Total de Membros',
            'content' => 'Visualize o número total de membros cadastrados no sistema.',
            'link' => 'consulta_membros',
            'stats' => $rowTotal['total'],
            'color' => '#3498db'
        ],
        [
            'icon' => 'fa-calendar-alt',
            'title' => 'Próximos Eventos',
            'content' => 'Confira os próximos eventos programados na igreja.',
            'link' => 'cadastroEvento',
            'stats' => '7',
            'color' => '#e74c3c'
        ],
        [
            'icon' => 'fa-chart-line',
            'title' => 'Relatórios',
            'content' => 'Acesse relatórios gerenciais e estatísticas do sistema.',
            'link' => 'listar_relatoriosdep',
            'stats' => '24',
            'color' => '#2ecc71'
        ],
        [
            'icon' => 'fa-music',
            'title' => 'Escalas Ativas',
            'content' => 'Visualize todas as escalas de louvor ativas no momento.',
            'link' => 'consultaescala',
            'stats' => contarEscalasAtivas($conexao),
            'color' => '#9b59b6'
        ],
        [
            'icon' => 'fa-user-plus',
            'title' => 'Novos Membros',
            'content' => 'Membros cadastrados nos últimos 30 dias.',
            'link' => 'consulta_membros',
            'stats' => contarNovosMembros($conexao),
            'color' => '#f39c12'
        ],
        [
            'icon' => 'fa-birthday-cake',
            'title' => 'Aniversariantes',
            'content' => 'Membros que fazem aniversário este mês.',
            'link' => 'consulta_niver',
            'stats' => contarAniversariantesMes($conexao),
            'color' => '#e74c3c'
        ],
        [
            'icon' => 'fa-exclamation-triangle',
            'title' => 'Alertas',
            'content' => 'Verifique alertas e necessidades de atenção no sistema.',
            'link' => 'consulta_logs',
            'stats' => '3',
            'color' => '#e67e22'
        ]
    ];
} elseif ($perfil === 'secretaria') {
    $dashboardCards = [
        [
            'icon' => 'fa-users',
            'title' => 'Total de Membros',
            'content' => 'Visualize o número total de membros cadastrados no sistema.',
            'link' => 'consulta_membros',
            'stats' => $rowTotal['total'],
            'color' => '#3498db'
        ],
        [
            'icon' => 'fa-user-plus',
            'title' => 'Novos Membros',
            'content' => 'Membros cadastrados nos últimos 30 dias.',
            'link' => 'consulta_membros',
            'stats' => contarNovosMembros($conexao),
            'color' => '#2ecc71'
        ],
        [
            'icon' => 'fa-birthday-cake',
            'title' => 'Aniversariantes',
            'content' => 'Membros que fazem aniversário este mês.',
            'link' => 'consulta_niver',
            'stats' => contarAniversariantesMes($conexao),
            'color' => '#e74c3c'
        ],
        [
            'icon' => 'fa-file-alt',
            'title' => 'Relatórios',
            'content' => 'Relatórios departamentais disponíveis.',
            'link' => 'listar_relatoriosdep',
            'stats' => '12',
            'color' => '#9b59b6'
        ]
    ];
}
// ... adicione os outros perfis conforme necessário

// Atividades Recentes (exemplo)
$atividadesRecentes = [
    ['tipo' => 'novo_membro', 'nome' => 'João Silva', 'data' => date('Y-m-d H:i:s', strtotime('-2 hours')), 'descricao' => null],
    ['tipo' => 'escala_atualizada', 'nome' => null, 'data' => date('Y-m-d H:i:s', strtotime('-1 day')), 'descricao' => 'Escala para o culto de domingo'],
    ['tipo' => 'relatorio_gerado', 'nome' => 'Departamento de Jovens', 'data' => date('Y-m-d H:i:s', strtotime('-3 days')), 'descricao' => null]
];

// Funções auxiliares (mantidas iguais)
function tempoDecorrido($data) {
    $agora = new DateTime();
    $dataAtividade = new DateTime($data);
    $diferenca = $agora->diff($dataAtividade);
    
    if ($diferenca->y > 0) {
        return "há " . $diferenca->y . " ano" . ($diferenca->y > 1 ? "s" : "");
    } elseif ($diferenca->m > 0) {
        return "há " . $diferenca->m . " mês" . ($diferenca->m > 1 ? "es" : "");
    } elseif ($diferenca->d > 0) {
        return "há " . $diferenca->d . " dia" . ($diferenca->d > 1 ? "s" : "");
    } elseif ($diferenca->h > 0) {
        return "há " . $diferenca->h . " hora" . ($diferenca->h > 1 ? "s" : "");
    } elseif ($diferenca->i > 0) {
        return "há " . $diferenca->i . " minuto" . ($diferenca->i > 1 ? "s" : "");
    } else {
        return "agora mesmo";
    }
}

function obterInfoAtividade($tipo) {
    switch ($tipo) {
        case 'novo_membro':
            return ['icon' => 'fa-user-plus', 'title' => 'Novo membro cadastrado'];
        case 'escala_atualizada':
            return ['icon' => 'fa-music', 'title' => 'Escala de louvor atualizada'];
        case 'relatorio_gerado':
            return ['icon' => 'fa-file-alt', 'title' => 'Relatório gerado'];
        default:
            return ['icon' => 'fa-info-circle', 'title' => 'Atividade no sistema'];
    }
}

$pageTitle = "Sistema Igreja - Home";
include('navegacao.php');

$data_login = date('Y-m-d'); // formato: 2025-04-21
$hora_login = date('H:i:s'); // formato: 14:30:05
$pagina = basename($_SERVER['PHP_SELF']); // pega o nome do arquivo atual, ex: membros.php

// Busca o nome e o nível de acesso do usuário atual
$usuario = $_SESSION['usuario'];
$nivel_acesso = $_SESSION['nivel_acesso'] ?? 'desconhecido';

$resultlist = null;

// Pega o mês selecionado
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');

// Consulta aniversariantes do mês
$sql = "SELECT id, nome, sobrenome, nascimento, email, telefone, foto, status 
        FROM membros 
        WHERE MONTH(nascimento) = $mes
        ORDER BY DAY(nascimento) ASC";
$resultlist = $conexao->query($sql);

// Array de meses para exibição
$meses = [
    1=>"Janeiro",2=>"Fevereiro",3=>"Março",4=>"Abril",5=>"Maio",6=>"Junho",
    7=>"Julho",8=>"Agosto",9=>"Setembro",10=>"Outubro",11=>"Novembro",12=>"Dezembro"
];
// (opcional) Se quiser pegar também o nome do usuário na tabela de usuários:
$stmtUser = $conexao->prepare("SELECT nome FROM cadastroadm WHERE usuario = ?");
$stmtUser->bind_param("s", $usuario);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$rowUser = $resultUser->fetch_assoc();
$nome_usuario = $rowUser['nome'] ?? $usuario; // se não encontrar, usa o login mesmo

// Inserir o registro no log
$logSql = "INSERT INTO log_login (usuario, nome, nivel_acesso, data_login, hora_login, acao)
           VALUES (?, ?, ?, ?, ?, ?)";
$logStmt = $conexao->prepare($logSql);
$logStmt->bind_param("ssssss", $usuario, $nome_usuario, $nivel_acesso, $data_login, $hora_login, $pagina);
$logStmt->execute();
$logStmt->close();

$bannerSql = "SELECT imagem, links FROM evento WHERE cartaz = 'avisos' ORDER BY id DESC LIMIT 1";
$bannerResult = $conexao->query($bannerSql);
$bannerData = $bannerResult->num_rows > 0 ? $bannerResult->fetch_assoc() : null;
$bannerAtual = $bannerData ? $bannerData['imagem'] : null;
$linkBanner = $bannerData ? $bannerData['links'] : null;

// Verifica se deve mostrar o banner
$mostrarBanner = isset($_SESSION['mostrar_banner_login']) && $_SESSION['mostrar_banner_login'];
$imagemBanner = isset($_SESSION['banner_imagem']) ? $_SESSION['banner_imagem'] : $bannerAtual;
?>
 <!-- Mensagem de boas-vindas -->
<style>
    .banner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .banner-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            text-align: center;
        }
        
        .banner-image {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5);
        }
        
        .close-banner {
            position: absolute;
            top: -15px;
            right: -15px;
            background: #e74c3c;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }
        
        .close-banner:hover {
            background: #c0392b;
        }
        
        .hidden {
            display: none;
        }
        
        .banner-message {
            color: white;
            margin-top: 15px;
            font-size: 14px;
        }

    /* Dashboard Cards */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
    }

    .card-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 1.5rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--secondary-color);
    }

    .card-content {
        color: #666;
        margin-bottom: 15px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .card-link:hover {
        text-decoration: underline;
    }

    .card-stats {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--secondary-color);
    }

    /* Quick Access */
    .quick-access {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: var(--secondary-color);
        padding-bottom: 10px;
        border-bottom: 2px solid var(--light-color);
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
        color: var(--text-color);
        transition: var(--transition);
    }

    .quick-access-item:hover {
        background: var(--primary-color);
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

    /* Recent Activity */
    .recent-activity {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: var(--shadow);
    }

    .activity-list {
        list-style: none;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        background: var(--light-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: var(--primary-color);
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .activity-desc {
        color: #666;
        margin-bottom: 5px;
    }

    .activity-time {
        font-size: 0.85rem;
        color: #888;
    }
     .video-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            margin: 0 auto 3rem;
            padding: 0 1rem;
        }
        
        /* Vídeo responsivo */
        .video-voluntarios {
            width: 100%;
            height: auto;
            aspect-ratio: 16/9;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: block;
        }
        
        .member-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
        
        .card { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); margin-bottom: 1.5rem; }
        .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
        .sidebar { background-color: #4e73df; color: white; height: 100vh; position: fixed; top: 73px; left: 0; width: 250px; padding: 20px; }
        .main-content { margin-left: 0px; padding: 20px; margin-top: 73px; }
        .navbar-brand { font-weight: 700; }
        .alert-primary { background-color: #e8f4ff; border-color: #b3d9ff; color: #0066cc; }
        .table th { border-top: none; font-weight: 600; color: #4e73df; }
        .empty-state { text-align: center; padding: 2rem; color: #6c757d; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; color: #dee2e6; }
    /* Responsive Styles para cards */
    @media (max-width: 768px) {
        .dashboard-cards {
            grid-template-columns: 1fr;
        }
        
        .quick-access-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .video-container {
                margin-bottom: 2rem;
            }
    }
</style>
<div id="bannerModal" class="banner-overlay <?php echo !$mostrarBanner || empty($imagemBanner) ? 'hidden' : ''; ?>">
    <div class="banner-content">
        <button class="close-banner" onclick="fecharBanner()">×</button>
        <?php if (!empty($imagemBanner)): ?>
            <?php if (!empty($linkBanner)): ?>
                <a href="<?php echo htmlspecialchars($linkBanner); ?>" target="_blank" style="display: block;">
                    <img src="<?php echo htmlspecialchars($imagemBanner); ?>" alt="Aviso" class="banner-image">
                </a>
            <?php else: ?>
                <img src="<?php echo htmlspecialchars($imagemBanner); ?>" alt="Aviso" class="banner-image">
            <?php endif; ?>
        <?php else: ?>
            <div style="color: white; padding: 20px;">
                <p>Nenhum aviso encontrado</p>
                <button onclick="fecharBanner()">Fechar</button>
            </div>
        <?php endif; ?>
        <div class="banner-message">
            <?php if (!empty($linkBanner)): ?>
                Clique na imagem para acessar o link | Clique fora ou pressione ESC para fechar
            <?php else: ?>
                Clique fora da imagem ou pressione ESC para fechar
            <?php endif; ?>
        </div>
    </div>
</div>
    <script>
        function fecharBanner() {
            // Esconde o banner
            document.getElementById('bannerModal').classList.add('hidden');
            
            // Remove a sessão via AJAX para não mostrar novamente no refresh
            fetch('remover_banner_session.php')
                .catch(error => console.error('Erro:', error));
        }
        
        // Fechar com ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                fecharBanner();
            }
        });
        
        // Fechar clicando fora da imagem
        document.getElementById('bannerModal').addEventListener('click', function(event) {
            if (event.target === this) {
                fecharBanner();
            }
        });
        
        // Fecha automaticamente após 15 segundos (opcional)
        setTimeout(fecharBanner, 15000);
    </script>
        <div class="welcome-message">
            <h1>Bem-vindo(a), <?php echo $logado; ?>!</h1>
            <p>Seu perfil de acesso: <?php echo $perfil; ?></p>
            <p>Hoje é <?php echo date('d/m/Y'); ?></p>
        </div>

<!-- Container do vídeo responsivo -->
        <div class="video-container">
            <video class="video-voluntarios" controls loop muted playsinline autoplay>
                <source src="voluntariado.mp4" type="video/mp4">
                <source src="voluntariado.webm" type="video/webm">
                Seu navegador não suporta a tag de vídeo.
            </video>
        </div>

        <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Aniversariantes de <?php echo $meses[$mes]; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaAniversariantes" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nome</th>
                                        <th>Dia aniversario</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($resultlist && $resultlist->num_rows > 0) {
                                        while($user_data = mysqli_fetch_assoc($resultlist)) {
                                            echo "<tr>";
                                            $foto = !empty($user_data['foto']) ? 'uploads/'.$user_data['foto'] : '';
                                            if (!empty($foto)) {
                                                echo "<td><img class='member-img' src='$foto' alt='Foto'></td>";
                                            } else {
                                                $iniciais = substr($user_data['nome'], 0, 1) . substr($user_data['sobrenome'], 0, 1);
                                                echo "<td><div class='member-img bg-primary text-white d-flex align-items-center justify-content-center'>$iniciais</div></td>";
                                            }
                                            echo "<td style='text-align: center'>".$user_data['nome']."</td>";
                                            // echo "<td>".$user_data['nome']." ".$user_data['sobrenome']."</td>";
                                           
                                            echo "<td style='text-align: center'>".date('d/m', strtotime($user_data['nascimento']))."</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center py-4'>";
                                        echo "<div class='empty-state'>";
                                        echo "<i class='fas fa-birthday-cake'></i>";
                                        echo "<h5>Nenhum aniversariante encontrado</h5>";
                                        echo "<p>Não há aniversariantes para o mês selecionado.</p>";
                                        echo "</div>";
                                        echo "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
        <!-- Dashboard com cards informativos -->
        <div class="dashboard-cards">
            <?php foreach ($dashboardCards as $card): ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: <?php echo $card['color']; ?>">
                        <i class="fas <?php echo $card['icon']; ?>"></i>
                    </div>
                    <div class="card-title"><?php echo $card['title']; ?></div>
                </div>
                <div class="card-content">
                    <?php echo $card['content']; ?>
                </div>
                <div class="card-footer">
                    <a href="<?php echo $card['link']; ?>" class="card-link">Acessar <i class="fas fa-arrow-right"></i></a>
                    <?php if (!empty($card['stats'])): ?>
                    <div class="card-stats"><?php echo $card['stats']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

                <!-- Tabela -->
                

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelaAniversariantes').DataTable({
                responsive: true,
                autoWidth: false, // Corrige problema de colunas
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
                },
                order: [[4, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
            });
        });
    </script>
        <!-- Acesso Rápido -->
        <div class="quick-access">
            <h2 class="section-title">Acesso Rápido</h2>
            <div class="quick-access-grid">
                <?php 
                // Mostrar apenas os 6 primeiros itens do menu para acesso rápido
                $count = 0;
                foreach ($menuOptions as $option): 
                    if ($count >= 6) break;
                    if ($option['url'] != 'sair'):
                ?>
                <a href="<?php echo $option['url']; ?>" class="quick-access-item">
                    <div class="quick-access-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="quick-access-text"><?php echo $option['title']; ?></div>
                </a>
                <?php 
                    $count++;
                    endif;
                endforeach; 
                ?>
            </div>
        </div>

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