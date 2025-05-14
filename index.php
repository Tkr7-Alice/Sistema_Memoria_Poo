<?php
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';
require_once 'classes/Sessao.php';

Sessao::iniciar();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $idioma = $_POST['idioma'] ?? 'pt';
        $tema = $_POST['tema'] ?? 'claro';
        $tipo = $_POST['tipo'] ?? 'usuario'; // opcional

        if (empty($email) || empty($senha)) {
            throw new Exception("Email e senha são obrigatórios.");
        }

        $usuarios = [];
        $arquivo = 'storagem/usuarios.json';
        if (file_exists($arquivo)) {
            $usuarios = json_decode(file_get_contents($arquivo), true);
        }

        $usuarioEncontrado = null;
        foreach ($usuarios as $u) {
            if ($u['email'] === $email) {
                $usuarioEncontrado = $u;
                break;
            }
        }

        if (isset($_POST['acao']) && $_POST['acao'] === 'login') {
            if (!$usuarioEncontrado) {
                throw new Exception("Usuário não encontrado.");
            }

            $obj = new Usuario(
                $usuarioEncontrado['nome'],
                $usuarioEncontrado['email'],
                $usuarioEncontrado['senha'],
                $usuarioEncontrado['idioma'],
                $usuarioEncontrado['tema']
            );

            if (!$obj->verificarSenha($senha)) {
                throw new Exception("Senha incorreta.");
            }

            Sessao::setarUsuario($obj);
            header("Location: dashboard.php");
            exit;
        }

        // Cadastro
        if ($usuarioEncontrado) {
            throw new Exception("Já existe um usuário com esse email.");
        }

        // Criar novo usuário
        $novo = new Usuario($nome, $email, $senha, $idioma, $tema);
        $usuarios[] = [
            'nome' => $nome,
            'email' => $email,
            'senha' => $novo->getSenhaHash(), // método que vamos adicionar
            'idioma' => $idioma,
            'tema' => $tema
        ];

        if (!is_dir('storagem')) {
            mkdir('storagem', 0777, true);
        }
        
        file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));

        $sucesso = "Usuário cadastrado com sucesso!";
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login e Cadastro</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="script/local.js" defer></script>
</head>
<body>
    <h2>Cadastro / Login</h2>

    <?php if ($erro): ?>
        <p style="color: red;"><?= $erro ?></p>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <p style="color: green;"><?= $sucesso ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome Completo:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <label>Idioma:</label><br>
        <select name="idioma" id="idioma">
            <option value="pt">Português</option>
            <option value="en">Inglês</option>
        </select><br><br>

        <label>Tema:</label><br>
        <input type="radio" name="tema" id="tema-claro" value="claro" checked> Claro
        <input type="radio" name="tema" id="tema-escuro" value="escuro"> Escuro<br><br>

        <button type="submit" name="acao" value="cadastrar">Cadastrar</button>
        <button type="submit" name="acao" value="login">Entrar</button>
    </form>
</body>
</html>

