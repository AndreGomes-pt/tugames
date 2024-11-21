<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if(!isset($_SESSION['user_id'])){
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && isset($_GET['adm'])) {
        $id_utilizador = $_GET['id'];
        $adm = $_GET['adm'];

        $query = "UPDATE utilizadores SET adm = ? WHERE id_utilizador = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii",$adm,$id_utilizador);

        if(!$stmt->execute()){
            http_response_code(500);
            exit();
        }
        exit();
    }
}

?>