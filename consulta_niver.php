<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

if(!isset($_SESSION['usuario']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit();
}

$logado = $_SESSION['usuario'];
$resultlist = null;

// Pega o mês selecionado
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');

// Consulta aniversariantes do mês
$sql = "SELECT id, nome, sobrenome, nascimento, email, telefone, foto, status 
        FROM membros 
        WHERE MONTH(nascimento) = $mes
        ORDER BY DAY(nascimento) ASC";
$resultlist = $conexao->query($sql);

// Array de meses para exibição
$meses = [
    1=>"Janeiro",2=>"Fevereiro",3=>"Março",4=>"Abril",5=>"Maio",6=>"Junho",
    7=>"Julho",8=>"Agosto",9=>"Setembro",10=>"Outubro",11=>"Novembro",12=>"Dezembro"
];
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aniversariantes</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        body { background-color: #f8f9fc; }
        .navbar-custom { background-color: #4e73df; }
        .member-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
        .badge-status { padding: 0.5em 0.8em; border-radius: 20px; font-size: 0.75em; }
        .export-btn { background-color: #1cc88a; border: none; border-radius: 20px; padding: 0.5rem 1.5rem; }
        .export-btn:hover { background-color: #17a673; }
        .card { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); margin-bottom: 1.5rem; }
        .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
        .sidebar { background-color: #4e73df; color: white; height: 100vh; position: fixed; top: 73px; left: 0; width: 250px; padding: 20px; }
        .main-content { margin-left: 0px; padding: 20px; margin-top: 73px; }
        .navbar-brand { font-weight: 700; }
        .alert-primary { background-color: #e8f4ff; border-color: #b3d9ff; color: #0066cc; }
        .table th { border-top: none; font-weight: 600; color: #4e73df; }
        .empty-state { text-align: center; padding: 2rem; color: #6c757d; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; color: #dee2e6; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <?php include("navegacao.php") ?>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- Main Content -->
            <main class="col-lg-10 col-md-9 ms-sm-auto main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                    <h1 class="h3">Consulta de Aniversariantes</h1>
                </div>

                <div class="alert alert-primary mb-4">
                    <i class="fas fa-birthday-cake me-2"></i> Bem-vindo, <strong><?php echo $logado; ?></strong>. Aqui você pode consultar os aniversariantes do mês selecionado.
                </div>

                <!-- Filtro por mês -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="mes" class="form-label"><strong>Selecione o mês:</strong></label>
                                    <select name="mes" id="mes" class="form-select">
                                        <?php
                                        foreach ($meses as $num=>$nome) {
                                            $sel = ($num==$mes) ? "selected" : "";
                                            echo "<option value='$num' $sel>$nome</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                                <div class="col-md-3">
                                    <a href="relatorio_aniverariantes.php?mes=<?php echo $mes; ?>" class="btn btn-success export-btn w-100">
                                        <i class="fas fa-file-excel me-2"></i> Exportar Excel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabela -->
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Aniversariantes de <?php echo $meses[$mes]; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaAniversariantes" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Data de Nascimento</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($resultlist && $resultlist->num_rows > 0) {
                                        while($user_data = mysqli_fetch_assoc($resultlist)) {
                                            echo "<tr>";
                                            $foto = !empty($user_data['foto']) ? 'uploads/'.$user_data['foto'] : '';
                                            if (!empty($foto)) {
                                                echo "<td><img class='member-img' src='$foto' alt='Foto'></td>";
                                            } else {
                                                $iniciais = substr($user_data['nome'], 0, 1) . substr($user_data['sobrenome'], 0, 1);
                                                echo "<td><div class='member-img bg-primary text-white d-flex align-items-center justify-content-center'>$iniciais</div></td>";
                                            }
                                            echo "<td>".$user_data['nome']." ".$user_data['sobrenome']."</td>";
                                            echo "<td>".$user_data['email']."</td>";
                                            echo "<td>".$user_data['telefone']."</td>";
                                            echo "<td>".date('d/m/Y', strtotime($user_data['nascimento']))."</td>";
                                            $status_class = $user_data['status'] == 'ativo' ? 'bg-success' : 'bg-secondary';
                                            echo "<td><span class='badge $status_class badge-status'>".$user_data['status']."</span></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center py-4'>";
                                        echo "<div class='empty-state'>";
                                        echo "<i class='fas fa-birthday-cake'></i>";
                                        echo "<h5>Nenhum aniversariante encontrado</h5>";
                                        echo "<p>Não há aniversariantes para o mês selecionado.</p>";
                                        echo "</div>";
                                        echo "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelaAniversariantes').DataTable({
                responsive: true,
                autoWidth: false, // Corrige problema de colunas
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
                },
                order: [[4, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
            });
        });
    </script>
</body>
</html>
