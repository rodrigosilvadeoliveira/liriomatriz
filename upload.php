<?php
include_once('config.php');

if (isset($_POST['croppedImage'])) {
    $data = $_POST['croppedImage'];

    // Extrai os dados da imagem
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);

    $nomeArquivo = uniqid() . '.jpg';
    $caminhoImagem = 'img/' . $nomeArquivo;

    // Salva no servidor
    file_put_contents($caminhoImagem, $data);

    // Redireciona de volta com o nome da imagem via session
    session_start();
    $_SESSION['imagem_cortada'] = $caminhoImagem;
    header("Location: cadastroMembrosAdm.php");
    exit();
}
?>
