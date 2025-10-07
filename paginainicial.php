<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if((!isset($_SESSION['usuario']) == true) && ($_SESSION['senha']) == true) {
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
?> <!-- Mensagem de boas-vindas -->
<style>
    /* CSS anterior mantido */

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

    /* Responsive Styles para cards */
    @media (max-width: 768px) {
        .dashboard-cards {
            grid-template-columns: 1fr;
        }
        
        .quick-access-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
        <div class="welcome-message">
            <h1>Bem-vindo(a), <?php echo $logado; ?>!</h1>
            <p>Seu perfil de acesso: <?php echo $perfil; ?></p>
            <p>Hoje é <?php echo date('d/m/Y'); ?></p>
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