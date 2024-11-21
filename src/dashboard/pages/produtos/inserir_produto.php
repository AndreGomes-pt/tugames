<?php
include "../../assets/db/db.php";
session_start();
// Função para sanitizar o nome do produto
function sanitizeFileName($name) {
    return preg_replace('/[^a-zA-Z0-9_\-]/', '', strtolower(str_replace(' ', '_', $name)));
}
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $imagens = $_FILES['imagens'] ?? [];

    // Validação
    if (empty($nome) || empty($descricao) || empty($preco) || empty($stock) || empty($categoria)) {
        echo json_encode(["success" => false, "message" => "Todos os campos são obrigatórios."]);
        exit;
    }

    // Inserir produto 
    $sql = "INSERT INTO produtos (nome, descricao, preco, stock, id_categoria) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdis", $nome, $descricao, $preco, $stock, $categoria);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Erro ao adicionar produto: " . $stmt->error]);
        exit;
    }

    // Obter o ID do produto recém-adicionado
    $produto_id = $stmt->insert_id;

    // Caminho para o diretório de imagens
    $target_dir = "../../assets/img/produtos/";

    $imagensSalvas = [];
    // Armazenar os nomes dos ficheiros que já foram processados
    $imagensProcessadas = [];
    foreach ($imagens['tmp_name'] as $key => $tmp_name) {
        if ($imagens['error'][$key] === UPLOAD_ERR_OK) {
            // Gera um código aleatório
            $codigoAleatorio = bin2hex(random_bytes(5)); 
            $sanitizedProductName = sanitizeFileName($nome);
            $nomeImagem = $sanitizedProductName . "_" . $codigoAleatorio . ".png"; 
            $target_file = $target_dir . $nomeImagem;

            // Verificar se a imagem já foi processada ou existe para evitar duplicação
            if (in_array($nomeImagem, $imagensProcessadas) || file_exists($target_file)) {
                continue; 
            }

            // Mover o ficheiro para o diretório de destino
            if (move_uploaded_file($tmp_name, $target_file)) {
                $imagensSalvas[] = $nomeImagem; 
                $imagensProcessadas[] = $nomeImagem; 
            } else {
                echo json_encode(["success" => false, "message" => "Erro ao mover o arquivo: " . $nomeImagem]);
                exit;
            }
        }
    }

    // Atualizar o banco de dados com as imagens
    if (!empty($imagensSalvas)) {
        $imagensSalvas = array_filter($imagensSalvas);
        $imagensString = implode(',', $imagensSalvas);
        $sql = "UPDATE produtos SET fotos = ? WHERE id_produto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $imagensString, $produto_id);
        if (!$stmt->execute()) {
            echo json_encode(["success" => false, "message" => "Erro ao atualizar as imagens: " . $stmt->error]);
            exit;
        }
    }

    // Fechar conexões
    $stmt->close();
    $conn->close();

    // Retornar sucesso
    echo json_encode(["success" => true]); 
}
?>
