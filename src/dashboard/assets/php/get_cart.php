<?php
include "../db/db.php";
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilizador não autenticado']);
    exit();
}

$userId = $_SESSION['user_id'];

// Obter os dados do carrinho para o utilizador
$query = "SELECT 
            c.id_carrinho, 
            c.quantidade, 
            p.preco,
            p.descricao,
            p.nome AS nome_produto, 
            SUBSTRING_INDEX(p.fotos, ',', 1) AS primeira_foto 
          FROM 
            carrinho c 
          JOIN 
            produtos p ON c.id_produto = p.id_produto 
          WHERE 
            c.id_utilizador = ?";
          
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($id_carrinho, $quantidade, $preco,$descricao,$nome,$foto);

$cartItems = [];
while ($stmt->fetch()) {
    $cartItems[] = [
        'id' => $id_carrinho, 
        'quantity' => $quantidade, 
        'name' => $nome, 
        'img' => $foto, 
        'price' => $preco,
        'description' => $descricao
    ];
}

echo json_encode($cartItems); 
?>
