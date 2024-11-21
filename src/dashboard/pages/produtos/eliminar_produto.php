<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if(!isset($_SESSION['user_id'])){
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id']) && isset($_GET['path'])) {
        $idProduto = $_GET['id'];
        $imagens = $_GET['path'];
    
        // Dividir os caminhos das imagens em um array
        $imagensArray = explode(',', $imagens);
    
        $caminhoImagens = "../../assets/img/produtos/";
    
        // Apagar cada imagem
        foreach ($imagensArray as $imagem) {
            $imagemPath = $caminhoImagens . trim($imagem);
            if (file_exists($imagemPath)) {
                unlink($imagemPath); 
            }
        }
    
        // Agora proceder à remoção do produto e dos seus dados na base de dados
        $query = "DELETE p, pe
                  FROM produtos p
                  LEFT JOIN produtos_encomendas pe ON p.id_produto = pe.id_produto 
                  WHERE p.id_produto = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idProduto);
    
        if (!$stmt->execute()) {
                echo json_encode([
        'success' => false,
                    'message' => 'Erro ao eliminar produto.'
    ]);
            $stmt->close();
            exit();
        }
        echo json_encode([
        'success' => true,
                    'message' => 'Produto eliminado com sucesso.'
    ]);
        // Fechar a conexão
        $stmt->close();
    }    
    exit();
} else {
    exit();
}



?>