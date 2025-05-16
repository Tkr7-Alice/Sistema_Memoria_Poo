<?php
// index.php
require_once 'classes/Usuario.php';
require_once 'classes/Administrador.php';
require_once 'classes/Sessao.php';
require_once 'config.php';

Sessao::iniciar();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitização e validação
        $nome = htmlspecialchars(strip_tags(trim($_POST['nome'] ?? '')));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $idioma = in_array($_POST['idioma'] ?? 'pt', ['pt', 'en']) ? $_POST['idioma'] : 'pt';
        $tema = in_array($_POST['tema'] ?? 'claro', ['claro', 'escuro']) ? $_POST['tema'] : 'claro';
        $tipo = $_POST['tipo'] ?? 'usuario';

        // Validação de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }
        if (empty($senha)) {
            throw new Exception("Senha é obrigatória.");
        }

        // Verificar se usuário já existe
        $usuarios = [];
        $arquivo = CAMINHO_USUARIOS;
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

        if ($_POST['acao'] === 'login') {
            if (!$usuarioEncontrado) {
                throw new Exception("Usuário não encontrado.");
            }

            // Corrigido: usar htmlspecialchars para evitar XSS se necessário
            $obj = ($email === EMAIL_ADMIN)
                ? new Administrador($u['nome'], $u['email'], $u['senha'], $u['idioma'], $u['tema'])
                : new Usuario($u['nome'], $u['email'], $u['senha'], $u['idioma'], $u['tema']);

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

        if (!is_dir('storagem')) {
            mkdir('storagem', 0777, true);
        }

        file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));

        Sessao::setarUsuario($novo);
        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>
