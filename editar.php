<?php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';

Sessao::iniciar();

$usuario = Sessao::getUsuario(); // Corrigido aqui
if (!$usuario) {
    header("Location: index.php");
    exit();
}

$idioma = htmlspecialchars($usuario->getIdioma(), ENT_QUOTES, 'UTF-8');
$usuarios = json_decode(file_get_contents(CAMINHO_USUARIOS), true);
$emailAtual = $usuario->getEmail();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome     = htmlspecialchars(trim($_POST['nome']), ENT_QUOTES, 'UTF-8');
    $novaSenha    = $_POST['nova_senha'];
    $novoIdioma   = in_array($_POST['idioma'], ['pt', 'en']) ? $_POST['idioma'] : 'pt';
    $novoTema     = in_array($_POST['tema'], ['claro', 'escuro']) ? $_POST['tema'] : 'claro';

    foreach ($usuarios as &$u) {
        if ($u['email'] === $emailAtual) {
            $u['nome'] = $novoNome;
            if (!empty($novaSenha)) {
                $u['senha'] = password_hash($novaSenha, PASSWORD_DEFAULT);
            }
            $u['idioma'] = $novoIdioma;
            $u['tema'] = $novoTema;
            break;
        }
    }

    // Atualiza sessão com novos dados
    $usuario->setNome($novoNome);
    $usuario->setIdioma($novoIdioma);
    $usuario->setTema($novoTema);
    Sessao::setarUsuario($usuario);

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
<body class="tema-<?= htmlspecialchars($usuario->getTema(), ENT_QUOTES, 'UTF-8') ?>">
    <div class="container">
        <h1>✏️ Editar Perfil</h1>
        <form method="post">
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario->getNome(), ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <input type="password" name="nova_senha" placeholder="Nova senha (deixe em branco para manter)"><br><br>

            <label>Tema:</label><br>
            <label>
                <input type="radio" name="tema" value="claro" <?= $usuario->getTema() === 'claro' ? 'checked' : '' ?>> Claro
            </label>
            <label>
                <input type="radio" name="tema" value="escuro" <?= $usuario->getTema() === 'escuro' ? 'checked' : '' ?>> Escuro
            </label><br><br>

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
