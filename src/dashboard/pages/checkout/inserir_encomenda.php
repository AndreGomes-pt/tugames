<?php
include "../../assets/db/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST)) {
        $userId = $_SESSION["user_id"];
        $shippingId = "";

        // Verifica se os dados de envio foram fornecidos
        if (empty($_POST["shippingId"])) {
            $nome = $_POST["fullName"] ?? null;
            $morada = $_POST["address"] ?? null;
            $dataNascimento = $_POST["birthdate"] ?? null;

            // Validação simples dos dados
            if (empty($nome) || empty($morada) || empty($dataNascimento)) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Todos os dados de envio são obrigatórios.",
                ]);
                exit();
            }

            // Insere dados de envio
            $sqlInsert =
                "INSERT INTO dadosenvio (nome_cliente, morada, data_nascimento, id_utilizador) VALUES (?, ?, ?, ?)";

            try {
                $stmt = $conn->prepare($sqlInsert);
                $stmt->bind_param(
                    "sssi",
                    $nome,
                    $morada,
                    $dataNascimento,
                    $userId
                );

                if ($stmt->execute()) {
                    $shippingId = $conn->insert_id;
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Falha ao inserir dados de envio.",
                    ]);
                    exit();
                }
            } catch (Exception $e) {
                echo json_encode([
                    "success" => false,
                    "message" => "Erro: " . $e->getMessage(),
                ]);
                exit();
            }
        } else {
            $shippingId = $_POST["shippingId"];
        }

        // Verifica se o `shippingId` existe na tabela `dadosenvio`
        $checkShippingId =
            "SELECT id_dadosenvio FROM dadosenvio WHERE id_dadosenvio = ?";
        $checkStmt = $conn->prepare($checkShippingId);
        $checkStmt->bind_param("i", $shippingId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows === 0) {
            echo json_encode([
                "status" => "error",
                "message" => "ID de dados de envio não encontrado.",
            ]);
            exit();
        }

        // Consulta para obter os produtos no carrinho
        $query =
            "SELECT c.id_produto, c.quantidade, p.preco FROM carrinho c JOIN produtos p ON c.id_produto = p.id_produto WHERE c.id_utilizador = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($id_produto, $quantidade, $preco);

        $produtos = [];
        $preco_total = 0;

        while ($stmt->fetch()) {
            $produtos[] = [
                "id_produto" => $id_produto,
                "quantidade" => $quantidade,
            ];
            $preco_total += $preco * $quantidade;
        }

        $stmt->close();

        // Gerar ID da encomenda com prefixo
        $prefixo = "ENC";
        $inserted_id = null;

        // Gerar um ID único para a encomenda
        do {
            $randomNumber = mt_rand(10000, 99999);
            $inserted_id = $prefixo . "-" . $randomNumber;

            $checkIdQuery =
                "SELECT id_encomenda FROM encomendas WHERE id_encomenda = ?";
            $checkIdStmt = $conn->prepare($checkIdQuery);
            $checkIdStmt->bind_param("s", $inserted_id);
            $checkIdStmt->execute();
            $checkIdStmt->store_result();
        } while ($checkIdStmt->num_rows > 0); // Continua até encontrar um ID único
        $checkIdStmt->close();
        // Inserir encomenda
        $query =
            "INSERT INTO encomendas (id_encomenda, id_dadosenvio, preco_total, id_utilizador) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssdi",
            $inserted_id,
            $shippingId,
            $preco_total,
            $userId
        );

        if (!$stmt->execute()) {
            $stmt->close();
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao criar encomenda: " . $stmt->error,
            ]);
            exit();
        }
        $stmt->close();
        // Inserir produtos na encomenda
        $produtosStmt = $conn->prepare(
            "INSERT INTO produtos_encomendas (id_produto, id_encomenda, quantidade) VALUES (?, ?, ?)"
        );
        $updateStockStmt = $conn->prepare(
            "UPDATE produtos SET stock = stock - ? WHERE id_produto = ?"
        );

        foreach ($produtos as $produto) {
            $id_produto = $produto["id_produto"];
            $quantidade = $produto["quantidade"];

            // Insere o produto na tabela de produtos_encomendas
            $produtosStmt->bind_param(
                "isi",
                $id_produto,
                $inserted_id,
                $quantidade
            );
            if (!$produtosStmt->execute()) {
                echo json_encode([
                    "status" => "error",
                    "message" =>
                        "Erro ao inserir produto na encomenda: " .
                        $produtosStmt->error,
                ]);
                exit();
            }

            // Atualiza o estoque do produto
            $updateStockStmt->bind_param("ii", $quantidade, $id_produto);
            if (!$updateStockStmt->execute()) {
                echo json_encode([
                    "status" => "error",
                    "message" =>
                        "Erro ao atualizar o estoque: " .
                        $updateStockStmt->error,
                ]);
                exit();
            }
        }

        $produtosStmt->close();
        $updateStockStmt->close();

        //Limpa o carrinho
        $stmt = $conn->prepare("DELETE FROM carrinho WHERE id_utilizador = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo json_encode([
            "status" => "success",
            "message" => "Encomenda criada com sucesso",
            "id_encomenda" => $inserted_id,
        ]);
        exit();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Dados não recebidos",
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método não permitido",
    ]);
}
