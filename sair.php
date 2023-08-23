<?php
// Inicia a sessão (se já não estiver iniciada)
session_start();

// Limpa todas as variáveis de sessão relacionadas ao login
unset($_SESSION['usuario_logado']);

// Destrói a sessão
session_destroy();

// Redireciona o usuário para a página de login (ou qualquer outra página de sua escolha)
header("Location: index.php");
exit();
?>
   // session_start();
   // unset($_SESSION['usuario']);
    //unset($_SESSION['senha']);
    //header("Location: index.php");

?>