<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if (!isset($_SESSION['user_id'])) {
    exit();
}

// Coleta Dados
$id_c_utilizador = $_SESSION['user_id'];
$data_adesao = isset($_GET['data_adesao']) ? $_GET['data_adesao'] : null;
$n_encomendas = isset($_GET['n_encomendas']) ? $_GET['n_encomendas'] : null;
$admin = isset($_GET['admin']) ? $_GET['admin'] : null;

// Base da Query
$sql = "SELECT 
            u.id_utilizador,
            u.adm,
            u.email,
            u.nome,
            u.data_adesao, 
            COUNT(e.id_encomenda) AS total_encomendas 
        FROM 
            utilizadores u 
        LEFT JOIN 
            encomendas e ON u.id_utilizador = e.id_utilizador 
        WHERE 
            u.id_utilizador != ? ";

// Adiciona filtros
if ($admin != "") {
    $sql .= " AND u.adm = ?";
}

// Adiciona a contagem de encomendas e agrupamento
$sql .= " GROUP BY u.id_utilizador";

// Adiciona a ordenação
if ($data_adesao) {
    $sql .= " ORDER BY u.data_adesao " . ($data_adesao === 'data_adesao_asc' ? "ASC" : "DESC");
    if ($n_encomendas) {
        $sql .= ", total_encomendas " . ($n_encomendas === 'n_encomomendas_asc' ? "ASC" : "DESC");
    }
} else {
    if ($n_encomendas) {
        $sql .= " ORDER BY total_encomendas " . ($n_encomendas === 'n_encomomendas_asc' ? "ASC" : "DESC");
    }
}

// Prepara a declaração
$stmt = $conn->prepare($sql);

// Faz o binding dos parâmetros
if ($admin != "") {
    $stmt->bind_param("ii", $id_c_utilizador, $admin);
} else {
    $stmt->bind_param("i", $id_c_utilizador);
}

// Executa a declaração
$stmt->execute();

// Faz o binding dos resultados
$stmt->bind_result($id_utilizador, $adm, $email, $nome, $data_adesao_res, $total_encomendas);

// Verifica se existem utilizadores
if ($stmt->fetch()) {
    do {
        echo "<tr>";
        echo "<td>{$id_utilizador}</td>";
        echo "<td>{$nome}</td>";
        echo "<td>{$email}</td>";
        echo "<td>{$total_encomendas}</td>";
        echo "<td>{$adm}</td>";
        echo "<td>{$data_adesao_res}</td>";
        echo "<td>
                <button class='btn btn-primary btn-editar' 
                    data-id='{$id_utilizador}'
                    data-adm='{$adm}'
                    data-toggle='modal' data-target='#editarUtilizador'>
                    Editar
                </button>
                <button class='btn btn-danger btn-eliminar' onClick='action(\"eliminar_utilizador.php\", {id: \"{$id_utilizador}\", message: \"Tem certeza que deseja eliminar este utilizador?\"}, true,aplicarFiltros)'>
                  Eliminar
                </button>
              </td>";
        echo "</tr>";
    } while ($stmt->fetch());
} else {
    // Caso nenhum utilizador seja encontrado
    echo "<tr>";
    echo "<td colspan='7' class='text-center'>Nenhum utilizador encontrado</td>";
    echo "</tr>";
}

$stmt->close();
?>
