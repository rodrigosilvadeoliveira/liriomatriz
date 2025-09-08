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
$resultlist = null;
// Definir opções de menu baseadas no perfil
$menuOptions = [];

if ($perfil === 'master') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => 'Cadastro Acesso', 'url' => 'formularioMaster'],
        ['title' => 'Cadastro Vendas', 'url' => 'cadastrodevendas'],
        ['title' => 'Log', 'url' => 'consulta_logs'],
        ['title' => 'Consulta Acessos', 'url' => 'consultaacessos'],
        ['title' => 'Novo Membro(a)', 'url' => 'cadastroMembrosAdm'],
        ['title' => 'Consultar Membros', 'url' => 'consulta_membros'],
        ['title' => 'Aniversariantes', 'url' => 'consulta_niver'],
        ['title' => 'Inscrições', 'url' => 'cadastroForm.php'],
        ['title' => 'Cadastrar Relatórios', 'url' => 'relatoriodepartamento'],
        ['title' => 'Consultar Relatórios', 'url' => 'listar_relatoriosdep'],
        ['title' => 'Site imagens', 'url' => 'cadastroEvento'],
        ['title' => 'Site Live', 'url' => 'cadastrolive'],
        ['title' => 'Cadastro Voluntario', 'url' => 'cadastrovoluntariadoescala'],
        ['title' => 'Consulta Voluntario', 'url' => 'consulta_voluntariado'],
        ['title' => 'Escala Louvor Igreja', 'url' => 'escalalouvor'],
        ['title' => 'Escala Louvor Kids', 'url' => 'escalalouvorkids'],
        ['title' => 'Escala Louvor GC Homens', 'url' => 'escalalouvorhomens'],
        ['title' => 'Escala Louvor GC Mulheres', 'url' => 'escalalouvormulheres'],
        ['title' => 'Consultar Escala Louvor', 'url' => 'consultaescala'],
        ['title' => 'Escala Midias', 'url' => 'escalamidias'],
        ['title' => 'Consultar Escala Midias', 'url' => 'consultaescalamidias'],
        ['title' => 'Escala Som', 'url' => 'escalasom'],
        ['title' => 'Consultar Escala Som', 'url' => 'consultaescalasom'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} elseif ($perfil === 'secretaria') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => 'Novo Membro(a)', 'url' => 'cadastroMembrosAdm'],
        ['title' => 'Consultar Membros', 'url' => 'consulta_membros'],
        ['title' => 'Aniversariantes', 'url' => 'consulta_niver'],
        ['title' => 'Inscrições', 'url' => 'cadastroForm.php'],
        ['title' => 'Cadastrar Relatórios', 'url' => 'relatoriodepartamento'],
        ['title' => 'Consultar Relatórios', 'url' => 'listar_relatoriosdep'],
        ['title' => 'Site imagens', 'url' => 'cadastroEvento'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} elseif ($perfil === 'midia') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => 'Site imagens', 'url' => 'cadastroEvento'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} elseif ($perfil === 'live') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => 'Site Live', 'url' => 'cadastrolive'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} elseif ($perfil === 'lider') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => 'Cadastro Acesso', 'url' => 'formulariolider'],
        ['title' => 'Cadastro Voluntario', 'url' => 'cadastrovoluntariadoescala'],
        ['title' => 'Escala Louvor Igreja', 'url' => 'escalalouvor'],
        ['title' => 'Escala Louvor Kids', 'url' => 'escalalouvorkids'],
        ['title' => 'Escala Louvor GC Homens', 'url' => 'escalalouvorhomens'],
        ['title' => 'Escala Louvor GC Mulheres', 'url' => 'escalalouvormulheres'],
        ['title' => 'Consultar Escala Louvor', 'url' => 'consultaescala'],
        ['title' => 'Escala Midias', 'url' => 'escalamidias'],
        ['title' => 'Consultar Escala Midias', 'url' => 'consultaescalamidias'],
        ['title' => 'Escala Som', 'url' => 'escalasom'],
        ['title' => 'Consultar Escala Som', 'url' => 'consultaescalasom'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} elseif ($perfil === 'consulta') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => 'Consultar Escala Louvor', 'url' => 'consultaescalavol'],
        ['title' => 'Consultar Escala Midias', 'url' => 'consultaescalamidiasvol'],
        ['title' => 'Consultar Escala Som', 'url' => 'consultaescalasomvol'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} else {
    // Perfil padrão caso não se encaixe em nenhum dos anteriores
    $menuOptions = [
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
}

// Agrupar opções por categoria para melhor organização
$categorizedOptions = [
    'Administração' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['formularioMaster', 'formulariolider', 'cadastrodevendas', 'consulta_logs', 'consultaacessos']);
    }),
    'Membros' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastroMembrosAdm', 'consulta_membros', 'consulta_niver']);
    }),
    'Voluntario' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastrovoluntariadoescala','consulta_voluntariado']);
    }),
    'Escala Louvor' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escalalouvor','escalalouvorkids', 'escalalouvorhomens', 'escalalouvormulheres','consultaescala','consultaescalavol']);
    }),
    'Escala Midias' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], [ 'escalamidias', 'consultaescalamidias', 'consultaescalamidiasvol']);
    }),
    'Escala Som' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escalasom', 'consultaescalasom', 'consultaescalasomvol']);
    }),
    'Relatórios' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['relatoriodepartamento', 'listar_relatoriosdep']);
    }),
    'Site' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastroEvento', 'cadastrolive']);
    }),
    'Geral' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastroForm.php', 'sair']);
    })
];


                                            $sqlTotal = "SELECT COUNT(*) as total FROM membros";
                                            $resultTotal = $conexao->query($sqlTotal);
                                            $rowTotal = $resultTotal->fetch_assoc();
                                            
                                            
