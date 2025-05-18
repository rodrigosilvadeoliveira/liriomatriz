<?php
session_start();

// Conecte-se ao banco de dados (substitua com suas configurações)
include('config.php');

// Verifique se a sessão do carrinho já existe; se não, crie-a
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = array();

}

$sql = "SELECT * FROM vendasprodutos WHERE estoque = 'sim' ORDER BY id ASC";
$result = $conexao->query($sql);

// Função para adicionar um produto ao carrinho
function adicionarProdutoAoCarrinho($idProduto, $conexao)
{
    // Consulte o banco de dados para obter detalhes do produto com base no ID
    $sql = "SELECT * FROM vendasprodutos WHERE id = $idProduto";
    $result = $conexao->query($sql);

    if ($result->num_rows > 0) {
        $produtoDB = $result->fetch_assoc();

        // Verifique se o produto já existe no carrinho
        
        $produtoExistente = false;
        // Se o produto não existe no carrinho, adicione-o
        if (!$produtoExistente) {
            $produtoDB['quantidade'] = 1; // Adicione a quantidade inicial
            $_SESSION['carrinho'][] = $produtoDB;
        }

        // Redirecione de volta para a página de produtos ou exiba uma mensagem de sucesso
        // header("Location: produtos.php"); // Redirecionar para a página de produtos, se necessário
        // exit(); // Encerre o script, se necessário

        return true; // Retorna verdadeiro para indicar que o produto foi adicionado com sucesso
    } else {
        return false; // Retorna falso para indicar que o produto não foi encontrado
    }
}

// Verifique se o ID do produto foi enviado via POST
if (isset($_POST['id'])) {
    $idProduto = $_POST['id'];
    adicionarProdutoAoCarrinho($idProduto, $conexao);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-97HPMQNNTQ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-97HPMQNNTQ');
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Lirio Matriz</title>
    <link rel="stylesheet" href="style.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    </head>

<body>
    <div class="cabecalho" id="cabecalhodoSite">
    <?php include('cabecalhoSite.php');?>
    </div>
    <br><br>
<h1 id="titulocategoria">Panquecas</h1>
<div id="tabelaSite">
<div class="produtos-container">
        <table>
            <tbody>   
            <?php
            // Listar produtos no carrinho aqui
            
            while ($produtoNoCarrinho = mysqli_fetch_assoc($result)) {
                echo '<tr class="produtos">';
                echo '<td>';
                echo '<img class="imagens" src="' . $produtoNoCarrinho['imagem'] . '">';
                echo '<div class="produto-info">';
                echo '<b>' . $produtoNoCarrinho['produto'] . '</b>';
                echo '<p>' . $produtoNoCarrinho['modelo'] .  '</p>';
                echo '<p>SKU ' . $produtoNoCarrinho['id'] . '</p>';
                echo '<p>R$ ' . $produtoNoCarrinho['valordevenda'] . '</p>';
                echo '<form action="carrinho.php" method="post">';
                echo '<input type="hidden" name="id" value="' . $produtoNoCarrinho['id'] . '">';
                echo '<input type="submit" class="addCarrinho" value="Adicionar ao Carrinho">';
                echo '</form>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
</div>

<script>
$(document).ready(function(){
    $('.carroussel-container').slick({
        slidesToShow: 1, // Quantidade de slides visíveis ao mesmo tempo
        slidesToScroll: 1, // Quantidade de slides para avançar/retroceder
        autoplay: true, // Ativar a reprodução automática
        autoplaySpeed: 4000, // Velocidade da reprodução automática (em milissegundos)
    });
});
</script>

<div class="footer" id="footer">
      <?php include('footerSite.php');?>
      </div>
</div>
</body>
</html>

<?php
$conexao->close();
?>