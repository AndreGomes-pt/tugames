<?php
include "../../assets/db/db.php";

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name']) && isset($_FILES['image'])) {
        $nome_categoria = $_POST['name'];
        $capa_nova = $_FILES['image'];
        
        // Verifica se o ficheiro foi enviado
        if ($capa_nova['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../assets/img/categorias/'; 
            $uploadFile = $uploadDir . basename($capa_nova['name']);
            $capa = basename($capa_nova['name']);
            
            // Move o ficheiro para o diretório
            if (move_uploaded_file($capa_nova['tmp_name'], $uploadFile)) {
                $stmt = $conn->prepare("INSERT INTO categorias (nome_categoria, capa) VALUES (?, ?)");
                $stmt->bind_param("ss",$nome_categoria,$capa);
                $stmt->execute();

                echo json_encode(['success' => true, 'message' => 'Categoria criada com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao mover o arquivo para o diretório.']);
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO categorias (nome_categoria, capa) VALUES (?, null)");
            $stmt->bind_param("s",$nome_categoria);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Categoria criada com sucesso!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
}
?>