// Remover categorias vazias
$categorizedOptions = array_filter($categorizedOptions);

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
            'stats' => '5',
            'color' => '#9b59b6'
        ],
        [
            'icon' => 'fa-user-plus',
            'title' => 'Novos Membros',
            'content' => 'Membros cadastrados nos últimos 30 dias.',
            'link' => 'consulta_membros',
            'stats' => '28',
            'color' => '#f39c12'
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
            'stats' => '1.245',
            'color' => '#3498db'
        ],
        [
            'icon' => 'fa-user-plus',
            'title' => 'Novos Membros',
            'content' => 'Membros cadastrados nos últimos 30 dias.',
            'link' => 'consulta_membros',
            'stats' => '28',
            'color' => '#2ecc71'
        ],
        [
            'icon' => 'fa-birthday-cake',
            'title' => 'Aniversariantes',
            'content' => 'Membros que fazem aniversário este mês.',
            'link' => 'consulta_niver',
            'stats' => '15',
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
} elseif ($perfil === 'midia' || $perfil === 'live') {
    $dashboardCards = [
        [
            'icon' => 'fa-video',
            'title' => 'Lives Realizadas',
            'content' => 'Transmissões ao vivo realizadas este mês.',
            'link' => 'cadastrolive',
            'stats' => '12',
            'color' => '#e74c3c'
        ],
        [
            'icon' => 'fa-eye',
            'title' => 'Visualizações',
            'content' => 'Média de visualizações por conteúdo.',
            'link' => 'cadastroEvento',
            'stats' => '2.4K',
            'color' => '#2ecc71'
        ]
    ];
} elseif ($perfil === 'lider') {
    $dashboardCards = [
        [
            'icon' => 'fa-music',
            'title' => 'Escalas de Louvor',
            'content' => 'Escalas de louvor cadastradas no sistema.',
            'link' => 'consultaescala',
            'stats' => '8',
            'color' => '#3498db'
        ],
        [
            'icon' => 'fa-microphone',
            'title' => 'Escalas de Som',
            'content' => 'Escalas de técnicos de som ativas.',
            'link' => 'consultaescalasom',
            'stats' => '5',
            'color' => '#e74c3c'
        ],
        [
            'icon' => 'fa-photo-video',
            'title' => 'Escalas de Mídia',
            'content' => 'Escalas de técnicos de mídia ativas.',
            'link' => 'consultaescalamidias',
            'stats' => '6',
            'color' => '#2ecc71'
        ],
        [
            'icon' => 'fa-calendar-check',
            'title' => 'Próximas Escalas',
            'content' => 'Próximas escalas agendadas para os próximos 7 dias.',
            'link' => 'consultaescala',
            'stats' => '4',
            'color' => '#9b59b6'
        ]
    ];
} elseif ($perfil === 'consulta') {
    $dashboardCards = [
        [
            'icon' => 'fa-music',
            'title' => 'Escalas de Louvor',
            'content' => 'Visualize as escalas de louvor cadastradas.',
            'link' => 'consultaescalavol',
            'stats' => '8',
            'color' => '#3498db'
        ],
        [
            'icon' => 'fa-microphone',
            'title' => 'Escalas de Som',
            'content' => 'Consulte as escalas de técnicos de som.',
            'link' => 'consultaescalasomvol',
            'stats' => '5',
            'color' => '#e74c3c'
        ],
        [
            'icon' => 'fa-photo-video',
            'title' => 'Escalas de Mídia',
            'content' => 'Consulte as escalas de técnicos de mídia.',
            'link' => 'consultaescalamidiasvol',
            'stats' => '6',
            'color' => '#2ecc71'
        ]
    ];
} else {
    $dashboardCards = [
        [
            'icon' => 'fa-info-circle',
            'title' => 'Bem-vindo',
            'content' => 'Seu perfil possui acesso limitado ao sistema.',
            'link' => '#',
            'stats' => '',
            'color' => '#3498db'
        ]
    ];
}

