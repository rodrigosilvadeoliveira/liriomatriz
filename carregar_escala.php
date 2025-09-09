<?php
include_once('config.php');

// Pega id enviado
$id = intval($_GET['id'] ?? 0);

$sql = "SELECT dados_escala FROM escalas_salvas WHERE id = $id";
$res = $conexao->query($sql);

if(!$res || $res->num_rows == 0){
    echo "<p>Escala não encontrada.</p>";
    exit;
}

$row = $res->fetch_assoc();
$dados = json_decode($row['dados_escala'], true);

if(!$dados || !isset($dados['datas']) || !isset($dados['escalas'])){
    echo "<p>Erro ao interpretar os dados da escala.</p>";
    exit;
}

// Pega lista de músicos com fotos
$sqlMusicos = "SELECT nome, foto FROM musicos";
$resMusicos = $conexao->query($sqlMusicos);
$musicos = [];
while($m = $resMusicos->fetch_assoc()){
    $musicos[$m['nome']] = $m['foto'];
}

function diaSemana($dataIso) {
    $dias = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    $time = strtotime($dataIso);
    return $dias[date('w', $time)];
}

// --- Conteúdo principal fica dentro de #conteudo para podermos aplicar inert/aria-hidden com segurança ---
echo "<div id='conteudo'>";

foreach ($dados['datas'] as $dataInfo) {
    $dataIso = $dataInfo['iso'];
    $dataFormatada = date('d/m', strtotime($dataIso));
    $diaSem = diaSemana($dataIso);

    echo "<div class='escala-card'>";
    echo "<div class='escala-data'>{$dataFormatada} - {$diaSem}</div>";

    foreach ($dados['escalas'] as $funcao => $dias) {
        if (isset($dias[$dataIso])) {
            $musico = htmlspecialchars($dias[$dataIso], ENT_QUOTES, 'UTF-8');
            $foto = isset($musicos[$musico]) && $musicos[$musico] != '' ? $musicos[$musico] : 'uploads/default.png';
            $foto = htmlspecialchars($foto, ENT_QUOTES, 'UTF-8');

            echo "<div class='escala-funcao'>
                    <img src='$foto' alt='Foto de $musico'>
                    <div><strong>$funcao:</strong> $musico</div>
                  </div>";
        }
    }

    // Botão de adicionar repertório (passa o elemento de disparo para devolver foco depois)
    echo "<button class='btn-repertorio' onclick=\"abrirModal('$dataIso', this)\">Adicionar Repertório</button>";

    // Espaço onde o repertório vai aparecer
    echo "<div id='repertorio-$dataIso' class='repertorio-list' aria-live='polite'></div>";

    echo "</div>";
}

echo "</div>"; // #conteudo
?>

