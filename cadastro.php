<?php
require_once 'config.php';
require_once 'classes/Usuario.php';
require_once 'classes/Sessao.php';

Sessao::iniciar();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = trim($_POST['nome'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $idioma = $_POST['idioma'] ?? 'pt';
        $tema = $_POST['tema'] ?? 'claro';

        if (empty($nome) || !preg_match('/^[\p{L} ]+$/u', $nome)) {
            throw new Exception("Nome inválido.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }

        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter no mínimo 6 caracteres.");
        }

        $usuarios = file_exists(CAMINHO_USUARIOS)
            ? json_decode(file_get_contents(CAMINHO_USUARIOS), true)
            : [];

        foreach ($usuarios as $u) {
            if ($u['email'] === $email) {
                throw new Exception("Email já cadastrado.");
            }
        }

        $novoUsuario = new Usuario($nome, $email, $senha, $idioma, $tema);
        $usuarios[] = $novoUsuario->toArray();

        file_put_contents(CAMINHO_USUARIOS, json_encode($usuarios, JSON_PRETTY_PRINT));

        Sessao::setarUsuario($novoUsuario);
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro</title>
    <link rel="stylesheet" href="assets/style.css" />
    <script src="script/local.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Cadastro</h1>

        <?php if ($erro): ?>
            <p class="erro"><strong>⚠ <?= htmlspecialchars($erro) ?></strong></p>
        <?php endif; ?>

        <form method="post" novalidate autocomplete="off">
            <input type="text" name="nome" placeholder="Nome" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" />
            <input type="email" name="email" placeholder="Email" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" />
            <input type="password" name="senha" placeholder="Senha" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly')" />

            <div class="botoes-form">
                <a href="index.php" class="botao botao-secundario">Voltar</a>
                <button type="submit" class="botao grande">Cadastrar</button>
            </div>
        </form>
    </div>

    <img src="assets/img/borboleta.png" class="borboleta dir" alt="Borboleta topo" />
    <img src="assets/img/borboleta.png" class="borboleta esq" alt="Borboleta base" />
</body>
</html>
