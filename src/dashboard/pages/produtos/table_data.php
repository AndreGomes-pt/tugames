<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if (!isset($_SESSION['user_id'])) {
    exit();
}

// Coleta Dados (filtros)
$categoriaId = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$stockOrder = isset($_GET['stock']) ? $_GET['stock'] : null;
$precoMin = isset($_GET['precoMin']) ? (float)$_GET['precoMin'] : null;
$precoMax = isset($_GET['precoMax']) ? (float)$_GET['precoMax'] : null;

// Monta a consulta SQL com base nos filtros
$sql = "SELECT 	id_produto,nome_produto,descricao,preco,stock,nome_categoria,id_categoria,todas_fotos FROM view_produtos WHERE 1 = 1"; 

// Filtro por categoria
if ($categoriaId !== null && $categoriaId !== '') {
    $sql .= " AND id_categoria = ?";
}

// Filtro por preço mínimo e máximo
if ($precoMin !== null && $precoMin > 0) {
    $sql .= " AND preco >= ?";
}
if ($precoMax !== null && $precoMax > 0) {
    $sql .= " AND preco <= ?";
}

// Filtro de ordenação de stock
if ($stockOrder) {
    if ($stockOrder == 'stock_asc') {
        $sql .= " ORDER BY stock ASC";
    } elseif ($stockOrder == 'stock_desc') {
        $sql .= " ORDER BY stock DESC";
    }
} else {
    $sql .= " ORDER BY id_produto DESC";
}

$stmt = $conn->prepare($sql);

// Vincula os parâmetros, se existirem
$params = [];
$types = '';

// Adiciona parâmetros conforme necessário
if ($categoriaId !== null && $categoriaId !== '') {
    $params[] = intval($categoriaId);
    $types .= 'i'; 
}
if ($precoMin !== null && $precoMin > 0) {
    $params[] = $precoMin;
    $types .= 'd'; 
}
if ($precoMax !== null && $precoMax > 0) {
    $params[] = $precoMax;
    $types .= 'd';
}

// Vincula os parâmetros
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$stmt->store_result(); // Armazena os resultados

// Vincula as variáveis que receberão os dados
$stmt->bind_result($id_produto, $nome_produto, $descricao, $preco, $stock, $nome_categoria, $id_categoria, $todas_fotos);

// Verifica se existem produtos
if ($stmt->num_rows > 0) {
    while ($stmt->fetch()) {
        $imagens = explode(',', $todas_fotos); 
        $dataImagens = implode(',', $imagens); 

        echo "<tr>";
        echo "<td>{$id_produto}</td>";
        echo "<td>{$nome_produto}</td>";
        echo "<td>{$descricao}</td>";
        echo "<td>€" . number_format($preco, 2) . "</td>";
        echo "<td class='" . ($stock == 0 ? 'stock-zero' : '') . "'>{$stock}</td>";
        echo "<td>{$nome_categoria}</td>";
        echo "<td>
                <button class='btn btn-primary btn-editar' 
                    data-id='{$id_produto}'
                    data-nome='" . htmlspecialchars($nome_produto, ENT_QUOTES, 'UTF-8') . "' 
                    data-descricao='" . htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') . "' 
                    data-preco='{$preco}' 
                    data-stock='{$stock}' 
                    data-categoria='{$nome_categoria}' 
                    data-categoria-id='{$id_categoria}' 
                    data-imagens='{$dataImagens}' 
                    data-toggle='modal' data-target='#editarProdutoModal'>
                    Editar
                </button>
                <button class='btn btn-danger btn-eliminar' onClick='action(\"eliminar_produto.php\", {id: \"{$id_produto}\", message: \"Tem certeza que deseja eliminar este produto?\", path: \"{$dataImagens}\"}, true,aplicarFiltros)'>
                  Eliminar
                </button>
              </td>";
        echo "</tr>";
    }
} else {
    // Caso nenhum produto seja encontrado
    echo "<tr>";
    echo "<td colspan='7' class='text-center'>Nenhum produto encontrado</td>";
    echo "</tr>";
}

$stmt->close();
$conn->close();
?>
