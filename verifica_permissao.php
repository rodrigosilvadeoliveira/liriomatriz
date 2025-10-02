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
    'consulta_niver' => ['secretaria', 'master'],
    
    'cadastroEvento' => ['midia', 'master'],
    'escalalouvor' => ['lider','master'],
    'paginainicial.php' => ['lider','master','secretaria','midias','consulta'],
    'formularioMaster.php' => ['master'],
    'formulariomaster.php' => ['master'],
    'cadastrodevendas' => ['master'],
    'consulta_membros.php' => ['secretaria', 'master'],
    'consulta_membros_busca.php' => ['secretaria', 'master'],
    'cadastroForm.php' => ['secretaria', 'master'],
    'cadastroMembrosAdm.php' => ['secretaria', 'master'],
    'consulta_logs.php' => ['master'],
    'edit_formularioMembros.php' => ['secretaria', 'master'],
    'cadastroEvento.php' => ['midia', 'master'],
    'cadastroevento.php' => ['midia', 'master'],
    'cadastrovoluntariadoescala' => ['lider','master'],
    'consulta_voluntariado' => ['lider','master'],
    'escalalouvor.php' => ['lider','master'],
    'escalalouvorkids' => ['lider','master'],
    'escalasom.php' => ['lider','master'],
    'escalamidias.php' => ['lider','master'],
    'escalamidias' => ['lider','master'],
    'escalacriativo' => ['lider','master'],
    'escaladanca' => ['lider','master'],
    'escalakids' => ['lider','master'],
    'escalalouvorhomens.php' => ['lider','master'],
    'escalalouvormulheres.php' => ['lider','master'],
    'consultaescala.php' => ['lider','master'],
    'consultaescala' => ['lider','master'],
    'consultaescalacriativo' => ['lider','master'],
    'consultaescalasom' => ['lider','master'],
    'consultaescalamidias' => ['lider','master'],
    'consultaescaladanca' => ['lider','master'],
    'consultaescalakids' => ['lider','master'],
    'consultaescalastaff' => ['lider','master'],
    'consultaescalasomvol' => ['consulta', 'ministro'],
    'consultaescaladancavol' => ['consulta', 'ministro'],
    'consultaescalamidiasvol' => ['consulta', 'ministro'],
    'consultaescalastaffvol' => ['consulta', 'ministro'],
    'consultaescalakidsvol' => ['consulta', 'ministro'],
    'formulariolider' => ['consulta', 'ministro'],
    'edit_voluntarioescala' => ['lider','master'],
     'musicas' => ['lider','master','ministro'],
     'consultarepertorio' => ['lider','master', 'consulta'],
     'edit_musica' => ['lider','master', 'minitro'],


    
    
];

// Obtém o nome da página atual
if (isset($permissoes[$paginaAtual])) {
    if (!in_array($perfil, $permissoes[$paginaAtual])) {
        header("Location: acesso_negado.php");
        exit;
    }
}
?>
