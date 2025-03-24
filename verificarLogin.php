<?php
function verificarLogin() {
    session_start();

    // Tempo limite em segundos (1 hora)
    $tempo_limite = 3600;

    // Verifica se existe um usuário logado
    if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
        header('Location: sistema.php');
        exit;
    }

    // Verifica se a sessão já passou do tempo limite
    if (isset($_SESSION['inicio_sessao'])) {
        $tempo_decorrido = time() - $_SESSION['inicio_sessao'];

        if ($tempo_decorrido > $tempo_limite) {
            // Sessão expirada, destruir e redirecionar
            session_unset();
            session_destroy();
            header('Location: sistema.php?timeout=1');
            exit;
        }
    } else {
        // Se não existir, define o tempo inicial da sessão
        $_SESSION['inicio_sessao'] = time();
    }
}
?>
