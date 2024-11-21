<?php
include "../../assets/db/db.php";
session_start();
header('Content-Type: application/json');
// Verifica se o utilizador tem sessão iniciada
if(!isset($_SESSION['user_id'])){
    exit();
}


if(isset($_GET['id'])){
    
    $idEncomenda = $_GET['id'];
    $stmt = $conn->prepare(" UPDATE encomendas SET status = 2, data_entrega = null WHERE id_encomenda = ?");
    $stmt->bind_param("s",$idEncomenda);
    $stmt->execute();

    $stmt->close();
    echo json_encode([
        'success' => true,
                    'message' => 'Encomenda cancelada com sucesso.'
    ]);
    exit();
}else{
    exit();
}
?>