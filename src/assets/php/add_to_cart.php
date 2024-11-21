<?php
include "../../dashboard/assets/db/db.php";
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilizador não autenticado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produto = $_POST['id'];
    $quantidadeSolicitada = $_POST['quantity'];
    $userId = $_SESSION['user_id'];

    // Verifica o estoque do produto
    $sql = "SELECT stock FROM produtos WHERE id_produto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

    if ($stock !== null) {
        // Verifica se o produto já está no carrinho do utilizador
        $sql = "SELECT quantidade FROM carrinho WHERE id_utilizador = ? AND id_produto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $id_produto);
        $stmt->execute();
        $stmt->bind_result($quantidadeAtual);
        $stmt->fetch();
        $stmt->close();

        if ($quantidadeAtual !== null) {
            // Produto já está no carrinho, então verifica a soma das quantidades
            $novaQuantidade = $quantidadeAtual + $quantidadeSolicitada;

            if ($novaQuantidade > $stock) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Quantidade solicitada maior do que disponível em stock. Estoque disponível: ' . $stock . ' .'
                ]);
                exit();
            }

            // Atualiza a quantidade no carrinho
            $sql = "UPDATE carrinho SET quantidade = ? WHERE id_utilizador = ? AND id_produto = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $novaQuantidade, $userId, $id_produto);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Quantidade atualizada no carrinho.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao atualizar a quantidade no carrinho.'
                ]);
            }
        } else {
            // Produto não está no carrinho, verifica se a quantidade solicitada é menor ou igual ao stock
            if ($quantidadeSolicitada > $stock) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Quantidade solicitada maior do que disponível em stock. Estoque disponível: ' . $stock . ' .'
                ]);
                exit();
            }

            // Insere o novo produto no carrinho
            $sql = "INSERT INTO carrinho (id_utilizador, id_produto, quantidade) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $userId, $id_produto, $quantidadeSolicitada);

            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Produto adicionado ao carrinho.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao adicionar produto ao carrinho.'
                ]);
            }
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao adicionar ao carrinho, produto não encontrado.'
        ]);
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>
