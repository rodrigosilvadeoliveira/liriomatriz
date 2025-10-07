<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Lirio Matriz</title>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
            --error-color: #ff6b6b;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Elementos decorativos de fundo */
        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--primary-color);
            top: -150px;
            right: -100px;
        }
        
        .shape-2 {
            width: 200px;
            height: 200px;
            background: var(--accent-color);
            bottom: -100px;
            left: -50px;
        }
        
        .shape-3 {
            width: 150px;
            height: 150px;
            background: var(--secondary-color);
            top: 50%;
            left: 10%;
        }
        
        /* Cabeçalho com logo e título */
        .header {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
            max-width: 400px;
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }
        
        #logo {
            max-width: 215px;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            margin-top: -15%;
        }
        
        .title {
            color: var(--dark-color);
            font-weight: 600;
            font-size: 1.8rem;
            line-height: 1.3;
            margin-bottom: 5px;
        }
        
        .title u {
            color: var(--primary-color);
            text-decoration: none;
            position: relative;
        }
        
        .title u::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }
        
        .subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 400;
        }
        
        /* Container do formulário - CENTRALIZADO */
        .tela-login {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            /* Centralização perfeita */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .tela-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
        }
        
        .tela-login:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* Formulário */
        .loginsenha {
            width: 100%;
        }
        
        #formlogin {
            text-align: center;
            color: var(--dark-color);
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .login {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #e1e5eb;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f8f9fa;
        }
        
        .login:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.2rem;
        }
        
        .inputSubmit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
        }
        
        .inputSubmit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .inputSubmit:active {
            transform: translateY(0);
        }
        
        /* Link de voltar (se necessário) */
        #voltarLogin {
            position: absolute;
            top: 20px;
            left: 20px;
            color: var(--dark-color);
            font-size: 1.5rem;
            transition: var(--transition);
            z-index: 10;
        }
        
        #voltarLogin:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }
        
        /* Mensagens de status */
        .status-message {
            padding: 10px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
            display: none;
        }
        
        .success {
            background-color: rgba(75, 181, 67, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(75, 181, 67, 0.3);
        }
        
        .error {
            background-color: rgba(255, 107, 107, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(255, 107, 107, 0.3);
        }
        
        /* Rodapé */
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 0.8rem;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            body {
                padding: 15px;
                justify-content: flex-start;
                padding-top: 40px;
            }
            
            .tela-login {
                padding: 25px 20px;
                margin-top: 20px;
            }
            
            .title {
                font-size: 1.5rem;
                margin-top: -15%;
            }
            
            #formlogin {
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                margin-bottom: 20px;
            }
            
            .title {
                font-size: 1.3rem;
            }
            
            .tela-login {
                padding: 20px 15px;
            }
            
            .login, .inputSubmit {
                padding: 12px 12px 12px 40px;
            }
        }
        
        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tela-login, .header {
            animation: fadeIn 0.8s ease-out;
        }
        
        /* Modo escuro (opcional) */
        @media (prefers-color-scheme: dark) {
            :root {
                --light-color: #1a1a1a;
                --dark-color: #f8f9fa;
            }
            
            body {
                background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            }
            
            .tela-login {
                background: #2d3748;
                color: #f8f9fa;
            }
            
            .login {
                background-color: #4a5568;
                border-color: #4a5568;
                color: #f8f9fa;
            }
            
            .login:focus {
                background-color: #4a5568;
            }
            
            .title, #formlogin {
                color: #f8f9fa;
            }
            
            .subtitle {
                color: #a0aec0;
            }
        }
    </style>
</head>

<body>
    <!-- Elementos decorativos de fundo -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    
    <!-- Cabeçalho com logo e título -->
    <div class="header">
        <div class="logo-container">
            <img id="logo" src="lirioMatriz_preto.png" alt="Logo Sistema Teste Matriz">
        </div>
        <h1 class="title">Sistema <br><u>Lirio Matriz</u></h1>
        <p class="subtitle">Área do Voluntario(a)</p>
    </div>
    
    <!-- Container do formulário - AGORA CENTRALIZADO -->
    <div class="tela-login">
        <!-- Botão de voltar (descomente se necessário) -->
        <!--
        <a href="homeLirio.php" id="voltarLogin">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
            </svg>
        </a>
        -->
      
        
        <form action="acessoAdm.php" method="POST">
            <div class="loginsenha">
                <h1 id="formlogin">Login</h1>
                
                <div class="input-group">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                    <input class="login" type="text" placeholder="Usuário" name="usuario" required>
                </div>
                
                <div class="input-group">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                    </svg>
                    <input class="login" type="password" placeholder="Senha" name="senha" required>
                </div>
                
                <input class="inputSubmit" type="submit" name="submit" value="Entrar">
            </div>
        </form>
    </div>
    
    <!-- Rodapé -->
    <div class="footer">
        <p>&copy; 2025 Sistema Lirio Matriz. Todos os direitos reservados.</p>
    </div>

    <script>
        // Tempo de inatividade em milissegundos (1 hora = 3600000 ms)
        const tempoLimite = 3600000;

        // Redireciona para logout após o tempo limite
        setTimeout(() => {
            window.location.href = "sistema.php?timeout=1"; 
        }, tempoLimite);
        
        // Verificar se há parâmetros de erro na URL para mostrar mensagem
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            
            if (error) {
                const errorMessage = document.getElementById('errorMessage');
                errorMessage.style.display = 'block';
                
                // Remover a mensagem após 5 segundos
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            
            // Service Worker para PWA
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/service-worker.js');
            }
        });
    </script>
</body>
</html>