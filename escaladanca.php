<?php
date_default_timezone_set('America/Sao_Paulo');
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
$resultlist = null;

$escalaSalva = null;

// Buscar sempre o último registro salvo (pela data ou pelo id maior)
$sql = "SELECT * FROM escalas_danca ORDER BY id DESC LIMIT 1";
$result = $conexao->query($sql);

if ($result && $result->num_rows > 0) {
    $escalaSalva = $result->fetch_assoc();
}

// Funções fixas (linhas da escala)
$funcoes = ["Danca"];
$nomesPorFuncao = [];

// Mapear fotos por nome
$fotosPorNome = [];
$resFotos = $conexao->query("SELECT nome, foto FROM musicos");
if ($resFotos && $resFotos->num_rows > 0) {
  while ($r = $resFotos->fetch_assoc()) {
    $fotosPorNome[$r['nome']] = $r['foto'];
  }
}
$fotosJSON = json_encode($fotosPorNome, JSON_UNESCAPED_UNICODE);

// Buscar nomes da tabela "musicos" para cada função
foreach ($funcoes as $funcao) {
    $coluna = strtolower($funcao);
    $sql = "SELECT DISTINCT $coluna AS nome FROM musicos WHERE $coluna IS NOT NULL AND $coluna <> '' ORDER BY $coluna ASC";
    $resultado = $conexao->query($sql);
    
    $nomes = [];
    if ($resultado && $resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $nomes[] = $row['nome'];
        }
    }
    $nomesPorFuncao[$funcao] = $nomes;
}

// Transformar em JSON para usar no JS
$nomesJSON = json_encode($nomesPorFuncao, JSON_UNESCAPED_UNICODE);
$funcoesJSON = json_encode($funcoes, JSON_UNESCAPED_UNICODE);

