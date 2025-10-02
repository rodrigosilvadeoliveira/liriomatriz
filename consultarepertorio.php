<?php
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];

// Buscar lista de repertórios
$sqlRepertorios = "SELECT id, data_repertorio, nome_musicas, arquivo_repertorio FROM repertorio ORDER BY data_repertorio DESC";
$resultRepertorios = $conexao->query($sqlRepertorios);

// Função PHP para traduzir dia da semana (não está sendo usada neste arquivo, mas mantive por precaução)
function diaSemana($dataIso) {
    $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    $time = strtotime($dataIso);
    return $dias[date('w', $time)];
}
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
                        <th>Data</th>
                        <th>Músicas</th>
                        <th>Arquivo PDF</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultRepertorios->num_rows > 0) {
                        while ($rowRepertorio = $resultRepertorios->fetch_assoc()) {
                            // Formata a data para exibir no formato dd/mm/aaaa
                            $dataBR = date('d/m/Y', strtotime($rowRepertorio['data_repertorio']));
                            
                            // Processa a lista de músicas
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
                            echo "<td>" . $dataBR . "</td>";
                            echo "<td>" . $musicasHTML . "</td>";
                            
                            if ($rowRepertorio['arquivo_repertorio']) {
                                // Cria o link para o arquivo PDF
                                echo "<td><a href='uploads/" . htmlspecialchars($rowRepertorio['arquivo_repertorio']) . "' target='_blank' class='btn btn-info btn-sm'><i class='fas fa-file-pdf'></i> Abrir</a></td>";
                            } else {
                                echo "<td>--</td>";
                            }
                            
                            // Botão de Excluir Repertório
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
            e.preventDefault(); // Impede o comportamento padrão do botão
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