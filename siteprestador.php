<?php
session_start();

// Conecte-se ao banco de dados
include('config.php');

// Pesquisa e filtros
$pesquisa = $_GET['pesquisa'] ?? '';
$servico_filtro = $_GET['servico'] ?? '';

// Construir a query base
$sql = "SELECT * FROM prestador WHERE status = 'ativo'";

// Adicionar filtros se existirem
if (!empty($pesquisa)) {
    $sql .= " AND (nome LIKE '%$pesquisa%' OR nome_empresa LIKE '%$pesquisa%' OR cidade LIKE '%$pesquisa%' OR descricao LIKE '%$pesquisa%')";
}

if (!empty($servico_filtro)) {
    $sql .= " AND servicos LIKE '%$servico_filtro%'";
}

$sql .= " ORDER BY nome ASC";
$result = $conexao->query($sql);

// Buscar tipos de serviços únicos para o filtro
$sql_servicos = "SELECT servicos FROM prestador WHERE status = 'ativo'";
$result_servicos = $conexao->query($sql_servicos);
$todos_servicos = [];

while ($row = mysqli_fetch_assoc($result_servicos)) {
    if (!empty($row['servicos'])) {
        $servicos_array = explode(',', $row['servicos']);
        foreach ($servicos_array as $servico) {
            $servico = trim($servico);
            if (!empty($servico) && !in_array($servico, $todos_servicos)) {
                $todos_servicos[] = $servico;
            }
        }
    }
}
sort($todos_servicos);
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Prestadores de Serviços - Lírio Matriz</title>
    <link rel="stylesheet" href="style.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #6f42c1;
            --success-color: #1cc88a;
            --light-bg: #f8f9fc;
        }
        
        .prestadores-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        #titulohome {
            text-align: center;
            color: var(--primary-color);
            margin: 30px 0;
            font-weight: 700;
        }
        
        .filtros-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .card-prestador {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 25px;
            height: 100%;
        }
        
        .card-prestador:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .card-prestador img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }
        
        .card-body-prestador {
            padding: 20px;
        }
        
        .prestador-nome {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .prestador-empresa {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        
        .prestador-local {
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .prestador-descricao {
            color: #495057;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .prestador-servicos {
            margin-bottom: 15px;
        }
        
        .servico-tag {
            display: inline-block;
            background: var(--light-bg);
            color: var(--primary-color);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
            border: 1px solid #e3e6f0;
        }
        
        .prestador-contato {
            border-top: 1px solid #e3e6f0;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .contato-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .contato-item i {
            width: 20px;
            color: var(--primary-color);
            margin-right: 10px;
        }
        
        .btn-contato {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-contato:hover {
            background: linear-gradient(90deg, #3a5fce 0%, #5a32a9 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .experiencia-badge {
            background: var(--success-color);
            color: white;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-left: 10px;
        }
        
        .valor-medio {
            color: #28a745;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .sem-resultados {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        
        .sem-resultados i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #dee2e6;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box .form-control {
            padding-right: 40px;
        }
        
        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .aviso-divulgacao {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .aviso-divulgacao i {
            color: #f39c12;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .aviso-divulgacao h5 {
            color: #856404;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .aviso-divulgacao p {
            color: #856404;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.5;
        }
    </style>
</head>
    
<body>
    <div class="cabecalho" id="cabecalhodoSite">
        <?php include('sitecabecalho.php');?>
    </div>
    
    <br><br>

    <h1 id="titulohome">Prestadores de Serviços</h1>
    <div class="aviso-divulgacao">
    <i class="fas fa-info-circle"></i>
    <h5>Aviso Importante</h5>
    <p>
        Esta é uma página de <strong>divulgação de serviços</strong>. 
        A qualidade e garantia dos trabalhos são de responsabilidade de cada profissional. 
        Sempre converse diretamente com o prestador para esclarecer dúvidas antes de contratar.
    </p>
</div>

    <div class="prestadores-container">
        <!-- Filtros e Pesquisa -->
        <div class="filtros-container">
            <form method="GET" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="search-box">
                            <input type="text" class="form-control" name="pesquisa" placeholder="Pesquisar por nome, empresa, cidade..." value="<?php echo htmlspecialchars($pesquisa); ?>">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="servico">
                            <option value="">Todos os serviços</option>
                            <?php foreach ($todos_servicos as $servico): ?>
                                <option value="<?php echo htmlspecialchars($servico); ?>" <?php echo ($servico_filtro == $servico) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($servico); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Lista de Prestadores -->
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($prestador = mysqli_fetch_assoc($result)): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card card-prestador">
                            <?php if (!empty($prestador['foto'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($prestador['foto']); ?>" alt="<?php echo htmlspecialchars($prestador['nome']); ?>">
                            <?php else: ?>
                                <img src="images/default-avatar.jpg" alt="Foto padrão">
                            <?php endif; ?>
                            
                            <div class="card-body-prestador">
                                <div class="prestador-nome">
                                    <?php echo htmlspecialchars($prestador['nome']); ?>
                                    <?php if (!empty($prestador['experiencia'])): ?>
                                        <span class="experiencia-badge"><?php echo $prestador['experiencia']; ?> anos de experiência</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($prestador['nome_empresa'])): ?>
                                    <div class="prestador-empresa"><?php echo htmlspecialchars($prestador['nome_empresa']); ?></div>
                                <?php endif; ?>
                                
                                <div class="prestador-local">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($prestador['cidade']); ?> - <?php echo htmlspecialchars($prestador['estado']); ?>
                                    <?php if (!empty($prestador['raio_atendimento'])): ?>
                                        <small class="text-muted">(Atende até <?php echo $prestador['raio_atendimento']; ?>km)</small>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($prestador['descricao'])): ?>
                                    <div class="prestador-descricao"><?php echo htmlspecialchars($prestador['descricao']); ?></div>
                                <?php endif; ?>
                                
                                <?php if (!empty($prestador['servicos'])): ?>
                                    <div class="prestador-servicos">
                                        <?php 
                                        $servicos = explode(',', $prestador['servicos']);
                                        foreach ($servicos as $servico): 
                                            if (!empty(trim($servico))):
                                        ?>
                                            <span class="servico-tag"><?php echo htmlspecialchars(trim($servico)); ?></span>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($prestador['valor_medio'])): ?>
                                    <div class="valor-medio mb-2">
                                        <i class="fas fa-tag"></i> Valor médio: R$ <?php echo number_format($prestador['valor_medio'], 2, ',', '.'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="prestador-contato">
                                    <?php if (!empty($prestador['telefone'])): ?>
                                        <div class="contato-item">
                                            <i class="fas fa-phone"></i>
                                            <span><?php echo htmlspecialchars($prestador['telefone']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($prestador['email'])): ?>
                                        <div class="contato-item">
                                            <i class="fas fa-envelope"></i>
                                            <span><?php echo htmlspecialchars($prestador['email']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
<?php if (!empty($prestador['instagram'])): ?>
    <div class="contato-item">
        <i class="fab fa-instagram"></i>
        <span>
            <a href="<?php echo htmlspecialchars($prestador['instagram']); ?>" 
               target="_blank" 
               class="instagram-link">
                @<?php echo htmlspecialchars($prestador['instagram']); ?>
            </a>
        </span>
    </div>
<?php endif; ?>                                    
                                    <?php if (!empty($prestador['disponibilidade'])): ?>
                                        <div class="contato-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo htmlspecialchars($prestador['disponibilidade']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="text-center mt-3">
                                        <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $prestador['telefone']); ?>" 
                                           target="_blank" 
                                           class="btn-contato">
                                            <i class="fab fa-whatsapp"></i> Entrar em Contato
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="sem-resultados">
                        <i class="fas fa-search"></i>
                        <h4>Nenhum prestador encontrado</h4>
                        <p>Tente ajustar os filtros de pesquisa</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer" id="footer">
        <?php include('sitefooter.php');?>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
</body>
</html>