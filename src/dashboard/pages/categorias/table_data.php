<?php
include "../../assets/db/db.php";
session_start();

// Verifica se o utilizador tem sessão iniciada
if (!isset($_SESSION['user_id'])) {
    exit();
}

$sql = "SELECT * FROM categorias WHERE id_categoria != 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($id_categoria,$nome_categoria,$capa);

while ($stmt->fetch()) {
    echo "<tr>
            <td>{$id_categoria}</td>
            <td>{$nome_categoria}</td>";
            // Verifica se a capa está vazia ou nula
      if (empty($capa)) {
         echo "<td>Sem capa</td>";
      } else {
         echo "<td><img src='../../assets/img/categorias/{$capa}' alt='Capa' style='width: 50px; height: 50px;'></td>";
      }
            echo "<td>
                <button class='btn btn-info btn-sm editar' 
                        data-id='{$id_categoria}' 
                        data-nome='{$nome_categoria}' 
                        data-capa='{$capa}'>
                    Editar
                </button>
                <span></span>
                <button class='btn btn-danger btn-sm' onClick='action(\"eliminar_categoria.php\", {id: \"{$id_categoria}\", message: \"Tem certeza que deseja eliminar esta categoria? Todos os produtos com esta categoria terão a sua categoria alterada para Outros. \",capa: \"{$capa}\" }, true,carregarTabela)'>Eliminar Categoria</button>
          </tr>";
}
$stmt->close();
$conn->close();
?>