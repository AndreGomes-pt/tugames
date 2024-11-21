<?php
include "../db/db.php";
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilizador não autenticado']);
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id_dadosenvio, nome_cliente, data_nascimento, morada FROM dadosenvio WHERE id_utilizador = ?");

if ($stmt) {
    $stmt->bind_param("i",$userId);
    $stmt->execute(); 
    $stmt->bind_result($id_dadosenvio, $nome_cliente, $data_nascimento,$morada);

    // Array para armazenar os dados
    $shippingData = [];

    // Recuperar dados
    while ($stmt->fetch()) {
        $shippingData[] = [
        'id_dadosenvio' => $id_dadosenvio, 
        'nome_cliente' => $nome_cliente, 
        'data_nascimento' => $data_nascimento,
        'morada' => $morada
        ];
    }

    // Retornar os dados em formato JSON
    echo json_encode($shippingData);

    // Fecha a declaração
    $stmt->close();
} else {
    echo "Erro na preparação da declaração: " . $conn->error;
}

// Fecha a conexão
$conn->close();

?>