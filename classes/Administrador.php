<?php
// classes/Administrador.php

require_once 'Usuario.php';

class Administrador extends Usuario {
    public function __construct($nome, $email, $senhaOuHash, $idioma = 'pt', $tema = 'claro', $jaHasheada = false) {
        parent::__construct($nome, $email, $senhaOuHash, $idioma, $tema, $jaHasheada);
    }

    public function listarUsuarios($caminho) {
        if (!file_exists($caminho)) {
            echo "<p>⚠ Nenhum usuário encontrado.</p>";
            return;
        }

        $usuarios = json_decode(file_get_contents($caminho), true);
        if (!is_array($usuarios)) {
            echo "<p>⚠ Erro ao ler os dados dos usuários.</p>";
            return;
        }

        echo "<h4>Lista de Usuários Cadastrados:</h4><ul>";
        foreach ($usuarios as $u) {
            echo "<li><strong>" . htmlspecialchars($u['nome']) . "</strong> – " . htmlspecialchars($u['email']) . "</li>";
        }
        echo "</ul>";
    }
}

