<?php
include_once('config.php');

// Verificação de login
if (!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];
$perfil = $_SESSION['nivel_acesso'];

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
        // ['title' => 'Pesquisa Membro(a)', 'url' => 'consulta_membros_busca'],
        ['title' => 'Aniversariantes', 'url' => 'consulta_niver'],
        ['title' => 'Inscrições', 'url' => 'cadastroForm.php'],
        ['title' => 'Cadastrar Relatórios', 'url' => 'relatoriodepartamento'],
        ['title' => 'Consultar Relatórios', 'url' => 'listar_relatoriosdep'],
        ['title' => 'Site imagens', 'url' => 'cadastroEvento'],
        ['title' => 'Site Live', 'url' => 'cadastrolive'],
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
} elseif ($perfil === 'secretaria') {
    $menuOptions = [
        ['title' => 'Inicio', 'url' => 'paginainicial'],
        ['title' => 'Novo Membro(a)', 'url' => 'cadastroMembrosAdm'],
        ['title' => 'Consultar Membros', 'url' => 'consulta_membros'],
        // ['title' => 'Pesquisa Membro(a)', 'url' => 'consulta_membros_busca'],
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
}

// Agrupar opções por categoria para melhor organização
$categorizedOptions = [
    'Inicio' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['paginainicial']);
    }),
    'Administração' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['formularioMaster', "formulariolider", 'cadastrodevendas', 'consulta_logs', 'consultaacessos']);
    }),
    'Membros' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastroMembrosAdm', 'consulta_membros', 'consulta_niver']);
    }),
    'Voluntario' => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['cadastrovoluntariadoescala']);
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

// Remover categorias vazias

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegação</title>
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

            <!-- <div class="user-info">
                <i class="fas fa-user"></i>
                <span><?php echo $logado . ' (' . $perfil . ')'; ?></span>
            </div> -->

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