<!-- Modal de Repertório (fora do #conteudo) -->
<div id="modalRepertorio"
     class="modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="tituloModalRepertorio"
     hidden>
  <div class="modal-content" role="document">
    <button type="button" class="fechar" aria-label="Fechar" onclick="fecharModal()">&times;</button>
    <h3 id="tituloModalRepertorio">Adicionar Repertório</h3>

    <form id="formRepertorio" onsubmit="event.preventDefault(); salvarRepertorio();">
      <input type="hidden" id="dataSelecionada" name="dataSelecionada">
      <label for="musica1">Música 1</label>
      <input type="text" id="musica1" autocomplete="off">

      <label for="musica2">Música 2</label>
      <input type="text" id="musica2" autocomplete="off">

      <label for="musica3">Música 3</label>
      <input type="text" id="musica3" autocomplete="off">

      <label for="musica4">Música 4</label>
      <input type="text" id="musica4" autocomplete="off">

      <div class="botoes">
        <button type="button" class="btn-sec" onclick="fecharModal()">Cancelar</button>
        <button type="submit" class="btn-pri">Confirmar</button>
      </div>
    </form>
  </div>
</div>

<style>
.escala-card { border:1px solid #ddd; padding:12px; margin:12px 0; border-radius:10px; background:#fff; }
.escala-data { font-weight:700; margin-bottom:8px; }
.escala-funcao { display:flex; align-items:center; gap:10px; margin:6px 0; }
.escala-funcao img { width:40px; height:40px; border-radius:50%; object-fit:cover; }
.btn-repertorio { margin-top:8px; padding:8px 12px; background:#0d6efd; color:#fff; border:none; border-radius:8px; cursor:pointer; }
.btn-repertorio:hover { background:#0b5ed7; }
.repertorio-list { margin-top:10px; }

.modal[hidden] { display:none !important; }
.modal { position:fixed; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.5); padding:16px; }
.modal-content { background:#fff; width:100%; max-width:420px; border-radius:12px; padding:18px; position:relative; }
.modal-content label { display:block; margin-top:10px; font-size:.9rem; }
.modal-content input { width:100%; padding:8px; border:1px solid #ccc; border-radius:8px; margin-top:4px; }
.botoes { display:flex; gap:8px; justify-content:flex-end; margin-top:14px; }
.btn-sec { background:#e9ecef; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; }
.btn-pri { background:#198754; color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; }
.fechar { position:absolute; top:8px; right:10px; background:transparent; border:none; font-size:22px; line-height:1; cursor:pointer; }
#conteudo[inert] { pointer-events:none; user-select:none; }
</style>

<script>
let ultimoDisparo = null; // para devolver o foco ao fechar

function abrirModal(dataIso, triggerEl){
    // guarda o botão que abriu
    ultimoDisparo = triggerEl || document.activeElement;

    const modal = document.getElementById("modalRepertorio");
    const fundo = document.getElementById("conteudo");

    // limpa campos
    document.getElementById("dataSelecionada").value = dataIso;
    ["musica1","musica2","musica3","musica4"].forEach(id => document.getElementById(id).value = "");

    // 🔹 primeiro desativa o fundo
    if (fundo) {
        fundo.setAttribute("inert", "");
        fundo.setAttribute("aria-hidden", "true");
    }

    // 🔹 abre modal
    modal.removeAttribute("hidden");

    // 🔹 só agora move o foco para dentro do modal
    setTimeout(() => {
        document.getElementById("musica1").focus();
    }, 0);

    // fecha com ESC
    document.addEventListener("keydown", escHandler);
}

function escHandler(e){
    if(e.key === "Escape"){
        fecharModal();
    }
}

function fecharModal(){
    const modal = document.getElementById("modalRepertorio");
    const fundo = document.getElementById("conteudo");

    // reativa o fundo ANTES de devolver o foco
    if (fundo) {
        fundo.removeAttribute("inert");
        fundo.removeAttribute("aria-hidden");
    }

    modal.setAttribute("hidden", "");

    // devolve o foco ao botão que abriu
    if (ultimoDisparo && document.body.contains(ultimoDisparo)) {
        ultimoDisparo.focus();
    }

    document.removeEventListener("keydown", escHandler);
}

function salvarRepertorio(){
    const dataIso = document.getElementById("dataSelecionada").value;
    const m1 = document.getElementById("musica1").value.trim();
    const m2 = document.getElementById("musica2").value.trim();
    const m3 = document.getElementById("musica3").value.trim();
    const m4 = document.getElementById("musica4").value.trim();

    const itens = [m1,m2,m3,m4].filter(Boolean).map(m => `<li>${escapeHtml(m)}</li>`).join("");

    const lista = itens
      ? `<div class="rep-wrap"><strong>Repertório:</strong><ul>${itens}</ul></div>`
      : `<div class="rep-wrap"><em>Nenhuma música informada.</em></div>`;

    const alvo = document.getElementById("repertorio-"+CSS.escape(dataIso));
    if (alvo) alvo.innerHTML = lista;

    fecharModal();
}

// util para evitar HTML injection ao exibir os nomes das músicas
function escapeHtml(str){
  return str.replace(/[&<>"']/g, s => ({
    "&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"
  }[s]));
}
</script>
