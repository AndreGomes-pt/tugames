<?php
include "../db/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilizador não autenticado']);
    exit();
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? null;
$response = ['success' => false];

switch ($action) {
    case 'add':
        $nome_cliente = $_POST['shippingName'];
        $morada = $_POST['shippingAddress'];
        $data_nascimento = $_POST['shippingBirthDate'];

        $stmt = $conn->prepare("INSERT INTO dadosenvio (nome_cliente, morada, data_nascimento, id_utilizador) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nome_cliente, $morada, $data_nascimento, $userId);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['newShippingData'] = [
                'id_dadosenvio' => $stmt->insert_id,
                'nome_cliente' => $nome_cliente,
                'morada' => $morada,
                'data_nascimento' => $data_nascimento
            ];
        }

        $stmt->close();
        break;

    case 'edit':
        $id = $_POST['id'];
        $nome_cliente = $_POST['shippingName'];
        $morada = $_POST['shippingAddress'];
        $data_nascimento = $_POST['shippingBirthDate'];

        $stmt = $conn->prepare("UPDATE dadosenvio SET nome_cliente = ?, morada = ?, data_nascimento = ? WHERE id_dadosenvio = ?");
        $stmt->bind_param("sssi", $nome_cliente, $morada, $data_nascimento, $id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['newShippingData'] = [
                'id_dadosenvio' => $id,
                'nome_cliente' => $nome_cliente,
                'morada' => $morada,
                'data_nascimento' => $data_nascimento
            ];
        }

        $stmt->close();
        break;

    case 'delete':
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM dadosenvio WHERE id_dadosenvio = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Registro deletado com sucesso.";
        } else {
            $response['success'] = false;
            $response['message'] = "Erro ao deletar o registro.";
        }

        $stmt->close();
        break;

    default:
        $response['success'] = false;
        $response['message'] = "Ação inválida.";
        break;
}


$conn->close(); 
header('Content-Type: application/json');
echo json_encode($response);
?>