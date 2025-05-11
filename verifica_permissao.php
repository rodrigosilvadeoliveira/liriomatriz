<?php

include_once('config.php');

$perfil = $_SESSION['nivel_acesso'] ?? '';

// Pega a página atual em letras minúsculas
$paginaAtual = strtolower(basename($_SERVER['PHP_SELF']));

// Define as permissões de acesso por página
$permissoes = [
    'vendas' => ['admin', 'master'],
    'vendasrealizadas' => ['admin', 'master'],
    'cadastroProduto' => ['admin', 'master'],
    'relatoriosAdm' => ['admin', 'master'],
    'consulta_voluntarios' => ['admin', 'master'],
    'formularioAdm' => ['admin', 'master'],
    'consulta_Adm' => ['admin', 'master'],
    'consultaProdutosAdm' => ['admin', 'master'],

    'vendas.php' => ['admin', 'master'],
    'vendasrealizadas.php' => ['admin', 'master'],
    'formularioAdm.php' => ['admin', 'master'],
    'consulta_Adm.php' => ['admin', 'master'],
    'cadastroProduto.php' => ['admin', 'master'],
    'relatoriosAdm.php' => ['admin', 'master'],
    'consulta_voluntarios.php' => ['admin', 'master'],
    'consultaProdutosAdm.php' => ['admin', 'master'],

    'formularioMaster' => ['master'],
    'consulta_membros' => ['secretaria', 'master'],
    'consulta_membros_busca' => ['secretaria', 'master'],
    'cadastroForm' => ['secretaria', 'master'],
    'cadastroMembrosAdm' => ['secretaria', 'master'],
    'consulta_logs' => ['master'],
    'edit_formularioMembros' => ['secretaria', 'master'],
    'cadastroEvento' => ['midia', 'master'],

    'formularioMaster.php' => ['master'],
    'formulariomaster.php' => ['master'],
    'consulta_membros.php' => ['secretaria', 'master'],
    'consulta_membros_busca.php' => ['secretaria', 'master'],
    'cadastroForm.php' => ['secretaria', 'master'],
    'cadastroMembrosAdm.php' => ['secretaria', 'master'],
    'consulta_logs.php' => ['master'],
    'edit_formularioMembros.php' => ['secretaria', 'master'],
    'cadastroEvento.php' => ['midia', 'master'],
    'cadastroevento.php' => ['midia', 'master'],
'cadastrolive' => ['midia', 'master']
    
    
];

// Obtém o nome da página atual
if (isset($permissoes[$paginaAtual])) {
    if (!in_array($perfil, $permissoes[$paginaAtual])) {
        header("Location: acesso_negadoperfil.php");
        exit;
    }
}
?>
