<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if(!isset($_SESSION['user_id'])){
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $idUtilizador = $_GET['id'];
        
        $query = "DELETE FROM utilizadores WHERE id_utilizador = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idUtilizador);
    
        if (!$stmt->execute()) {
            $stmt->close();
            echo json_encode([
        'success' => false,
                    'message' => 'Erro ao eliminar utilizador.'
    ]);
            exit();
        }
        echo json_encode([
        'success' => true,
                    'message' => 'Utilizador eliminado com sucesso.'
    ]);
        // Fechar a conexão
        $stmt->close();
    }    
    exit();
} else {
    exit();
}

?>