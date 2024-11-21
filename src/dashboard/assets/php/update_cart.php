<?php
include "../db/db.php";
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilizador não autenticado']);
    exit();
}

if (isset($_POST['cartId']) && isset($_POST['newQuantity'])) {
    $userId = $_SESSION['user_id'];
    $cartId = intval($_POST['cartId']);  
    $newQuantity = intval($_POST['newQuantity']);  

    // Verifica se a nova quantidade é válida (maior ou igual a 0)
    if ($newQuantity >= 0) {
        $stmt = $conn->prepare("
            SELECT p.id_produto, p.stock 
            FROM carrinho c 
            JOIN produtos p ON c.id_produto = p.id_produto 
            WHERE c.id_carrinho = ? AND c.id_utilizador = ?
        ");
        $stmt->bind_param("ii", $cartId, $userId);
        $stmt->execute();
        $stmt->bind_result($idProduto, $stock);
        $stmt->fetch();
        $stmt->close();

        // Verifica se a nova quantidade é maior que o stock
        if ($newQuantity > $stock) {
            echo json_encode(['success' => false, 'message' => 'Quantidade solicitada maior do que disponível em stock. Estoque disponível: '.$stock.', Nova quantidade: '.$newQuantity]);
            exit();
        }

        // Atualizar o carrinho
        if ($newQuantity > 0) {
            $stmt = $conn->prepare("UPDATE carrinho SET quantidade = ? WHERE id_carrinho = ? AND id_utilizador = ?");
            $stmt->bind_param("iii", $newQuantity, $cartId, $userId);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Quantidade atualizada com sucesso! Id: '.$cartId.' Qnt: ' . $newQuantity]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a quantidade no banco de dados.']);
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("DELETE FROM carrinho WHERE id_carrinho = ? AND id_utilizador = ?");
            $stmt->bind_param("ii", $cartId, $userId);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Produto removido do carrinho com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao remover o produto do carrinho.']);
            }
            $stmt->close();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Quantidade inválida.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos enviados.']);
}

$conn->close();
?>
