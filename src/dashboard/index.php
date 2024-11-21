<?php
   include "assets/db/db.php";
   session_start();
   
   // Redireciona se o utilizador tiver sessao iniciada
   if(!isset($_SESSION['user_id'])){
       header('Location: pages/login/login.php');
       exit();
   }else{
     //Caso tenha sessão inciada
     $username = $_SESSION['username'];
     $userId = $_SESSION['user_id'];
     $admin = ($_SESSION['is_admin'] == 0) ? false : true;
   }
   ?>
<!DOCTYPE html>
<html lang="pt">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Tugames</title>
      <link rel="icon" href="assets/img/tugameslogo-512x512.png">
      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="assets/css/adminlte.min.css">
   </head>
   <body class="hold-transition sidebar-mini" data-base-url="">
      <div class="wrapper">
         <!-- Navbar -->
         <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left NavBar -->
            <ul class="navbar-nav">
               <li class="nav-item">
                  <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
               </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
               <!-- Dropdown Carrinho de Compras na Navbar -->
               <li class="nav-item dropdown">
                  <a class="nav-link" href="#" id="navbarDropdownCart" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-shopping-cart"></i>
                  <span class="badge badge-pill badge-danger" id="cartItemCount"></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="navbarDropdownCart" style="width: 300px;">
                     <p class="text-center text-muted" id="emptyCartMessage">Seu carrinho está vazio.</p>
                     <ul id="cartDropdownItems" class="list-group">
                        <!-- Itens do Carrinho (serão preenchidos dinamicamente pelo JS) -->
                     </ul>
                     <a href="#" class="dropdown-item text-center mt-2" data-toggle="modal" data-target="#cartModal" id="seeFullCart">Ver Carrinho Completo</a>
                     <p class="text-right mt-3 font-weight-bold totalPrice">Total: 0.00 €</p>
                     <!-- Botão de Checkout -->
                     <div class="d-flex justify-content-end mt-3">
                        <a href="pages/checkout/checkOut.php" class="btn btn-primary" id="checkOutBtn">Finalizar Compra</a>
                     </div>
                  </div>
               </li>
               <li class="nav-item dropdown">
                  <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-user" style="color: #666;"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                     <a href="pages/perfil/perfil.php" class="dropdown-item">
                     <i class="fas fa-user mr-2"></i> Perfil
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="pages/logout/logout.php" class="dropdown-item">
                     <i class="fas fa-sign-out-alt mr-2"></i> Terminar Sessão
                     </a>
                  </div>
               </li>
            </ul>
         </nav>
         <!-- /.navbar -->
         <!-- Main Sidebar Container -->
         <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../" class="brand-link">
            <img src="assets/img/tugameslogo-512x512.png" alt="Tugames Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Tugames</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
               <!-- Sidebar Menu -->
               <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                     <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                     <li class="nav-item">
                        <a href="index.php" class="nav-link active">
                           <i class="fas fa-home nav-icon"></i>
                           <p>Início</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="pages/encomendas/encomendas.php" class="nav-link">
                           <i class="fas fas fa-boxes nav-icon"></i>
                           <p>Encomendas</p>
                        </a>
                     </li>
                     <?php if ($admin == true): ?>
                     <li class="nav-item">
                        <a href="pages/produtos/produtos.php" class="nav-link">
                           <i class="fas fa-cubes nav-icon"></i>
                           <p>Gerir Produtos</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="pages/utilizadores/utilizadores.php" class="nav-link">
                           <i class="fas fa-users nav-icon"></i>
                           <p>Gerir Utilizadores</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="pages/categorias/categorias.php" class="nav-link">
                           <i class="fas fa-th-list nav-icon"></i>
                           <p>Gerir Categorias</p>
                        </a>
                     </li>
                     <?php endif; ?>
                  </ul>
               </nav>
               <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
         </aside>
         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
               <div class="container-fluid">
                  <div class="row mb-2">
                     <div class="col-sm-6">
                        <h1 class="m-0">Olá <?php echo $username; ?></h1>
                     </div>
                     <!-- /.col -->
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item active"><a href="#">Início</a></li>
                        </ol>
                     </div>
                     <!-- /.col -->
                  </div>
                  <!-- /.row -->
               </div>
               <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <div class="content">
               <div class="container-fluid">
                  <div class="row">
                     <!-- Resumo encomendas recentes -->
                     <div class="col-lg-6">
                        <div class="card">
                           <div class="card-header border-transparent">
                              <h3 class="card-title">Encomendas Recentes</h3>
                              <div class="card-tools">
                                 <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                 <i class="fas fa-minus"></i>
                                 </button>
                              </div>
                           </div>
                           <!-- /.card-header -->
                           <div class="card-body p-0">
                              <div class="table-responsive">
                                 <table class="table m-0">
                                    <thead>
                                       <tr>
                                          <th>Numero Encomenda</th>
                                          <th>Produto/s</th>
                                          <th>Status</th>
                                          <th>Total</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
