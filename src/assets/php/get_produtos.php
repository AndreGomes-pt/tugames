<?php
include "../../dashboard/assets/db/db.php";
session_start();
header('Content-Type: application/json');

// Configuração de paginação
$limit = 8; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

// Query base
$sql = "SELECT * FROM view_produtos WHERE 1=1"; 

$condition = isset($_GET['condition']) ? $_GET['condition'] : '';
$categoria = isset($_GET['id_categoria']) ? (int)$_GET['id_categoria'] : 0; 
$query = isset($_GET['query']) ? $_GET['query'] : ''; 

// Adicionar filtros conforme necessário
switch ($condition) {
    case 'recent':
        $sql .= " ORDER BY data_adicionado DESC";
        break;

    case 'random':
        $sql .= " ORDER BY RAND()";
        break;

    default:
        break;
}

// Filtrar por categoria se fornecido
if ($categoria > 0) {
    $sql .= " AND id_categoria = ?"; 
}

// Filtrar por pesquisa se fornecido
if (!empty($query)) {
    $query = "%$query%"; 
    $sql .= " AND nome_produto LIKE ?"; 
}

// Adiciona a cláusula LIMIT e OFFSET
$sql .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

// Binding dos parâmetros
if ($categoria > 0 && !empty($query)) {
    $stmt->bind_param("ssii", $query, $categoria, $limit, $offset);
} elseif ($categoria > 0) {
    $stmt->bind_param("iii", $categoria, $limit, $offset);
} elseif (!empty($query)) {
    $stmt->bind_param("sii", $query, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

// Executa a consulta
$stmt->execute();

// Vincula as variáveis aos resultados
$stmt->bind_result($id_produto, $nome_produto, $descricao, $preco, $stock, $todas_fotos, $id_categoria, $nome_categoria);

// Array para armazenar os produtos
$produtos = [];

// Busca os resultados
while ($stmt->fetch()) {
    // Armazena os dados no array
    $produtos[] = [
        'id' => $id_produto,
        'nome' => $nome_produto,
        'descricao' => $descricao,
        'preco' => $preco,
        'stock' => $stock,
        'fotos' => $todas_fotos,
        'idcat' => $id_categoria,
        'nomecat' => $nome_categoria
    ];
}

// Contar o total de produtos para calcular o número total de páginas
$totalSql = "SELECT COUNT(*) as total FROM view_produtos WHERE 1=1";
$params = [];

if ($categoria > 0) {
    $totalSql .= " AND id_categoria = ?";
    $params[] = $categoria;
}

// Adiciona condição de busca se fornecida
if (!empty($query)) {
    $totalSql .= " AND nome_produto LIKE ?";
    $params[] = "%$query%"; 
}

// Preparar a consulta total
$totalStmt = $conn->prepare($totalSql);

// Bind dos parâmetros de contagem
if (!empty($params)) {
    $types = str_repeat('i', count($params)); 
    if (strpos($totalSql, 'LIKE') !== false) {
        $types = 's'; 
    }
    $totalStmt->bind_param($types, ...$params);
}

// Executa a consulta para o total de produtos
$totalStmt->execute();

// Vincula a variável ao resultado
$totalStmt->bind_result($totalProducts);

// Busca o resultado
$totalStmt->fetch();

// Retorna os produtos e informações de paginação
$response = [
    'produtos' => $produtos,
    'total' => $totalProducts,
    'page' => $page,
    'limit' => $limit,
    'total_pages' => ceil($totalProducts / $limit)
];

// Tentar converter o array para JSON
$json_response = json_encode($response);

// Verificar se ocorreu algum erro na codificação JSON
if (json_last_error() != JSON_ERROR_NONE) {
    echo 'Erro ao codificar JSON: ' . json_last_error_msg();
} else {
    // Se não houver erro, exibe o JSON
    header('Content-Type: application/json');
    echo $json_response;
}
?>
