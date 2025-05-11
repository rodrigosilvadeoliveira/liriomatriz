<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload do Composer
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Conexão com o banco de dados
    include_once('config.php');
    
    if ($conexao->connect_error) die("Erro de conexão");

    // Verifica se e-mail existe
    $stmt = $conexao->prepare("SELECT id FROM cadastroadm WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Atualiza o token e a expiração no banco
        $stmt = $conexao->prepare("UPDATE cadastroadm SET token_recuperacao = ?, token_expira = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expira, $email);
        $stmt->execute();

        $link = "http://localhost/redefinir_senha.php?token=$token";

        // Envia e-mail usando PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'qarodrigo.teste@gmail.com'; // Seu e-mail
            $mail->Password   = 'Digodw19';       // Senha de app do Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('qarodrigo.teste@gmail.com', 'Rodrigo');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Redefinição de Senha';
            $mail->Body    = "Olá!<br>Clique no link abaixo para redefinir sua senha:<br><a href='$link'>$link</a><br><br>Esse link expira em 1 hora.";

            $mail->send();
            echo "Um e-mail foi enviado com o link de redefinição.";
        } catch (Exception $e) {
            echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        }
    } else {
        echo "E-mail não encontrado.";
    }

    $stmt->close();
    $conexao->close();
}
?>

<!-- HTML do formulário -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Esqueci a Senha</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; }
        .container { width: 350px; margin: 100px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input[type="email"], input[type="submit"] {
            width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc;
        }
        input[type="submit"] { background: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Esqueci minha senha</h2>
        <form method="POST">
            <label for="email">Digite seu e-mail:</label>
            <input type="email" name="email" required>
            <input type="submit" value="Enviar link de redefinição">
        </form>
    </div>
</body>
</html>