$query = "
    SELECT 
        e.id_encomenda, 
        e.status, 
        GROUP_CONCAT(p.nome SEPARATOR ', ') AS produtos, 
        e.preco_total
    FROM 
        encomendas e
    JOIN 
        produtos_encomendas pe ON e.id_encomenda = pe.id_encomenda
    JOIN 
        produtos p ON pe.id_produto = p.id_produto
";

// Adiciona a cláusula WHERE se o usuário não for admin
if (!$admin) {
    $query .= " WHERE e.id_utilizador = ?";
}

$query .= "
    GROUP BY 
        e.id_encomenda, e.status
    ORDER BY 
        e.data_encomenda DESC
    LIMIT 7
";

// Preparar a consulta e executar
if ($stmt = $conn->prepare($query)) {
    if (!$admin) {
        $stmt->bind_param("i", $userId);
    }

    $stmt->execute();

    // Definir variáveis para armazenar os resultados
    $stmt->bind_result($id_encomenda, $status, $produtos, $preco_total);
    
    $stmt->store_result(); // Para contar o número de linhas
    
    if ($stmt->num_rows > 0) {
        while ($stmt->fetch()) {
            // Define a classe da badge de acordo com o status
            switch ($status) {
                case 1: 
                    $badge_class = 'badge-info';
                    $status_label = 'Enviado';
                    break;
                case 3: 
                    $badge_class = 'badge-success';
                    $status_label = 'Entregue';
                    break;
                case 2: 
                    $badge_class = 'badge-danger';
                    $status_label = 'Cancelado';
                    break;
                case 0: 
                    $badge_class = 'badge-warning';
                    $status_label = 'Processamento';
                    break;
                default:
                    $badge_class = 'badge-secondary';
                    $status_label = 'Desconhecido';
            }

            // Exibe a linha da encomenda
            echo "<tr>";
            echo "<td><a href='pages/encomendas/encomendas.php?id={$id_encomenda}'>#{$id_encomenda}</a></td>";
            echo "<td>{$produtos}</td>"; 
            echo "<td><span class='badge $badge_class'>{$status_label}</span></td>";
            echo "<td>{$preco_total} €</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4' class='text-center'>Não há encomendas recentes.</td></tr>";
    }
} else {
    echo "<tr><td colspan='4' class='text-center'>Erro ao obter encomendas.</td></tr>";
}
?>

                                    </tbody>
                                 </table>
                              </div>
                              <!-- /.table-responsive -->
                           </div>
                           <!-- /.card-body -->
                           <div class="card-footer clearfix">
                              <a href="pages/encomendas/encomendas.php" class="btn btn-sm btn-secondary float-right">Ver todas as encomendas</a>
                           </div>
                           <!-- /.card-footer -->
                        </div>
                     </div>
                     <!-- Acesso Rápido às Funções Principais -->
                     <div class="col-lg-6">
                        <div class="card">
                           <div class="card-header">
                              <h3 class="card-title">Acesso Rápido</h3>
                              <div class="card-tools">
                                 <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                 <i class="fas fa-minus"></i>
                                 </button>
                              </div>
                           </div>
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-6 mt-3">
                                    <a href="pages/perfil/perfil.php" class="btn btn-block btn-success">
                                    <i class="fas fa-user-edit"></i> Editar Perfil
                                    </a>
                                 </div>
                                 <div class="col-6 mt-3">
                                    <a href="pages/perfil/perfil.php" class="btn btn-block btn-success">
                                    <i class="fas fa-address-card"></i> Gerir Dados de Envio
                                    </a>
                                 </div>
                                 <div class="col-6 mt-3">
                                    <a href="pages/encomendas/encomendas.php" class="btn btn-block btn-success">
                                    <i class="fas fa-box-open"></i> Encomendas
                                    </a>
                                 </div>
                                 <div class="col-6 mt-3">
                                    <a href="#" class="btn btn-block btn-success"  data-toggle="modal" data-target="#cartModal">
                                    <i class="fas fa-shopping-cart"></i> Ver Carrinho de Compras
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.row -->
               </div>
               <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->
         <!-- Modal Carrinho de Compras Completo -->
         <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="cartModalLabel">Carrinho de Compras</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <!-- Lista de itens do carrinho no modal -->
                     <ul id="cartModalItems" class="list-group">
                        <!-- Exemplo de item no carrinho (será preenchido dinamicamente pelo JS) -->
                     </ul>
                     <p class="text-right mt-3 font-weight-bold totalPrice">Total: 0.00 € </p>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                     <!-- Botão de Checkout -->
                     <a href="pages/checkout/checkOut.php" class="btn btn-primary">Finalizar Compra</a>
                  </div>
               </div>
            </div>
         </div>
         <!-- Main Footer -->
         <footer class="main-footer">
            <!-- Default to the left -->
            <strong>Copyright &copy; 2022-2024 <a href="#tugames.pt">Tugames</a>.</strong> All rights reserved.
         </footer>
      </div>
      <!-- ./wrapper -->
      <!-- REQUIRED SCRIPTS -->
      <!-- jQuery -->
      <script src="assets/plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap 4 -->
      <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="assets/js/adminlte.min.js"></script>
      <!-- Sweet Alert -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <!-- Cart -->
      <script src="assets/js/cart.js"></script>
   </body>
</html>