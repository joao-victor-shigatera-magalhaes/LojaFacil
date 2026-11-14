<?php 

require_once __DIR__ . '/../conexao.php';

header('Content-Type: application/json; charset=utf-8');

//1. Verificar se a requisição utiliza o método POST

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido. Utilize POST.'
    ], JSON_UNESCAPED_UNICODE);

    exit;

}

//2. Receber os dados enviados pelo formulário

    $nome = isset($_POST['nome']) && is_string($_POST['nome']) ? trim($_POST['nome']): '';

    $descricao = isset($_POST['descricao']) && is_string($_POST['descricao']) ? trim($_POST['descricao']): '';

    $marca = isset($_POST['marca']) && is_string($_POST['marca']) ? trim($_POST['marca']): '';

    $unidade = isset($_POST['unidade']) && is_string($_POST['unidade']) ? trim($_POST['unidade']): '';

    $quantidade = isset($_POST['quantidade']) && is_string($_POST['quantidade']) ? trim($_POST['quantidade']): '';

    $precoVenda = isset($_POST['preco_venda']) && is_string($_POST['preco_venda']) ? trim($_POST['preco_venda']): '';

    $imagemUrl = isset($_POST['imagem_url']) && is_string($_POST['imagem_url']) ? trim($_POST['imagem_url']): '';

//3. Validar os campos obrigatórios

if ($nome === '' || $unidade === '' || $precoVenda === '') {

    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Preencha todos os campos obrigatorios.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

//4. Validar o tamanho dos campos

if (strlen($nome) > 180 || strlen($unidade) > 10) {

    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'O nome ou a unidade ultrapassou o limite permitido de caracteres.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

//5. Validar a quantidade

if (!is_numeric($quantidade) || (float) $quantidade < 0) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe uma quantidade válida. Ela não pode ser negativa.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 6. Validar o preço

if (!is_numeric($precoVenda) || (float) $precoVenda <= 0) {

    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Informe um preço válido e maior que zero.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

//7. Preparar os valores opcionais

$descricao = $descricao !== '' ? $descricao : null;
$marca = $marca !== '' ? $marca : null;
$imagemUrl = $imagemUrl !== '' ? $imagemUrl : null;

$quantidade = (float) $quantidade;
$precoVenda = (float) $precoVenda;

//8. Preparar a instrção SQL

$sql = "INSERT INTO produtos (
    nome,
    descricao,
    marca,
    unidade,
    quantidade,
    preco_venda,
    imagem_url) VALUES (?,?,?,?,?,?,?)";

$consulta = mysqli_prepare($conexao, $sql);

if (!$consulta) {

    error_log(mysqli_error($conexao));
    
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possivel preparar o cadastro do produto. Tente novamente.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 9. Vincular os parâmetros

mysqli_stmt_bind_param(
    $consulta,
    'ssssdds',
    $nome,
    $descricao,
    $marca,
    $unidade,
    $quantidade,
    $precoVenda,
    $imagemUrl
);

// 10. Executar o cadastro

if (!mysqli_stmt_execute($consulta)) {

error_log(mysqli_stmt_error($consulta));

mysqli_stmt_close($consulta);

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Não foi possivel cadastrar o produto. Tente novamente.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

// 11. Recuperar o ID do produto cadastrado

$idProduto = mysqli_insert_id($conexao);

// 12. Fechar a consulta

mysqli_stmt_close($consulta);

//13. Retornaro resultado

http_response_code(201);

echo json_encode([
    'sucesso' => true,
    'mensagem' => 'Produto cadastrado com sucesso!',
    'id' => $idProduto
], JSON_UNESCAPED_UNICODE);

exit;
?>