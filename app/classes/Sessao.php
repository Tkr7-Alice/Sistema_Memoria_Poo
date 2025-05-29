<?php
// Gerenciamento de sessão com boas práticas
class Sessao {

    // Inicia a sessão, se ainda não estiver iniciada
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Define o usuário na sessão
    public static function setarUsuario($usuario) {
        if (is_object($usuario)) {
            $_SESSION['usuario'] = $usuario;
        }
    }

    // Recupera o usuário da sessão
    public static function getUsuario() {
        return $_SESSION['usuario'] ?? null;
    }

    // Verifica se há um usuário logado
    public static function validar() {
        return isset($_SESSION['usuario']);
    }

    // Encerra a sessão com segurança
    public static function encerrar() {
        session_unset();
        session_destroy();
    }
}
?>
