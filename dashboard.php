<?php
// dashboard.php
require_once 'config.php';
require_once 'classes/Sessao.php';
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';
require_once 'lang.php';

Sessao::iniciar();

$usuario = Sessao::getUsuario();
if (!$usuario) {
    header("Location: index.php");
    exit();
}

$nome   = htmlspecialchars($usuario->getNome(), ENT_QUOTES, 'UTF-8');
$email  = htmlspecialchars($usuario->getEmail(), ENT_QUOTES, 'UTF-8');
$tema   = htmlspecialchars($usuario->getTema(), ENT_QUOTES, 'UTF-8');
$idioma = htmlspecialchars($usuario->getIdioma(), ENT_QUOTES, 'UTF-8');

$isAdmin = $usuario instanceof Administrador || $usuario->getEmail() === EMAIL_ADMIN;

$listaUsuarios = [];
if ($isAdmin && file_exists(CAMINHO_USUARIOS)) {
    $listaUsuarios = json_decode(file_get_contents(CAMINHO_USUARIOS), true) ?: [];
}
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars(traduzir('welcome', $idioma)) ?>, <?= $nome ?></title>
    <link rel="stylesheet" href="assets/style.css" />
    <style>
        .menu-botoes {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .botao {
            background-color: #e0c341;
            color: #3b2363;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .botao:hover {
            background-color: #d4b933;
        }

        .admin-box {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            color: #fff;
        }

        .admin-box ul {
            list-style: none;
            padding-left: 0;
        }

        .admin-box ul li {
            padding: 5px 0;
        }

        .admin-box a {
            color: #ff6b6b;
            text-decoration: none;
            margin-left: 10px;
        }

        .admin-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="tema-<?= $tema ?>">
    <div class="container">
        <h1>🎉 <?= htmlspecialchars(traduzir('welcome', $idioma)) ?>, <?= $nome ?>!</h1>

        <p><strong><?= htmlspecialchars(traduzir('email', $idioma)) ?>:</strong> <?= $email ?></p>
        <p><strong><?= htmlspecialchars(traduzir('theme', $idioma)) ?>:</strong> <?= ucfirst($tema) ?></p>
        <p><strong><?= htmlspecialchars(traduzir('language', $idioma)) ?>:</strong> <?= strtoupper($idioma) ?></p>

        <div class="menu-botoes">
            <a href="editar.php" class="botao"><?= htmlspecialchars(traduzir('edit_profile', $idioma)) ?></a>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao"><?= htmlspecialchars(traduzir('logout', $idioma)) ?></button>
            </form>
        </div>

        <?php if ($isAdmin): ?>
            <div class="admin-box">
                <h2>🔐 <?= htmlspecialchars(traduzir('admin_area', $idioma)) ?></h2>
                <h3><?= htmlspecialchars(traduzir('user_list', $idioma)) ?>:</h3>
                <ul>
                    <?php foreach ($listaUsuarios as $u):
                        $nomeUsuario  = htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8');
                        $emailUsuario = htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8');
                        $linkExcluir  = 'excluir.php?email=' . urlencode($u['email']);
                        $labelExcluir = htmlspecialchars(traduzir('delete_user', $idioma));
                    ?>
                        <li>
                            <?= $nomeUsuario ?> — <?= $emailUsuario ?>
                            <a href="<?= $linkExcluir ?>" onclick="return confirm('<?= addslashes(traduzir('confirm_delete_user', $idioma)) ?>');">
                                [<?= $labelExcluir ?>]
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <p style="margin-top: 20px;"><?= htmlspecialchars(traduzir('logged_as_user', $idioma)) ?></p>
        <?php endif; ?>
    </div>

    <img src="assets/img/borboleta.png" class="borboleta dir" alt="Decorativa" />
    <img src="assets/img/borboleta.png" class="borboleta esq" alt="Decorativa" />
</body>
</html>
