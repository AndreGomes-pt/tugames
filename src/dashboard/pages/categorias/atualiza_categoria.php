<?php
include "../../assets/db/db.php"; 
session_start();

// Redireciona se o utilizador não tiver sessão iniciada
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
    exit();
}

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'], $_POST['name'])) {
        $id_categoria = $_POST['id']; 
        $nome_categoria = $_POST['name']; 
        $capa_nova = isset($_FILES['image']) ? $_FILES['image'] : null; 

        $capa_atual = null;

        // Verifica se uma nova capa foi enviada
        if ($capa_nova && $capa_nova['error'] == UPLOAD_ERR_OK) {
            $extensao = pathinfo($capa_nova['name'], PATHINFO_EXTENSION);
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extensao, $extensoes_permitidas)) {
                $capa_atual = uniqid() . '.' . $extensao; 
                move_uploaded_file($capa_nova['tmp_name'], '../../assets/img/categorias/' . $capa_atual);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Extensão de arquivo inválida.']);
                exit();
            }
        }

        if (!$capa_atual) {
            $query = "SELECT capa FROM categorias WHERE id_categoria = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $stmt->bind_result($capa_atual);
            $stmt->fetch();
            $stmt->close();
        } else {
            $capa_atual = $capa_atual;
        }

        // Atualiza a categoria na base de dados
        $query = "UPDATE categorias SET nome_categoria = ?, capa = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nome_categoria, $capa_atual, $id_categoria);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Categoria atualizada com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar a categoria.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
    }
}

$conn->close();
?>
