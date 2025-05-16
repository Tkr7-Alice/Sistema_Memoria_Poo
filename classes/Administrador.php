<?php
require_once 'Usuario.php';

class Administrador extends Usuario
{
    public function listarUsuarios($caminhoArquivo)
    {
        if (!file_exists($caminhoArquivo)) {
            echo "Arquivo de usuários não encontrado.";
            return;
        }

        $dados = json_decode(file_get_contents($caminhoArquivo), true);

        foreach ($dados as $usuario) {
            // Evita XSS usando htmlspecialchars na saída
            $nome = htmlspecialchars($usuario['nome'] ?? '');
            $email = htmlspecialchars($usuario['email'] ?? '');

            echo "Usuário: $nome - Email: $email<br>";
        }
    }
}

