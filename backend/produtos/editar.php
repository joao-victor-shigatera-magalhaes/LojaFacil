<?php

// 1.Incluir a conexão com o banco de dados

require_once __DIR__ . '/../conexao.php';

// 2. Definir o retorno como JSON

header('ContentType: application/json; charset=utf-8');

//3. Verificar se a requiaição utiliza POST

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido. Utilize POST.'
    ], JSON_UNESCAPED_UNICODE);

    exit;

}

// 4. Receber o ID do produto

$id = isset($_POST['id']) ? $_POST['id']: '';

// 5. Validar o ID recebido
if (!filter_var($id, FILTER_VALIDATE_INT) || (int) $id <= 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe um ID de produto válido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

$id = (int) $id;

//6. Consultar se o produto existe

$sql = "SELECT id FROM produtos WHERE id = ?";

$consulta = mysqli_prepare($conexao, $sql);

if (!$consulta) {
    error_log(mysqli_error($conexao));

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possível consultar o produto.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 7. Vincular o ID à consulta

mysqli_stmt_bind_param($consulta, 'i', $id);

// 8. Executar a consulta

mysqli_stmt_execute($consulta);

//9. Recuperar o resultado

$resultado = mysqli_stmt_get_result($consulta);

if (mysqli_num_rows($resultado) === 0) {

mysqli_stmt_close($consulta);

    http_response_code(404);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Produto não encontrado.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 10. Fechar a consulta

mysqli_stmt_close($consulta);

// 11. Receber os novos dados do produto

$nome= isset($_POST['nome']) && is_string($_POST['nome']) ? trim($_POST['nome']) : '';
$descricao = isset($_POST['descricao']) && is_string($_POST['descricao']) ? trim($_POST['descricao']) : '';
$marca = isset($_POST['marca']) && is_string($_POST['marca']) ? trim($_POST['marca']) : '';
$unidade = isset($_POST['unidade']) && is_string($_POST['unidade']) ? trim($_POST['unidade']) : '';
$quantidade = isset($_POST['quantidade']) && is_string($_POST['quantidade']) ? trim($_POST['quantidade']) : '';
$precoVenda = isset($_POST['preco_venda']) && is_string($_POST['preco_venda']) ? trim($_POST['preco_venda']) : '';
$imagemUrl = isset($_POST['imagem_url']) && is_string($_POST['imagem_url']) ? trim($_POST['imagem_url']) : '';

// 12. Validar os campos obrigatórios

if ($nome === '' || $unidade === '' || $quantidade === '' || $precoVenda === '') {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha todos os campos obrigatórios.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 13. Validar o tamanho dos camppos

if (
    strlen($nome) > 180 || strlen($marca) > 100 || strlen($unidade) > 10 || strlen($imagemUrl) > 500
) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Um ou mais campos ultrapassaram o limite permitido.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 14. Validar a quantidade

if (!is_numeric($quantidade) || (float) $quantidade < 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe uma qunatidade válida. Ela não pode ser negativa.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 15. Validar o preço de venda

if (!is_numeric($precoVenda) || (float) $precoVenda <= 0) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe um preço válido e maior que zero.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 16. Preparar os valores opcionais

$descricao = $descricao !=='' ? $descricao : null;
$marca = $marca !=='' ? $marca : null;
$imagemUrl = $imagemUrl !=='' ? $imagemUrl : null;

//Converter os valores numéricos

$quantidade = (float) $quantidade;
$precoVenda = (float) $precoVenda;

// 17. Preparar a instrução SQL para atualizar o produto

$sql = "UPDATE produtos SET
    nome = ?,
    descricao = ?,
    marca = ?,
    unidade = ?,
    quantidade = ?,
    preco_venda = ?,
    imagem_url =?
WHERE id = ?";

$consulta = mysqli_prepare($conexao, $sql);

if (!$consulta) {

error_log(mysqli_error($conexao));

    http_response_code(500);

    echo json_encode([
        'suceso' => false,
        'mensagem' => 'Não foi possível preparar a atualização do produto.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 18. Validar os parãmetros

mysqli_stmt_bind_param(
    $consulta,
    'ssssddsi',
    $nome,
    $descricao,
    $marca,
    $unidade,
    $quantidade,
    $precoVenda,
    $imagemUrl,
    $id
);

// 19. Executar a atualização

if (!mysqli_stmt_execute($consulta)) {
error_log(mysqli_stmt_error($consulta));

mysqli_stmt_close($consulta);

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possível atualizar o produto.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 20. Fechar a consulta

mysqli_stmt_close($consulta);

// 21. Retornar o resultado 
http_response_code(200);

echo json_encode([
   'sucesso' => true,
   'mensagem' => 'Produto atualizado com sucesso!',
   'id' => $id
], JSON_UNESCAPED_UNICODE);

exit;