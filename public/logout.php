<?php
require_once 'classes/Sessao.php';
Sessao::iniciar();
Sessao::encerrar();
header('Location: index.php');
exit();