// Se houver escala salva, converter para JSON também
$escalaSalvaJSON = $escalaSalva ? json_encode($escalaSalva, JSON_UNESCAPED_UNICODE) : 'null';
include('registroslog.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Sistema de Escalas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styledaescala.css?v=<?=time()?>">
</head>
<body>

<div class="container">
   <div class="navegacao">
   <?php include("navegacao.php")?>
   </div>
   <br><br>
 <h1><i class="fas fa-user-friends"></i> Sistema de Escalas Musicais</h1>
   <h1> <p class="description">Gerencie e compartilhe escalas de forma fácil e rápida</p></h1>
    
  <div class="content">
    <div class="card">
      <div class="card-title"><i class="fas fa-calendar-plus"></i> Adicionar Datas</div>
      <div class="controls">
        <label for="datePicker">Selecione as datas:</label>
        <input type="date" id="datePicker" multiple />

        <button type="button" id="btnAdd" class="btn btn-primary">
          <i class="fas fa-plus-circle"></i> Adicionar Coluna
        </button>
        <button type="button" id="btnAutoFill" class="btn btn-auto">
          <i class="fas fa-magic"></i> Preenchimento Automático
        </button>
        <button type="button" id="btnNew" class="btn-new">
          <i class="fas fa-file-alt"></i> Nova Escala
        </button>
        <span id="status" class="pill"></span>
      </div>
    </div>
    
    <div id="escalaWrapper">
      <table id="escalaTable" aria-label="Tabela de Escala">
        <thead>
          <tr id="headerRow">
            <th>Escala Dança</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
    
    <div class="action-buttons">
      <button type="button" id="btnValidate" class="btn btn-warning">
        <i class="fas fa-check"></i> Validar Escala
      </button>
      <button type="button" id="btnSave" class="btn btn-info">
        <i class="fas fa-save"></i> Salvar Escala
      </button>
      <button type="button" id="btnExport" class="btn btn-success">
        <i class="fas fa-download"></i> Exportar como PDF
      </button>
      <button type="button" id="btnPreview" class="btn btn-primary">
        <i class="fas fa-eye"></i> Visualizar Escala
      </button>
      <button type="button" id="btnShare" class="btn btn-warning">
        <i class="fab fa-whatsapp"></i> Compartilhar
      </button>
    </div>
    
    <p class="footnote">
      <i class="fas fa-lightbulb"></i> Dica: Adicione quantas datas quiser e depois salve ou exporte a escala.
    </p>
  </div>
</div>

<!-- Modal para salvar escala -->
<div id="saveModal" class="modal">
  <div class="modal-content">
    <div class="close-modal">&times;</div>
    <div class="modal-header">
      <i class="fas fa-save fa-2x"></i>
      <h2>Salvar Escala</h2>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label for="escalaName">Nome da Escala:</label>
        <input type="text" id="escalaName" placeholder="Ex: Escala Janeiro 2024" />
      </div>
      <div class="form-group">
        <label for="escalaDescription">Descrição (opcional):</label>
        <input type="text" id="escalaDescription" placeholder="Ex: Escala para os cultos de janeiro" />
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" id="btnCancelSave" class="btn btn-warning">Cancelar</button>
      <button type="button" id="btnConfirmSave" class="btn btn-success">Salvar</button>
    </div>
  </div>
</div>

<!-- Modal de confirmação para nova escala -->
<div id="confirmNewModal" class="modal">
  <div class="modal-content">
    <div class="close-modal">&times;</div>
    <div class="modal-header">
      <i class="fas fa-exclamation-triangle fa-2x" style="color: #ffcc00;"></i>
      <h2>Nova Escala</h2>
    </div>
    <div class="modal-body">
      <p>Tem certeza que deseja criar uma nova escala? Todas as datas adicionadas serão removidas.</p>
    </div>
    <div class="modal-footer">
      <button type="button" id="btnCancelNew" class="btn btn-warning">Cancelar</button>
      <button type="button" id="btnConfirmNew" class="btn btn-success">Confirmar</button>
    </div>
  </div>
</div>

<!-- Toast notification -->
<div id="toast" class="toast">
  <i class="fas fa-check-circle"></i>
  <span id="toastMessage"></span>
</div>

<!-- Preview da imagem -->
<div id="imagePreview" class="image-preview">
  <div class="image-preview-content">
    <div class="close-preview">&times;</div>
    <h3>Prévia da Escala</h3>
    <div id="previewContainer"></div>
    <div class="preview-actions">
      <button id="btnDownloadPreview" class="btn btn-success">
        <i class="fas fa-download"></i> Baixar
      </button>
      <button id="btnSharePreview" class="btn btn-warning">
        <i class="fab fa-whatsapp"></i> Compartilhar
      </button>
    </div>
  </div>
</div>

<!-- html2canvas (print do HTML em PNG) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<!-- jsPDF para gerar PDFs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js.map"></script>

<script>
  // Verificar se html2canvas foi carregado corretamente
  if (typeof html2canvas === 'undefined') {
    console.error('html2canvas não foi carregado corretamente');
    // Tentar carregar de outra fonte se a primeira falhar
    document.write('<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"><\/script>');
  }

  // Verificar se jsPDF foi carregado corretamente
  if (typeof window.jspdf === 'undefined') {
    console.error('jsPDF não foi carregado corretamente');
    document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"><\/script>');
  }

  // Dados vindos do PHP
  const nomesPorFuncao = <?= $nomesJSON ?? '{}' ?>;
  const funcoes = <?= $funcoesJSON ?? '[]' ?>;
  const fotosPorNome = <?= $fotosJSON ?? '{}' ?>;
  const AVATAR_PADRAO = 'assets/avatar-default.png';
  
  // Elementos
const tbody = document.getElementById('tableBody');
const headerRow = document.getElementById('headerRow');
const statusEl = document.getElementById('status');
const toastEl = document.getElementById('toast');
const toastMsg = document.getElementById('toastMessage');
const previewEl = document.getElementById('imagePreview');
const previewContainer = document.getElementById('previewContainer');
const saveModal = document.getElementById('saveModal');
const confirmNewModal = document.getElementById('confirmNewModal');
const addedDates = new Set(); // evita colunas duplicadas (por ISO)

// Variáveis para armazenar a imagem atual e o PDF
let currentBlob = null;
let currentPdfBlob = null;
let currentFileName = '';

// Cria as linhas base (primeira coluna = Escala)
if (funcoes.length > 0) {
  funcoes.forEach(funcao => {
    const tr = document.createElement('tr');
    tr.dataset.funcao = funcao;
    const td = document.createElement('td');
    td.innerHTML = `<strong>${funcao}</strong>`;
    tr.appendChild(td);
    tbody.appendChild(tr);
  });
}

// Função para formatar data ISO para BR com dia da semana
function formatISOToBRcomSemana(isoDate) {
  if (!isoDate) return '';
  
  const diasSemana = [
    "Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"
  ];
  
  // Dividir a data ISO manualmente para evitar problemas de fuso horário
  const partes = isoDate.split('-');
  if (partes.length !== 3) return isoDate;
  
  const ano = parseInt(partes[0]);
  const mes = parseInt(partes[1]) - 1; // Mês é 0-11 em JavaScript
  const dia = parseInt(partes[2]);
  
  // Criar data com hora fixa (meio-dia) para evitar problemas de fuso
  const data = new Date(ano, mes, dia, 12, 0, 0);
  
  // Verificar se a data é válida
  if (isNaN(data.getTime())) return isoDate;
  
  const diaSemana = diasSemana[data.getDay()];
  const diaFormatado = String(data.getDate()).padStart(2, "0");
  const mesFormatado = String(data.getMonth() + 1).padStart(2, "0");
  const anoFormatado = data.getFullYear();

  return `${diaFormatado}/${mesFormatado}/${anoFormatado} (${diaSemana})`;
}

function showStatus(msg, isError = false) {
  statusEl.textContent = msg;
  statusEl.style.display = 'inline-block';
  statusEl.style.background = isError ? 'rgba(247, 37, 133, 0.2)' : 'rgba(76, 201, 240, 0.2)';
  statusEl.style.borderColor = isError ? '#f72585' : '#4cc9f0';
  statusEl.style.color = isError ? '#a01a58' : '#138496';
  
  clearTimeout(showStatus._t);
  showStatus._t = setTimeout(() => statusEl.style.display = 'none', 3000);
}

function showToast(msg, type = 'success') {
  toastMsg.textContent = msg;
  const icon = toastEl.querySelector('i');
  
  if (type === 'error') {
    icon.className = 'fas fa-exclamation-circle';
    toastEl.style.background = '#f72585';
  } else {
    icon.className = 'fas fa-check-circle';
    toastEl.style.background = '#4cc9f0';
  }
  
  toastEl.classList.add('show');
  setTimeout(() => toastEl.classList.remove('show'), 3000);
}

function getFoto(nome) {
  if (!nome) return AVATAR_PADRAO;
  return fotosPorNome[nome] || AVATAR_PADRAO;
}

// cria <div class="select-avatar"><img><select></div>
function makeSelectWithAvatar() {
  const wrap = document.createElement('div');
  wrap.className = 'select-avatar';

  const img = document.createElement('img');
  img.className = 'avatar';
  img.alt = 'foto';

  const sel = document.createElement('select');
  wrap.appendChild(img);
  wrap.appendChild(sel);

  // atualiza imagem ao mudar seleção
  sel.addEventListener('change', () => {
    img.src = getFoto(sel.value);
  });

  // expõe o select (alguns lugares do seu código buscam #escalaTable select)
  wrap._select = sel;
  wrap._img = img;

  return wrap;
}

function createSelect(funcao, isoDate) {
  const wrap = makeSelectWithAvatar();
  const sel = wrap._select;
  sel.name = `escala[${funcao}][${isoDate}]`;

  const opt0 = document.createElement('option');
  opt0.value = '';
  opt0.textContent = '-- Selecione --';
  sel.appendChild(opt0);

  const nomes = (nomesPorFuncao[funcao] || []);
  nomes.forEach(nome => {
    const opt = document.createElement('option');
    opt.value = nome;
    opt.textContent = nome;
    sel.appendChild(opt);
  });

  // setar imagem inicial (vazio → avatar padrão)
  wrap._img.src = getFoto(sel.value);

  return wrap; // agora retorna <div> com <img> e <select>
}

function addColumn() {
  const iso = document.getElementById('datePicker').value; // "YYYY-MM-DD"
  if (!iso) { 
    showStatus('Escolha uma data primeiro.', true); 
    return; 
  }
  if (addedDates.has(iso)) { 
    showStatus('Essa data já foi adicionada.', true); 
    return; 
  }

  // Cabeçalho da coluna
  const th = document.createElement('th');
  th.dataset.iso = iso;

  const span = document.createElement('span');
  span.textContent = formatISOToBRcomSemana(iso);

  // Botão remover
  const btn = document.createElement('button');
  btn.textContent = "✖"; 
  btn.className = "remove-col";
  btn.title = "Remover esta data";
  btn.onclick = () => removeColumn(iso);

  th.appendChild(span);
  th.appendChild(btn);
  headerRow.appendChild(th);

  // Célula com select em cada linha
  [...tbody.rows].forEach(tr => {
    const funcao = tr.dataset.funcao;
    const td = document.createElement('td');
    td.appendChild(createSelect(funcao, iso)); // aqui já funciona, pq retorna wrap
    tr.appendChild(td);
  });

  addedDates.add(iso);
  showStatus(`Data ${formatISOToBRcomSemana(iso)} adicionada.`);
  showToast(`Data ${formatISOToBRcomSemana(iso)} adicionada com sucesso!`);
}

// Função para preenchimento automático
function autoFillTable() {
  if (addedDates.size === 0) {
    showToast('Adicione pelo menos uma data antes de usar o preenchimento automático.', 'error');
    return;
  }

  const selects = document.querySelectorAll('#escalaTable select');
  
  selects.forEach(select => {
    if (select.options.length > 1) {
      const randomIndex = Math.floor(Math.random() * (select.options.length - 1)) + 1;
      select.selectedIndex = randomIndex;

      // Forçar atualização da foto
      select.dispatchEvent(new Event("change"));
    }
  });

  showToast('Tabela preenchida automaticamente!');
}

// Coletar dados da escala para salvar
function getEscalaData() {
  const data = {
    nome: document.getElementById('escalaName').value,
    descricao: document.getElementById('escalaDescription').value,
    datas: [],
    escalas: {}
  };
  
  // Coletar datas
  const dates = headerRow.querySelectorAll('th[data-iso]');
  dates.forEach(th => {
    data.datas.push({
      iso: th.dataset.iso,
      formatada: th.textContent
    });
  });
  
  // Coletar escalas por função
  const rows = tbody.querySelectorAll('tr');
  rows.forEach(row => {
    const funcao = row.dataset.funcao;
    data.escalas[funcao] = {};
    
    const selects = row.querySelectorAll('select');
    selects.forEach((select, index) => {
      const dateIso = dates[index].dataset.iso;
      data.escalas[funcao][dateIso] = select.value;
    });
  });
  
  return data;
}

// Função para salvar a escala
async function saveEscala() {
  const escalaData = getEscalaData();
  
  if (!escalaData.nome) {
    showToast('Por favor, informe um nome para a escala.', 'error');
    return;
  }

  try {
    // Gerar o PDF da escala
    const { blob } = await gerarPdfBlob();

    // Salvar no banco com o PDF
    salvarEscalaNoBanco(escalaData, blob, () => {
      closeSaveModal();
    });
  } catch (e) {
    console.error('Erro ao gerar PDF para salvar:', e);
    showToast('Erro ao gerar PDF da escala.', 'error');
  }
}

// Função para criar uma nova escala (limpar todas as colunas)
function novaEscala() {
  // Remover todas as colunas exceto a primeira
  const ths = [...headerRow.querySelectorAll('th[data-iso]')];
  ths.forEach(th => {
      removeColumn(th.dataset.iso);
  });
  
  // Limpar campos de nome e descrição
  document.getElementById('escalaName').value = '';
  document.getElementById('escalaDescription').value = '';
  
  showToast('Nova escala iniciada. Adicione as datas desejadas.');
}

// Função para remover uma coluna
function removeColumn(iso) {
  // Encontrar o índice da coluna a remover
  const ths = [...headerRow.children];
  const index = ths.findIndex(th => th.dataset.iso === iso);
  if (index === -1) return;

  // Remove cabeçalho
  ths[index].remove();

  // Remove cada célula dessa coluna
  [...tbody.rows].forEach(tr => {
    tr.cells[index].remove();
  });

  // Remove do Set de datas adicionadas
  addedDates.delete(iso);

  showToast(`Data ${formatISOToBRcomSemana(iso)} removida com sucesso.`);
}

// Substituir selects por textos na tabela clonada
function replaceSelectsWithText(clonedTable) {
  // pega os selects originais (ordem) para ler valor selecionado
  const originalSelects = document.querySelectorAll('#escalaTable select');

  // pega os selects clonados
  const clonedSelects = clonedTable.querySelectorAll('select');

  clonedSelects.forEach((select, idx) => {
    const originalSelect = originalSelects[idx];
    const selectedValue = originalSelect ? originalSelect.value : '';
    const displayName = selectedValue || '-- Selecione --';
    const foto = getFoto(selectedValue);

    // wrapper que será exibido no print
    const display = document.createElement('div');
    display.className = 'print-cell';

    const img = document.createElement('img');
    img.className = 'avatar';
    img.alt = displayName;
    img.src = foto;

    const span = document.createElement('span');
    span.textContent = displayName;

    display.appendChild(img);
    display.appendChild(span);

    // substituir o wrapper inteiro quando possível
    const wrapper = select.closest('.select-avatar') || select.parentNode;
    wrapper.innerHTML = '';        // limpa tudo dentro do wrapper
    wrapper.appendChild(display);  // coloca imagem + nome
  });
}

// Função para pré-carregar imagens
function preloadImages() {
  return new Promise((resolve) => {
    const images = document.querySelectorAll('#escalaTable img');
    let loaded = 0;
    const total = images.length;
    
    if (total === 0) {
      resolve();
      return;
    }
    
    images.forEach(img => {
      if (img.complete) {
        loaded++;
        if (loaded === total) resolve();
      } else {
        img.onload = () => {
          loaded++;
          if (loaded === total) resolve();
        };
        img.onerror = () => {
          loaded++;
          if (loaded === total) resolve();
        };
      }
    });
  });
}

// Funções para a barra de progresso
function showProgress(message, percent) {
  let overlay = document.getElementById('progressOverlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.id = 'progressOverlay';
    overlay.className = 'progress-overlay';
    overlay.innerHTML = `
      <div class="progress-message">${message}</div>
      <div class="progress-bar">
        <div class="progress-fill"></div>
      </div>
    `;
    document.body.appendChild(overlay);
  }
  
  overlay.querySelector('.progress-message').textContent = message;
  overlay.querySelector('.progress-fill').style.width = percent + '%';
}

function hideProgress() {
  const overlay = document.getElementById('progressOverlay');
  if (overlay) {
    overlay.remove();
  }
}

// Função para gerar imagem blob (para visualização)
function gerarImagemBlob() {
  return new Promise((resolve, reject) => {
    showProgress('Preparando visualização...', 10);
    
    if (typeof html2canvas === 'undefined') {
      hideProgress();
      return reject(new Error('A biblioteca html2canvas não foi carregada.'));
    }

    const table = document.getElementById('escalaTable');
    if (!table) {
      hideProgress();
      return reject(new Error('Tabela não encontrada.'));
    }

    // Força blur para aplicar o valor atual dos selects
    document.activeElement && document.activeElement.blur();

    // Clonar a tabela (deep)
    const clonedTable = table.cloneNode(true);
    
    // Ajustar estilos para impressão
    clonedTable.style.width = 'auto';
    clonedTable.style.minWidth = '100%';
    
    // Aplicar estilos específicos para garantir renderização correta
    clonedTable.querySelectorAll('th, td').forEach(cell => {
      cell.style.border = '1px solid #ccc';
      cell.style.padding = '8px';
    });
    
    clonedTable.querySelectorAll('th').forEach(th => {
      th.style.backgroundColor = '#eef7ff';
      th.style.fontWeight = 'bold';
    });
    
    // Substituir selects por textos
    replaceSelectsWithText(clonedTable);

    // Criar um container temporário para renderização
    const container = document.createElement('div');
    container.style.position = 'absolute';
    container.style.left = '-9999px';
    container.style.top = '0';
    container.appendChild(clonedTable);
    document.body.appendChild(container);

    // Pequeno timeout para garantir que o DOM seja atualizado
    setTimeout(() => {
      showProgress('Renderizando imagem...', 50);
      
      html2canvas(container, { 
        scale: window.devicePixelRatio >= 2 ? 3 : 2,
        logging: false,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        width: clonedTable.offsetWidth,
        height: clonedTable.offsetHeight,
        onclone: function(clonedDoc) {
          // Garantir que todos os estilos sejam aplicados no clone
          clonedDoc.querySelectorAll('img').forEach(img => {
            if (!img.complete) {
              img.onload = function() {
                // Forçar redraw quando a imagem carregar
                img.style.opacity = 0.99;
                setTimeout(() => { img.style.opacity = 1; }, 10);
              };
            }
          });
        }
      }).then(canvas => {
        showProgress('Finalizando...', 90);
        
        // Remover container temporário
        document.body.removeChild(container);
        
        canvas.toBlob(blob => {
          if (!blob) {
            hideProgress();
            return reject(new Error('Falha ao gerar imagem.'));
          }
          
          showProgress('Concluído!', 100);
          setTimeout(() => hideProgress(), 500);
          resolve({ canvas, blob, fileName: getImageFileName() });
        }, 'image/png');
      }).catch(error => {
        // Remover container temporário em caso de erro
        if (document.body.contains(container)) {
          document.body.removeChild(container);
        }
        hideProgress();
        reject(error);
      });
    }, 100);
  });
}

// Função para gerar PDF blob (para salvamento)
function gerarPdfBlob() {
  return new Promise((resolve, reject) => {
    showProgress('Preparando PDF...', 10);
    
    if (typeof html2canvas === 'undefined') {
      hideProgress();
      return reject(new Error('A biblioteca html2canvas não foi carregada.'));
    }

    if (typeof window.jspdf === 'undefined') {
      hideProgress();
      return reject(new Error('A biblioteca jsPDF não foi carregada.'));
    }

    const table = document.getElementById('escalaTable');
    if (!table) {
      hideProgress();
      return reject(new Error('Tabela não encontrada.'));
    }

    // Força blur para aplicar o valor atual dos selects
    document.activeElement && document.activeElement.blur();

    // Clonar a tabela (deep)
    const clonedTable = table.cloneNode(true);
    
    // Ajustar estilos para impressão
    clonedTable.style.width = 'auto';
    clonedTable.style.minWidth = '100%';
    
    // Aplicar estilos específicos para garantir renderização correta
    clonedTable.querySelectorAll('th, td').forEach(cell => {
      cell.style.border = '1px solid #ccc';
      cell.style.padding = '8px';
    });
    
    clonedTable.querySelectorAll('th').forEach(th => {
      th.style.backgroundColor = '#eef7ff';
      th.style.fontWeight = 'bold';
    });
    
    // Substituir selects por textos
    replaceSelectsWithText(clonedTable);

    // Criar um container temporário para renderização
    const container = document.createElement('div');
    container.style.position = 'absolute';
    container.style.left = '-9999px';
    container.style.top = '0';
    container.appendChild(clonedTable);
    document.body.appendChild(container);

    // Pequeno timeout para garantir que o DOM seja atualizado
    setTimeout(() => {
      showProgress('Renderizando PDF...', 50);
      
      html2canvas(container, { 
        scale: window.devicePixelRatio >= 2 ? 3 : 2,
        logging: false,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        width: clonedTable.offsetWidth,
        height: clonedTable.offsetHeight,
        onclone: function(clonedDoc) {
          // Garantir que todos os estilos sejam aplicados no clone
          clonedDoc.querySelectorAll('img').forEach(img => {
            if (!img.complete) {
              img.onload = function() {
                // Forçar redraw quando a imagem carregar
                img.style.opacity = 0.99;
                setTimeout(() => { img.style.opacity = 1; }, 10);
              };
            }
          });
        }
      }).then(canvas => {
        showProgress('Finalizando...', 90);
        
        // Remover container temporário
        document.body.removeChild(container);
        
        // Criar PDF
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('landscape', 'pt', [canvas.width, canvas.height]);
        
        // Adicionar a imagem ao PDF
        const imgData = canvas.toDataURL('image/png');
        pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
        
        // Gerar blob do PDF
        const pdfBlob = pdf.output('blob');
        
        showProgress('Concluído!', 100);
        setTimeout(() => hideProgress(), 500);
        resolve({ pdf, blob: pdfBlob, fileName: getPdfFileName() });
      }).catch(error => {
        // Remover container temporário em caso de erro
        if (document.body.contains(container)) {
          document.body.removeChild(container);
        }
        hideProgress();
        reject(error);
      });
    }, 100);
  });
}

function getImageFileName() {
  const ts = new Date();
  const pad = n => String(n).padStart(2,'0');
  return `escala_musical_${ts.getFullYear()}${pad(ts.getMonth()+1)}${pad(ts.getDate())}.png`;
}

function getPdfFileName() {
  const ts = new Date();
  const pad = n => String(n).padStart(2,'0');
  return `escala_musical_${ts.getFullYear()}${pad(ts.getMonth()+1)}${pad(ts.getDate())}.pdf`;
}

// Função para baixar a imagem
function downloadImage(blob, fileName) {
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = fileName;
  document.body.appendChild(a);
  a.click();
  a.remove();
  URL.revokeObjectURL(url);
}

// Função para baixar o PDF
function downloadPdf(blob, fileName) {
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = fileName;
  document.body.appendChild(a);
  a.click();
  a.remove();
  URL.revokeObjectURL(url);
}

// Função para visualizar a imagem (modal)
async function previewImage() {
  const btn = document.getElementById('btnPreview');
  const originalText = btn.innerHTML;
  
  try {
    btn.innerHTML = '<span class="spinner"></span> Gerando...';
    btn.disabled = true;
    
    // Pré-carregar imagens antes de gerar o canvas
    await preloadImages();
    
    const { canvas, blob, fileName } = await gerarImagemBlob();
    
    // Armazenar para uso posterior
    currentBlob = blob;
    currentFileName = fileName;
    
    // Exibir preview
    previewContainer.innerHTML = '';
    const img = document.createElement('img');
    img.src = canvas.toDataURL('image/png');
    img.style.maxWidth = '100%';
    previewContainer.appendChild(img);
    
    previewEl.classList.add('show');
    showToast('Prévia gerada com sucesso!');
  } catch (e) {
    console.error('Erro ao gerar prévia:', e);
    showStatus('Não foi possível gerar a prévia.', true);
    showToast('Erro ao gerar prévia. Tente novamente.', 'error');
  } finally {
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
}

// Função para exportar o PDF
async function exportPdf() {
  const btn = document.getElementById('btnExport');
  const originalText = btn.innerHTML;
  
  try {
    btn.innerHTML = '<span class="spinner"></span> Exportando...';
    btn.disabled = true;
    
    // Pré-carregar imagens antes de gerar o PDF
    await preloadImages();
    
    const { blob, fileName } = await gerarPdfBlob();
    downloadPdf(blob, fileName);
    
    // Armazenar para uso posterior
    currentPdfBlob = blob;
    currentFileName = fileName;
    
    showToast('PDF exportado com sucesso!');
  } catch (e) {
    console.error('Erro ao exportar PDF:', e);
    showStatus('Não foi possível exportar o PDF.', true);
    showToast('Erro ao exportar PDF. Tente novamente.', 'error');
  } finally {
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
}

// Função para compartilhar via WhatsApp
async function shareOnWhatsApp() {
  const btn = document.getElementById('btnShare');
  const originalText = btn.innerHTML;
  
  try {
    btn.innerHTML = '<span class="spinner"></span> Preparando...';
    btn.disabled = true;
    
    // Se não temos um blob atual, gerar um novo (PDF para compartilhamento)
    if (!currentPdfBlob) {
      // Pré-carregar imagens antes de gerar o PDF
      await preloadImages();
      
      const result = await gerarPdfBlob();
      currentPdfBlob = result.blob;
      currentFileName = result.fileName;
    }
    
    // 1) Faz o download local primeiro
    downloadPdf(currentPdfBlob, currentFileName);

    // 2) Tenta compartilhamento nativo (Android/iOS/alguns desktops)
    const file = new File([currentPdfBlob], currentFileName, { type: 'application/pdf' });
    
    if (navigator.canShare && navigator.canShare({ files: [file] })) {
      await navigator.share({
        files: [file],
        title: 'Escala Musical',
        text: 'Segue a escala gerada:'
      });
      showToast('Escala compartilhada com sucesso!');
      return;
    }

    // 3) Fallback para WhatsApp Web com texto
    const mensagem = `📅 Escala Musical\n\nSegue a escala gerada.\nO PDF foi baixado como ${currentFileName}. Anexe-o na conversa.`;
    const waUrl = `https://wa.me/?text=${encodeURIComponent(mensagem)}`;
    window.open(waUrl, '_blank');

    showToast('PDF baixado. WhatsApp aberto para enviar.');
  } catch (e) {
    console.error('Erro ao compartilhar:', e);
    showStatus('Não foi possível compartilhar.', true);
    showToast('Erro ao compartilhar. Tente novamente.', 'error');
  } finally {
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
}

// Funções para abrir e fechar o modal de salvar
function openSaveModal() {
  if (addedDates.size === 0) {
    showToast('Adicione pelo menos uma data antes de salvar.', 'error');
    return;
  }
  saveModal.classList.add('show');
}

function closeSaveModal() {
  saveModal.classList.remove('show');
  document.getElementById('escalaName').value = '';
  document.getElementById('escalaDescription').value = '';
}

// Funções para abrir e fechar o modal de nova escala
function openNewModal() {
  if (addedDates.size === 0) {
    // Se não há colunas, não precisa de confirmação
    novaEscala();
    return;
  }
  confirmNewModal.classList.add('show');
}

function closeNewModal() {
  confirmNewModal.classList.remove('show');
}

// Carregar escala salva se existir
function carregarEscalaSalva(dadosEscala) {
  if (!dadosEscala) return;
  
  try {
      const escala = JSON.parse(dadosEscala);
      
      // Preencher nome e descrição se existirem
      if (escala.nome) {
          document.getElementById('escalaName').value = escala.nome;
      }
      
      if (escala.descricao) {
          document.getElementById('escalaDescription').value = escala.descricao;
      }
      
      // Adicionar colunas com as datas
      if (escala.datas && escala.datas.length > 0) {
          escala.datas.forEach(dataObj => {
              const iso = dataObj.iso;
              if (!addedDates.has(iso)) {
                  // Adicionar coluna
                  const th = document.createElement('th');
                  th.dataset.iso = iso;

                  const span = document.createElement('span');
                  span.textContent = formatISOToBRcomSemana(iso);

                  const btn = document.createElement('button');
                  btn.textContent = "✖"; 
                  btn.className = "remove-col";
                  btn.title = "Remover esta data";
                  btn.onclick = () => removeColumn(iso);

                  th.appendChild(span);
                  th.appendChild(btn);
                  headerRow.appendChild(th);

                  // Adicionar selects em cada linha
                  [...tbody.rows].forEach(tr => {
                      const funcao = tr.dataset.funcao;
                      const td = document.createElement('td');
                      const wrap = createSelect(funcao, iso);
                      const sel  = wrap._select; // pega o select real

                      // Selecionar o valor salvo se existir
                      if (escala.escalas[funcao] && escala.escalas[funcao][iso]) {
                          const valorSalvo = escala.escalas[funcao][iso];
                          sel.value = valorSalvo;          // seta o valor
                          wrap._img.src = getFoto(valorSalvo); // atualiza a imagem junto
                      }

                      td.appendChild(wrap);
                      tr.appendChild(td);
                  });

                  addedDates.add(iso);
              }
          });
          
          showToast('Escala anterior carregada com sucesso!');
      }
  } catch (e) {
      console.error('Erro ao carregar escala salva:', e);
      showToast('Erro ao carregar escala salva.', 'error');
  }
}

// Função para salvar/atualizar escala no banco de dados
function salvarEscalaNoBanco(escalaData, blobPdf, callback) {
  const formData = new FormData();
  formData.append('acao', 'salvar_escaladanca');
  formData.append('dados', JSON.stringify(escalaData));

  // adiciona o PDF como arquivo
  if (blobPdf) {
    formData.append('pdf', blobPdf, 'escala.pdf');
  }

  fetch('salvar_escaladanca.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast('Escala salva com sucesso!');
      if (callback) callback();
    } else {
      showToast('Erro ao salvar escala: ' + data.message, 'error');
    }
  })
  .catch(error => {
    console.error('Erro:', error);
    showToast('Erro ao conectar com o servidor.', 'error');
  });
}

// Função para validar escala
function validarEscala() {
  const escalaData = getEscalaData();

  if (escalaData.datas.length === 0) {
    showToast('Adicione ao menos uma data para validar.', 'error');
    return;
  }

  fetch('validar_escala.php', {
    method: 'POST',
    body: new URLSearchParams({
      dados: JSON.stringify(escalaData)
    })
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      showToast(res.message, 'success');
      alert(res.message);
    } else {
      showToast('Conflitos encontrados!', 'error');
      // Mostra conflitos detalhados em modal/alert
      alert(res.message);
    }
  })
  .catch(err => {
    console.error(err);
    showToast('Erro ao validar escala.', 'error');
  });
}

// Eventos
document.getElementById('btnAdd').addEventListener('click', addColumn);
document.getElementById('btnAutoFill').addEventListener('click', autoFillTable);
document.getElementById('btnNew').addEventListener('click', openNewModal);
document.getElementById('datePicker').addEventListener('keydown', e => {
  if (e.key === 'Enter') { e.preventDefault(); addColumn(); }
});
document.getElementById('btnSave').addEventListener('click', openSaveModal);
document.getElementById('btnExport').addEventListener('click', exportPdf);
document.getElementById('btnPreview').addEventListener('click', previewImage);
document.getElementById('btnShare').addEventListener('click', shareOnWhatsApp);
document.getElementById('btnValidate').addEventListener('click', validarEscala);

// Eventos para o modal de salvar
document.querySelector('#saveModal .close-modal').addEventListener('click', closeSaveModal);
document.getElementById('btnCancelSave').addEventListener('click', closeSaveModal);
document.getElementById('btnConfirmSave').addEventListener('click', saveEscala);

// Eventos para o modal de nova escala
document.querySelector('#confirmNewModal .close-modal').addEventListener('click', closeNewModal);
document.getElementById('btnCancelNew').addEventListener('click', closeNewModal);
document.getElementById('btnConfirmNew').addEventListener('click', function() {
  novaEscala();
  closeNewModal();
});

// Eventos para o preview
document.querySelector('.close-preview').addEventListener('click', () => {
  previewEl.classList.remove('show');
});

document.getElementById('btnDownloadPreview').addEventListener('click', () => {
  if (currentBlob) {
    downloadImage(currentBlob, currentFileName);
    previewEl.classList.remove('show');
    showToast('Imagem baixada com sucesso!');
  }
});

document.getElementById('btnSharePreview').addEventListener('click', () => {
  previewEl.classList.remove('show');
  shareOnWhatsApp();
});

// Fechar modais clicando fora
saveModal.addEventListener('click', (e) => {
  if (e.target === saveModal) {
    closeSaveModal();
  }
});

confirmNewModal.addEventListener('click', (e) => {
  if (e.target === confirmNewModal) {
    closeNewModal();
  }
});

previewEl.addEventListener('click', (e) => {
  if (e.target === previewEl) {
    previewEl.classList.remove('show');
  }
});

// Focar no campo de data ao carregar a página
document.getElementById('datePicker').focus();

// Carregar escala salva ao iniciar a página
<?php if ($escalaSalva): ?>
carregarEscalaSalva('<?php echo addslashes($escalaSalva['dados_escala']); ?>');
<?php endif; ?>
</script>

</body>
</html>