<?php include("cabecalhoAdm.php")?>
<?php
include('verificarLogin.php');
verificarLogin();
    include_once('config.php');

    if(!empty($_GET['id']))
    {
        $id = $_GET['id'];
        $sqlSelect = "SELECT * FROM vendas WHERE id=$id";
        $result = $conexao->query($sqlSelect);
        if($result->num_rows > 0)
        {
            while($user_data = mysqli_fetch_assoc($result))
            {

                $barra = $user_data['barra'];
                $produto = $user_data['produto'];
                $modelo = $user_data['modelo'];
                $tamanho = $user_data['tamanho'];
                $categoria = $user_data['categoria'];
                $valordevenda = $user_data['valordevenda'];
                $usuario = $user_data['usuario'];
                
            }
        }
        else
        {
            header('Location: vendasrealizadas.php');
        }
    }
    else
    {
        header('Location: vendasrealizadas.php');
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Sistema Flowers</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <script src="bootstrap.min.js"></script>
    </head>

<body>
   <br>
<a href="vendasrealizadas.php" >
<svg xmlns="http://www.w3.org/2000/svg" id="voltar" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
</svg>
</a>

<br>
<fieldset class="boxformularioeditVendas">
         
         
             <form id="insert_form" name="editvendas" method="post" action="saveEditVendas.php">
                
 
             <div class="col-md-5">
                     <label class="campodeFormulario">Produto: *</label><br>
                     <input type="text"class="campoTexto" name="produto" id="produto" maxlength="50" value="<?php echo $produto ?>" required>
                 </div><br>
 
                 <div class="col-md-5">
                     <label class="campodeFormulario">Modelo: *</label><br>
                     <input type="text" class="campoTexto" name="modelo" id="modelo" maxlength="" value="<?php echo $modelo ?>" required>
                 </div><br>
 
                 <div class="col-md-5">
                     <label class="campodeFormulario">Tamanho: *</label><br>
                     <input type="varchar" class="campoTexto" name="tamanho" id="tamanho" maxlength="50" value="<?php echo $tamanho ?>">
                     <br>
                 </div><br>

                 <div class="col-md-5">
                     <label class="campodeFormulario">Categoria: *</label><br>
                     <input type="varchar" class="campoTexto" name="categoria" id="categoria" maxlength="50" value="<?php echo $categoria ?>">
                     <br>
                 </div><br>

                 <div>
                 <label class="campodeFormulario">Preço: *</label><br>
                     <input type="decimal" class="campoNumero" name="valordevenda" placeholder="valor proposto para venda" id="valordevenda" maxlength="6" value="<?php echo $valordevenda ?>">
                 </div><br>
                 <div class="col-md-5">
                     <label class="campodeFormulario">Voluntário: *</label><br>
                     <input type="text"class="campoTexto" name="usuario" id="usuario" maxlength="50" value="<?php echo $usuario ?>" required>
                 </div><br>

                 <div class="col-md-5">
                     <label class="campodeFormulario">Código de Barras: *</label><br>
                     <p><input type="number" class="campoNumero" name="barra" placeholder="Ler código de Barra" id="barra" maxlength="15" value="<?php echo $barra ?>"></p>
                    
                     <input type="hidden" name="id" value="<?php echo $id ?>">
                     
                     <input type="submit" name="update" id="submit">
                     
                     

                 </div><br>

             </form>   
                
 </fieldset>
</body>
</html>