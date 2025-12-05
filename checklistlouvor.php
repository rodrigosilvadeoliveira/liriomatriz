<?php
date_default_timezone_set('America/Sao_Paulo');
include('verificarLogin.php');
verificarLogin();
include('verifica_permissao.php');
include_once('config.php');

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
    <title>Cadastro de Membros</title>
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

            height: 168px;

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
            <!-- <img class="x32" src="./mesa/x32.png" alt="Logo"></p> -->
        </div>
        <div class="card card-form">
            <div class="card-header card-header-custom">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações para ministério de Louvor
                    e regimento interno</h5>
            </div>
            <div class="card-body">

                <form method="POST" action="" enctype="multipart/form-data" class="row g-3">

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#tabernaculo"
                            role="button" aria-expanded="false" aria-controls="musicos">1.Tabernaculo <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="tabernaculo">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>Átrio:</b> é onde a maioria do povo de Deus se reúne – e fica. Lá, cantamos
                                        cânticos de júbilo, de guerra, de testemunho e de convite ao louvor.</p>
                                    <p><b>Santo Lugar:</b> é o lugar do sacerdote, é um aprofundamento. É um lugar de
                                        ministração de louvor através de cânticos de comunhão, de Edificação no Espírito
                                        Santo, de clamor, de Exaltação.</p>
                                    <p><b>Santo dos Santos:</b> antes do sacrifício de Jesus, o véu dividia o espaço
                                        onde estava a arca da presença do Senhor, e lá, somente o sacerdote poderia
                                        entrar uma vez ao ano. Após a cruz, esse véu foi rasgado de alto a baixo, e o
                                        acesso a Deus foi aberto a todos os filhos adoradores que foram salvos, lavados
                                        e edificados em Cristo. E quais os hinos desta etapa da adoração? Hinos de
                                        Contemplação, de Adoração</p>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#ensaio"
                            role="button" aria-expanded="false" aria-controls="musicos">2.Ensaios <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="ensaio">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>Quintas:</b></p>
                                    <p>Todos as 19:00.</p>
                                    <p><b>Sabados:</b>
                                    <p>Instrumentos - 17:00</p>
                                    <p>Vozes - 18:00</p>
                                    <p><b>Domingos:</b>
                                    <p>Passagem de som - 08:00</p>
                                    <p><b>Importante:</b>Atraso - Tolerado 15 minutos (avisado no pv) - em casos
                                        extraordinarios me procurem o lider</p>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#importante"
                            role="button" aria-expanded="false" aria-controls="musicos">3.Informações durante os cultos
                            <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="importante">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">

                                    <p>Sempre vamos subir 1h10 depois do inicio do culto, ou seja ..</p>
                                    <p>Quinta - 21:10</p>
                                    <p>Domingo - 10:10</p>
                                    <p>Usar Vestimentas pretas - Dúvidas procurem o Lider</p>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#wifi"
                            role="button" aria-expanded="false" aria-controls="musicos">4.Rede WI-FI <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="wifi">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>Rede:</b> TPlink som</p>
                                    <p><b>Semha:</b> 124578A23</p>
                                    <p>Rede não possui acesso a internet, somente conecta o APP a mesa de som</p>
                                </h3>
                            </div>
                        </div>
                    </div>
            </div>
            <h5>
                <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#app" role="button"
                    aria-expanded="false" aria-controls="musicos">
                    5.Ponto de retorno (Primeiro Acesso)<i class="fas fa-chevron-down ms-2"></i>
                </a>
            </h5>
            <div class="collapse" id="app">
                <div class="row">
                    <div class="col-md-6">
                        <h3 id="textos">
                            <p><b>Atenção: </b>Deve estar conectado a rede WIFI "TPlink som"
                            <p><b>A.</b>Se não tiver o aplicativo "Mixing Station" realizar o download da Playstore ou
                                Apple
                                Store</p>
                            <img class="mesa" src="./mesa/mixingStation.jpg" alt="Logo"></p>
                            <p><b>B.</b>Após instalação Abrir o aplicativo e clicar em "Add Mixer"</p>
                            <img class="mesa" src="./mesa/mixingAdd.jpg" alt="Logo"></p>
                            <p><b>C.</b>Clicar na Marca Behringer</p>
                            <img class="mesa" src="./mesa/mixingAdd1.jpg" alt="Logo"></p>
                            <p><b>D.</b>Clicar o Modelo X32/M32</p>
                            <img class="mesa" src="./mesa/mixingAdd2.jpg" alt="Logo"></p>
                            <p><b>E.</b>Clicar em Custom</p>
                            <img class="mesa" src="./mesa/mixingAdd3.jpg" alt="Logo"></p>
                            <p><b>E.</b>Clicar no Bus "1-Bateria, 2-Baixo, 3-vocal1, 4-vocal2, 5-vocal3, 6-teclado,
                                7-violão, 8-Ministro.</p>
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
                <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#regulagem" role="button"
                    aria-expanded="false" aria-controls="livr">
                    6: Regulagem ponto de retorno<i class="fas fa-chevron-down ms-2"></i>
                </a>
            </h5>
            <div class="collapse" id="regulagem">
                <div class="row">
                    <div class="col-md-6">
                        <h3 id="textos">
                            <p><b>Anteção: </b>Deve estar conectado a rede WIFI "SOM-5G", verificar se aplaca de audio
                                esta ligada</p>
                            <img class="mesa" src="./mesa/maudio1.jpg" alt="Logo"></p>
                            <p><b>A.</b>Abrir o aplicativo Mixing Station e clicar em Search no BUS desejado</p>
                            <img class="mesa" src="./mesa/mixingSearch.jpg" alt="Logo"></p>
                            <p><b>B.</b>Clicar Nomavemte em Connect</p>
                            <img class="mesa" src="./mesa/mixingAdd6.jpg" alt="Logo"></p>
                            <p><b>C.</b>Conexão realizada com Sucesso</p>
                            <img class="mesa" src="./mesa/mixingAdd7.jpg" alt="Logo"></p>
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