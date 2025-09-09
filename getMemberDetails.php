<?php
include_once('config.php');

if(isset($_GET['id'])) {
    $memberId = $_GET['id'];
    
    $sql = "SELECT * FROM membros WHERE id = $memberId";
    $result = $conexao->query($sql);
    
    if($result && $result->num_rows > 0) {
        $member = $result->fetch_assoc();
        
        // Format dates
        $dataNascimento = date('d/m/Y', strtotime($member['nascimento']));
        $dataMembroDesde = date('d/m/Y', strtotime($member['datas']));
        
        // Calculate age
        $idade = floor((time() - strtotime($member['nascimento'])) / 31556926);
        
        // Format boolean values
        $batizado = $member['batizado'] ? 'Sim' : 'Não';
        $voluntario = $member['voluntario'] ? 'Sim' : 'Não';
        $lider = $member['lider'] ? 'Sim' : 'Não';
        
        // Status badge
        $status_class = $member['status'] == 'ativo' ? 'bg-success' : 'bg-secondary';
        
        echo '
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="uploads/'.$member['foto'].'" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" onerror="this.src=\'https://via.placeholder.com/150?text=Sem+Imagem\'">
                <h4>'.$member['nome'].' '.$member['sobrenome'].'</h4>
                <span class="badge '.$status_class.'">'.$member['status'].'</span>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-sm-6">
                        <p><strong>Email:</strong> '.$member['email'].'</p>
                        <p><strong>Telefone:</strong> '.$member['telefone'].'</p>
                        <p><strong>Data de Nascimento:</strong> '.$dataNascimento.'</p>
                        <p><strong>Idade:</strong> '.$idade.' anos</p>
                    </div>
                    <div class="col-sm-6">
                        <p><strong>Batizado:</strong> '.$batizado.'</p>
                        <p><strong>Membro desde:</strong> '.$dataMembroDesde.'</p>
                        <p><strong>Voluntário:</strong> '.$voluntario.'</p>
                        <p><strong>Líder:</strong> '.$lider.'</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Departamentos:</strong> '.$member['departamentos'].'</p>
                        <p><strong>Observações:</strong> '.$member['observacoes'].'</p>
                    </div>
                </div>
            </div>
        </div>';
    } else {
        echo '<div class="alert alert-warning">Membro não encontrado.</div>';
    }
} else {
    echo '<div class="alert alert-danger">ID do membro não especificado.</div>';
}
?>