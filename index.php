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
        $acao = $_POST['acao'] ?? 'login';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }
        if (empty($senha)) {
            throw new Exception("Senha é obrigatória.");
        }

        $usuarios = [];
        if (file_exists(CAMINHO_USUARIOS)) {
            $usuarios = json_decode(file_get_contents(CAMINHO_USUARIOS), true);
        }

        $usuarioExistente = null;
        foreach ($usuarios as $u) {
            if ($u['email'] === $email) {
                $usuarioExistente = $u;
                break;
            }
        }

        if ($acao === 'login') {
            if (!$usuarioExistente) throw new Exception("Usuário não encontrado.");
            $obj = ($email === EMAIL_ADMIN)
                ? new Administrador($u['nome'], $u['email'], $u['senha'], $u['idioma'], $u['tema'], true)
                : new Usuario($u['nome'], $u['email'], $u['senha'], $u['idioma'], $u['tema'], true);

            if (!$obj->verificarSenha($senha)) {
                throw new Exception("Senha incorreta.");
            }

            Sessao::setarUsuario($obj);
            header("Location: dashboard.php");
            exit();
        }

        // Cadastro
        if ($usuarioExistente) {
            throw new Exception("Já existe um usuário com esse email.");
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
    <meta charset="UTF-8">
    <title>Login / Cadastro</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="local.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo ao Sistema</h1>

        <?php if ($erro): ?>
            <p style="color: yellow; margin-bottom: 15px;"><strong>⚠ <?= htmlspecialchars($erro) ?></strong></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="nome" placeholder="Nome" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="senha" placeholder="Senha" required><br><br>

            <div class="opcoes">
                <div class="grupo-tema">
                    <label><input type="radio" name="tema" value="claro" checked> Claro</label>
                    <label><input type="radio" name="tema" value="escuro"> Escuro</label>
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
                <button type="submit" name="acao" value="login" class="botao">Entrar</button>
                <button type="submit" name="acao" value="cadastro" class="botao">Cadastrar</button>
            </div>
        </form>
    </div>
</body>
</html>
