<?php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';

Sessao::iniciar();

$usuario = Sessao::getUsuario();
if (!$usuario) {
    header("Location: index.php");
    exit();
}

// Define tema atual para o <body>
$temaSelecionado = $_SESSION['tema'] ?? $usuario->getTema() ?? 'padrao';
$idioma = htmlspecialchars($usuario->getIdioma(), ENT_QUOTES, 'UTF-8');
$usuarios = json_decode(file_get_contents(CAMINHO_USUARIOS), true);
$emailAtual = $usuario->getEmail();

// Textos para idiomas
$titulos = [
    'pt' => [
        'editar' => '✏️ Editar Perfil',
        'salvar' => 'Salvar',
        'cancelar' => 'Cancelar',
        'tema' => 'Tema:',
        'nenhum' => 'Nenhum',
        'claro' => 'Claro',
        'escuro' => 'Escuro',
        'idioma' => 'Idioma:',
        'nova_senha' => 'Nova senha (deixe em branco para manter)'
    ],
    'en' => [
        'editar' => '✏️ Edit Profile',
        'salvar' => 'Save',
        'cancelar' => 'Cancel',
        'tema' => 'Theme:',
        'nenhum' => 'None',
        'claro' => 'Light',
        'escuro' => 'Dark',
        'idioma' => 'Language:',
        'nova_senha' => 'New password (leave blank to keep current)'
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome     = htmlspecialchars(trim($_POST['nome']), ENT_QUOTES, 'UTF-8');
    $novaSenha    = $_POST['nova_senha'];
    $novoIdioma   = in_array($_POST['idioma'], ['pt', 'en']) ? $_POST['idioma'] : 'pt';
    $novoTema     = in_array($_POST['tema'], ['claro', 'escuro']) ? $_POST['tema'] : 'padrao';

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

    $usuario->setNome($novoNome);
    $usuario->setIdioma($novoIdioma);
    $usuario->setTema($novoTema);
    Sessao::setarUsuario($usuario);
    $_SESSION['tema'] = $novoTema;

    file_put_contents(CAMINHO_USUARIOS, json_encode($usuarios, JSON_PRETTY_PRINT));
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $titulos[$idioma]['editar'] ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="tema-<?= htmlspecialchars($temaSelecionado, ENT_QUOTES, 'UTF-8') ?>">
    <main class="container">
        <h1><?= $titulos[$idioma]['editar'] ?></h1>
        <form method="post">
            <input type="text" name="nome" value="<?= htmlspecialchars($usuario->getNome(), ENT_QUOTES, 'UTF-8') ?>" required><br><br>

            <input type="password" name="nova_senha" placeholder="<?= $titulos[$idioma]['nova_senha'] ?>"><br><br>

            <label><?= $titulos[$idioma]['tema'] ?></label><br>
            <label>
                <input type="radio" name="tema" value="padrao" <?= $usuario->getTema() === 'padrao' ? 'checked' : '' ?>>
                <?= $titulos[$idioma]['nenhum'] ?>
            </label>
            <label>
                <input type="radio" name="tema" value="claro" <?= $usuario->getTema() === 'claro' ? 'checked' : '' ?>>
                <?= $titulos[$idioma]['claro'] ?>
            </label>
            <label>
                <input type="radio" name="tema" value="escuro" <?= $usuario->getTema() === 'escuro' ? 'checked' : '' ?>>
                <?= $titulos[$idioma]['escuro'] ?>
            </label><br><br>

            <label for="idioma"><?= $titulos[$idioma]['idioma'] ?></label>
            <select name="idioma" id="idioma">
                <option value="pt" <?= $usuario->getIdioma() === 'pt' ? 'selected' : '' ?>>Português</option>
                <option value="en" <?= $usuario->getIdioma() === 'en' ? 'selected' : '' ?>>English</option>
            </select><br><br>

            <div class="botoes-form">
                <button type="submit" class="botao"><?= $titulos[$idioma]['salvar'] ?></button>
                <a href="dashboard.php" class="botao grande"><?= $titulos[$idioma]['cancelar'] ?></a>
            </div>
        </form>
    </main>
</body>
</html>

</html>
