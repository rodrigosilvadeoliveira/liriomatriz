<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('config.php');

echo "<h3>Debug - Verificação de Repertórios</h3>";

// Verificar se a tabela existe
$result = $conexao->query("SHOW TABLES LIKE 'repertorio'");
if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Tabela 'repertorio' não existe!</div>";
    
    // Criar tabela
    $sql = "CREATE TABLE repertorio (
        id INT AUTO_INCREMENT PRIMARY KEY,
        data_repertorio DATE NOT NULL,
        arquivo_repertorio VARCHAR(255) NOT NULL,
        musicas TEXT NOT NULL,
        criado_em DATETIME NOT NULL
    )";
    
    if ($conexao->query($sql)) {
        echo "<div class='alert alert-success'>Tabela criada com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao criar tabela: " . $conexao->error . "</div>";
    }
} else {
    echo "<div class='alert alert-success'>Tabela 'repertorio' existe!</div>";
}

// Verificar repertórios cadastrados
$result = $conexao->query("SELECT * FROM repertorio ORDER BY criado_em DESC");
echo "<h4>Repertórios cadastrados:</h4>";
if ($result->num_rows > 0) {
    echo "<table class='table table-striped'><tr><th>ID</th><th>Data</th><th>Arquivo</th><th>Músicas</th><th>Criado em</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['data_repertorio']}</td>
                <td>{$row['arquivo_repertorio']}</td>
                <td>" . substr($row['musicas'], 0, 50) . "...</td>
                <td>{$row['criado_em']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='alert alert-warning'>Nenhum repertório cadastrado ainda.</div>";
}

// Verificar e criar diretórios
echo "<h4>Verificação e criação de diretórios:</h4>";
$dirs = [
    $_SERVER['DOCUMENT_ROOT'] . '/uploads/musicas/',
    $_SERVER['DOCUMENT_ROOT'] . '/uploads/repertorios/'
];

foreach ($dirs as $dir) {
    $exists = is_dir($dir);
    $writable = is_writable($dir);
    
    echo "<div>Diretório: " . basename($dir) . " - ";
    echo "Existe: " . ($exists ? '✅' : '❌');
    
    if (!$exists) {
        // Tentar criar o diretório
        if (mkdir($dir, 0777, true)) {
            echo " - Criado: ✅";
            $exists = true;
            $writable = is_writable($dir);
        } else {
            echo " - Falha ao criar: ❌";
        }
    }
    
    echo " - Gravável: " . ($writable ? '✅' : '❌') . "</div>";
}

// Verificar permissões dos diretórios criados
echo "<h4>Permissões dos diretórios:</h4>";
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = fileperms($dir);
        echo "<div>" . basename($dir) . ": " . substr(sprintf('%o', $perms), -4) . "</div>";
    }
}

// Verificar se há arquivos na pasta de músicas
$musicas_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/musicas/';
if (is_dir($musicas_dir)) {
    $arquivos = scandir($musicas_dir);
    $pdf_files = array_filter($arquivos, function($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
    });
    
    echo "<h4>Arquivos PDF em musicas:</h4>";
    if (count($pdf_files) > 0) {
        echo "<ul>";
        foreach ($pdf_files as $file) {
            echo "<li>$file</li>";
        }
        echo "</ul>";
    } else {
        echo "<div class='alert alert-warning'>Nenhum arquivo PDF encontrado na pasta musicas</div>";
    }
}

// Verificar configuração do PHP
echo "<h4>Configuração do PHP:</h4>";
echo "<div>PHP Version: " . PHP_VERSION . "</div>";
echo "<div>Memory Limit: " . ini_get('memory_limit') . "</div>";
echo "<div>Upload Max Filesize: " . ini_get('upload_max_filesize') . "</div>";
echo "<div>Post Max Size: " . ini_get('post_max_size') . "</div>";

// Verificar se FPDI está carregado
echo "<h4>Verificação de Dependências:</h4>";
if (class_exists('setasign\Fpdi\Fpdi')) {
    echo "<div>FPDI: ✅ Carregado</div>";
} else {
    echo "<div>FPDI: ❌ Não carregado</div>";
}

// Criar arquivo de teste para verificar permissões
$test_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/test_write.txt';
if (file_put_contents($test_file, 'Teste de escrita ' . date('Y-m-d H:i:s'))) {
    echo "<div>Teste de escrita: ✅ Sucesso</div>";
    unlink($test_file);
} else {
    echo "<div>Teste de escrita: ❌ Falhou</div>";
}
?>