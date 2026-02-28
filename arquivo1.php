<?php
include_once('config.php');
include_once('config_language.php');

// Verificação de login
if (!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];
$perfil = $_SESSION['nivel_acesso'];

$perfil_usuario = $_SESSION['nivel_acesso'] ?? 'consulta'; // valor padrão caso não exista
$pode_excluir = in_array($perfil_usuario, ['master', 'lider']);
$pode_editar = in_array($perfil_usuario, ['master', 'lider']);
// Definir opções de menu baseadas no perfil
$menuOptions = [];

if ($perfil === 'master') {
    $menuOptions = [
        ['title' => __('header_inicio') , 'url' => 'paginainicial'],
        ['title' => __('cadastro_acesso'), 'url' => 'formularioMaster'],
        ['title' => __('cadastro_vendas'), 'url' => 'cadastrodevendas'],
        ['title' => 'Log', 'url' => 'consulta_logs'],
        ['title' => __('consulta_acessos') , 'url' => 'consultaacessos'],
        ['title' => __('novo_membro') , 'url' => 'cadastroMembrosAdm'],
        ['title' => __('consultar_membros') , 'url' => 'consulta_membros'],
        ['title' => __('aniversariantes') , 'url' => 'consulta_niver'],
        ['title' => __('inscricoes') , 'url' => 'cadastroForm.php'],
        ['title' => __('cadastrar_relatorio') , 'url' => 'relatoriodepartamento'],
        ['title' => __('consulta_relatorio') , 'url' => 'listar_relatoriosdep'],
        ['title' => __('site_imagens') , 'url' => 'cadastroEvento'],
        ['title' => 'Site Live', 'url' => 'cadastrolive'],
        ['title' => __('cadastro_voluntario') , 'url' => 'cadastrovoluntariadoescala'],
        ['title' => __('consulta_voluntario') , 'url' => 'consulta_voluntariado'],
        ['title' => __('escala_criativo') , 'url' => 'escalacriativo'],
        ['title' => __('consultar_escala_criativo') , 'url' => 'consultaescalacriativo'],
        ['title' =>__('escala_danca') , 'url' => 'escaladanca'],
        ['title' =>__('consultar_escala_danca') , 'url' => 'consultaescaladanca'],
        ['title' =>__('_escala_kids') , 'url' => 'escalakids'],
        ['title' =>__('consultar_escala_kids') , 'url' => 'consultaescalakids'],
        ['title' =>__('escala_quinta_domingo_manha') , 'url' => 'escalalouvor'],
	    ['title' =>__('escala_domingo_noite'), 'url' => 'escalalouvornoite'],
        ['title' =>__('escala_louvor_kids') , 'url' => 'escalalouvorkids'],
        ['title' =>__('escala_louvor_gcs') , 'url' => 'escalalouvorhomens'],
       
        ['title' =>__('consultar_escala_louvor') , 'url' => 'consultaescala'],
        ['title' =>__('ri_louvor') , 'url' => 'checklistlouvor'],
        ['title' =>__('escala_midias') , 'url' => 'escalamidias'],
        ['title' =>__('consultar_escala_midias') , 'url' => 'consultaescalamidias'],
        ['title' =>__('escala_staff') , 'url' => 'escalastaff'],
        ['title' => __('consultar_escala_staff'), 'url' => 'consultaescalastaff'],
        ['title' => __('escala_som') , 'url' => 'escalasom'],
        ['title' => __('consultar_escala_som'), 'url' => 'consultaescalasom'],
        ['title' =>__('guia_mesa_live') , 'url' => 'checklistlive'],
        ['title' =>__('guia_mesa_som') , 'url' => 'checklistsom'],
        ['title' =>__('repertorio') , 'url' => 'musicas'],
        ['title' =>__('consultar_repertorio') , 'url' => 'consultarepertorio'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
        
    ];
} elseif ($perfil === 'secretaria') {
    $menuOptions = [
        ['title' => __('header_inicio') , 'url' => 'paginainicial'],
        ['title' => __('novo_membro') , 'url' => 'cadastroMembrosAdm'],
        ['title' => __('consultar_membros') , 'url' => 'consulta_membros'],
        ['title' => __('aniversariantes') , 'url' => 'consulta_niver'],
        ['title' => __('inscricoes') , 'url' => 'cadastroForm.php'],
        ['title' => __('cadastrar_relatorio') , 'url' => 'relatoriodepartamento'],
        ['title' => __('consulta_relatorio') , 'url' => 'listar_relatoriosdep'],
        ['title' => __('site_imagens') , 'url' => 'cadastroEvento'],        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
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
        ['title' => __('header_inicio') , 'url' => 'paginainicial'],
        ['title' => __('cadastro_acesso_lider') , 'url' => 'formulariolider'],
        ['title' => __('cadastro_voluntario') , 'url' => 'cadastrovoluntariadoescala'],
        ['title' => __('consulta_voluntario') , 'url' => 'consulta_voluntariado'],
        ['title' => __('escala_criativo') , 'url' => 'escalacriativo'],
        ['title' => __('consultar_escala_criativo') , 'url' => 'consultaescalacriativo'],
        ['title' =>__('escala_danca') , 'url' => 'escaladanca'],
        ['title' =>__('consultar_escala_danca') , 'url' => 'consultaescaladanca'],
        ['title' =>__('_escala_kids') , 'url' => 'escalakids'],
        ['title' =>__('consultar_escala_kids') , 'url' => 'consultaescalakids'],
        ['title' =>__('escala_quinta_domingo_manha') , 'url' => 'escalalouvor'],
	    ['title' =>__('escala_domingo_noite'), 'url' => 'escalalouvornoite'],
        ['title' =>__('escala_louvor_kids') , 'url' => 'escalalouvorkids'],
        ['title' =>__('escala_louvor_gcs') , 'url' => 'escalalouvorhomens'],
       
        ['title' =>__('consultar_escala_louvor') , 'url' => 'consultaescala'],
        ['title' =>__('ri_louvor') , 'url' => 'checklistlouvor'],
        ['title' =>__('escala_midias') , 'url' => 'escalamidias'],
        ['title' =>__('consultar_escala_midias') , 'url' => 'consultaescalamidias'],
        ['title' =>__('escala_staff') , 'url' => 'escalastaff'],
        ['title' => __('consultar_escala_staff'), 'url' => 'consultaescalastaff'],
        ['title' => __('escala_som') , 'url' => 'escalasom'],
        ['title' => __('consultar_escala_som'), 'url' => 'consultaescalasom'],
        ['title' =>__('guia_mesa_live') , 'url' => 'checklistlive'],
        ['title' =>__('guia_mesa_som') , 'url' => 'checklistsom'],
        ['title' =>__('repertorio') , 'url' => 'musicas'],
        ['title' =>__('consultar_repertorio') , 'url' => 'consultarepertorio'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} elseif ($perfil === 'consulta') {
    $menuOptions = [
        ['title' => __('header_inicio') , 'url' => 'paginainicial'],
        ['title' => __('consultar_escala_criativo') , 'url' => 'consultaescalacriativo'],
        ['title' =>__('consultar_escala_danca') , 'url' => 'consultaescaladanca'],
        ['title' =>__('consultar_escala_kids') , 'url' => 'consultaescalakids'],
        ['title' =>__('consultar_escala_louvor') , 'url' => 'consultaescala'],
        ['title' =>__('ri_louvor') , 'url' => 'checklistlouvor'],
        ['title' =>__('consultar_escala_midias') , 'url' => 'consultaescalamidias'],
        ['title' => __('consultar_escala_staff'), 'url' => 'consultaescalastaff'],
        ['title' => __('consultar_escala_som'), 'url' => 'consultaescalasom'],
        ['title' =>__('guia_mesa_live') , 'url' => 'checklistlive'],
        ['title' =>__('guia_mesa_som') , 'url' => 'checklistsom'],
        ['title' =>__('repertorio') , 'url' => 'musicas'],
        ['title' =>__('consultar_repertorio') , 'url' => 'consultarepertorio'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
} elseif ($perfil === 'ministro') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => __('header_inicio') , 'url' => 'paginainicial'],
        ['title' => __('consultar_escala_criativo') , 'url' => 'consultaescalacriativo'],
        ['title' =>__('consultar_escala_danca') , 'url' => 'consultaescaladanca'],
        ['title' =>__('consultar_escala_kids') , 'url' => 'consultaescalakids'],
        ['title' =>__('consultar_escala_louvor') , 'url' => 'consultaescala'],
        ['title' =>__('ri_louvor') , 'url' => 'checklistlouvor'],
        ['title' =>__('consultar_escala_midias') , 'url' => 'consultaescalamidias'],
        ['title' => __('consultar_escala_staff'), 'url' => 'consultaescalastaff'],
        ['title' => __('consultar_escala_som'), 'url' => 'consultaescalasom'],
        ['title' =>__('guia_mesa_live') , 'url' => 'checklistlive'],
        ['title' =>__('guia_mesa_som') , 'url' => 'checklistsom'],
        ['title' =>__('repertorio') , 'url' => 'musicas'],
        ['title' =>__('consultar_repertorio') , 'url' => 'consultarepertorio'],
        ['title' => 'Sair', 'url' => 'sair', 'class' => 'btn-danger']
    ];
}

// Agrupar opções por categoria para melhor organização
$categorizedOptions = [
    __('header_inicio') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['paginainicial']);
    }),
    __('administracao') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['formularioMaster', "formulariolider", 'cadastrodevendas', 'consulta_logs', 'consultaacessos']);
    }),
    __('musica') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['musicas', 'consultarepertorio']);
    }),
    __('membros') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastroMembrosAdm', 'consulta_membros', 'consulta_niver']);
    }),
    __('voluntario') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastrovoluntariadoescala', 'consulta_voluntariado']);
    }),
     __('criativo') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escalacriativo', 'consultaescalacriativo', 'consultaescalacriativovol']);
    }),
     __('dance') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escaladanca','consultaescaladanca']);
    }),
    'Kids' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escalakids','consultaescalakids']);
    }),
   __('louvor') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escalalouvor','escalalouvornoite','escalalouvorkids', 'escalalouvorhomens', 'escalalouvormulheres', 'escalalouvorJovens','consultaescala', 'checklistlouvor']);
    }),
    'Midias' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], [ 'escalamidias', 'consultaescalamidias']);
    }),
    __('som') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escalasom', 'consultaescalasom', 'checklistsom', 'checklistlive']);
    }),
    'Staff' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['escalastaff', 'consultaescalastaff']);
    }),
    __(key: 'relatorios') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['relatoriodepartamento', 'listar_relatoriosdep']);
    }),
    'Site' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastroEvento', 'cadastrolive']);
    }),
     __(key: 'outros') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastroForm.php', 'sair']);
    })
];

