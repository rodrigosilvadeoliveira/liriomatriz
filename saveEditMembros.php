<?php 
include_once('config.php');

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $nascimento = $_POST['nascimento'];
    $batizado = $_POST['batizado'];
    $datas = $_POST['datas'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $voluntario = $_POST['voluntario'];
    $lider = $_POST['lider'];
    $departamentoum = $_POST['departamentoum'];
    $departamentodois = $_POST['departamentodois'];
    $departamentotres = $_POST['departamentotres'];
    $status = $_POST['status'];

    // Atualização da imagem recortada se foi enviada
    if (!empty($_POST['imagem_crop'])) {
        // Buscar nome da foto atual
        $sqlBusca = "SELECT foto_crop FROM membros WHERE id = $id";
        $resultBusca = $conexao->query($sqlBusca);
        $fotoAtual = '';

        if ($resultBusca && $resultBusca->num_rows > 0) {
            $row = $resultBusca->fetch_assoc();
            $fotoAtual = $row['foto_crop'];
        }

        // Salvar nova imagem
        $base64 = $_POST['imagem_crop'];
        $base64 = explode(',', $base64)[1];
        $base64 = base64_decode($base64);

        $novoNomeImagem = uniqid() . '.jpg';
        $caminhoImagem = 'upload/membros/' . $novoNomeImagem;

        file_put_contents($caminhoImagem, $base64);

        // Exclui a antiga, se existir
        if (!empty($fotoAtual) && file_exists('upload/membros/' . $fotoAtual)) {
            unlink('upload/membros/' . $fotoAtual);
        }

        // Atualiza com nova imagem
        $sqlInsert = "UPDATE membros 
            SET nome='$nome',
                sobrenome='$sobrenome',
                nascimento='$nascimento',
                batizado='$batizado',
                datas='$datas',
                telefone='$telefone',
                email='$email',
                voluntario='$voluntario',
                lider='$lider',
                departamentoum='$departamentoum',
                departamentodois='$departamentodois',
                departamentotres='$departamentotres',
                status='$status',
                foto_crop='$novoNomeImagem'
            WHERE id=$id";
    } else {
        // Atualização sem imagem
        $sqlInsert = "UPDATE membros 
            SET nome='$nome',
                sobrenome='$sobrenome',
                nascimento='$nascimento',
                batizado='$batizado',
                datas='$datas',
                telefone='$telefone',
                email='$email',
                voluntario='$voluntario',
                lider='$lider',
                departamentoum='$departamentoum',
                departamentodois='$departamentodois',
                departamentotres='$departamentotres',
                status='$status'
            WHERE id=$id";
    }

    $result = $conexao->query($sqlInsert);
}

header('Location: consulta_membros.php');
exit;
?>