// CONEXÃO COM O BANCO DE DADOS E CONSULTA DE ATIVIDADES RECENTES
$atividadesRecentes = [];

try {
    // Conectar ao banco de dados (substitua com suas credenciais)
    $pdo = new PDO('mysql:host=localhost;dbname=seu_banco_de_dados', 'usuario', 'senha');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Consulta para obter as atividades recentes
    $sql = "
        (SELECT 'novo_membro' as tipo, nome, data_cadastro as data, NULL as descricao 
         FROM membros 
         ORDER BY data_cadastro DESC 
         LIMIT 2)
        UNION
        (SELECT 'escala_atualizada' as tipo, NULL as nome, data_atualizacao as data, descricao 
         FROM escalas 
         ORDER BY data_atualizacao DESC 
         LIMIT 2)
        UNION
        (SELECT 'relatorio_gerado' as tipo, departamento as nome, data_envio as data, NULL as descricao 
         FROM relatorios 
         ORDER BY data_envio DESC 
         LIMIT 2)
        ORDER BY data DESC 
        LIMIT 5
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $atividadesRecentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Em caso de erro, usar dados de exemplo
    $atividadesRecentes = [
        ['tipo' => 'novo_membro', 'nome' => 'João Silva', 'data' => date('Y-m-d H:i:s', strtotime('-2 hours')), 'descricao' => null],
        ['tipo' => 'escala_atualizada', 'nome' => null, 'data' => date('Y-m-d H:i:s', strtotime('-1 day')), 'descricao' => 'Escala para o culto de domingo'],
        ['tipo' => 'relatorio_gerado', 'nome' => 'Departamento de Jovens', 'data' => date('Y-m-d H:i:s', strtotime('-3 days')), 'descricao' => null]
    ];
}

// Função para formatar a data relativa (há x tempo)
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