// Remover categorias vazias
$categorizedOptions = array_filter($categorizedOptions);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            filter: brightness(0) invert(1)
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
            border-left: 3px solid blue;
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
            border-left: 3px solid blue;
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
            padding: 0px;
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
        /* Destacar item específico apenas na categoria Louvor */
.menu-category .submenu a[href="checklistlouvor"] {
    color: #e74c3c !important;
    font-weight: bold;
}

.menu-category .submenu a[href="checklistlouvor"]:hover {
    color: #c0392b !important;
    border-left: 3px solid #e74c3c;
}

/* Para a versão mobile - apenas na categoria Som */
.mobile-category .mobile-submenu a[href="checklistlouvor"] {
    color: #e74c3c !important;
    font-weight: bold;
    border-left: 3px solid #e74c3c;
}

.mobile-category .mobile-submenu a[href="checklistlouvor"]:hover {
    background: #ffeaea;
    color: #c0392b !important;
}
        /* Destacar item específico apenas na categoria Som */
.menu-category .submenu a[href="checklistsom"] {
    color: #e74c3c !important;
    font-weight: bold;
}

.menu-category .submenu a[href="checklistsom"]:hover {
    color: #c0392b !important;
    border-left: 3px solid #e74c3c;
}

/* Para a versão mobile - apenas na categoria Som */
.mobile-category .mobile-submenu a[href="checklistsom"] {
    color: #e74c3c !important;
    font-weight: bold;
    border-left: 3px solid #e74c3c;
}

