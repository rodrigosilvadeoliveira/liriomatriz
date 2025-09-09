<?php
include_once('config.php');

if(isset($_GET['escala_id']) && isset($_GET['data_escala'])) {
    $escala_id = $_GET['escala_id'];
    $data_escala = $_GET['data_escala'];
    
    $sql = "SELECT * FROM repertorios WHERE escala_id = '$escala_id' AND data_escala = '$data_escala'";
    $res = $conexao->query($sql);
    
    if($res->num_rows > 0) {
        $repertorio = $res->fetch_assoc();
        echo json_encode($repertorio);
    } else {
        echo json_encode(null);
    }
}
?>