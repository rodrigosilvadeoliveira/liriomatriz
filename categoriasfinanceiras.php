<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');

include("navegacao.php");
include_once('config.php');
include_once('config_language.php');

if((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$logado = $_SESSION['usuario'];
$igreja = $_SESSION['igreja_id'];

/*
=====================================
SALVAR CATEGORIA (COM PROTEÇÃO DUPLA)
=====================================
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'criar') {

    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $tipo = $_POST['tipo'];

    $sqlVerifica = "
        SELECT id
        FROM categorias_financeiras
        WHERE nome = ?
        AND tipo = ?
        AND igreja_id = ?
    ";

    $stmtVerifica = $conexao->prepare($sqlVerifica);
    $stmtVerifica->bind_param("ssi", $nome, $tipo, $igreja);
    $stmtVerifica->execute();
    $resultado = $stmtVerifica->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['mensagem'] = "Categoria já cadastrada.";
    } else {
        $sql = "INSERT INTO categorias_financeiras (nome, descricao, tipo, igreja_id) VALUES (?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssi", $nome, $descricao, $tipo, $igreja);

        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Categoria criada com sucesso.";
        } else {
            $_SESSION['mensagem'] = "Erro ao criar categoria.";
        }
    }

    header("Location: categoriasfinanceiras.php");
    exit;
}

/*
=====================================
BUSCAR CATEGORIAS
=====================================
*/

$sqlReceitas = "SELECT id, nome, descricao, tipo FROM categorias_financeiras WHERE tipo = 'receita' AND igreja_id = ? ORDER BY nome";
$stmtR = $conexao->prepare($sqlReceitas);
$stmtR->bind_param("i", $igreja);
$stmtR->execute();
$resultReceitas = $stmtR->get_result();
$totalReceitas = $resultReceitas->num_rows;

$sqlDespesas = "SELECT id, nome, descricao, tipo FROM categorias_financeiras WHERE tipo = 'despesa' AND igreja_id = ? ORDER BY nome";
$stmtD = $conexao->prepare($sqlDespesas);
$stmtD->bind_param("i", $igreja);
$stmtD->execute();
$resultDespesas = $stmtD->get_result();
$totalDespesas = $resultDespesas->num_rows;

$totalGeral = $totalReceitas + $totalDespesas;
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Categorias Financeiras - Sistema Financeiro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #eef1f4;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container Principal Responsivo */
        .main-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Cards Responsivos */
        .card-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }

        .card-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header-modern i {
            font-size: 1.5rem;
        }

        .card-body-modern {
            padding: 30px;
        }

        /* Formulário Responsivo */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 0.9rem;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 12px;
            border: 2px solid #e1e5e9;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Radio Buttons Estilizados */
        .tipo-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .tipo-option {
            flex: 1;
            min-width: 120px;
        }

        .tipo-option input[type="radio"] {
            display: none;
        }

        .tipo-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            border: 2px solid #e1e5e9;
            background: white;
            margin: 0;
        }

        .tipo-option.receita label {
            color: #28a745;
        }

        .tipo-option.despesa label {
            color: #dc3545;
        }

        .tipo-option input[type="radio"]:checked + label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        /* Botões */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Seção de Resultados */
        .resultados-section {
            margin-top: 30px;
        }

        .stats-bar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .total-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Box de Categorias */
        .categoria-box {
            border-radius: 20px;
            margin-bottom: 30px;
            overflow: hidden;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .categoria-header {
            padding: 18px 25px;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .categoria-header.receita {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .categoria-header.despesa {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        }

        .categoria-count {
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        /* Tabela Responsiva */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eef2f7;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* Ações */
        .acoes {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-editar, .btn-remover {
            padding: 8px 16px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-editar {
            background: #0d6efd;
            color: white;
        }

        .btn-editar:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
        }

        .btn-remover {
            background: #dc3545;
            color: white;
        }

        .btn-remover:hover {
            background: #bb2d3b;
            transform: translateY(-2px);
        }

        /* Modal Responsivo */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 20px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-content input,
        .modal-content textarea {
            margin-bottom: 15px;
        }

        .modal-botoes {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-botoes button {
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .modal-botoes button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-botoes button[type="button"] {
            background: #6c757d;
            color: white;
        }

        /* Mensagem */
        .mensagem {
            margin-top: 20px;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        .mensagem.sucesso {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensagem.erro {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .card-body-modern {
                padding: 20px;
            }

            .card-header-modern {
                padding: 15px 20px;
                font-size: 1.1rem;
            }

            th, td {
                padding: 10px;
                font-size: 0.85rem;
            }

            .tipo-group {
                flex-direction: column;
            }

            .tipo-option {
                min-width: auto;
            }

            .stats-bar {
                flex-direction: column;
                text-align: center;
            }

            .btn-editar, .btn-remover {
                padding: 6px 12px;
                font-size: 0.75rem;
            }

            .modal-content {
                margin: 20% auto;
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .categoria-header {
                flex-direction: column;
                text-align: center;
            }

            .acoes {
                flex-direction: column;
            }

            .btn-editar, .btn-remover {
                width: 100%;
                justify-content: center;
            }

            .modal-content {
                width: 95%;
                margin: 30% auto;
            }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <!-- Card de Criação -->
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="fas fa-plus-circle"></i>
            <span>Criar Nova Categoria</span>
        </div>
        <div class="card-body-modern">
            <form method="POST">
                <input type="hidden" name="acao" value="criar">
                
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Nome da categoria</label>
                    <input type="text" name="nome" placeholder="Ex: Dízimos, Ofertas, Água, Luz..." required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Descrição (opcional)</label>
                    <textarea name="descricao" placeholder="Descreva a finalidade desta categoria..."></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-chart-line"></i> Tipo de categoria</label>
                    <div class="tipo-group">
                        <div class="tipo-option receita">
                            <input type="radio" name="tipo" value="receita" id="tipo_receita" required>
                            <label for="tipo_receita">
                                <i class="fas fa-arrow-up"></i>
                                Receita
                            </label>
                        </div>
                        <div class="tipo-option despesa">
                            <input type="radio" name="tipo" value="despesa" id="tipo_despesa">
                            <label for="tipo_despesa">
                                <i class="fas fa-arrow-down"></i>
                                Despesa
                            </label>
                        </div>
                    </div>
                </div>

                <div style="text-align: center;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Criar Categoria
                    </button>
                </div>
            </form>

            <?php if (isset($_SESSION['mensagem'])): ?>
                <div class="mensagem <?php echo strpos($_SESSION['mensagem'], 'sucesso') !== false ? 'sucesso' : (strpos($_SESSION['mensagem'], 'Erro') !== false ? 'erro' : 'sucesso'); ?>">
                    <i class="fas <?php echo strpos($_SESSION['mensagem'], 'sucesso') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php 
                        echo $_SESSION['mensagem']; 
                        unset($_SESSION['mensagem']);
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Seção de Resultados -->
    <div class="resultados-section">
        <div class="stats-bar">
            <div class="total-badge">
                <i class="fas fa-chart-pie"></i> Total: <?php echo $totalGeral; ?> categorias
            </div>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <span style="color: #28a745;">
                    <i class="fas fa-arrow-up"></i> Receitas: <?php echo $totalReceitas; ?>
                </span>
                <span style="color: #dc3545;">
                    <i class="fas fa-arrow-down"></i> Despesas: <?php echo $totalDespesas; ?>
                </span>
            </div>
        </div>

        <!-- Box Receitas -->
        <div class="categoria-box">
            <div class="categoria-header receita">
                <div>
                    <i class="fas fa-arrow-up"></i> Receitas
                </div>
                <div class="categoria-count">
                    <i class="fas fa-list"></i> <?php echo $totalReceitas; ?> categorias
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-tag"></i> Nome da categoria</th>
                            <th><i class="fas fa-align-left"></i> Descrição</th>
                            <th><i class="fas fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($totalReceitas > 0): ?>
                            <?php while ($r = $resultReceitas->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($r['nome']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($r['descricao'] ?: '—'); ?></td>
                                    <td class="acoes">
                                        <a href="#" class="btn-editar" onclick="abrirModal(
                                            '<?php echo $r['id']; ?>',
                                            '<?php echo htmlspecialchars($r['nome'], ENT_QUOTES, 'UTF-8'); ?>',
                                            '<?php echo htmlspecialchars($r['descricao'], ENT_QUOTES, 'UTF-8'); ?>',
                                            '<?php echo $r['tipo']; ?>'
                                        )">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="remover_categoriafinanceira.php?id=<?php echo $r['id']; ?>" 
                                           class="btn-remover" 
                                           onclick="return confirm('⚠️ Deseja realmente remover a categoria \"<?php echo htmlspecialchars($r['nome']); ?>\"?\n\nEsta ação não poderá ser desfeita!')">
                                            <i class="fas fa-trash"></i> Remover
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Nenhuma categoria de receita cadastrada</p>
                                        <small>Clique em "Criar Categoria" para adicionar</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Box Despesas -->
        <div class="categoria-box">
            <div class="categoria-header despesa">
                <div>
                    <i class="fas fa-arrow-down"></i> Despesas
                </div>
                <div class="categoria-count">
                    <i class="fas fa-list"></i> <?php echo $totalDespesas; ?> categorias
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-tag"></i> Nome da categoria</th>
                            <th><i class="fas fa-align-left"></i> Descrição</th>
                            <th><i class="fas fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($totalDespesas > 0): ?>
                            <?php while ($d = $resultDespesas->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($d['nome']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($d['descricao'] ?: '—'); ?></td>
                                    <td class="acoes">
                                        <a href="#" class="btn-editar" onclick="abrirModal(
                                            '<?php echo $d['id']; ?>',
                                            '<?php echo htmlspecialchars($d['nome'], ENT_QUOTES, 'UTF-8'); ?>',
                                            '<?php echo htmlspecialchars($d['descricao'], ENT_QUOTES, 'UTF-8'); ?>',
                                            '<?php echo $d['tipo']; ?>'
                                        )">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="remover_categoriafinanceira.php?id=<?php echo $d['id']; ?>" 
                                           class="btn-remover" 
                                           onclick="return confirm('⚠️ Deseja realmente remover a categoria \"<?php echo htmlspecialchars($d['nome']); ?>\"?\n\nEsta ação não poderá ser desfeita!')">
                                            <i class="fas fa-trash"></i> Remover
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>Nenhuma categoria de despesa cadastrada</p>
                                        <small>Clique em "Criar Categoria" para adicionar</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDITAR -->
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <h3>
            <i class="fas fa-edit"></i>
            Editar Categoria
        </h3>
        <form method="POST" action="editar_categoriafinanceira.php">
            <input type="hidden" name="id" id="edit_id">
            
            <label><i class="fas fa-tag"></i> Nome</label>
            <input type="text" name="nome" id="edit_nome" required>
            
            <label><i class="fas fa-align-left"></i> Descrição</label>
            <textarea name="descricao" id="edit_descricao" rows="4"></textarea>
            
            <label><i class="fas fa-chart-line"></i> Tipo</label>
            <div class="tipo-group" style="margin-top: 5px;">
                <div class="tipo-option receita" style="flex: 1;">
                    <input type="radio" name="tipo" value="receita" id="edit_receita">
                    <label for="edit_receita" style="margin: 0;">
                        <i class="fas fa-arrow-up"></i> Receita
                    </label>
                </div>
                <div class="tipo-option despesa" style="flex: 1;">
                    <input type="radio" name="tipo" value="despesa" id="edit_despesa">
                    <label for="edit_despesa" style="margin: 0;">
                        <i class="fas fa-arrow-down"></i> Despesa
                    </label>
                </div>
            </div>
            
            <div class="modal-botoes">
                <button type="submit">
                    <i class="fas fa-save"></i> Salvar
                </button>
                <button type="button" onclick="fecharModal()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModal(id, nome, descricao, tipo) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nome').value = nome;
        document.getElementById('edit_descricao').value = descricao;
        
        if (tipo === 'receita') {
            document.getElementById('edit_receita').checked = true;
        } else {
            document.getElementById('edit_despesa').checked = true;
        }
        
        document.getElementById('modalEditar').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function fecharModal() {
        document.getElementById('modalEditar').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Fechar modal ao clicar fora
    window.onclick = function(event) {
        const modal = document.getElementById('modalEditar');
        if (event.target === modal) {
            fecharModal();
        }
    }

    // Fechar modal com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            fecharModal();
        }
    });

    // Scroll suave para mensagens
    if (document.querySelector('.mensagem')) {
        setTimeout(() => {
            document.querySelector('.mensagem').style.opacity = '0';
            setTimeout(() => {
                const msg = document.querySelector('.mensagem');
                if (msg) msg.style.display = 'none';
            }, 500);
        }, 3000);
    }
</script>

</body>
</html>