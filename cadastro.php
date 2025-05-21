<?php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';

Sessao::iniciar();

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $idioma = $_POST['idioma'] ?? 'pt';
        $tema = $_POST['tema'] ?? 'claro';

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
            if ($u['email'] === $email) {
                throw new Exception("Já existe um usuário com esse email.");
            }
        }

        $novo = ($email === EMAIL_ADMIN)
            ? new Administrador($nome, $email, $senha, $idioma, $tema)
            : new Usuario($nome, $email, $senha, $idioma, $tema);

        $usuarios[] = [
            'nome' => $nome,
            'email' => $email,
            'senha' => $novo->getSenhaHash(),
            'idioma' => $idioma,
            'tema' => $tema
        ];

        file_put_contents(CAMINHO_USUARIOS, json_encode($usuarios, JSON_PRETTY_PRINT));
        Sessao::setarUsuario($novo);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro</title>
    <link rel="stylesheet" href="assets/style.css" />
</head>
<body>
    <div class="container">
        <h1 style="margin-bottom: 0.5em;">Cadastro</h1>

        <?php if ($erro): ?>
            <p class="erro"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <form method="post" novalidate>
            <input type="text" name="nome" placeholder="Nome" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="senha" placeholder="Senha" required />

            <div class="opcoes">
                <div class="grupo-tema">
                    <label><input type="radio" name="tema" value="claro" checked /> Claro</label>
                    <label><input type="radio" name="tema" value="escuro" /> Escuro</label>
                </div>
                <div class="grupo-idioma">
                    <label for="idioma">Idioma:</label>
                    <select name="idioma" id="idioma">
                        <option value="pt">Português</option>
                        <option value="en">English</option>
                    </select>
                </div>
            </div>

            <div class="botoes-form">
                <a href="index.php" class="botao pequeno">Voltar</a>
                <button type="submit" class="botao grande">Cadastrar</button>
            </div>
        </form>
    </div>

    <img src="assets/img/borboleta.png" class="borboleta dir" alt="Borboleta topo" />
    <img src="assets/img/borboleta.png" class="borboleta esq" alt="Borboleta base" />

    <script src="script/local.js"></script>
</body>
</html>
