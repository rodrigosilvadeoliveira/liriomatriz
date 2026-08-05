<?php
date_default_timezone_set('America/Sao_Paulo');

include('verificarLogin.php');
verificarLogin();

include('verifica_permissao.php');
include_once('config.php');

// ======================================
// VERIFICAÇÃO DE SESSÃO
// ======================================
if (!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {

    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);

    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];

// ======================================
// PAGINAÇÃO
// ======================================
$limite = 20;

$pagina = isset($_GET['pagina'])
    ? (int) $_GET['pagina']
    : 1;

if ($pagina < 1) {
    $pagina = 1;
}

$offset = ($pagina - 1) * $limite;

// ======================================
// PESQUISA
// ======================================
$where  = "";
$params = [];
$types  = "";

if (!empty($_GET['search'])) {

    $search = trim($_GET['search']);

    $where = "
        WHERE
            nome LIKE ?
            OR usuario LIKE ?
            OR email LIKE ?
            OR nivel_acesso LIKE ?
    ";

    $searchTerm = "%{$search}%";

    $params = [
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    ];

    $types = "ssss";
}

// ======================================
// CONSULTAS
// ======================================
$sqlCount = "
    SELECT COUNT(*) AS total
    FROM cadastroadm
    {$where}
";

$sqlData = "
    SELECT *
    FROM cadastroadm
    {$where}
    ORDER BY id DESC
    LIMIT ? OFFSET ?
";

// ======================================
// TOTAL REGISTROS
// ======================================
$total_rows = 0;
$total_paginas = 1;

$stmtCount = $conexao->prepare($sqlCount);

if (!$stmtCount) {
    die("Erro SQL COUNT: " . $conexao->error);
}

if (!empty($params)) {
    $stmtCount->bind_param($types, ...$params);
}

$stmtCount->execute();

$resultCount = $stmtCount->get_result();

if ($rowCount = $resultCount->fetch_assoc()) {

    $total_rows = (int) $rowCount['total'];
}

$stmtCount->close();

$total_paginas = max(1, ceil($total_rows / $limite));

// ======================================
// CONSULTA PRINCIPAL
// ======================================
$stmtData = $conexao->prepare($sqlData);

if (!$stmtData) {
    die("Erro SQL DATA: " . $conexao->error);
}

if (!empty($params)) {

    $paramsData = $params;
    $paramsData[] = $limite;
    $paramsData[] = $offset;

    $typesData = $types . "ii";

    $stmtData->bind_param(
        $typesData,
        ...$paramsData
    );

} else {

    $stmtData->bind_param(
        "ii",
        $limite,
        $offset
    );
}

$stmtData->execute();

$resultlist = $stmtData->get_result();

// ======================================
// PAGINAÇÃO VISÍVEL
// ======================================
$max_paginas_visiveis = 10;
$meio = floor($max_paginas_visiveis / 2);

