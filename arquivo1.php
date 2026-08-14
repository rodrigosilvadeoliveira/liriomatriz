<?php
ob_start();

include_once('config.php');
include_once('config_language.php');

if (empty($_SESSION['usuario']) || empty($_SESSION['senha'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    ob_end_flush();
    exit;
}

$logado = $_SESSION['usuario'];
$perfil = $_SESSION['nivel_acesso'] ?? 'consulta';
$igreja = $_SESSION['igreja_id'];

if (!function_exists('obterPermissoes')) {
    function obterPermissoes($conexao, $igreja, $perfil)
    {    $perfil = strtolower(trim($perfil));

    $sql = "SELECT 
                pode_editar,
                pode_excluir,
                ocultar_opcoes,
                ocultar_administracao,
                ocultar_membros,
                ocultar_membros1,
                ocultar_voluntario,
                ocultar_criativo,
                ocultar_criativo1,
                ocultar_danca,
                ocultar_danca1,
                ocultar_kids,
                ocultar_kids1,
                ocultar_louvor,
                ocultar_louvor1,
                ocultar_midias,
                ocultar_midias1,
                ocultar_som,
                ocultar_som1,
                ocultar_staff,
                ocultar_staff1,
                ocultar_relatorios,
                ocultar_recepcao,
                ocultar_recepcao1,
                ocultar_configuracao
            FROM parametros_permissoes
            WHERE igreja_id = ?
            AND LOWER(perfil) = ?
            LIMIT 1";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        return [
            'pode_editar' => 0,
            'pode_excluir' => 0,
            'ocultar_opcoes' => 0,
            'ocultar_administracao' => 0,
            'ocultar_membros' => 0,
            'ocultar_membros1' => 0,
            'ocultar_voluntario' => 0,
            'ocultar_criativo' => 0,
            'ocultar_criativo1' => 0,
            'ocultar_danca' => 0,
            'ocultar_danca1' => 0,
            'ocultar_kids' => 0,
            'ocultar_kids1' => 0,
            'ocultar_louvor' => 0,
            'ocultar_louvor1' => 0,
            'ocultar_midias' => 0,
            'ocultar_midias1' => 0,
            'ocultar_som' => 0,
            'ocultar_som1' => 0,
            'ocultar_staff' => 0,
            'ocultar_staff1' => 0,
            'ocultar_relatorios' => 0,
            'ocultar_recepcao' => 0,
            'ocultar_recepcao1' => 0,
            'ocultar_configuracao' => 0
        ];
    }

    $stmt->bind_param("is", $igreja, $perfil);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row;
    }

    return [
        'pode_editar' => 0,
        'pode_excluir' => 0,
        'ocultar_opcoes' => 0,
        'ocultar_administracao' => 0,
        'ocultar_membros' => 0,
        'ocultar_membros1' => 0,
        'ocultar_voluntario' => 0,
        'ocultar_criativo' => 0,
        'ocultar_criativo1' => 0,
        'ocultar_danca' => 0,
        'ocultar_danca1' => 0,
        'ocultar_kids' => 0,
        'ocultar_kids1' => 0,
        'ocultar_louvor' => 0,
        'ocultar_louvor1' => 0,
        'ocultar_midias' => 0,
        'ocultar_midias1' => 0,
        'ocultar_som' => 0,
        'ocultar_som1' => 0,
        'ocultar_staff' => 0,
        'ocultar_staff1' => 0,
        'ocultar_relatorios' => 0,
        'ocultar_recepcao' => 0,
        'ocultar_recepcao1' => 0,
        'ocultar_configuracao' => 0
    ];
}
}
$perfil_usuario = strtolower(trim($_SESSION['nivel_acesso'] ?? 'consulta'));
$igreja = $_SESSION['igreja_id'] ?? 0;

$permissoes = obterPermissoes($conexao, $igreja, $perfil_usuario);

