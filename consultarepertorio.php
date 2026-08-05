<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if ((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];
$igreja = $_SESSION['igreja_id'];

// Buscar lista de repertórios (agora com o campo periodo)
$sqlRepertorios = "SELECT id, data_repertorio, periodo, nome_musicas, arquivo_repertorio 
                   FROM repertorio 
                   WHERE igreja_id = '$igreja'
                   ORDER BY data_repertorio DESC";
$resultRepertorios = $conexao->query($sqlRepertorios);

// Função auxiliar para traduzir o dia da semana
function diaSemana($dataIso) {
    $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    $time = strtotime($dataIso);
    return $dias[date('w', $time)];
}

include('registroslog.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta Repertório</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: Arial, sans-serif; }
        .btn-excluir { background:#e74c3c; border:none; color:#fff; padding:5px 10px; border-radius:6px; font-size:14px; cursor:pointer; }
        .btn-excluir:hover { background:#c0392b; }
        .repertorio-card { background:#fff; border-radius:12px; padding:20px; margin-top:20px; box-shadow:0 4px 6px rgba(0,0,0,0.1); }
        .musicas-list { 
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 0;
            width: 300px;
        }
        .musicas-list li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        .musicas-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #000;
            font-size: 18px;
        }
        .table td {
            vertical-align: middle;
        }
        tbody, td, tfoot, th, thead, tr {
    border-color: black;
    border-style: solid;
    border-width: 0;
}
 
    </style>
</head>
<body class="container py-4">

    <div class="navegacao">
        <?php include("navegacao.php") ?>
    </div>
<br><br>

    <div class="repertorio-card">
        <h3 class="mb-4">Repertórios Musicais</h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Data / Período</th>
                        <th>Músicas</th>
                        <th>Arquivo PDF</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultRepertorios->num_rows > 0) {
                        while ($rowRepertorio = $resultRepertorios->fetch_assoc()) {
                            // Formata a data para dd/mm/aaaa
                            $dataBR = date('d/m/Y', strtotime($rowRepertorio['data_repertorio']));
                            $periodo = htmlspecialchars($rowRepertorio['periodo']);

                            // Processa lista de músicas
                            $musicasArray = explode(',', $rowRepertorio['nome_musicas']);
                            $musicasHTML = '<ul class="musicas-list">';
                            foreach ($musicasArray as $musica) {
                                $musica = trim(htmlspecialchars($musica));
                                if (!empty($musica)) {
                                    $musicasHTML .= '<li>' . $musica . '</li>';
                                }
                            }
                            $musicasHTML .= '</ul>';
                            
                            echo "<tr>";
                            // Mostra data e período juntos
                            echo "<td><strong>{$dataBR}</strong><br><small>{$periodo}</small></td>";
                            echo "<td>{$musicasHTML}</td>";
                            
                            if (!empty($rowRepertorio['arquivo_repertorio'])) {
                                echo "<td><a href='uploads/" . htmlspecialchars($rowRepertorio['arquivo_repertorio']) . "' target='_blank' class='btn btn-info btn-sm'><i class='fas fa-file-pdf'></i> Abrir</a></td>";
                            } else {
                                echo "<td>--</td>";
                            }
                            
                            echo "<td><button class='btn-excluir' data-id='" . $rowRepertorio['id'] . "'>Excluir</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Nenhum repertório cadastrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function(){
        // Excluir Repertório
        $(".btn-excluir").click(function(e){
            e.preventDefault();
            let id = $(this).data("id");
            if(confirm("Tem certeza que deseja excluir este repertório e o arquivo PDF associado?")){
                $.post("excluir_repertorio.php", {id:id}, function(resposta){
                    if(resposta.trim() === "ok"){
                        location.reload(); 
                    } else {
                        alert("Erro ao excluir repertório: " + resposta);
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
