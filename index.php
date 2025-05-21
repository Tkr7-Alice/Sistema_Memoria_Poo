<?php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';

Sessao::iniciar();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }
        if (empty($senha)) {
            throw new Exception("Senha é obrigatória.");
        }

        $usuarios = file_exists(CAMINHO_USUARIOS)
            ? json_decode(file_get_contents(CAMINHO_USUARIOS), true)
            : [];

        foreach ($usuarios as $u) {
            if ($u['email'] === $email && password_verify($senha, $u['senha'])) {
                $usuario = ($email === EMAIL_ADMIN)
                    ? new Administrador($u['nome'], $u['email'], $senha, $u['idioma'], $u['tema'])
                    : new Usuario($u['nome'], $u['email'], $senha, $u['idioma'], $u['tema']);

                Sessao::setarUsuario($usuario);
                header("Location: dashboard.php");
                exit();
            }
        }

        throw new Exception("Usuário ou senha incorretos.");
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css" />
    <script src="script/local.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        <?php if ($erro): ?>
            <p class="erro"><strong>⚠ <?= htmlspecialchars($erro) ?></strong></p>
        <?php endif; ?>

        <form method="post" novalidate>
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="senha" placeholder="Senha" required />

            <div class="botoes-form">
                <a href="cadastro.php" class="botao botao-secundario">Cadastrar</a>
                 <button type="submit" class="botao grande">Entrar</button>
            </div>

        </form>
    </div>

    <img src="assets/img/borboleta.png" class="borboleta dir" alt="Borboleta topo" />
    <img src="assets/img/borboleta.png" class="borboleta esq" alt="Borboleta base" />
</body>
</html>
