<?php

mysqli_report(MYSQLI_REPORT_OFF);

// 1. Localizar o arquivo de configuração
$arquivoEnv = dirname(__DIR__) . '/.env';

// 2. Verificar se o arquivo existe
if (!file_exists($arquivoEnv)) {
    http_response_code(500);
    exit('Arquivo de configuração não encontrado.');
}

// 3. Ler as configurações
$configuracoes = parse_ini_file($arquivoEnv);

if ($configuracoes === false) {
    http_response_code(500);
    exit('Erro ao ler as configurações.');
}

// 4. Recuperar os dados do banco
$host = $configuracoes['DB_HOST'];
$porta = (int) $configuracoes['DB_PORT'];
$banco = $configuracoes['DB_NAME'];
$usuario = $configuracoes['DB_USER'];
$senha = $configuracoes['DB_PASSWORD'];

// 5. Estabelecer conexão
$conexao = mysqli_connect(
    $host,
    $usuario,
    $senha,
    $banco,
    $porta
);

// 6. Verificar conexão
if (!$conexao) {

    error_log(mysqli_connect_error());

    http_response_code(500);

    exit('Não foi possível conectar ao banco de dados.');
}

// 7. Configurar caracteres
if (!mysqli_set_charset($conexao, 'utf8mb4')) {

    error_log(mysqli_error($conexao));

    http_response_code(500);

    exit('Não foi possível configurar o charset.');
}