if ($total_paginas <= $max_paginas_visiveis) {

    $pagina_inicial = 1;
    $pagina_final   = $total_paginas;

} else {

    $pagina_inicial = max(
        1,
        $pagina - $meio
    );

    $pagina_final = min(
        $total_paginas,
        $pagina_inicial + $max_paginas_visiveis - 1
    );

    if (
        ($pagina_final - $pagina_inicial)
        < ($max_paginas_visiveis - 1)
    ) {

        $pagina_inicial = max(
            1,
            $pagina_final - $max_paginas_visiveis + 1
        );
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Acessos de Voluntário</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

</head>

<body>

<div class="navegacao">
    <?php include("navegacao.php"); ?>
</div>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="h3 text-gray-800">
            <i class="fas fa-user-plus me-2"></i>
            Acessos de Voluntários
        </h1>

    </div>

</div>

<!-- PESQUISA -->
<div class="container mb-4">

    <form
        method="GET"
        action=""
        class="row g-3"
    >

        <div class="col-md-8">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Pesquisar nome, usuário, email ou perfil..."
                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
            >

        </div>

        <div class="col-md-2">

            <button
                type="submit"
                class="btn btn-primary w-100"
            >
                <i class="fas fa-search"></i>
                Pesquisar
            </button>

        </div>

        <div class="col-md-2">

            <a
                href="?"
                class="btn btn-secondary w-100"
            >
                <i class="fas fa-refresh"></i>
                Limpar
            </a>

        </div>

        <input
            type="hidden"
            name="pagina"
            value="1"
        >

    </form>

</div>

<!-- TABELA -->
<div class="container">

    <div class="table-responsive">

        <table class="table table-striped table-hover">

            <thead class="table-dark">

            <tr>

                <th>#</th>
                <th>Voluntário(a)</th>
                <th>Usuário</th>
                <th>Perfil</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Celular</th>
                <th>Ações</th>

            </tr>

            </thead>

            <tbody>

            <?php if ($resultlist && $resultlist->num_rows > 0): ?>

                <?php while ($user_data = $resultlist->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $user_data['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user_data['nome']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user_data['usuario']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user_data['nivel_acesso']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user_data['email']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user_data['telefone']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($user_data['celular']); ?>
                        </td>

                        <td>

                            <a
                                class="btn btn-sm btn-primary"
                                href="editar_acesso.php?id=<?php echo $user_data['id']; ?>"
                                title="Editar"
                            >
                                <i class="fas fa-edit"></i>
                            </a>

                            <a
                                class="btn btn-sm btn-danger"
                                href="deletevol.php?id=<?php echo $user_data['id']; ?>"
                                title="Excluir"
                                onclick="return confirm('Deseja realmente excluir?')"
                            >
                                <i class="fas fa-trash"></i>
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td
                        colspan="8"
                        class="text-center"
                    >
                        Nenhum registro encontrado.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- PAGINAÇÃO -->
    <?php if ($total_paginas > 1): ?>

        <nav class="mt-4">

            <ul class="pagination justify-content-center flex-wrap">

                <?php
                $search_param = isset($_GET['search'])
                    ? '&search=' . urlencode($_GET['search'])
                    : '';
                ?>

                <!-- PRIMEIRA -->
                <li class="page-item <?php echo ($pagina <= 1 ? 'disabled' : ''); ?>">

                    <a
                        class="page-link"
                        href="?pagina=1<?php echo $search_param; ?>"
                    >
                        <i class="fas fa-angle-double-left"></i>
                    </a>

                </li>

                <!-- ANTERIOR -->
                <li class="page-item <?php echo ($pagina <= 1 ? 'disabled' : ''); ?>">

                    <a
                        class="page-link"
                        href="?pagina=<?php echo max(1, $pagina - 1) . $search_param; ?>"
                    >
                        <i class="fas fa-angle-left"></i>
                    </a>

                </li>

                <!-- NÚMEROS -->
                <?php for ($i = $pagina_inicial; $i <= $pagina_final; $i++): ?>

                    <li class="page-item <?php echo ($i == $pagina ? 'active' : ''); ?>">

                        <a
                            class="page-link"
                            href="?pagina=<?php echo $i . $search_param; ?>"
                        >
                            <?php echo $i; ?>
                        </a>

                    </li>

                <?php endfor; ?>

                <!-- PRÓXIMA -->
                <li class="page-item <?php echo ($pagina >= $total_paginas ? 'disabled' : ''); ?>">

                    <a
                        class="page-link"
                        href="?pagina=<?php echo min($total_paginas, $pagina + 1) . $search_param; ?>"
                    >
                        <i class="fas fa-angle-right"></i>
                    </a>

                </li>

                <!-- ÚLTIMA -->
                <li class="page-item <?php echo ($pagina >= $total_paginas ? 'disabled' : ''); ?>">

                    <a
                        class="page-link"
                        href="?pagina=<?php echo $total_paginas . $search_param; ?>"
                    >
                        <i class="fas fa-angle-double-right"></i>
                    </a>

                </li>

            </ul>

        </nav>

        <div class="text-center text-muted mb-4">

            <small>

                Página
                <strong><?php echo $pagina; ?></strong>
                de
                <strong><?php echo $total_paginas; ?></strong>

                |

                Total:
                <strong><?php echo $total_rows; ?></strong>

                registros

            </small>

        </div>

    <?php endif; ?>

</div>

<script>

    // ======================================
    // TIMEOUT
    // ======================================

    const tempoLimite = 3600000;

    let tempoInatividade;

    function resetarTempo() {

        clearTimeout(tempoInatividade);

        tempoInatividade = setTimeout(() => {

            window.location.href =
                "sistema.php?timeout=1";

        }, tempoLimite);
    }

    document.addEventListener(
        'mousemove',
        resetarTempo
    );

    document.addEventListener(
        'keypress',
        resetarTempo
    );

    document.addEventListener(
        'click',
        resetarTempo
    );

    resetarTempo();

    // ======================================
    // FOCO PESQUISA
    // ======================================

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const searchInput =
                document.querySelector(
                    'input[name="search"]'
                );

            if (
                searchInput &&
                !searchInput.value &&
                window.innerWidth > 1300
            ) {

                searchInput.focus();
            }
        }
    );

</script>

</body>
</html>