<?php

require_once __DIR__ .'/../conexao.php';

header('Content-Type: application/json; charset=utf-8');

$sql = "
    SELECT
        p.id,
        p.nome,
        p.descricao,
        p.marca,
        p.unidade,
        p.quantidade,
        p.preco_venda,
        p.imagem_url,
        p.ativo,
        p.categoria_id,
        c.nome AS categoria_nome
    FROM produtos p
    LEFT JOIN categorias c ON p.categoria_id = c.id ORDER BY p.nome ASC";

    $consulta = mysqli_query($conexao, $sql);

    if (!$consulta) {
        error_log(mysqli_error($conexao));

        http_response_code(500);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Não foi possivel listar os produtos.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $produtos = [];

    while ($produto = mysqli_fetch_assoc($consulta)) {
        $produtos[] = $produto;
    }

    $total = count($produtos);

    echo json_encode([
        'sucesso' => true,
        'total' => $total,
        'produtos' => $produtos
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    mysqli_free_result($consulta);