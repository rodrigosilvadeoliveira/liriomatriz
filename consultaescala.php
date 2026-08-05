<?php
date_default_timezone_set('America/Sao_Paulo');

include('verificarLogin.php');
verificarLogin();

include('verifica_permissao.php');
include_once('config.php');
include_once('config_language.php');

if ((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];
$igreja = intval($_SESSION['igreja_id']);


/*
========================================
CARREGAR TIPOS DE ESCALA
========================================
*/
$tabelas_escalas = [];

$stmtCategorias = $conexao->prepare("
    SELECT tipo
    FROM categoria_escala
    WHERE igreja_id = ?
    ORDER BY tipo ASC
");

$stmtCategorias->bind_param("i", $igreja);
$stmtCategorias->execute();

$resCategorias = $stmtCategorias->get_result();

while ($row = $resCategorias->fetch_assoc()) {

    $tipo = trim($row['tipo']);

    if (!empty($tipo)) {
        $tabelas_escalas[$tipo] = $tipo;
    }
}

$stmtCategorias->close();


/*
========================================
VALIDAR TIPO SELECIONADO
========================================
*/
$tabela_selecionada = trim($_GET['tabela'] ?? '');

if (
    empty($tabela_selecionada)
    || !array_key_exists($tabela_selecionada, $tabelas_escalas)
) {

    $primeiroTipo = array_key_first($tabelas_escalas);

    $tabela_selecionada = $primeiroTipo ?: '';
}


/*
========================================
VALIDAÇÃO
========================================
*/
if ($tabela_selecionada == '') {

    die("
    <div class='container mt-5'>
        <div class='alert alert-warning'>
            Nenhuma categoria de escala cadastrada.
        </div>
    </div>
    ");
}


/*
========================================
BUSCAR ESCALAS
========================================
*/
$stmtEscalas = $conexao->prepare("
    SELECT 
        id,
        nome,
        pdf_path,
        data_criacao
    FROM escalas_louvor
    WHERE igreja_id = ?
    AND tipo = ?
    ORDER BY id DESC
");

$stmtEscalas->bind_param(
    "is",
    $igreja,
    $tabela_selecionada
);

$stmtEscalas->execute();

$res = $stmtEscalas->get_result();

$escalas = [];

while ($row = $res->fetch_assoc()) {
    $escalas[] = $row;
}

$stmtEscalas->close();


/*
========================================
ESTATÍSTICAS
========================================
*/
function obterEstatisticasTabela($conexao, $tipo, $igreja)
{
    $stmt = $conexao->prepare("
        SELECT COUNT(*) as total
        FROM escalas_louvor
        WHERE igreja_id = ?
        AND tipo = ?
    ");

    $stmt->bind_param("is", $igreja, $tipo);
    $stmt->execute();

    $res = $stmt->get_result();

    $total = 0;

    if ($res && $row = $res->fetch_assoc()) {
        $total = intval($row['total']);
    }

    $stmt->close();

    return $total;
}

include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>
        Consulta Escalas -
        <?php echo htmlspecialchars($tabela_selecionada); ?>
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="styleconsultaescala.css?t=<?= time() ?>">

</head>

<body class="container py-4">

    <div class="navegacao">
        <?php include("navegacao.php") ?>
    </div>

    <br><br>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <h2>
            <i class="bi bi-music-note-list"></i>
            <?php echo __('consultar_escala') ?>
        </h2>

        <?php if ($hideigreja): ?>

            <a href="consultarepertorio.php" class="btn-consulta-repertorio">
                <i class="bi bi-music-note-beamed"></i>
                <?php echo __('consultar_repertorio') ?>
            </a>

        <?php endif; ?>

    </div>


    <!-- SELECT DE TIPOS -->
    <div class="row mb-4">

        <div class="col-md-4">

            <form method="GET">

                <label class="form-label fw-bold">
                    <?php echo __('selecione_tipo_escala') ?>
                </label>

                <select
                    name="tabela"
                    class="form-select"
                    onchange="this.form.submit()">

                    <?php foreach ($tabelas_escalas as $tabela_id => $tabela_nome): ?>

                        <?php
                        $total = obterEstatisticasTabela(
                            $conexao,
                            $tabela_id,
                            $igreja
                        );
                        ?>

                        <option
                            value="<?php echo htmlspecialchars($tabela_id); ?>"
                            <?php echo $tabela_selecionada == $tabela_id ? 'selected' : ''; ?>>

                            <?php echo htmlspecialchars($tabela_nome); ?>
                            (<?php echo $total; ?>)

                        </option>

                    <?php endforeach; ?>

                </select>

            </form>

        </div>

    </div>


    <!-- LISTA -->
    <div class="row">

        <div class="col-12">

            <h4 class="mb-3">

                <?php echo htmlspecialchars($tabela_selecionada); ?>

                <span class="badge bg-primary">
                    <?php echo count($escalas); ?> itens
                </span>

            </h4>

            <?php if (empty($escalas)): ?>

                <div class="alert alert-info">

                    <i class="bi bi-info-circle"></i>

                    Nenhuma escala encontrada para esta categoria.

                </div>

            <?php else: ?>

                <?php foreach ($escalas as $escala): ?>

                    <div class="escala-card">

                        <div
                            class="escala-header"
                            data-id="<?php echo $escala['id']; ?>"
                            data-tabela="<?php echo htmlspecialchars($tabela_selecionada); ?>">

                            <div>

                                <div class="escala-titulo">

                                    <?php echo htmlspecialchars($escala['nome']); ?>

                                    <span class="badge-tabela">
                                        <?php echo htmlspecialchars($tabela_selecionada); ?>
                                    </span>

                                </div>

                                <?php if (!empty($escala['data_criacao'])): ?>

                                    <div class="escala-data text-muted small mt-1">

                                        <i class="bi bi-calendar"></i>

                                        <?php echo date('d/m/Y H:i', strtotime($escala['data_criacao'])); ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                            <div class="escala-acoes d-flex gap-2 flex-wrap">

                                <?php if (!empty($escala['pdf_path'])): ?>

                                    <button
                                        class="btn-download"
                                        onclick="visualizarPDF(event, '<?php echo htmlspecialchars($escala['pdf_path']); ?>')">

                                        <i class="bi bi-download"></i>
                                        PDF

                                    </button>

                                <?php else: ?>

                                    <button class="btn-download" disabled>

                                        <i class="bi bi-file-earmark-x"></i>
                                        Sem PDF

                                    </button>

                                <?php endif; ?>


                                <?php if ($pode_editar): ?>

                                    <a
                                        href="editar_escala.php?id=<?php echo $escala['id']; ?>&tabela=<?php echo urlencode($tabela_selecionada); ?>"
                                        class="btn-editar">

                                        <i class="bi bi-pencil"></i>
                                        <?php echo __('editar') ?>

                                    </a>

                                <?php endif; ?>


                                <?php if ($pode_excluir): ?>

                                    <button
                                        class="btn-excluir"
                                        onclick="excluirEscala(event, <?php echo $escala['id']; ?>, '<?php echo htmlspecialchars($tabela_selecionada); ?>')">

                                        <i class="bi bi-trash"></i>
                                        <?php echo __('excluir') ?>

                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                        <?php
$tabelaId = preg_replace(
    '/[^a-zA-Z0-9\-_]/',
    '',
    str_replace(' ', '-', trim($tabela_selecionada))
);
?>

<div 
    class="escala-detalhes"
    id="detalhes-<?php echo $tabelaId; ?>-<?php echo $escala['id']; ?>"
    data-carregado="false">
</div>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>


    <!-- MODAL PDF -->
    <div
        class="modal fade"
        id="modalPdf"
        tabindex="-1"
        aria-labelledby="modalPdfLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalPdfLabel">
                        <?php echo __('visualizar_escala') ?>
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>

                <div class="modal-body">

                    <iframe
                        id="pdfPreview"
                        class="pdf-preview"
                        src="">
                    </iframe>

                </div>

                <div class="modal-footer">

                    <a
                        id="downloadPdf"
                        href="#"
                        class="btn btn-success"
                        download>

                        <i class="bi bi-download"></i>
                        Download PDF

                    </a>

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        <?php echo __('fechar') ?>

                    </button>

                </div>

            </div>

        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        $(document).ready(function () {

            $(".escala-header").click(function (e) {

                if ($(e.target).closest(
                    '.btn-download, .btn-excluir, .btn-editar'
                ).length) {
                    return;
                }

                let id = $(this).data("id");
                let tabela = $(this).data("tabela");

let tabelaId = tabela
    .toString()
    .trim()
    .replace(/\s+/g, '-')
    .replace(/[^\w\-]/g, '');

                let detalhesDiv = $("#detalhes-" + tabelaId + "-" + id);

                let carregado = detalhesDiv.data("carregado");

                if (detalhesDiv.is(":visible")) {

                    detalhesDiv.slideUp();

                } else {

                    if (
                        carregado === false
                        || carregado === "false"
                    ) {

                        detalhesDiv.html(`
                            <div class="text-center p-3">
                                <i class="bi bi-hourglass-split"></i>
                                Carregando...
                            </div>
                        `).slideDown();

                        $.get("carregar_escala.php", {

                            id: id,
                            tabela: tabela

                        }, function (html) {

                            detalhesDiv
                                .html(html)
                                .data("carregado", true);

                        }).fail(function () {

                            detalhesDiv.html(`
                                <div class="alert alert-danger">
                                    Erro ao carregar detalhes
                                </div>
                            `);
                        });

                    } else {

                        detalhesDiv.slideDown();
                    }
                }
            });

        });


        function visualizarPDF(event, pdfPath) {

            event.stopPropagation();

            if (!pdfPath) {

                alert('PDF não disponível para visualização.');
                return;
            }

            $("#pdfPreview").attr('src', pdfPath);

            $("#downloadPdf").attr('href', pdfPath);

            let modal = new bootstrap.Modal(
                document.getElementById('modalPdf')
            );

            modal.show();
        }


        function excluirEscala(event, id, tabela) {

            event.stopPropagation();

            if (
                confirm(
                    "Tem certeza que deseja excluir esta escala?\nEsta ação não pode ser desfeita."
                )
            ) {

                $.post("excluir_escala.php", {

                    id: id,
                    tabela: tabela

                }, function (resposta) {

                    if (resposta.trim() === "ok") {

                        location.reload();

                    } else {

                        alert(
                            "Erro ao excluir escala: " + resposta
                        );
                    }

                }).fail(function () {

                    alert(
                        "Erro na comunicação com o servidor."
                    );

                });
            }
        }

    </script>

</body>

</html>