$pode_editar  = (int)$permissoes['pode_editar'] === 1;
$pode_excluir = (int)$permissoes['pode_excluir'] === 1;
$hideigreja   = (int)$permissoes['ocultar_opcoes'] === 1;
$hideadministracao   = (int)$permissoes['ocultar_administracao'] === 1;
$hidemembros   = (int)$permissoes['ocultar_membros'] === 1;
$hidemembros1   = (int)$permissoes['ocultar_membros1'] === 1;
$hidevoluntario   = (int)$permissoes['ocultar_voluntario'] === 1;
$hidecriativo   = (int)$permissoes['ocultar_criativo'] === 1;
$hidecriativo1   = (int)$permissoes['ocultar_criativo1'] === 1;
$hidedanca   = (int)$permissoes['ocultar_danca'] === 1;
$hidedanca1   = (int)$permissoes['ocultar_danca1'] === 1;
$hidekids   = (int)$permissoes['ocultar_kids'] === 1;
$hidekids1   = (int)$permissoes['ocultar_kids1'] === 1;
$hidelouvor   = (int)$permissoes['ocultar_louvor'] === 1;
$hidelouvor1   = (int)$permissoes['ocultar_louvor1'] === 1;
$hidemidias   = (int)$permissoes['ocultar_midias'] === 1;
$hidemidias1   = (int)$permissoes['ocultar_midias1'] === 1;
$hidesom   = (int)$permissoes['ocultar_som'] === 1;
$hidesom1   = (int)$permissoes['ocultar_som1'] === 1;
$hidestaff   = (int)$permissoes['ocultar_staff'] === 1;
$hidestaff1   = (int)$permissoes['ocultar_staff1'] === 1;
$hiderelatorios   = (int)$permissoes['ocultar_relatorios'] === 1;
$hiderecepcao   = (int)$permissoes['ocultar_recepcao'] === 1;
$hiderecepcao1   = (int)$permissoes['ocultar_recepcao1'] === 1;
$hiderpermissoes   = (int)$permissoes['ocultar_configuracao'] === 1;

$foto_perfil = "uploads/foto_67feba4ab0f0a.jpg";// imagem padrão

// =============================
// 2️⃣ Buscar foto com JOIN (mais eficiente)
// =============================
$sql = "
SELECT m.foto
FROM cadastroadm c
LEFT JOIN voluntarios m ON m.cadastroadm_id = c.cadastroadm_id
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
// Definir opções de menu baseadas no perfil
$menuOptions = [];

