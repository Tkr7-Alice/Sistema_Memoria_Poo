<?php
function traduzir(string $chave, string $idioma): string {
    // Dicionário básico de traduções
    $traducoes = [
        'pt' => [
            'welcome'         => 'Bem-vindo',
            'email'           => 'Email',
            'theme'           => 'Tema',
            'language'        => 'Idioma',
            'edit_profile'    => 'Editar Perfil',
            'logout'          => 'Sair',
            'admin_area'      => 'Área do Administrador',
            'user_list'       => 'Lista de Usuários',
            'delete_user'     => 'Excluir Usuário',
            'confirm_delete_user' => 'Tem certeza que deseja excluir este usuário?',
            'logged_as_user'  => 'Você está logado como usuário comum.',
        ],
        'en' => [
            'welcome'         => 'Welcome',
            'email'           => 'Email',
            'theme'           => 'Theme',
            'language'        => 'Language',
            'edit_profile'    => 'Edit Profile',
            'logout'          => 'Logout',
            'admin_area'      => 'Admin Area',
            'user_list'       => 'User List',
            'delete_user'     => 'Delete User',
            'confirm_delete_user' => 'Are you sure you want to delete this user?',
            'logged_as_user'  => 'You are logged in as a regular user.',
        ],
    ];

    // Retorna a tradução se existir, senão retorna a chave
    return $traducoes[$idioma][$chave] ?? $chave;
}