.mobile-category .mobile-submenu a[href="checklistsom"]:hover {
    background: #ffeaea;
    color: #c0392b !important;
}

.menu-category .submenu a[href="checklistlive"] {
    color: #e74c3c !important;
    font-weight: bold;
}

.menu-category .submenu a[href="checklistlive"]:hover {
    color: #c0392b !important;
    border-left: 3px solid #e74c3c;
}

/* Para a versão mobile - apenas na categoria Som */
.mobile-category .mobile-submenu a[href="checklistlive"] {
    color: #e74c3c !important;
    font-weight: bold;
    border-left: 3px solid #e74c3c;
}

.mobile-category .mobile-submenu a[href="checklistlive"]:hover {
    background: #ffeaea;
    color: #c0392b !important;
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

        /* Agora o menu horizontal nunca será exibido — só o menu hambúrguer */
.menu {
    display: none !important;
}

.menu-toggle {
    display: flex !important;
}

.mobile-menu {
    display: block !important;
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

            <div class="nav-item">
            <a class="nav-link" href="?lang=pt">
        <img src="https://flagcdn.com/w40/br.png" width="24" alt="Português">
    </a>
            <a class="nav-link" href="?lang=en">
        <img src="https://flagcdn.com/w40/us.png" width="24" alt="English">
    </a>
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
    