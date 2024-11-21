<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if (!isset($_SESSION['user_id'])) {
    exit();
}
// Função para sanitizar o nome do produto
function sanitizeFileName($name) {
    return preg_replace('/[^a-zA-Z0-9_\-]/', '', strtolower(str_replace(' ', '_', $name)));
}
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se as chaves existem antes de atribuí-las
    $idProduto = $_POST['id'] ?? null;
    $nomeProduto = $_POST['nome'] ?? null;
    $descricaoProduto = $_POST['descricao'] ?? null;
    $precoProduto = $_POST['preco'] ?? null;
    $stockProduto = $_POST['stock'] ?? null;
    $categoriaIdProduto = $_POST['categoria'] ?? null;

    // Verifica se todos os dados necessários foram enviados
    if (is_null($idProduto) || is_null($nomeProduto) || is_null($descricaoProduto) || is_null($precoProduto) || is_null($stockProduto) || is_null($categoriaIdProduto)) {
        echo json_encode(['status' => 'error', 'message' => 'Dados do produto incompletos.']);
        exit();
    }

    // Obter as imagens existentes do banco de dados
    $stmt = $conn->prepare("SELECT fotos FROM produtos WHERE id_produto = ?");
    $stmt->bind_param("i", $idProduto);
    $stmt->execute();
    $stmt->bind_result($stmtfotos);
    $existingImages = [];
    
    // Verificar se o produto existe e obter as imagens
    if ($stmt->fetch()) {
        // Usa o operador de coalescência nula para garantir que sempre é uma string
        $existingImages = explode(',', $stmtfotos ?? ''); 
    }
    $stmt->close();

    // Lidar com novas imagens
    $imagensSalvas = $existingImages; 
    if (isset($_FILES['imagens']) && $_FILES['imagens']['error'][0] !== UPLOAD_ERR_NO_FILE) {
        $novasImagens = $_FILES['imagens'];

        for ($i = 0; $i < count($novasImagens['name']); $i++) {
            if ($novasImagens['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $novasImagens['tmp_name'][$i];
                $ext = pathinfo($novasImagens['name'][$i], PATHINFO_EXTENSION);
                $sanitizedProductName = sanitizeFileName($nomeProduto);
                $fileName =$sanitizedProductName . '_' . uniqid() . '.' . $ext; 
                $filePath = "../../assets/img/produtos/" . $fileName;

                if (move_uploaded_file($tmpName, $filePath)) {
                    $imagensSalvas[] = $fileName; 
                } else {
                    echo json_encode(['status' => 'error', 'message' => "Erro ao mover o arquivo: $fileName"]);
                    exit();
                }
            }
        }
    }

    // Lidar com imagens removidas
    if (isset($_POST['imagens_removidas'])) {
        $imagensRemovidas = $_POST['imagens_removidas'];

        foreach ($imagensRemovidas as $imagem) {
            $filePath = "../../assets/img/produtos/" . $imagem;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $imagensSalvas = array_filter($imagensSalvas, function($img) use ($imagem) {
                return $img !== $imagem;
            });
        }
    }

    // Atualizar os dados do produto no banco de dados
    $imagensSalvas = array_filter($imagensSalvas);
    $imagensSalvasString = implode(',', $imagensSalvas); 
    $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, stock = ?, id_categoria = ?, fotos = ? WHERE id_produto = ?");
    $stmt->bind_param("ssdissi", $nomeProduto, $descricaoProduto, $precoProduto, $stockProduto, $categoriaIdProduto, $imagensSalvasString, $idProduto);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Produto atualizado com sucesso.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Erro ao atualizar produto: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    exit();
}
?>
