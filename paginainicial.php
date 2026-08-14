<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
ini_set('display_errors', 1);
include_once('config.php');
include_once('config_language.php');

if(!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}

$logado = $_SESSION['usuario'];
$perfil = $_SESSION['nivel_acesso'];
$igreja = $_SESSION['igreja_id'];
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

function contarAniversariantesMes($conexao, $igreja) {
    try {
        $mesAtual = date('m');

        $sql = "SELECT COUNT(id) as total 
                FROM membros 
                WHERE MONTH(nascimento) = '$mesAtual'
                AND igreja_id = '$igreja'";

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
            'stats' => contarAniversariantesMes($conexao, $igreja),
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
            'stats' => contarAniversariantesMes($conexao, $igreja),
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
        AND igreja_id = '$igreja'
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="sistemastyle.css?t=<?=time()?>">
    
</head>
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
            <!-- <div class="profile-icon-container">
        <a href="perfil.php" class="profile-link">
     <img src="<?php echo $foto_perfil; ?>" alt="Perfil" class="profile-icon">
        </a>
    </div> -->
            <h1><?php echo __('bem_vindo') ?> <?php echo $logado; ?>!</h1>
            <p><?php echo __('seu_login') ?> <?php echo $perfil; ?></p>
            <p><?php echo __('hoje') ?> <?php echo date('d/m/Y'); ?></p>
        </div>

<!-- Container do vídeo responsivo -->
        <div class="video-container">
            <video class="video-voluntarios" controls loop muted playsinline autoplay>
                <source src="sabedoria.mp4" type="video/mp4">
                
                <?php __('errdevideo') ?>
            </video>
        </div>

        <div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0"><?php echo __('aniveriantesde') ?> <?php echo $meses[$mes]; ?></h5>
        
        <p class="text-muted">Role horizontalmente para ver mais</p>
    </div>
    <div class="card-body">
        <?php if ($resultlist && $resultlist->num_rows > 0): ?>
            <!-- Container com scroll horizontal -->
            <div class="birthday-scroll-container">
                <div class="birthday-scroll-wrapper">
                    <?php while($user_data = mysqli_fetch_assoc($resultlist)): ?>
                        <div class="birthday-card">
                            <div class="birthday-card-inner">
                                <!-- Foto ou avatar -->
                                <div class="birthday-avatar">
                                    <?php 
                                    $foto = !empty($user_data['foto']) ? $user_data['foto'] : '';
                                    if (!empty($foto)): 
                                    ?>
                                        <img src="<?php echo $foto; ?>" alt="<?php echo $user_data['nome']; ?>" 
                                             class="birthday-photo" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><rect width=\"100\" height=\"100\" fill=\"%233498db\"/><text x=\"50\" y=\"55\" font-size=\"40\" text-anchor=\"middle\" fill=\"white\" font-family=\"Arial\">
                                    <?php else: 
                                        $iniciais = substr($user_data['nome'], 0, 1) . substr($user_data['sobrenome'], 0, 1);
                                    ?>
                                        <div class="birthday-initials">
                                            <?php echo $iniciais; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Elemento decorativo de aniversário -->
                                    <div class="birthday-decoration">
                                        <i class="fas fa-birthday-cake"></i>
                                    </div>
                                </div>
                                
                                <!-- Informações -->
                                <div class="birthday-info">
                                    <h6 class="birthday-name" title="<?php echo htmlspecialchars($user_data['nome'] . ' ' . $user_data['sobrenome']); ?>">
                                        <?php echo htmlspecialchars($user_data['nome']); ?>
                                    </h6>
                                    <div class="birthday-date">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <span><?php echo date('d/m', strtotime($user_data['nascimento'])); ?></span>
                                    </div>
                                    
                                    <!-- Dia da semana do aniversário (opcional) -->
                                    <?php 
                                    $dataNascimento = date_create($user_data['nascimento']);
                                    $diaSemana = date_format($dataNascimento, 'w');
                                    $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
                                    ?>
                                   
                                    
                                    <!-- Indicador se é hoje (opcional) -->
                                    <?php if (date('m-d') == date('m-d', strtotime($user_data['nascimento']))): ?>
                                        <span class="birthday-today-badge">Hoje!</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Navegação do scroll (opcional) -->
                <div class="scroll-navigation">
                    <button class="scroll-btn scroll-prev" aria-label="Anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="scroll-indicator"></div>
                    <button class="scroll-btn scroll-next" aria-label="Próximo">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <!-- Estado vazio -->
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon mb-3">
                    <i class="fas fa-birthday-cake fa-3x text-muted"></i>
                </div>
                <h5 class="empty-state-title">Nenhum aniversariante encontrado</h5>
                <p class="empty-state-subtitle text-muted">Não há aniversariantes para o mês selecionado.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// JavaScript para controle do scroll horizontal
document.addEventListener('DOMContentLoaded', function() {
    const scrollWrapper = document.querySelector('.birthday-scroll-wrapper');
    const scrollPrev = document.querySelector('.scroll-prev');
    const scrollNext = document.querySelector('.scroll-next');
    const scrollIndicator = document.querySelector('.scroll-indicator');
    
    if (!scrollWrapper) return;
    
    // Atualiza o indicador de scroll
    function updateScrollIndicator() {
        const scrollWidth = scrollWrapper.scrollWidth - scrollWrapper.clientWidth;
        const scrollLeft = scrollWrapper.scrollLeft;
        const percentage = scrollWidth > 0 ? Math.round((scrollLeft / scrollWidth) * 100) : 0;
        
        if (scrollIndicator) {
            scrollIndicator.textContent = `${percentage}%`;
        }
        
        // Habilita/desabilita botões
        if (scrollPrev) {
            scrollPrev.disabled = scrollLeft <= 0;
        }
        if (scrollNext) {
            scrollNext.disabled = scrollLeft >= scrollWidth - 1;
        }
    }
    
    // Configura botões de navegação
    if (scrollPrev) {
        scrollPrev.addEventListener('click', () => {
            scrollWrapper.scrollBy({ left: -200, behavior: 'smooth' });
        });
    }
    
    if (scrollNext) {
        scrollNext.addEventListener('click', () => {
            scrollWrapper.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }
    
    // Atualiza indicador durante o scroll
    scrollWrapper.addEventListener('scroll', updateScrollIndicator);
    
    // Atualiza indicador ao redimensionar a janela
    window.addEventListener('resize', updateScrollIndicator);
    
    // Inicializa indicador
    updateScrollIndicator();
    
    // Adiciona navegação por arrasto (drag)
    let isDown = false;
    let startX;
    let scrollLeft;
    
    scrollWrapper.addEventListener('mousedown', (e) => {
        isDown = true;
        scrollWrapper.classList.add('active');
        startX = e.pageX - scrollWrapper.offsetLeft;
        scrollLeft = scrollWrapper.scrollLeft;
    });
    
    scrollWrapper.addEventListener('mouseleave', () => {
        isDown = false;
        scrollWrapper.classList.remove('active');
    });
    
    scrollWrapper.addEventListener('mouseup', () => {
        isDown = false;
        scrollWrapper.classList.remove('active');
    });
    
    scrollWrapper.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - scrollWrapper.offsetLeft;
        const walk = (x - startX) * 2; // Velocidade do scroll
        scrollWrapper.scrollLeft = scrollLeft - walk;
    });
    
    // Suporte para touch em dispositivos móveis
    scrollWrapper.addEventListener('touchstart', (e) => {
        startX = e.touches[0].pageX - scrollWrapper.offsetLeft;
        scrollLeft = scrollWrapper.scrollLeft;
    });
    
    scrollWrapper.addEventListener('touchmove', (e) => {
        const x = e.touches[0].pageX - scrollWrapper.offsetLeft;
        const walk = (x - startX) * 2;
        scrollWrapper.scrollLeft = scrollLeft - walk;
    });
});
</script>
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
                    if ($count >= 1) break;
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
                <a href="consultaescala.php" class="quick-access-item">
                    <div class="quick-access-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="quick-access-text">Consultar Escalas</div>
                </a>
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