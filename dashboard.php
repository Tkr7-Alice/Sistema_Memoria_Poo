<?php

// Tela principal após login
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';

Sessao::iniciar();

$usuario = Sessao::getUsuario();

if (!$usuario) {
    header('Location: index.php');
    exit;
}

// Detecta se é administrador por e-mail ou lógica futura
$isAdmin = ($usuario instanceof Administrador || $usuario->getEmail() === 'admin@site.com');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h2>Bem-vindo, <?= htmlspecialchars($usuario->getNome()) ?>!</h2>

    <div>
        <?= $usuario->exibirPerfil() ?>
    </div>

    <?php if ($isAdmin): ?>
        <h3>Lista de Usuários:</h3>
        <?php
            $admin = new Administrador(
                $usuario->getNome(),
                $usuario->getEmail(),
                $usuario->getSenhaHash(),
                $usuario->getIdioma(),
                $usuario->getTema()
            );
            $admin->listarUsuarios('storagem/usuarios.json');
        ?>
    <?php endif; ?>

    <br><br>
    <a href="logout.php">Sair</a>
</body>
</html>

