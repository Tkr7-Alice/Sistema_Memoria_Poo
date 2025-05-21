<?php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';

Sessao::iniciar();
$usuario = Sessao::pegarUsuario();
if (!$usuario) {
    header("Location: index.php");
    exit();
}

$idioma = $usuario->getIdioma();
$usuarios = json_decode(file_get_contents(CAMINHO_USUARIOS), true);
$emailAtual = $usuario->getEmail();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($usuarios as &$u) {
        if ($u['email'] === $emailAtual) {
            $u['nome'] = $_POST['nome'];
            if (!empty($_POST['nova_senha'])) {
                $u['senha'] = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
            }
            $u['idioma'] = $_POST['idioma'];
            $u['tema'] = $_POST['tema'];
            break;
        }
    }

    file_put_contents(CAMINHO_USUARIOS, json_encode($usuarios, JSON_PRETTY_PRINT));
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="tema-<?= $usuario->getTema() ?>">
    <div class="container">
        <h1>✏️ Editar Perfil</h1>
        <form method="post">
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario->getNome()) ?>" required><br><br>
            <input type="password" name="nova_senha" placeholder="Nova senha (deixe em branco para manter)"><br><br>

            <label>Tema:</label><br>
            <label><input type="radio" name="tema" value="claro" <?= $usuario->getTema() === 'claro' ? 'checked' : '' ?>> Claro</label>
            <label><input type="radio" name="tema" value="escuro" <?= $usuario->getTema() === 'escuro' ? 'checked' : '' ?>> Escuro</label><br><br>

            <label for="idioma">Idioma:</label>
            <select name="idioma" id="idioma">
                <option value="pt" <?= $usuario->getIdioma() === 'pt' ? 'selected' : '' ?>>Português</option>
                <option value="en" <?= $usuario->getIdioma() === 'en' ? 'selected' : '' ?>>English</option>
            </select><br><br>

            <div class="botoes-form">
                <button type="submit" class="botao">Salvar</button>
                <a href="dashboard.php" class="botao">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
