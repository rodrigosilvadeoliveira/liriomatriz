<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

// Verificação de sessão
if (!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];

// Configuração da paginação
$limite = 20; // Itens por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1; // Garante que a página seja no mínimo 1
$offset = ($pagina - 1) * $limite;

// Pesquisa segura
$where = "";
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $where = "WHERE usuario LIKE ? OR acao LIKE ? OR nivel_acesso LIKE ?";
    $search_term = "%$search%";
    $params = [$search_term, $search_term, $search_term];
    $types = "sss";
}

// Consulta principal
$sql_count = "SELECT COUNT(*) as total FROM log_login $where";
$sql_data = "SELECT * FROM log_login $where ORDER BY id DESC LIMIT ? OFFSET ?";

// Preparar e executar contagem
if ($stmt = $conexao->prepare($sql_count)) {
    if (!empty($where)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result_count = $stmt->get_result();
    $total_rows = $result_count->fetch_assoc()['total'];
    $stmt->close();
    
    $total_paginas = ceil($total_rows / $limite);
} else {
    $total_rows = 0;
    $total_paginas = 1;
}

// Preparar e executar consulta de dados
$params_data = $params;
array_push($params_data, $limite, $offset);
$types_data = $types . "ii";

if ($stmt = $conexao->prepare($sql_data)) {
    if (!empty($where)) {
        $stmt->bind_param($types_data, ...$params_data);
    } else {
        $stmt->bind_param("ii", $limite, $offset);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $resultlist = $result;
} else {
    $sql_fallback = "SELECT * FROM log_login $where ORDER BY id DESC LIMIT $limite OFFSET $offset";
    $resultlist = $conexao->query($sql_fallback);
}

// Configuração da paginação limitada
$max_paginas_visiveis = 10; // Máximo de páginas mostradas na navegação
$meio = floor($max_paginas_visiveis / 2);

// Calcula a página inicial e final para exibição
if ($total_paginas <= $max_paginas_visiveis) {
    $pagina_inicial = 1;
    $pagina_final = $total_paginas;
} else {
    $pagina_inicial = max(1, $pagina - $meio);
    $pagina_final = min($total_paginas, $pagina_inicial + $max_paginas_visiveis - 1);
    
    // Ajusta se estiver perto do final
    if ($pagina_final - $pagina_inicial < $max_paginas_visiveis - 1) {
        $pagina_inicial = max(1, $pagina_final - $max_paginas_visiveis + 1);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Consultar Log</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php
echo "<h1 id='BemVindo'>Bem vindo <U>$logado</u><p>Consulta Log de Acesso</p></h1>";
?>

<br>
<div class="navegacao">
    <?php include("navegacao.php") ?>
</div>

<br>

<!-- Formulário de Pesquisa -->
<div class="container mb-4">
    <form method="GET" action="" class="row g-3">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control" placeholder="Pesquisar por usuário, ação ou nível de acesso..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Pesquisar
            </button>
        </div>
        <div class="col-md-2">
            <a href="?" class="btn btn-secondary w-100">
                <i class="fas fa-refresh"></i> Limpar
            </a>
        </div>
        <!-- Manter a página atual na pesquisa -->
        <input type="hidden" name="pagina" value="1">
    </form>
</div>

<div class="container">
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Usuário</th>
                    <th scope="col">Nível Acesso</th>
                    <th scope="col">Data</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Ação</th>
                    <th scope="col">Funções</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultlist && $resultlist->num_rows > 0) {
                    while ($user_data = $resultlist->fetch_assoc()) {
                        $data_formatada = !empty($user_data['data_login']) ? 
                            date('d/m/Y', strtotime($user_data['data_login'])) : '-';
                        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user_data['usuario']) . "</td>";
                        echo "<td>" . htmlspecialchars($user_data['nivel_acesso']) . "</td>";
                        echo "<td>{$data_formatada}</td>";
                        echo "<td>" . htmlspecialchars($user_data['hora_login']) . "</td>";
                        echo "<td>" . htmlspecialchars($user_data['acao']) . "</td>";
                        echo "<td>  
                            <a href='delete_log.php?id=" . $user_data['id'] . "' 
                               class='btn btn-sm btn-danger' 
                               onclick=\"return confirm('Tem certeza que deseja excluir este log?')\">
                                <i class='fas fa-trash'></i> Excluir
                            </a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Nenhum registro encontrado</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Sistema de Paginação Melhorado -->
    <?php if ($total_paginas > 1): ?>
    <nav aria-label="Navegação de páginas">
        <ul class="pagination justify-content-center flex-wrap">
            <?php
            $search_param = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';

            // Link para primeira página (<<)
            if ($pagina > 1) {
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?pagina=1' . $search_param . '" title="Primeira página">';
                echo '<i class="fas fa-angle-double-left"></i>';
                echo '</a>';
                echo '</li>';
            } else {
                echo '<li class="page-item disabled">';
                echo '<span class="page-link"><i class="fas fa-angle-double-left"></i></span>';
                echo '</li>';
            }

            // Link para página anterior (<)
            if ($pagina > 1) {
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?pagina=' . ($pagina - 1) . $search_param . '" title="Página anterior">';
                echo '<i class="fas fa-angle-left"></i>';
                echo '</a>';
                echo '</li>';
            } else {
                echo '<li class="page-item disabled">';
                echo '<span class="page-link"><i class="fas fa-angle-left"></i></span>';
                echo '</li>';
            }

            // Links das páginas (máximo 10)
            for ($i = $pagina_inicial; $i <= $pagina_final; $i++) {
                $active = ($i == $pagina) ? ' active' : '';
                echo '<li class="page-item' . $active . '">';
                echo '<a class="page-link" href="?pagina=' . $i . $search_param . '">' . $i . '</a>';
                echo '</li>';
            }

            // Link para próxima página (>)
            if ($pagina < $total_paginas) {
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?pagina=' . ($pagina + 1) . $search_param . '" title="Próxima página">';
                echo '<i class="fas fa-angle-right"></i>';
                echo '</a>';
                echo '</li>';
            } else {
                echo '<li class="page-item disabled">';
                echo '<span class="page-link"><i class="fas fa-angle-right"></i></span>';
                echo '</li>';
            }

            // Link para última página (>>)
            if ($pagina < $total_paginas) {
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?pagina=' . $total_paginas . $search_param . '" title="Última página">';
                echo '<i class="fas fa-angle-double-right"></i>';
                echo '</a>';
                echo '</li>';
            } else {
                echo '<li class="page-item disabled">';
                echo '<span class="page-link"><i class="fas fa-angle-double-right"></i></span>';
                echo '</li>';
            }
            ?>
        </ul>
    </nav>
    
    <div class="text-center text-muted mb-4">
        <small>
            Página <strong><?php echo $pagina; ?></strong> de <strong><?php echo $total_paginas; ?></strong> 
            | Total de <strong><?php echo $total_rows; ?></strong> registros
            <?php if ($total_paginas > $max_paginas_visiveis): ?>
            | Mostrando páginas <strong><?php echo $pagina_inicial; ?></strong> a <strong><?php echo $pagina_final; ?></strong>
            <?php endif; ?>
        </small>
    </div>
    <?php endif; ?>
</div>

<script>
    // Timeout de inatividade (1 hora)
    const tempoLimite = 3600000;
    let tempoInatividade;

    function resetarTempo() {
        clearTimeout(tempoInatividade);
        tempoInatividade = setTimeout(() => {
            window.location.href = "sistema.php?timeout=1";
        }, tempoLimite);
    }

    // Reinicia o timer em eventos do usuário
    document.addEventListener('mousemove', resetarTempo);
    document.addEventListener('keypress', resetarTempo);
    document.addEventListener('click', resetarTempo);
    
    // Inicia o timer quando a página carrega
    resetarTempo();

    // Foca no campo de pesquisa quando a página carrega
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            // Se não há valor de pesquisa, foca no campo
            if (!searchInput.value) {
                searchInput.focus();
            }
        }
    });
</script>

</body>
</html>