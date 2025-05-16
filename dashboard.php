<?php
require_once 'classes/Sessao.php';
require_once 'classes/Administrador.php';
require_once 'config.php';

Sessao::iniciar();

$usuario = Sessao::pegarUsuario();
if (!$usuario) {
    header("Location: index.php");
    exit;
}

$isAdmin = $usuario instanceof Administrador || $usuario->getEmail() === EMAIL_ADMIN;
$nome = htmlspecialchars($usuario->getNome());
$email = htmlspecialchars($usuario->getEmail());
$tema = htmlspecialchars($usuario->getTema());
$idioma = htmlspecialchars($usuario->getIdioma());
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Usuário</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .painel {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 600px;
            margin-top: 80px;
            animation: entradaSuave 0.8s ease;
        }
        .painel h2 {
            color: #4b0082;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .painel p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .painel .admin-box {
            margin-top: 20px;
            padding: 15px;
            background-color: rgba(255,255,255,0.2);
            border-radius: 10px;
        }
        .painel button {
            padding: 10px 20px;
            background-color: #ffffff;
            color: #6404be;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        .painel button:hover {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <div class="painel">
        <h2>🎉 Bem-vindo, <?= $nome ?>!</h2>
        <p><strong>Email:</strong> <?= $email ?></p>
        <p><strong>Tema:</strong> <?= $tema ?></p>
        <p><strong>Idioma:</strong> <?= $idioma ?></p>

        <?php if ($isAdmin): ?>
            <div class="admin-box">
                <h3>🔐 Área do Administrador</h3>
                <?php $usuario->listarUsuarios(CAMINHO_USUARIOS); ?>
            </div>
        <?php else: ?>
            <p>Você está logado como <strong>usuário comum</strong>.</p>
        <?php endif; ?>

        <form action="logout.php" method="post">
            <button type="submit">Sair</button>
        </form>
    </div>
</body>
</html>
