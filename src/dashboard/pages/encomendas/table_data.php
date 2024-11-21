<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if (!isset($_SESSION['user_id'])) {
    exit();
}

// Coleta Dados
$userId = $_SESSION['user_id'];
$dataRealizacao = isset($_GET['dataRealizacao']) ? $_GET['dataRealizacao'] : null;
$dataEntrega = isset($_GET['dataEntrega']) ? $_GET['dataEntrega'] : null;
$precoMin = isset($_GET['precoMin']) ? (float)$_GET['precoMin'] : null;
$precoMax = isset($_GET['precoMax']) ? (float)$_GET['precoMax'] : null;
$admin = isset($_GET['admin']) ? (bool)$_GET['admin'] : false;

// SQL inicial
$sql = "SELECT 
            e.id_encomenda, 
            e.data_encomenda, 
            e.data_entrega, 
            e.status,
            e.preco_total,
            p.nome AS nome_produto,
            p.preco,
            p.fotos AS imagem,
            pe.quantidade
        FROM 
            encomendas e
        JOIN 
            produtos_encomendas pe ON e.id_encomenda = pe.id_encomenda
        JOIN 
            produtos p ON pe.id_produto = p.id_produto
        JOIN 
            dadosenvio d ON e.id_dadosenvio = d.id_dadosenvio";

// Adiciona a cláusula WHERE se não for admin
if (!$admin) {
    $sql .= " WHERE e.id_utilizador = ?";
}

// Ordena os resultados
$sql .= " ORDER BY e.data_encomenda DESC";

// Prepara a consulta
$stmt = $conn->prepare($sql);
if (!$admin) {
    $stmt->bind_param("i", $userId);
}

// Executa a consulta
$stmt->execute();

// Definir variáveis para armazenar os resultados
$stmt->bind_result($id_encomenda, $data_encomenda, $data_entrega, $status, $preco_total, $nome_produto, $preco_produto, $imagem, $quantidade);
$stmt->store_result(); // Para contar o número de linhas

$encomendas = [];
while ($stmt->fetch()) {
    $encomenda_id = $id_encomenda;

    if (!isset($encomendas[$encomenda_id])) {
        
        // Define a classe de badge com base no status da encomenda
        $statusText = '';
        $statusBadgeClass = '';

        switch ($status) {
            case 0:
                $statusText = 'Processamento';
                $statusBadgeClass = 'badge-warning'; // Amarelo para "Processamento"
                break;
            case 1:
                $statusText = 'Enviado';
                $statusBadgeClass = 'badge-primary'; // Verde para "Enviado"
                break;
            case 2:
                $statusText = 'Cancelado';
                $statusBadgeClass = 'badge-danger'; // Vermelho para "Cancelado"
                break;
            case 3:
                $statusText = 'Entregue';
                $statusBadgeClass = 'badge-success'; // Verde para "Entregue"
                break;
            default:
                $statusText = 'Desconhecido';
                $statusBadgeClass = 'badge-secondary'; // Cinza para "Desconhecido"
                break;
        }
        
        // Adiciona a encomenda ao array
        $encomendas[$encomenda_id] = [
            "id" => $id_encomenda,
            "dataRealizacao" => $data_encomenda,
            "dataEntrega" => $data_entrega ? $data_entrega : "Sem data de entrega prevista",
            "status" => $statusText,  // Texto legível para o status
            "statusBadgeClass" => $statusBadgeClass,  // Classe CSS do badge
            "precoTotal" => $preco_total,
            "produtos" => []
        ];
    }

    $encomendas[$encomenda_id]["produtos"][] = [
        "nome" => $nome_produto,
        "preco" => $preco_produto,
        "imagem" => explode(',', $imagem)[0],
        "quantidade" => $quantidade
    ];
}

$encomendas = array_values($encomendas);

// Filtra os dados com base nos parâmetros
$encomendasFiltradas = array_filter($encomendas, function($encomenda) use ($dataRealizacao, $dataEntrega, $precoMin, $precoMax) {
    $filtroData = (!$dataRealizacao || $encomenda['dataRealizacao'] == $dataRealizacao) &&
                  (!$dataEntrega || $encomenda['dataEntrega'] == $dataEntrega);

    $filtroPreco = (!$precoMin || $encomenda['precoTotal'] >= $precoMin) &&
                   (!$precoMax || $encomenda['precoTotal'] <= $precoMax);

    return $filtroData && $filtroPreco;
});

// Verifica se existem encomendas filtradas
if (empty($encomendasFiltradas)) {
    echo "<tr>";
    echo "<td colspan='7' class='text-center'>Nenhuma encomenda encontrada</td>";
    echo "</tr>";
} else {
    // Gera o HTML das linhas da tabela com base nas encomendas filtradas
    foreach ($encomendasFiltradas as $encomenda) {
        echo "<tr>";
        echo "<td>{$encomenda['id']}</td>";
        echo "<td>
                <button class='btn btn-link' type='button' data-toggle='collapse' data-target='#produtosEncomenda{$encomenda['id']}' aria-expanded='false' aria-controls='produtosEncomenda{$encomenda['id']}'>
                    Ver Produtos
                </button>
                <div class='collapse' id='produtosEncomenda{$encomenda['id']}'>
                    <ul>";
        
        // Lista os produtos da encomenda, incluindo imagem e quantidade
        foreach ($encomenda['produtos'] as $produto) {
            echo "<li>{$produto['nome']} - €".number_format($produto['preco'], 2)." - Quantidade: {$produto['quantidade']}</li>";
        }

        echo "      </ul>
                </div>
              </td>";
        echo "<td>{$encomenda['dataRealizacao']}</td>";
        echo "<td>{$encomenda['dataEntrega']}</td>";
        echo "<td><span class='badge {$encomenda['statusBadgeClass']}'>{$encomenda['status']}</span></td>";
        echo "<td>€".number_format($encomenda['precoTotal'], 2)."</td>";
        echo "<td>
                <button class='btn btn-info btn-sm ver-detalhes' 
                        data-id='{$encomenda['id']}' 
                        data-realizacao='{$encomenda['dataRealizacao']}' 
                        data-entrega='{$encomenda['dataEntrega']}'
                        data-produtos='".json_encode($encomenda['produtos'])."' 
                        data-preco-total='{$encomenda['precoTotal']}'>
                    Ver Detalhes
                </button>
                <span></span>";
        
        if ($encomenda['status'] === "Processamento") {
            echo "<button class='btn btn-danger btn-sm' onClick='action(\"cancelar_encomenda.php\", {id: \"{$encomenda['id']}\", message: \"Tem certeza que deseja cancelar esta encomenda?\"}, true,aplicarFiltros)'>Cancelar Encomenda</button>";
        }

        echo "</td>";
        echo "</tr>";
    }
}
?>
