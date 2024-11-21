<?php
   include "../../assets/db/db.php";
   session_start();
   
   // Redireciona se o utilizador tiver sessao iniciada
   if(!isset($_SESSION['user_id'])){
       header('Location: ../login/login.php');
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
      <link rel="icon" href="../../assets/img/tugameslogo-512x512.png">
      <!-- Google Font: Source Sans Pro -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
   </head>
   <body class="hold-transition sidebar-mini" data-base-url="../../">
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
                        <a href="../checkout/checkOut.php" class="btn btn-primary" id="checkOutBtn">Finalizar Compra</a>
                     </div>
                  </div>
               </li>
               <li class="nav-item dropdown">
                  <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-user" style="color: #666;"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                     <a href="../perfil/perfil.php" class="dropdown-item">
                     <i class="fas fa-user mr-2"></i> Perfil
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="../logout/logout.php" class="dropdown-item">
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
            <a href="../../../" class="brand-link">
            <img src="../../assets/img/tugameslogo-512x512.png" alt="Tugames Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Tugames</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
               <!-- Sidebar Menu -->
               <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                     <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                     <li class="nav-item">
                        <a href="../../index.php" class="nav-link">
                           <i class="fas fa-home nav-icon"></i>
                           <p>
                              Início
                           </p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="../encomendas/encomendas.php" class="nav-link">
                           <i class="fas fas fa-boxes nav-icon"></i>
                           <p>
                              Encomendas
                           </p>
                        </a>
                     </li>
                     <?php if ($admin == true): ?>
                     <li class="nav-item">
                        <a href="../produtos/produtos.php" class="nav-link">
                           <i class="fas fa-cubes nav-icon"></i>
                           <p>Gerir Produtos</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="../utilizadores/utilizadores.php" class="nav-link active">
                           <i class="fas fa-users nav-icon"></i>
                           <p>Gerir Utilizadores</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="../categorias/categorias.php" class="nav-link">
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
                        <h1 class="m-0">Utilizadores</h1>
                     </div>
                     <!-- /.col -->
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="../../">Início</a></li>
                           <li class="breadcrumb-item active">Utilizadores</li>
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
                  <!-- Filtros Utilizadores -->
                  <div class="row mb-3">
                     <!-- Filtro Data Adesão -->
                     <div class="col-md-3">
                        <label for="data_adesao">Data Adesão</label>
                        <select class="form-control" id="data_adesao" name="data_adesao">
                           <option value="">Ordenar por Data de Adesão</option>
                           <option value="data_adesao_asc">Crescente</option>
                           <option value="data_adesao_desc">Decrescente</option>
                        </select>
                     </div>
                     <!-- Filtro Nº Encomendas -->
                     <div class="col-md-3">
                        <label for="nEncomomendas">Nº de Encomendas</label>
                        <select class="form-control" id="nEncomomendas" name="nEncomomendas">
                           <option value="">Ordenar por Numero de Encomendas</option>
                           <option value="n_encomomendas_asc">Crescente</option>
                           <option value="n_encomomendas_desc">Decrescente</option>
                        </select>
                     </div>
                     <!-- Filtro Admin -->
                     <div class="col-md-3 ">
                        <label for="admin">Administrador</label>
                        <select class="form-control" id="admin" name="admin">
                           <option value="">Mostrar apenas Administrador</option>
                           <option value="1">Sim</option>
                           <option value="0">Não</option>
                        </select>
                     </div>
                     <div class="col-md-12 mt-3">
                        <button class="btn btn-primary btn-block">Aplicar Filtros</button>
                     </div>
                  </div>
                  <!-- Tabela de Utilizadores -->
                  <div class="row">
                     <div class="col-12">
                        <div class="table-responsive">
                           <table class="table table-bordered table-striped">
                              <thead>
                                 <tr>
                                    <th>#ID do Utilizador</th>
                                    <th>Nome</th>
                                    <th>email</th>
                                    <th>Nº Encomendas</th>
                                    <th>Administrador</th>
                                    <th>Data de Adesão</th>
                                    <th>Ação</th>
                                 </tr>
                              </thead>
                              <tbody id="table-tbody">
                                 <!-- O conteúdo será carregado dinamicamente pelo JavaScript -->
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- MODAL EDITAR -->
            <div class="modal fade" id="editarUtilizador" tabindex="-1" role="dialog" aria-labelledby="editarUtilizadorLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="editarUtilizadorLabel">Gerir Permissões</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <div class="form-group">
                           <input type="hidden" id="userIdEdit" name="userIdEdit">
                        </div>
                        <div class="form-check">
                           <input type="checkbox" class="form-check-input" id="isAdmin" name="isAdmin">
                           <label class="form-check-label" for="isAdmin">É Administrador</label>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="savePermissions">Salvar Permissões</button>
                     </div>
                  </div>
               </div>
            </div>
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
                     <!-- item no carrinho (será preenchido dinamicamente pelo JS) -->
                  </ul>
                  <p class="text-right mt-3 font-weight-bold totalPrice">Total: 0.00 € </p>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                  <!-- Botão de Checkout -->
                  <a href="../checkout/checkOut.php" class="btn btn-primary">Finalizar Compra</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Main Footer -->
      <footer class="main-footer">
         <strong>Copyright &copy; 2022-2024 <a href="#tugames.pt">Tugames</a>.</strong> All rights reserved.
      </footer>
      </div>
      <!-- ./wrapper -->
      <!-- REQUIRED SCRIPTS -->
      <!-- jQuery -->
      <script src="../../assets/plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap 4 -->
      <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="../../assets/js/adminlte.min.js"></script>
      <!-- Sweet Alert -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <!-- Cart -->
      <script src="../../assets/js/cart.js"></script>
      <!-- Table -->
      <script src="../../assets/js/table.js"></script>
      <!-- Filtros Table -->
      <script>
         function aplicarFiltros() {
             //Alterar filtros users
             let params = {
                 data_adesao: $('#data_adesao').val(),
                 n_encomendas: $('#nEncomomendas').val(),
                 admin: $('#admin').val()
             };
             console.log(params);
             carregarTabela(params);
         }
             $('.btn-primary').on('click', function() {
             aplicarFiltros();
         });
      </script>
      <!-- MODAL -->
      <script>
         $(document).ready(function() {
         $(document).on('click', '.btn-editar', function() {
         const userId = $(this).data('id');
         const isAdmin = $(this).data('adm');
         
         // Preencher os campos do modal
         $('#userIdEdit').val(userId);
         $('#isAdmin').prop('checked', isAdmin == 1);
         });
         
         // Salvar permissões
         $('#savePermissions').on('click', function() {
         const userId = $('#userIdEdit').val();
         const isAdmin = $('#isAdmin').is(':checked') ? 1 : 0;
         
         action('atualizar_utilizador.php',{id:userId, message: null, adm: isAdmin},false, aplicarFiltros);
         $('#editarUtilizador').modal('hide');
         });
         });
      </script>
   </body>
</html>