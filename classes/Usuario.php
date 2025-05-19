<?php
class Usuario {
    protected $nome;
    protected $email;
    protected $senhaHash;
    protected $idioma;
    protected $tema;

    public function __construct($nome, $email, $senhaOuHash, $idioma = 'pt', $tema = 'claro', $jaHasheada = false) {
        $this->nome = $nome;
        $this->email = $email;
        $this->idioma = $idioma;
        $this->tema = $tema;
        $this->senhaHash = $jaHasheada ? $senhaOuHash : password_hash($senhaOuHash, PASSWORD_DEFAULT);
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getIdioma() {
        return $this->idioma;
    }

    public function getTema() {
        return $this->tema;
    }

    public function getSenhaHash() {
        return $this->senhaHash;
    }

    public function verificarSenha($senhaDigitada) {
        return password_verify($senhaDigitada, $this->senhaHash);
    }
}
