<?php

require_once __DIR__ . '/../conexao.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['id']) || !is_string($_GET['id']) || !ctype_digit($_GET['id']) ||(int) $_GET['id'] <1) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe umID de produto valido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$id = (int) $_GET['id'];

$sql = "
    SELECT
        p.id,
        p.nome,
        p.descricao,
        p.marca,
        p.unidade,
        p.preco_venda,
        p.imagem_url,
        p.ativo,
        p.categoria_id,
        c.nome AS categoria_nome
    FROM produtos p
    LEFT JOIN categorias c ON p.categoria_id = c.id
    WHERE p.id = ? LIMIT 1
    ";

$consulta = mysqli_prepare($conexao, $sql);

if (!$consulta) {
    error_log(mysqli_error($conexao));

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possível buscar o produto.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

mysqli_stmt_bind_param($consulta, 'i', $id);

if (!mysqli_stmt_execute($consulta)) {
    error_log(mysqli_stmt_error($consulta));

    mysqli_stmt_close($consulta);

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possível buscar o produto.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$resultado = mysqli_stmt_get_result($consulta);

if (!$resultado) {
    error_log(mysqli_stmt_error($consulta));

    mysqli_stmt_close($consulta);

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possível recuperar o produto.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$produto = mysqli_fetch_assoc($resultado);

if (!$produto) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Produto não encontrado.'
    ], JSON_UNESCAPED_UNICODE);

} else {

    echo json_encode([
        'sucesso' => true,
        'produto' => $produto
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

mysqli_free_result($resultado);
mysqli_stmt_close($consulta);