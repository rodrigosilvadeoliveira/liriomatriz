<?php
include_once("config.php");

$id = intval($_POST['id'] ?? 0);

if($id > 0){
    $sql = "DELETE FROM escalas_som WHERE id = $id";
    if($conexao->query($sql)){
        echo "ok";
    } else {
        echo "Erro ao excluir.";
    }
} else {
    echo "ID inválido.";
}

?>