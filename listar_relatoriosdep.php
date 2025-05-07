<?php include("cabecalhoMembros.php")?>
<?php


//session_start();
include_once('config.php');
   // print_r($_SESSION);
    

if ($conexao->connect_error) {
    die("Erro: " . $conexao->connect_error);
}

// Consulta relatórios
$sql = "SELECT * FROM relatorios ORDER BY criado_em DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Lista de Relatórios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

   
<?php
    echo "<h1 id='BemVindo'>Bem vinda Pastora</u><p>Relatório Departamentos</p></h1>";
?>
<br><br><br>
  <h2>Relatórios cadastrados</h2>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Departamento</th>
        <th>Liderança</th>
        <th>Evento</th>
        <th>Data</th>
        <th>Tema</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['departamento']) ?></td>
          <td><?= htmlspecialchars($row['lideranca']) ?></td>
          <td><?= htmlspecialchars($row['evento']) ?></td>
          <td><?= $row['data'] ?></td>
          <td><?= htmlspecialchars($row['tema']) ?></td>
          <td>
  <button class="btn btn-primary btn-sm" onclick="toggleDetalhes(<?= $row['id'] ?>)">Ver Detalhes</button>
  <a class="btn btn-danger btn-sm" href="exportar_pdf.php?id=<?= $row['id'] ?>" target="_blank">Exportar PDF</a>
</td>

        </tr>
        <tr id="detalhes-<?= $row['id'] ?>" style="display:none;">
          <td colspan="6">
            <strong>Qtd. Presentes:</strong> <?= $row['qtdpresentes'] ?><br>
            <strong>Pessoas/Casais:</strong> <?= $row['pessoas'] ?><br>
            <strong>Atividades:</strong> <?= nl2br(htmlspecialchars($row['atividades'])) ?><br>
            <strong>Metas:</strong> <?= nl2br(htmlspecialchars($row['metas'])) ?><br>
            <strong>Dificuldades:</strong> <?= nl2br(htmlspecialchars($row['dificuldades'])) ?><br>
            <strong>Soluções:</strong> <?= nl2br(htmlspecialchars($row['solucoes'])) ?><br>
            <strong>materiais:</strong> <?= nl2br(htmlspecialchars($row['materiais'])) ?><br>
            <strong>Recursos:</strong> <?= nl2br(htmlspecialchars($row['recursos'])) ?><br>
            <strong>Próximas Atividades:</strong> <?= nl2br(htmlspecialchars($row['proxatividade'])) ?><br>
            <strong>Impacto:</strong> <?= nl2br(htmlspecialchars($row['impacto'])) ?><br><br>

            <strong>Recursos Financeiros:</strong>
            <table class="table table-sm table-bordered mt-2">
              <thead>
                <tr>
                  <th>Produto</th>
                  <th>Valor (R$)</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $relatorio_id = $row['id'];
                $sql_itens = "SELECT * FROM relatorio_itens WHERE relatorio_id = $relatorio_id";
                $itens = $conexao->query($sql_itens);
                while ($item = $itens->fetch_assoc()):
                ?>
                <tr>
                  <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                  <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <script>
    function toggleDetalhes(id) {
      const linha = document.getElementById('detalhes-' + id);
      linha.style.display = linha.style.display === 'none' ? '' : 'none';
    }
  </script>

</body>
</html>

<?php $conexao->close(); ?>
