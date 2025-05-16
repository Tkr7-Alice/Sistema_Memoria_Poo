<?php
class Sessao {
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setarUsuario($usuario) {
        $_SESSION['usuario'] = serialize($usuario);
    }

    public static function pegarUsuario() {
        return isset($_SESSION['usuario']) ? unserialize($_SESSION['usuario']) : null;
    }

    public static function encerrar() {
        session_destroy();
    }
}
