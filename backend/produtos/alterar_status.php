<?php

// 1. Importar a conexão com o banco de dados
require_once __DIR__ . '/../conexao.php';

// 2. Definir o formato da resposta
header('Content-Type: application/json; charset=utf-8');

// 3. Verificar se a requisição utiliza POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido. Utilize POST.'
    ]);

    exit;
}

// 4. Receber os dados enviados pelo formulário ou JavaScript
$id = $_POST['id'] ?? null;
$ativo = $_POST['ativo'] ?? null;

// 5. Validar o ID do produto
$id = filter_var($id, FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {

    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'ID do produto inválido.'
    ]);

    exit;
}

// 6. Validar o status
if(!in_array((string) $ativo, ['0', '1'], true)) {

    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Status inválido.'
    ]);

    exit;
}
// Converter os valores para inteiro
$id = (int) $id;
$ativo = (int) $ativo;

// 7. Preparar consulta para verifiar se o produto existe
$sql = "SELECT id FROM  produtos WHERE id =?";

$consulta = mysqli_prepare($conexao, $sql);

if (!$consulta) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao preparar consulta.'
    ]);

    exit;
}

// 8. Associar o ID à consulta
mysqli_stmt_bind_param($consulta, 'i', $id);

// 9. Executar a consulta
mysqli_stmt_execute($consulta);

// 10. Obter o resultado
$resultado = mysqli_stmt_get_result($consulta);

// 11. Verificar se encontrou o produto
if (mysqli_num_rows($resultado) === 0) {
    
mysqli_stmt_close($consulta);

    http_response_code(404);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Produto não encontrado.'
    ]);

    exit;
}

// 12. Fechar a consulta
mysqli_stmt_close($consulta);

// 13. Preparar a atualização do status
$sql = "UPDATE produtos SET ativo = ? WHERE id = ?";

$consulta = mysqli_prepare($conexao, $sql);

if(!$consulta) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao preparar atualização.'
    ]);

    exit;
}

// 14. Associar os valores à consulta
mysqli_stmt_bind_param($consulta, 'ii', $ativo, $id);

// 15. Executar a atualização
if (! mysqli_stmt_execute($consulta)) {

mysqli_stmt_close($consulta);

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao atualizar o status do produto.'
    ]);

    exit;
}

// 16. Fechar a consulta
mysqli_stmt_close($consulta);

// 17. Retornar confirmação
echo json_encode([
    'sucesso' => true,
    'mensagem' => 'Status do produto atualizado com sucesso!',
    'id' => $id,
    'ativo' => $ativo
]);
