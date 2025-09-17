<?php
require_once('vendor/autoload.php');

// Verificar se FPDI está carregado
if (class_exists('setasign\Fpdi\Fpdi')) {
    echo "FPDI carregado com sucesso!<br>";
} else {
    echo "Erro: FPDI não encontrado.<br>";
}

// Verificar se extensão zip está ativa (necessária para FPDI)
if (extension_loaded('zip')) {
    echo "Extensão ZIP está ativa.<br>";
} else {
    echo "Aviso: Extensão ZIP não está ativa.<br>";
}

// Verificar permissões de diretório
$dirs = ['uploads/musicas', 'uploads/repertorios'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    if (is_writable($dir)) {
        echo "Diretório {$dir} é gravável.<br>";
    } else {
        echo "Erro: Diretório {$dir} não é gravável.<br>";
    }
}
?>