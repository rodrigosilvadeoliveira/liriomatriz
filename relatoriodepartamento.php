<?php include("cabecalhoIgreja.php")?>
<?php
// include('verificarLogin.php');
// verificarLogin();
//session_start();
// include_once('config.php');
   // print_r($_SESSION);
//     if((!isset($_SESSION['usuario'])== true) and ($_SESSION['senha']) == true)
//     {
//       unset($_SESSION['usuario']);
//       unset($_SESSION['senha']);
//       header('Location: login.php');
      
//     }$logado = $_SESSION['usuario'];
//     if(!empty($_GET['search']))
//     {
//         $data = $_GET['search'];
//         $sql = "SELECT * FROM evento WHERE imagem LIKE '%$data%' or produto LIKE '%$data%' or modelo LIKE '%$data%' or categoria LIKE '%$data%' ORDER BY id DESC";
//     }
//     else
//     {
//       $sql = "SELECT * FROM evento WHERE cartaz= 'formulario' ORDER BY id DESC";
//     }
//     $result = $conexao->query($sql);

//     if(isset($_POST['submitAdm']))
// {
// include_once("config.php");

// // Insira as informações da compra no banco de dados
// // Data e hora atual

// if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"])){
//   $imagem = "./img/".$_FILES["imagem"]["name"];
//   move_uploaded_file($_FILES["imagem"]["tmp_name"] ,$imagem);
// }else{
//   $imagem = "";
// }
// $result = mysqli_query($conexao, "INSERT INTO evento(imagem) 
// VALUES ('$imagem')");

// header('Location: cadastroForm.php');
// }

// if(isset($_POST['submitEvento']))
// {
// include_once("config.php");

// // Insira as informações da compra no banco de dados
// // Data e hora atual
// // $cartaz = isset($_POST['cartaz']) ? $_POST['cartaz'] : null;
// $cartaz = $_POST['cartaz'];
// $inscricao = $_POST['inscricao'];
// $links = $_POST['links'];
// $inicio = $_POST['inicio'];
// $fim = $_POST['fim'];

// $result = mysqli_query($conexao, "INSERT INTO evento(imagem,cartaz,inscricao,links,inicio,fim) 
// VALUES ('$imagem','$cartaz','$inscricao','$links','$inicio','$fim')");

// header('Location: cadastroForm.php');
// }
?>
     
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Inscrição</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <br><br><br>
<?php
    echo "<h1 id='BemVindo'>Relatório de Atividades</h1>";
?>


    <form id="insert_form" class="row g-3" name="realoriodep" action="salvar_realoriodep.php" method="POST" enctype="multipart/form-data">
    <div class="dadoscontato">
      <h1>Relatorio Departamento</h1>
    
  
      <!-- <label class="nomedoCampo">Imagem: *</label> -->
      
      <div class="col-md-2">
    <label for="inputState" class="form-label">*Departamento</label>
    <br>
    <select id="departamento" class="form-select" name="departamento" required>
    <option value="">Selecione</option>
    <option value="Arena">Arena</option>
        <option value="Lirioplay">Lirio Play</option>
        <option value="Mergulhar">Mergulhar</option>

      <option value="Consagracao">Consagração</option>
      <option value="Coral">Coral</option>
      <option value="Criativo">Criativo</option>
      <option value="Danca">Dança</option>
      <option value="GCcasados">GC Casados</option>
      <option value="GCjovens">GC Jovens</option>
      <option value="Intercessao">Intercessão</option>
      <option value="Kids">Kids</option>
      <option value="Loja">Loja</option>
      <option value="Louvor">Louvor</option>
      <option value="Midias">Mídias</option>
      <option value="Oficiais">Oficiais</option>
      <option value="Recepcao">Recepção</option>
      <option value="Salavoluntarios">Sala Voluntários</option>
      <option value="Som">Mesa de Som</option>
      <option value="Teatro">Teatro</option>
      <option value="Transito">Trânsito</option>
      <option value="Visitas">Visitas</option>      
    </select>
</div>
<div class="col-md-5">
<label class="form-label">Liderança</label>
       <input type="text" class="form-control" name="lideranca" placeholder="" id="lideranca" maxlength="300" required>
     </div> <br>

     <div class="col-md-5">
<label class="form-label">Qual foi evento ou programação</label>
       <input type="text" class="form-control" name="evento" placeholder="" id="evento" maxlength="300" required>
     </div> <br>
     <div class="col-md-5">
<label class="form-label">Data:</label>
       <input type="date" class="form-control" name="data" placeholder="" id="data" required>
     </div> <br>
     <div class="col-md-5">
