<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['usuario']) && !empty($_POST['senha'])) {
    include_once('config.php');
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Consulta para buscar o usuário e seu perfil
    $sql = "SELECT * FROM cadastroadm WHERE usuario = ? AND senha = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $usuario, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows < 1) {
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        echo 'Usuário ou senha inválidos';
        echo '<a href="homeLirio.php" id="voltar">Voltar para a Página Home</a>';
    } else {
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['senha'] = $senha;
        $_SESSION['nivel_acesso'] = $row['nivel_acesso']; // Armazena o perfil do usuário na sessão

        // Redireciona com base no perfil
        switch ($row['nivel_acesso']) {
            case 'admin':
                header('Location: vendas.php');
                break;
            case 'voluntario':
                header('Location: vendasVol.php');
                break;
            case 'organizador':
                header('Location: cadastroMembrosAdm.php');
                break;
            default:
                header('Location: acesso_negado.php');
                break;
        }
        exit();
    }
} else {
    header('Location: acesso_negado.php');
    exit();
}
?>
