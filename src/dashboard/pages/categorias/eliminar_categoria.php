<?php
include "../../assets/db/db.php";
session_start();
header('Content-Type: application/json');
// Verifica se o utilizador tem sessão iniciada
if(!isset($_SESSION['user_id'])){
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id_categoria = $_GET['id'];
        $capa = $_GET['capa'];
        $response = ['success' => false, 'message' => ''];

        // Caminho completo para a imagem de capa
        $caminho_capa = '../../assets/img/categorias/' . $capa; 
        
    
        // Verifica se a capa não está vazia
        if (!empty($capa)) {
            if (file_exists($caminho_capa)) {
                if (!unlink($caminho_capa)) {
                    $response['success'] = false;
                    $response['message'] = "Erro ao tentar remover a imagem de capa.";
                    echo json_encode($response);
                    http_response_code(500); 
                } 
            }
        }
    

        //Elminar o registro da bd e altera a categoria de todos os produtos associados á categoria para "Outro" (id 1)
        $sql = "UPDATE produtos SET id_categoria = 1 WHERE id_categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$id_categoria);
        if(!$stmt->execute()){
            $stmt-close();
            $conn->close();
            $response['success'] = false;
            $response['message'] = "Erro ao tentar atualizar os produtos.";
            echo json_encode($response);
            exit();
        }
        
        //Remove a categoria
        $sql = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt->prepare($sql);
        $stmt->bind_param("i",$id_categoria);
        if(!$stmt->execute()){
            $stmt->close();
            $conn->close();
            $response['success'] = false;
            $response['message'] = "Erro ao tentar eliminar a categoria.";
            echo json_encode($response);
            exit();
        }
        $stmt->close();
        $conn->close();
        $response['success'] = true;
        $response['message'] = "Categoria eliminada com sucesso.";
        echo json_encode($response);
        exit();
    }
}
?>