<label class="form-label">Tema Apresentado</label>
       <input type="text" class="form-control" name="tema" placeholder="" id="tema" maxlength="300">
     </div> <br>

     <div class="col-md-2">
<label class="form-label">Qtd. pessoas presentes ?</label>
       <input type="number" class="form-control" name="qtdpresentes" placeholder="" id="qtdpresentes" maxlength="300">
       <label class="form-label">Pessoas/Casais</label>
       <select id="pessoas" class="form-select" name="pessoas" required>
    <option value="">Selecione</option>
    <option value="Pessoas">Pessoas</option>
        <option value="Casais">Casais</option>
        </select>
        </div>
     <div class="col-md-9">
    <label for="mensagem" class="form-label">Descrição das atividades: (Relato das atividades ou eventos realizados durante 
    o encontro)</label>
    <textarea name="atividades" id="atividades" class="form-control" rows="8" ></textarea>
</div><br>
<div class="col-md-9">
    <label for="mensagem" class="form-label">Metas alcançadas: (Quais objetivos ou metas foram atingidos.)</label>
    <textarea name="metas" id="metas" class="form-control" rows="8" ></textarea>
</div><br>
<div class="col-md-9">
    <label for="mensagem" class="form-label">Dificuldades enfrentadas: (Quais desafios ou obstáculos surgiram durante a 
    execução das atividades.) </label>
    <textarea name="dificuldades" id="dificuldades" class="form-control" rows="8" ></textarea>
</div><br>
<div class="col-md-9">
    <label for="mensagem" class="form-label">Soluções implementadas: (Estratégias adotadas para superar os problemas 
    encontrados/ Junto com a Pastora) </label>
    <textarea name="solucoes" id="solucoes" class="form-control" rows="8" ></textarea>
</div><br>

<div class="col-md-9">
    <label for="mensagem" class="form-label">Recursos materiais: Materiais necessários (como equipamentos, espaço, materiais gráficos, 
    alimentos, etc.) </label>
    <textarea name="materias" id="materias" class="form-control" rows="8" ></textarea>
</div><br>

<div class="col-md-12">
  <label class="form-label">Recursos financeiros: (Quanto foi gasto nas atividades e quais recursos financeiros foram utilizados.)</label>
  
  <div id="financeiro-lista">
    <div class="row mb-2 align-items-center">
      <div class="col-md-6">
        <input type="text" name="produto[]" class="form-control" placeholder="Nome do produto" >
      </div>
      <div class="col-md-4">
        <input type="number" name="valor[]" class="form-control" placeholder="Valor (R$)" step="0.01" >
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-success" onclick="adicionarCampo()">
          +
        </button>
      </div>
    </div>
  </div>
</div>
<div class="col-md-9">
    <label for="mensagem" class="form-label">Recursos humanos: (Número de voluntários ou membros envolvidos no ministério.) </label>
    <textarea name="recursos" id="recursos" class="form-control" rows="8" ></textarea>
</div><br>
<div class="col-md-9">
    <label for="mensagem" class="form-label">Próximas atividades: (O que está planejado para o próximo período.) </label>
    <textarea name="proxatividade" id="proxatividade" class="form-control" rows="8" ></textarea>
</div><br>
<div class="col-md-9">
    <label for="mensagem" class="form-label">Impacto no ministério e na igreja: (Como as atividades influenciaram a vida espiritual 
    dos membros, a comunidade ou o crescimento do ministério.) </label>
    <textarea name="impacto" id="impacto" class="form-control" rows="8" ></textarea>
</div><br>
  <div class="col-md-10">
    <button type="submit" name="submitEvento" id="submitEvento" class="btn btn-primary">Enviar</button>
  </div>
  </div>
</form>
<script>
  function adicionarCampo() {
    const lista = document.getElementById('financeiro-lista');
    
    const linha = document.createElement('div');
    linha.className = 'row mb-2 align-items-center';
    
    linha.innerHTML = `
      <div class="col-md-6">
        <input type="text" name="produto[]" class="form-control" placeholder="Nome do produto" >
      </div>
      <div class="col-md-4">
        <input type="number" name="valor[]" class="form-control" placeholder="Valor (R$)" step="0.01" >
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-danger" onclick="removerCampo(this)">
          &minus;
        </button>
      </div>
    `;
    
    lista.appendChild(linha);
  }

  function removerCampo(botao) {
    botao.closest('.row').remove();
  }
</script>
<script>
    // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
    const tempoLimite = 3600000;

    // Redireciona para logout após o tempo limite
    setTimeout(() => {
        window.location.href = "sistema.php?timeout=1"; 
    }, tempoLimite);
</script>

</body>


</html>