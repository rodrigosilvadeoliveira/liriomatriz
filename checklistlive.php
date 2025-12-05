<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

// Verifica imagem cortada da sessão
$imagem = isset($_SESSION['imagem_cortada']) ? $_SESSION['imagem_cortada'] : '';

// Verifica login
if ((!isset($_SESSION['usuario']) == true) and ($_SESSION['senha']) == true) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}
$logado = $_SESSION['usuario'];
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Live</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Cropper CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --success-color: #1cc88a;
            --light-bg: #f8f9fc;
        }


        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px;
        }

        .navbar-custom {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-form {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header-custom {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .card-title {
            margin-bottom: var(--bs-card-title-spacer-y);
            color: var(--bs-card-title-color);
            text-align: center;
        }

        .text-decoration-none {
            text-decoration: none !important;
            text-align: center;
        }


        #textos {
            margin-top: 1%;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 24px;
            color: ;
        }

        .x32 {

            height: 175px;

        }

        .mesa {

            width: 168px;

        }

        .col-md-6 {
            flex: 0 0 auto;
            width: 100%;
        }

        @media (max-width: 768px) {}
    </style>
</head>

<body>
    <!-- Navbar -->


    <div class="container">
        <!-- Cabeçalho -->


        <div>
            <?php include("navegacao.php") ?>
        </div>


        <!-- Mensagem de Boas-Vindas -->


        <!-- Formulário -->
        <div id='textos'>
            <img class="x32" src="./mesa/x32.png" alt="Logo"></p>
        </div>
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações mesa de som Beringher X32
                </h5>
            </div>
            <div class="card-body">

                <form method="POST" action="salvar_voluntariado_escala.php" enctype="multipart/form-data"
                    class="row g-3">

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#live"
                            role="button" aria-expanded="false" aria-controls="livr">
                            1: Regulagem da Live(Primeiro Acesso)<i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="live">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>Atenção: </b>Deve estar conectado a rede WIFI "TPlink som", verificar se a
                                        placa de audio esta ligada</p>
                                    <img class="mesa" src="./mesa/maudio1.jpg" alt="Logo"></p>
                                    <p><b>A.</b>Se não tiver o aplicativo "Mixing Station" realizar o download da
                                        Playstore ou Apple Store</p>
                                    <img class="mesa" src="./mesa/mixingStation.jpg" alt="Logo"></p>
                                    <p><b>B.</b>Após instalação Abrir o aplicativo e clicar em "Add Mixer"</p>
                                    <img class="mesa" src="./mesa/mixingAdd.jpg" alt="Logo"></p>
                                    <p><b>C.</b>Clicar na Marca Behringer</p>
                                    <img class="mesa" src="./mesa/mixingAdd1.jpg" alt="Logo"></p>
                                    <p><b>D.</b>Clicar o Modelo X32/M32</p>
                                    <img class="mesa" src="./mesa/mixingAdd2.jpg" alt="Logo"></p>
                                    <p><b>E.</b>Clicar em Custom</p>
                                    <img class="mesa" src="./mesa/mixingAdd3.jpg" alt="Logo"></p>
                                    <p><b>E.</b>Clicar em Bus12</p>
                                    <img class="mesa" src="./mesa/mixingAdd4.jpg" alt="Logo"></p>
                                    <p><b>E.</b>Clicar em Connect</p>
                                    <img class="mesa" src="./mesa/mixingAdd5.jpg" alt="Logo"></p>
                                    <p><b>F.</b>Clicar Nomavemte em Connect</p>
                                    <img class="mesa" src="./mesa/mixingAdd6.jpg" alt="Logo"></p>
                                    <p><b>G.</b>Conexão realizada com Sucesso</p>
                                    <img class="mesa" src="./mesa/mixingAdd7.jpg" alt="Logo"></p>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#regulagem"
                            role="button" aria-expanded="false" aria-controls="livr">
                            2: Regulagem da Live<i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="regulagem">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>Atebção: </b>Deve estar conectado a rede WIFI "STPlink som", verificar se a
                                        placa de audio esta ligada</p>
                                    <img class="mesa" src="./mesa/maudio1.jpg" alt="Logo"></p>
                                    <p><b>A.</b>Abrir o aplicativo Mixing Station e clicar em Search no BUS 11 OU BUS 12
                                    </p>
                                    <img class="mesa" src="./mesa/mixingSearch.jpg" alt="Logo"></p>
                                    <p><b>B.</b>Clicar Nomavemte em Connect</p>
                                    <img class="mesa" src="./mesa/mixingAdd6.jpg" alt="Logo"></p>
                                    <p><b>C.</b>Conexão realizada com Sucesso</p>
                                    <img class="mesa" src="./mesa/mixingAdd7.jpg" alt="Logo"></p>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#somlive"
                            role="button" aria-expanded="false" aria-controls="livr">
                            3: Não sai som na Live<i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="somlive">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>A.</b>Verificar se a placa de audio esta ligada</p>
                                    <p><b>B.</b>Verificar se Bus 11 ou Bus 12 esta no mute</p>
                                    <p><b>C.</b>Verificar se aplaca esta conectada ao computador pelo cabo USB/MIDI</p>
                                    <p><b>D.</b>Verificar com midias se no OBS esta conectado com a placa "Fastrack 1/2"
                                        como entrada de audio</p>
                                </h3>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Cropper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>


        // Auto logout após 1 hora de inatividade
        const tempoLimite = 3600000;
        setTimeout(() => {
            window.location.href = "sistema.php?timeout=1";
        }, tempoLimite);
    </script>
</body>

</html>