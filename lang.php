<?php
function traduzir($chave, $idioma = 'pt') {
    $mensagens = [
        'pt' => [
            'welcome' => 'Bem-vindo',
            'email' => 'Email',
            'theme' => 'Tema',
            'language' => 'Idioma',
            'admin_area' => 'Área do Administrador',
            'logged_as_user' => 'Você está logado como usuário comum.',
            'edit_profile' => 'Editar Perfil',
            'logout' => 'Sair',
            'delete_user' => 'Excluir',
            'user_list' => 'Lista de Usuários',
        ],
        'en' => [
            'welcome' => 'Welcome',
            'email' => 'Email',
            'theme' => 'Theme',
            'language' => 'Language',
            'admin_area' => 'Administrator Area',
            'logged_as_user' => 'You are logged in as a regular user.',
            'edit_profile' => 'Edit Profile',
            'logout' => 'Logout',
            'delete_user' => 'Delete',
            'user_list' => 'User List',
        ],
    ];

    return $mensagens[$idioma][$chave] ?? $chave;
}
