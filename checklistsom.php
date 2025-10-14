<?php
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

        
#textos{
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

        @media (max-width: 768px) {
           
        }
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
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações mesa de som Beringher X32</h5>
            </div>
            <div class="card-body">

                <form method="POST" action="salvar_voluntariado_escala.php" enctype="multipart/form-data"
                    class="row g-3">

                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#musicos"
                            role="button" aria-expanded="false" aria-controls="musicos">1.Ligar a Mesa <i
                                class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="musicos">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>A.</b> Ligar o 1º estabilizador de energia (automaticamente liga o compressor)</p>
                                    <img class="mesa" src="./mesa/estabilizadores1.jpg" alt="Logo"></p>
                                    <p><b>B.</b> Ligar a mesa de som (Botão power atrás da mesa)</p>
                                    <img class="mesa" src="./mesa/botãoligar.jpg" alt="Logo"></p>
                                    <p><b>C.</b> Liga estabilizadores 2º e 3º (caixas frontais e P.A)</p>
                                    <img class="mesa" src="./mesa/estabilizadores2e3.jpg" alt="Logo"></p>
                                    <p><b>D.</b> Subir volume Geral das P.A no “zero”</p>
                                    <p><b>E.</b> Ligar na tomada receptores dos mics sem Fio.</p>
                                    <p><b>F.</b> Selecionar a cena <b>(Louvor padrão)</b> ou <b>(Coral1309 para cultos de "CEIA")</b></p>
                                    <p>1.1 Em scene na parte superor a direita da mesa, clicar em View.</p>
                                    <img class="mesa" src="./mesa/mesasceneview.jpg" alt="Logo"></p>
                                    <p>1.2 Selecionar a aba SCENE</p>
                                    <p>1.3 Clicar em Load</p>
                                    <img class="mesa" src="./mesa/mesaload.jpg" alt="Logo"></p>
                                    <p>1.4 Clicar em Confirmar seta pra direita</p>
                                    <img class="mesa" src="./mesa/mesaseta.jpg" alt="Logo"></p>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#desligar"
                            role="button" aria-expanded="false" aria-controls="som">
                            2.Desligar a mesa <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="desligar">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b> A.</b> Desligar estabilizadores 2º e 3º</p>
                                    <img class="mesa" src="./mesa/estabilizadores2e3.jpg" alt="Logo"></p>
                                    <p><b>B.</b> Na mesa de som clicar no botão Setup, selecionar Shutdown e confirmar.
                                        Desligar (Botão power atrás da mesa)</p>
                                        <img class="mesa" src="./mesa/botãoligar.jpg" alt="Logo"></p>
                                    <p><b>C.</b> Desligar o 1º estabilizador de energia (automaticamente desliga o compressor)</p>
                                    <img class="mesa" src="./mesa/estabilizadores1.jpg" alt="Logo"></p>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#semsom"
                            role="button" aria-expanded="false" aria-controls="som">3.Caso não sai som de algum
                            instrumento ou voz<i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="semsom">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>A.</b>Verificar se canal esta baixo ou no Mute</p>
                                    <p><b>B.</b>Verificar se Canal está com LR ativo</p>
                                    <p><b>C.</b>Verificar se os canais no DCA não está no mute</p>
                                    <img class="mesa" src="./mesa/mesadca.jpg" alt="Logo"></p>
                                    <p><b>D.</b>Verificar o passo do item (1. Ligar a Mesa) foi seguido corretamente, se estabilizador não estiver ligado nãoliga as caixas</p>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#somcaixa"
                            role="button" aria-expanded="false" aria-controls="som">
                            4.Caso Uma das caixas não saia som <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="somcaixa">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>A.</b>Verificar antes o passo item (1. Ligar a Mesa) foi seguido corretamente</p>
                                    <p><b>B.</b>Ver no compressor se mostra sinal chegando na caixa</p>
                                    <p><b>C</b>.Pode ser feito teste com chiado na caixa (Somente antes ou após fim do culto)
                                    </p>
                                    <p><b>D.</b>Verificar se estabilizadores foram ligados corretamente.</p>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#passagem"
                            role="button" aria-expanded="false" aria-controls="som">
                            5. Passagem de som antes do culto: <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="passagem">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>A.</b>Chegar 1 hora antes do culto junto com a equipe de louvor</p>
                                    <p><b>B.</b>Verificar som e verificar retorno da equipe</p>
                                    <p><b>C.</b>Testar microfone da pastora</p>
                                    <p><b>D.</b>Mutar toda equipe no DCA após os testes para finalizar passagem de som somente
                                        no retorno</p>
                                    <p><b>E.</b>Após terminarem desmutar DCA</p>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <h5>
                        <a class="text-decoration-none d-block py-2" data-bs-toggle="collapse" href="#coral"
                            role="button" aria-expanded="false" aria-controls="som">
                            6. Posicionamento de Microfones do Coral e dia de Ceia: <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                    </h5>
                    <div class="collapse" id="coral">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 id="textos">
                                    <p><b>A.</b>Cena Coral1309 selecionada na mesa, canal tenor já esta com 40+ ativado, ainda não temos 3 microfones condensadores</p>
                                    <p><b>B.</b>Então para tenor trocar o microfone para usar Microfone condensador que temos no momento</p>
                                    <p><b>C.</b>Para contralto e Soprano utilizar microfone que ja estão la na frente</p>
                                    <p><b>D.</b>Posiconamento do microfone deve ser alto direcionar para as pessoas posicionadas em fila um atrás do outros exemplo. 1 pessoa, atrás 2 pessoas, atrás 3 pessoas etc..</p>
                                    <p><b>E.</b>Em bre imagens ilustrando os passo a passo</p>
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