// Função para obter ícone e título com base no tipo de atividade
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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Igreja - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos anteriores mantidos */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --text-color: #2c3e50;
            --text-light: #f8f9fa;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--secondary-color), var(--dark-color));
            color: var(--text-light);
            padding: 0;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1.5rem;
        }

        .logo {
            height: 40px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 15px;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .user-info i {
            font-size: 1.2rem;
        }

        /* Menu Styles */
        .menu-container {
            display: flex;
            align-items: center;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 5px;
        }

        .menu-toggle span {
            background: var(--text-light);
            height: 3px;
            width: 25px;
            margin: 3px 0;
            border-radius: 2px;
            transition: var(--transition);
        }

        .menu {
            display: flex;
            gap: 5px;
        }

        .menu-category {
            position: relative;
        }

        .category-title {
            color: var(--text-light);
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            transition: var(--transition);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .category-title:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .category-title i {
            font-size: 0.8rem;
        }

        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 220px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            padding: 10px 0;
            z-index: 1000;
        }

        .menu-category:hover .submenu {
            display: block;
        }

        .submenu a {
            display: block;
            padding: 12px 20px;
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .submenu a:hover {
            background: #f0f4f8;
            border-left: 3px solid var(--primary-color);
            padding-left: 17px;
        }

        .submenu a.btn-danger {
            color: #e74c3c;
            font-weight: bold;
        }

        .submenu a.btn-danger:hover {
            background: #ffeaea;
            border-left: 3px solid #e74c3c;
        }

        /* Mobile Menu Styles */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 57px;
            left: 0;
            width: 100%;
            height: calc(100vh - 57px);
            background: var(--light-color);
            z-index: 999;
            overflow-y: auto;
            padding: 20px;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        .mobile-category {
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .mobile-category-title {
            padding: 15px 10px;
            font-weight: bold;
            color: var(--secondary-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .mobile-category-title i {
            transition: transform 0.3s ease;
        }

        .mobile-category-title.active i {
            transform: rotate(180deg);
        }

        .mobile-submenu {
            display: none;
            padding: 0 10px 15px;
        }

        .mobile-submenu a {
            display: block;
            padding: 12px 15px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: var(--transition);
        }

        .mobile-submenu a:hover {
            background: #f0f4f8;
            transform: translateX(5px);
        }

        .mobile-submenu a.btn-danger {
            color: #e74c3c;
            font-weight: bold;
            border-left: 3px solid #e74c3c;
        }

        .search-container {
            margin: 15px 0;
            position: relative;
        }

        .search-container input {
            width: 100%;
            padding: 12px 15px;
            padding-left: 40px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        /* Content spacer */
        .content-spacer {
            margin-top: 70px;
            padding: 20px;
        }

        /* Welcome message */
        .welcome-message {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            text-align: center;
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

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .menu {
                gap: 2px;
            }

            .category-title {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 900px) {
            .menu {
                display: none;
            }

            .menu-toggle {
                display: flex;
            }

            .mobile-menu {
                display: block;
            }

            .user-info span {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .quick-access-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Animation for menu toggle */
        .menu-toggle.active span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        /* Scrollbar styling for mobile menu */
        .mobile-menu::-webkit-scrollbar {
            width: 6px;
        }

        .mobile-menu::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .mobile-menu::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .mobile-menu::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <img class="logo" src="LÍRIO MATRIZ (PRETO)_menor.png" alt="Logo">

            <div class="user-info">
                <i class="fas fa-user"></i>
                <span><?php echo $logado . ' (' . $perfil . ')'; ?></span>
            </div>

            <div class="menu-container">
                <div class="menu">
                    <?php foreach ($categorizedOptions as $category => $options): ?>
                        <?php if (!empty($options)): ?>
                            <div class="menu-category">
                                <div class="category-title">
                                    <?php echo $category; ?> <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="submenu">
                                    <?php foreach ($options as $option): ?>
                                        <a href="<?php echo $option['url']; ?>"
                                           class="<?php echo isset($option['class']) ? $option['class'] : ''; ?>">
                                            <?php echo $option['title']; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="menu-toggle" id="menuToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Pesquisar opções..." id="menuSearch">
        </div>

        <?php foreach ($categorizedOptions as $category => $options): ?>
            <?php if (!empty($options)): ?>
                <div class="mobile-category">
                    <div class="mobile-category-title">
                        <?php echo $category; ?> <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mobile-submenu">
                        <?php foreach ($options as $option): ?>
                            <a href="<?php echo $option['url']; ?>"
                               class="<?php echo isset($option['class']) ? $option['class'] : ''; ?>">
                                <?php echo $option['title']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Espaço para o conteúdo não ficar escondido atrás do header fixo -->
    <div class="content-spacer">
        <!-- Mensagem de boas-vindas -->
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
        <div class="recent-activity">
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
    </div>

    <script>
        // Menu mobile toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('mobileMenu').classList.toggle('active');
        });

        // Mobile category accordion
        const mobileCategories = document.querySelectorAll('.mobile-category-title');
        mobileCategories.forEach(category => {
            category.addEventListener('click', () => {
                category.classList.toggle('active');
                const submenu = category.nextElementSibling;

                if (submenu.style.display === 'block') {
                    submenu.style.display = 'none';
                } else {
                    // Close any other open submenus
                    document.querySelectorAll('.mobile-submenu').forEach(item => {
                        if (item !== submenu) item.style.display = 'none';
                    });
                    document.querySelectorAll('.mobile-category-title').forEach(item => {
                        if (item !== category) item.classList.remove('active');
                    });

                    submenu.style.display = 'block';
                }
            });
        });

        // Search functionality for mobile menu
        document.getElementById('menuSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const menuItems = document.querySelectorAll('.mobile-submenu a');

            document.querySelectorAll('.mobile-category').forEach(category => {
                let hasVisibleItems = false;
                const categoryItems = category.querySelectorAll('.mobile-submenu a');

                categoryItems.forEach(item => {
                    if (item.textContent.toLowerCase().includes(searchTerm)) {
                        item.style.display = 'block';
                        hasVisibleItems = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide category based on whether it has visible items
                category.style.display = hasVisibleItems ? 'block' : 'none';
            });

            // Open all categories when searching
            if (searchTerm.length > 0) {
                document.querySelectorAll('.mobile-submenu').forEach(submenu => {
                    submenu.style.display = 'block';
                });
                document.querySelectorAll('.mobile-category-title').forEach(title => {
                    title.classList.add('active');
                });
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menuToggle = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');

            if (!menuToggle.contains(event.target) && !mobileMenu.contains(event.target) && mobileMenu.classList.contains('active')) {
                menuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });

        // Prevent submenu from closing when clicking inside it (desktop)
        document.querySelectorAll('.submenu').forEach(submenu => {
            submenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>