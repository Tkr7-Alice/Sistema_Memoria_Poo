<?php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Administrador.php';

Sessao::iniciar();
$admin = Sessao::pegarUsuario();
if (!$admin || !($admin instanceof Administrador)) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $usuarios = json_decode(file_get_contents(CAMINHO_USUARIOS), true);
    $usuarios = array_filter($usuarios, fn($u) => $u['email'] !== $email);
    file_put_contents(CAMINHO_USUARIOS, json_encode(array_values($usuarios), JSON_PRETTY_PRINT));
}

header("Location: dashboard.php");
exit();