if (in_array($perfil, ['master', 'lider', 'ministro', 'secretaria', 'consulta'])) {

    $menuOptions = [

    [
        'title' => __('header_inicio'),
        'url' => 'paginainicial',
        'icon' => 'fa-solid fa-house'
    ],

    [
        'title' => __('cadastro_acesso'),
        'url' => 'formularioMaster',
        'icon' => 'fa-solid fa-user-shield'
    ],

    [
        'title' => __('cadastro_vendas'),
        'url' => 'cadastrodevendas',
        'icon' => 'fa-solid fa-cart-shopping'
    ],

    [
        'title' => 'Log',
        'url' => 'consulta_logs',
        'icon' => 'fa-solid fa-file-lines'
    ],

    [
        'title' => __('consulta_acessos'),
        'url' => 'consultaacessos',
        'icon' => 'fa-solid fa-users'
    ],

    [
        'title' => 'Categorias',
        'url' => 'categoriasfinanceiras',
        'icon' => 'fa-solid fa-tags'
    ],

    [
        'title' => 'Transações',
        'url' => 'transacoes',
        'icon' => 'fa-solid fa-money-bill-transfer'
    ],

    [
        'title' => __('novo_membro'),
        'url' => 'cadastroMembrosAdm',
        'icon' => 'fa-solid fa-user-plus'
    ],

    [
        'title' => __('consultar_membros'),
        'url' => 'consulta_membros',
        'icon' => 'fa-solid fa-address-book'
    ],

    [
        'title' => 'Departamentos', 
         'url' => 'cadastro_departamento',
        'icon' => '' // Lupa com criança (pode usar fa-child com lupa)
    ],
    
    [
        'title' => 'Novas Funções', 
         'url' => 'cadastrar_funcao',
        'icon' => '' // Lupa com criança (pode usar fa-child com lupa)
    ],

    [
        'title' => 'Voluntários e Funções', 
        'url' => 'lista_voluntarios',
        'icon' => '' // Lupa com criança (pode usar fa-child com lupa)
    ],
    
    [
        'title' => __('aniversariantes'),
        'url' => 'consulta_niver',
        'icon' => 'fa-solid fa-cake-candles'
    ],

    [
        'title' => __('inscricoes'),
        'url' => 'cadastroForm.php',
        'icon' => 'fa-solid fa-clipboard-list'
    ],

    [
        'title' => __('cadastrar_relatorio'),
        'url' => 'relatoriodepartamento',
        'icon' => 'fa-solid fa-file-pen'
    ],

    [
        'title' => __('consulta_relatorio'),
        'url' => 'listar_relatoriosdep',
        'icon' => 'fa-solid fa-folder-open'
    ],

    [
        'title' => __('site_imagens'),
        'url' => 'cadastroEvento',
        'icon' => 'fa-solid fa-image'
    ],

    [
        'title' => 'Site Live',
        'url' => 'cadastrolive',
        'icon' => 'fa-solid fa-video'
    ],

    // Escala Voluntarios
    [
        'title' => 'Incluir Escala', 
        'url' => 'escalalouvor',
        'icon' => 'fa-solid fa-church' // Igreja
    ],
    
   
    
    [
        'title' => 'Consultar Escalas', 
        'url' => 'consultaescala',
        'icon' => 'fa-solid fa-magnifying-glass-desktop' // Lupa com música
    ],
    
    [
        'title' => 'Guia de informações louvor', 
        'url' => 'checklistlouvor',
        'icon' => 'fa-solid fa-users-viewfinder' // Checklist
    ],
    
   
    
    ['title' =>__('guia_mesa_som') , 'url' => 'checklistsom'],
    ['title' =>__('guia_mesa_live') , 'url' => 'checklistlive'],

    
    
    [
        'title' => __('repertorio'),
        'url' => 'musicas',
        'icon' => 'fa-solid fa-music'
    ],

    [
        'title' => __('playbacks'),
        'url' => 'playbacks',
        'icon' => 'fa-solid fa-headphones'
    ],
    
    [
        'title' => __('consultar_repertorio'), 
        'url' => 'consultarepertorio',
        'icon' => 'fa-solid fa-magnifying-glass-musical-note' // Lupa com música
    ],

[
        'title' => 'Tons das Musicas',
        'url' => 'ministros_tons',
        'icon' => 'fa-solid fa-music'
    ],
    
    [
        'title' => __('permissoes'),
        'url' => 'tela_parametros_permissoes',
        'icon' => 'fa-solid fa-lock'
    ],

    [
        'title' => 'Sair',
        'url' => 'sair',
        'icon' => 'fa-solid fa-right-from-bracket',
        'class' => 'btn-danger'
    ]

];
}
// Agrupar opções por categoria para melhor organização
$categorizedOptions = [
    __('header_inicio') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], ['paginainicial']);
    }),
    __('administracao') => array_filter($menuOptions, function($item) use ($hideadministracao) {

        if ($hideadministracao) {
            return false; // esconde todo o menu para igreja 2
        }
        return in_array($item['url'], ['formularioMaster', "formulariolider", 'cadastrodevendas', 'consulta_logs', 'consultaacessos']);
    }),
    'Financeiro' => array_filter($menuOptions, function($item) use ($hideadministracao) {

        if ($hideadministracao) {
            return false; // esconde todo o menu para igreja 2
        }
        return in_array($item['url'], ['categoriasfinanceiras', 'transacoes']);
    }),
    __('musica') => array_filter($menuOptions, function($item) use ($hideigreja) {

        if ($hideigreja) {
            return false; // esconde todo o menu para igreja 2
        }
        return in_array($item['url'], ['musicas', 'consultarepertorio', 'ministros_tons', 'playbacks']);
    }),
    __('membros') => array_filter($menuOptions, function($item) use ($hidemembros,$hidemembros1) {
        $paginasMembros = [
        'cadastroMembrosAdm', 'consulta_membros', 'consulta_niver'
    ];

        if ($hidemembros) {
             return false;
    }

        $paginasMembros1 = [
        'cadastroMembrosAdm', 'consulta_niver'
    ];
         if ($hidemembros1 && in_array($item['url'], $paginasMembros1)) {
        return false;
         }
          return in_array($item['url'], $paginasMembros);
    }),
   
    'Voluntariado e Funções' => array_filter($menuOptions, function($item) use ($hidevoluntario) {

        if ($hidevoluntario) {
            return false; // esconde todo o menu para igreja 2
        }
        return in_array($item['url'], ['cadastrovoluntariadoescala','cadastrar_funcao','cadastro_departamento', 'lista_voluntarios']);
    }),
     __('criativo') => array_filter($menuOptions, function($item) use ($hidecriativo, $hidecriativo1) {

        $paginasCriativo = [
        'escalacriativo',
        'consultaescalacriativo',
        'consultaescalacriativovol'
    ];

    // 1) Ocultar TODAS as opções do criativo
        if ($hidecriativo) {
        return false;
    }

    // 2) Ocultar somente a página escalacriativo
        if ($hidecriativo1 && $item['url'] === 'escalacriativo') {
        return false;
    }

    // 3) Mostrar apenas as páginas do módulo
    return in_array($item['url'], $paginasCriativo);

    }),
     __('danca') => array_filter($menuOptions, function($item) use ($hidedanca,$hidedanca1) {
          $paginasDanca = [
        'escaladanca','consultaescaladanca'
    ];
    if ($hidedanca) {
        return false;
    }

        if ($hidedanca1 && $item['url'] === 'escaladanca') {
            return false; // esconde todo o menu para igreja 2
        }
          return in_array($item['url'], $paginasDanca);
    }),
    'Kids' => array_filter($menuOptions, function($item) use ($hidekids,$hidekids1) {
         $paginasKids = [
        'escalakids','consultaescalakids'
    ];
    if ($hidekids) {
        return false;
    }

        if ($hidekids1 && $item['url'] === 'escalakids') {
            return false; // esconde todo o menu para igreja 2
        }
          return in_array($item['url'], $paginasKids);
    }),
   'Escalas Voluntários' => array_filter($menuOptions, function($item) use ($hidelouvor,$hidelouvor1) {
         $paginasLouvor = [
        'escalalouvor','escalalouvornoite','escalalouvorkids', 'escalalouvorgcs','consultaescala', 'checklistlouvor', 'checklistsom', 'checklistlive'
    ];
if ($hidelouvor) {
        return false;
    }
$paginasLouvor1 = [
        'escalalouvor','escalalouvornoite','escalalouvorkids', 'escalalouvorgcs'
    ];
         if ($hidelouvor1 && in_array($item['url'], $paginasLouvor1)) {
        return false;
         }
          return in_array($item['url'], $paginasLouvor);
    }),
    'Midias' => array_filter($menuOptions, function($item) use ($hidemidias,$hidemidias1) {
         $paginasMidias = [
        'escalamidias', 'consultaescalamidias'
    ];

         if ($hidemidias) {
        return false;
    }

        if ($hidemidias1 && $item['url'] === 'escalamidias') {
            return false; // esconde todo o menu para igreja 2
        }
          return in_array($item['url'], $paginasMidias);
    }),
     __('recepcao') => array_filter($menuOptions, function($item) use ($hiderecepcao,$hiderecepcao1) {
           $paginasRecepcao = [
        'escalarecepcao', 'escalarecepcaonoite', 'consultaescalarecepcao'
    ];

       if ($hiderecepcao) {
        return false;
    }

        if ($hiderecepcao1 && $item['url'] === 'escalarecepcao') {
            return false; // esconde todo o menu para igreja 2
        }
          return in_array($item['url'], $paginasRecepcao);
    }),
    // __('som') => array_filter($menuOptions, function($item) use ($hidesom,$hidesom1) {
    //        $paginasSom = [
    //     'escalasom', 'consultaescalasom', 'checklistsom', 'checklistlive'
    // ];

    //    if ($hidesom) {
    //     return false;
    // }

    //     if ($hidesom1 && $item['url'] === 'escalasom') {
    //         return false; // esconde todo o menu para igreja 2
    //     }
    //       return in_array($item['url'], $paginasSom);
    // }),
    'Staff' => array_filter($menuOptions, function($item) use ($hidestaff,$hidestaff1) {
        $paginasStaff = [
        'escalastaff', 'consultaescalastaff'
    ];

         if ($hidestaff) {
        return false;
    }

        if ($hidestaff1 && $item['url'] === 'escalastaff') {
            return false; // esconde todo o menu para igreja 2
        }
          return in_array($item['url'], $paginasStaff);
    }),
    __(key: 'relatorios') => array_filter($menuOptions, function($item) use ($hiderelatorios) {

        if ($hiderelatorios) {
            return false; // esconde todo o menu para igreja 2
        }
        return in_array($item['url'], ['relatoriodepartamento', 'listar_relatoriosdep']);
    }),
    'Site e configurações' => array_filter($menuOptions, function($item) use ($hiderpermissoes) {

        if ($hiderpermissoes) {
            return false; // esconde todo o menu para igreja 2
        }
        return in_array($item['url'], ['cadastroForm.php', 'tela_parametros_permissoes','cadastroEvento', 'cadastrolive']);
    }),
         __(key: 'outros') => array_filter($menuOptions, function($item) {
        return in_array($item['url'], [ 'sair']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="arquivo1style.css?t=<?=time()?>">
    
</head>
<body>
    <header>
        <nav>
            <div class="profile-icon-container">
        <a href="perfil.php" class="profile-link">
     <img src="<?php echo $foto_perfil; ?>" alt="Perfil" class="profile-icon-menu">
        </a>
    </div>

            <div class="nav-item">

            <img class="logo" src="LÍRIO MATRIZ (PRETO)_menor.png" alt="Logo">
            <!-- <a class="banderira" href="?lang=pt">
        <img src="https://flagcdn.com/w40/br.png" width="44" alt="Português">
    </a>
            <a class="banderira" href="?lang=en">
        <img src="https://flagcdn.com/w40/us.png" width="44" height="32" alt="English">
    </a> -->
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
                                            <i class="<?php echo $option['icon'] ?? 'fas fa-circle'; ?>"></i>
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
        <div class="profile-icon-container">
        <a href="perfil.php" class="profile-link">
     <img src="<?php echo $foto_perfil; ?>" alt="Perfil" class="profile-icon">
        </a>
    </div>
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
                                <i class="<?php echo $option['icon'] ?? 'fas fa-circle'; ?>"></i>
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
    