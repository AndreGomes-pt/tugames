<?php
include "../../dashboard/assets/db/db.php";
session_start();
header('Content-Type: application/json');

//Query base
$sql = "SELECT * FROM categorias";
$stmt = $conn->prepare($sql);
// Executa a consulta
$stmt->execute();

// Vincula as variáveis aos resultados
$stmt->bind_result($id_categoria, $nome_categoria, $capa);

// Array para armazenar as categorias
$categorias = [];

// Busca os resultados
while ($stmt->fetch()) {
    // Armazena os dados no array
    $categorias[] = [
        'id' => $id_categoria,
        'nome' => $nome_categoria,
        'capa' => $capa
    ];
}


echo json_encode($categorias); 
?>