<?php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';
require_once 'lang.php';

Sessao::iniciar();

$usuario = Sessao::pegarUsuario();
if (!$usuario) {
    header("Location: index.php");
    exit();
}

$isAdmin = $usuario instanceof Administrador || $usuario->getEmail() === EMAIL_ADMIN;
$nome = htmlspecialchars($usuario->getNome());
$email = htmlspecialchars($usuario->getEmail());
$tema = htmlspecialchars($usuario->getTema());
$idioma = htmlspecialchars($usuario->getIdioma());
?>

<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8">
    <title><?= traduzir('welcome', $idioma) ?>, <?= $nome ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="tema-<?= $tema ?>">
    <div class="container">
        <h1>🎉 <?= traduzir('welcome', $idioma) ?>, <?= $nome ?>!</h1>

        <p><strong><?= traduzir('email', $idioma) ?>:</strong> <?= $email ?></p>
        <p><strong><?= traduzir('theme', $idioma) ?>:</strong> <?= ucfirst($tema) ?></p>
        <p><strong><?= traduzir('language', $idioma) ?>:</strong> <?= strtoupper($idioma) ?></p>

        <div class="menu-botoes">
            <a href="editar.php" class="botao"><?= traduzir('edit_profile', $idioma) ?></a>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao"><?= traduzir('logout', $idioma) ?></button>
            </form>
        </div>

        <?php if ($isAdmin): ?>
            <div class="admin-box" style="margin-top:30px;">
                <h2>🔐 <?= traduzir('admin_area', $idioma) ?></h2>
                <h3><?= traduzir('user_list', $idioma) ?>:</h3>
                <ul>
                    <?php
                    $lista = json_decode(file_get_contents(CAMINHO_USUARIOS), true);
                    foreach ($lista as $u) {
                        echo "<li>" . htmlspecialchars($u['nome']) . " — " . htmlspecialchars($u['email']);
                        echo " <a href='excluir.php?email=" . urlencode($u['email']) . "' onclick=\"return confirm('Tem certeza que deseja excluir este usuário?');\">[" . traduzir('delete_user', $idioma) . "]</a></li>";
                    }
                    ?>
                </ul>
            </div>
        <?php else: ?>
            <p style="margin-top: 20px;"><?= traduzir('logged_as_user', $idioma) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
