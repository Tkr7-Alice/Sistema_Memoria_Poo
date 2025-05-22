<?php
session_start();

// Atualiza tema se enviado pelo formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tema'])) {
    $_SESSION['tema'] = $_POST['tema'];
}

// Define o tema atual (padrão = 'padrao')
$temaSelecionado = $_SESSION['tema'] ?? 'padrao';
?>
