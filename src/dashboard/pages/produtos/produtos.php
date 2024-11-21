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
   
   //Funçaõ para obter categorias
function obterCategorias($conn) {
    $categorias = [];
    $stmt = $conn->prepare("SELECT id_categoria, nome_categoria FROM categorias");

    if ($stmt->execute()) {
        $stmt->store_result(); // Armazena os resultados

        // Vincula as variáveis que receberão os dados
        $stmt->bind_result($id_categoria, $nome_categoria);

        // Verifica se existem categorias
        while ($stmt->fetch()) {
            $categorias[] = [
                'id_categoria' => $id_categoria,
                'nome_categoria' => $nome_categoria
            ];
        }
    }

    $stmt->close();
    return $categorias; 
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
      <style>
        .stock-zero {
         color: red;
        }
      </style>
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
                        <a href="../produtos/produtos.php" class="nav-link active">
                           <i class="fas fa-cubes nav-icon"></i>
                           <p>Gerir Produtos</p>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a href="../utilizadores/utilizadores.php" class="nav-link">
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
                        <h1 class="m-0">Produtos</h1>
                     </div>
                     <!-- /.col -->
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="../../">Início</a></li>
                           <li class="breadcrumb-item active">Produtos</li>
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
                  <!-- Filtros Produtos -->
                  <div class="row mb-3">
                     <!-- Filtro Categoria -->
                     <div class="col-md-3">
                        <label for="categoria">Categoria</label>
                        <?php
                           $categorias = obterCategorias($conn); // Chama a função para obter as categorias
                           ?>
                        <select class="form-control" id="categoria" name="categoria">
                           <option value="">Todas as Categorias</option>
                           <?php foreach ($categorias as $categoria): ?>
                           <option value="<?= $categoria['id_categoria'] ?>"><?= $categoria['nome_categoria'] ?></option>
                           <?php endforeach; ?>
                        </select>
                     </div>
                     <!-- Filtro Stock -->
                     <div class="col-md-3">
                        <label for="stock">Stock</label>
                        <select class="form-control" id="stock" name="stock">
                           <option value="">Ordenar por Stock</option>
                           <option value="stock_asc">Crescente</option>
                           <option value="stock_desc">Decrescente</option>
                        </select>
                     </div>
                     <!-- Filtro Preço Mínimo -->
                     <div class="col-md-3">
                        <label for="precoMin">Preço Mínimo</label>
                        <input type="number" class="form-control" id="precoMin" placeholder="Preço Mínimo">
                     </div>
                     <!-- Filtro Preço Máximo -->
                     <div class="col-md-3">
                        <label for="precoMax">Preço Máximo</label>
                        <input type="number" class="form-control" id="precoMax" placeholder="Preço Máximo">
                     </div>
                     <div class="col-md-12 mt-3">
                        <button class="btn btn-primary btn-block">Aplicar Filtros</button>
                     </div>
                  </div>
                  <!-- Botão Adicionar Produto -->
                  <div class="row mb-3">
                     <div class="col-md-12">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#adicionarProdutoModal">Adicionar Produto</button>
                     </div>
                  </div>
                  <!-- Tabela de Produtos -->
                  <div class="row">
                     <div class="col-12">
                        <div class="table-responsive">
                           <table class="table table-bordered table-striped">
                              <thead>
                                 <tr>
                                    <th>#ID do Produto</th>
                                    <th>Titulo</th>
                                    <th>Descrição</th>
                                    <th>Preço</th>
                                    <th>Stock</th>
                                    <th>Categoria</th>
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
            <!-- Modal Editar Produto -->
            <div class="modal fade" id="editarProdutoModal" tabindex="-1" role="dialog" aria-labelledby="editarProdutoModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="editarProdutoModalLabel">Editar Produto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <form id="formProduto" enctype="multipart/form-data">
                           <input type="hidden" id="modalProdutoId" name="id" />
                           <!-- Campos de edição do produto -->
                           <div class="form-group">
                              <label for="modalNomeProduto">Nome do Produto</label>
                              <input type="text" class="form-control" id="modalNomeProduto" name="nome" required />
                           </div>
                           <div class="form-group">
                              <label for="modalCategoriaProduto">Categoria do Produto </label>
                              <select class="form-control" id="modalCategoriaProduto" name="categoria">
                                <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id_categoria'] ?>"><?= $categoria['nome_categoria'] ?></option>
                                <?php endforeach; ?>
                              </select>
                           </div>
                           <div class="form-group">
                              <label for="modalDescricaoProduto">Descrição</label>
                              <textarea class="form-control" id="modalDescricaoProduto" name="descricao" required></textarea>
                           </div>
                           <div class="form-group">
                              <label for="modalPrecoProduto">Preço</label>
                              <input type="number" class="form-control" id="modalPrecoProduto" name="preco" step="0.01" min="0" required />
                           </div>
                           <div class="form-group">
                              <label for="modalStockProduto">Stock</label>
                              <input type="number" class="form-control" id="modalStockProduto" name="stock" required />
                           </div>
                           <div class="form-group">
                              <label for="modalImagensProduto">Imagens do Produto</label>
                              <ul id="modalImagensProduto" class="list-unstyled">
                                 <!-- Imagens do produto serão carregadas aqui -->
                              </ul>
                              <input type="file" id="novaImagemProduto" name="imagens[]" accept="image/*" multiple hidden/>
                              <button type="submit" id="btnAdicionarImagens" class="btn btn-secondary mt-2">Adicionar Imagens</button>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="btnSalvarEdicoes">Salvar Alterações</button>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Modal Adicionar Produto -->
            <div class="modal fade" id="adicionarProdutoModal" tabindex="-1" role="dialog" aria-labelledby="adicionarProdutoModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="adicionarProdutoModalLabel">Adicionar Produto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <div class="modal-body">
                        <form id="formAdicionarProduto" enctype="multipart/form-data">
                           <input type="hidden" id="modalProdutoId" name="id" />
                           <!-- Campos de adição do produto -->
                           <div class="form-group">
                              <label for="modalNomeProduto">Nome do Produto</label>
                              <input type="text" class="form-control" id="modalNomeProduto" name="nome" required />
                           </div>
                           <div class="form-group">
                              <label for="modalCategoriaProduto">Categoria do Produto </label>
                              <select class="form-control" id="modalCategoriaProduto" name="categoria">
                                <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id_categoria'] ?>"><?= $categoria['nome_categoria'] ?></option>
                                <?php endforeach; ?>
                              </select>
                           </div>
                           <div class="form-group">
                              <label for="modalDescricaoProduto">Descrição</label>
                              <textarea class="form-control" id="modalDescricaoProduto" name="descricao" required></textarea>
                           </div>
                           <div class="form-group">
                              <label for="modalPrecoProduto">Preço</label>
                              <input type="number" class="form-control" id="modalPrecoProduto" name="preco" step="0.01" min="0" required />
                           </div>
                           <div class="form-group">
                              <label for="modalStockProduto">Stock</label>
                              <input type="number" class="form-control" id="modalStockProduto" name="stock" required />
                           </div>
                           <div class="form-group">
                              <label for="modalImagensProdutoAdicionar">Imagens do Produto</label>
                              <ul id="modalImagensProdutoAdicionar" class="list-unstyled">
                                 <!-- Imagens do produto serão carregadas aqui -->
                              </ul>
                              <input type="file" id="novaImagemProdutoAdicionar" name="imagens[]" accept="image/*" multiple hidden />
                              <button type="submit" id="btnAdicionarImagensAdicionar" class="btn btn-secondary mt-2">Adicionar Imagens</button>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="btnSalvarAdicionar">Salvar Produto</button>
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
             let params = {
                 categoria: $('#categoria').val(),
                 stock: $('#stock').val(),
                 precoMin: $('#precoMin').val(),
                 precoMax: $('#precoMax').val()
             };
             carregarTabela(params);
         }
             $('.btn-primary').on('click', function() {
             aplicarFiltros();
         });
      </script>
      <!-- Modal -->
      <script>
         $(document).ready(function() {
             // Variáveis para o modal de editar
             let imagensProduto = [], imagensRemovidas = [];
           
             // Variáveis para o modal de adicionar
             let imagensProdutoAdicionar = [], imagensRemovidasAdicionar = [];
         
             // Função para carregar dados do produto no modal de edição
             function carregarDadosModal(button) {
                 const { id, nome, descricao, preco, stock, categoria,categoriaId, imagens } = button.data();
         
                 $('#modalProdutoId').val(id);
                 $('#modalNomeProduto').val(nome);
                 $('#modalDescricaoProduto').val(descricao);
                 $('#modalPrecoProduto').val(preco);
                 $('#modalStockProduto').val(stock);

                 $('#modalCategoriaProduto option').each(function() {
                   if ($(this).val() == categoriaId) {
                     $(this).prop('selected', true);
                   }
                  });
         
                 $('#modalImagensProduto').empty();
                 imagensProduto = imagensRemovidas = [];
         
                 imagens.split(',').forEach((img, index) => {
                     const trimmedImage = img.trim();
                     if (trimmedImage) {
                         const imagemId = `imagem_${index}_${Date.now()}`;
                         $('#modalImagensProduto').append(`
                             <li class="d-flex align-items-center mb-2" data-imagem-id="${imagemId}" data-imagem="${trimmedImage}">
                                 <img src="../../assets/img/produtos/${trimmedImage}" alt="${nome}" style="width: 50px; height: 50px; margin-right: 10px;" />
                                 <button type="button" class="btn btn-danger btn-remover-imagem" data-imagem-id="${imagemId}">Remover</button>
                             </li>
                         `);
                         imagensProduto.push({ id: imagemId, path: trimmedImage });
                     }
                 });
                 $('#editarProdutoModal').modal('show');
             }
         
             // Adicionar nova imagem ao modal de edição
             $('#btnAdicionarImagens').on('click', function(e) {
                 e.preventDefault();
                 $('#novaImagemProduto').click();
             });
         
             $('#novaImagemProduto').on('change', function() {
                 const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                 [...this.files].forEach(ficheiro => {
                     if (tiposPermitidos.includes(ficheiro.type)) {
                         const reader = new FileReader();
                         reader.onload = e => {
                             const imagemId = `novaImagem_${Date.now()}`;
                             $('#modalImagensProduto').append(`
                                 <li class="d-flex align-items-center mb-2" data-imagem-id="${imagemId}" data-imagem="${ficheiro.name}">
                                     <img src="${e.target.result}" alt="${ficheiro.name}" style="width: 50px; height: 50px; margin-right: 10px;" />
                                     <button type="button" class="btn btn-danger btn-remover-imagem" data-imagem-id="${imagemId}">Remover</button>
                                 </li>
                             `);
                             imagensProduto.push({ id: imagemId, path: ficheiro.name, file: ficheiro });
                         };
                         reader.readAsDataURL(ficheiro);
                     } else {
                         Swal.fire({
                             icon: "error",
                             title: "Ficheiro inválido",
                             text: `${ficheiro.name} não é suportado.`
                         });
                     }
                 });
             });
         
             // Remover imagem do modal de edição
             $(document).on('click', '.btn-remover-imagem', function() {
                 const imagemId = $(this).data('imagem-id');
         
                 Swal.fire({
                     title: 'Tem a certeza?',
                     text: 'Deseja remover esta imagem?',
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#d33',
                     cancelButtonColor: '#3085d6',
                     confirmButtonText: 'Sim, remover!',
                     cancelButtonText: 'Cancelar'
                 }).then((result) => {
                     if (result.isConfirmed) {
                         // Remove visualmente do DOM
                         $(`[data-imagem-id="${imagemId}"]`).remove();
         
                         // Remove a imagem do array imagensProduto
                         const index = imagensProduto.findIndex(imagem => imagem.id === imagemId);
                         if (index !== -1) {
                             imagensRemovidas.push(imagensProduto[index].path);
                             imagensProduto.splice(index, 1);
                         }
         
                         // Atualiza o input de ficheiros
                         const input = $('#novaImagemProduto')[0];
                         const dt = new DataTransfer();
         
                         imagensProduto.forEach(img => {
                             if (img.file) {
                                 dt.items.add(img.file);
                             }
                         });
         
                         // Atualiza o input com os ficheiros restantes
                         input.files = dt.files;
                     }
                 });
             });
         
             $(document).on('click', '.btn-editar', function() {
                 carregarDadosModal($(this));
             });
         
             // Salvar edições
             $('#btnSalvarEdicoes').on('click', function() {
                 const formProduto = $('#formProduto')[0];
                 if (!formProduto.checkValidity()) {
                     Swal.fire({
                         icon: "warning",
                         title: "Campos Obrigatórios",
                         text: "Preencha todos os campos obrigatórios."
                     });
                     return;
                 }
         
                 const formData = new FormData(formProduto);
                 imagensProduto.forEach(imagem => formData.append('imagens[]', imagem.path));
                 imagensRemovidas.forEach(imagem => formData.append('imagens_removidas[]', imagem));
         
                 $.ajax({
                     url: 'atualizar_produto.php',
                     type: 'POST',
                     data: formData,
                     contentType: false,
                     processData: false,
                     success: function(response) {
                         $('#editarProdutoModal').modal('hide');
                         aplicarFiltros();
         
                         Swal.fire({
                             icon: 'success',
                             title: 'Atualizado!',
                             text: 'O produto foi atualizado com sucesso.',
                             confirmButtonText: 'OK'
                         });
                     },
                     error: function() {
                         Swal.fire({
                             icon: "error",
                             title: "Oops...",
                             text: "Erro ao atualizar o produto."
                         });
                         aplicarFiltros();
                     }
                 });
             });
         
             // --- Lógica para o modal de adicionar produto ---
         
         // Adicionar nova imagem ao modal de adicionar
         $('#btnAdicionarImagensAdicionar').on('click', function(e) {
             e.preventDefault();
             $('#novaImagemProdutoAdicionar').click(); 
         });
         
         $('#novaImagemProdutoAdicionar').on('change', function() {
             const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
             [...this.files].forEach(ficheiro => {
                 if (tiposPermitidos.includes(ficheiro.type)) {
                     const reader = new FileReader();
                     reader.onload = e => {
                         const imagemId = `novaImagemAdicionar_${Date.now()}`;
                         $('#modalImagensProdutoAdicionar').append(`
                             <li class="d-flex align-items-center mb-2" data-imagem-id="${imagemId}" data-imagem="${ficheiro.name}">
                                 <img src="${e.target.result}" alt="${ficheiro.name}" style="width: 50px; height: 50px; margin-right: 10px;" />
                                 <button type="button" class="btn btn-danger btn-remover-imagem-adicionar" data-imagem-id="${imagemId}">Remover</button>
                             </li>
                         `);
                         imagensProdutoAdicionar.push({ id: imagemId, path: ficheiro.name, file: ficheiro });
                     };
                     reader.readAsDataURL(ficheiro);
                 } else {
                     Swal.fire({
                         icon: "error",
                         title: "Ficheiro inválido",
                         text: `${ficheiro.name} não é suportado.`
                     });
                 }
             });
         });
         
             // Remover imagem do modal de adicionar
             $(document).on('click', '.btn-remover-imagem-adicionar', function() {
                 const imagemId = $(this).data('imagem-id');
         
                 Swal.fire({
                     title: 'Tem a certeza?',
                     text: 'Deseja remover esta imagem?',
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#d33',
                     cancelButtonColor: '#3085d6',
                     confirmButtonText: 'Sim, remover!',
                     cancelButtonText: 'Cancelar'
                 }).then((result) => {
                     if (result.isConfirmed) {
                         // Remove visualmente do DOM
                         $(`[data-imagem-id="${imagemId}"]`).remove();
         
                         // Remove a imagem do array imagensProduto
                         const index = imagensProduto.findIndex(imagem => imagem.id === imagemId);
                         if (index !== -1) {
                             imagensRemovidas.push(imagensProduto[index].path);
                             imagensProduto.splice(index, 1);
                         }
         
                         // Atualiza o input de ficheiros
                         const input = $('#novaImagemProdutoAdicionar')[0];
                         const dt = new DataTransfer();
         
                         imagensProduto.forEach(img => {
                             if (img.file) {
                                 dt.items.add(img.file);
                             }
                         });
         
                         // Atualiza o input com os ficheiros restantes
                         input.files = dt.files;
                     }
                 });
             });
         
         // Salvar novo produto
         $('#btnSalvarAdicionar').on('click', function() {
             const formAdicionarProduto = $('#formAdicionarProduto')[0];
             if (!formAdicionarProduto.checkValidity()) {
                 Swal.fire({
                     icon: "warning",
                     title: "Campos Obrigatórios",
                     text: "Preencha todos os campos obrigatórios."
                 });
                 return;
             }
         
             const formData = new FormData(formAdicionarProduto);
             imagensProdutoAdicionar.forEach(imagem => formData.append('imagens[]', imagem.path));
             imagensRemovidasAdicionar.forEach(imagem => formData.append('imagens_removidas[]', imagem));
         
             

             $.ajax({
                 url: 'inserir_produto.php',
                 type: 'POST',
                 data: formData,
                 contentType: false,
                 processData: false,
                 success: function(response) {
                     if (response.success) {
                         $('#adicionarProdutoModal').modal('hide');
                         aplicarFiltros();
                         //Limpar Formulario
                         formAdicionarProduto.reset();
                         $('#modalImagensProdutoAdicionar').empty();
                         // Limpa os arrays
                         imagensProdutoAdicionar = [];
                         imagensRemovidasAdicionar = [];
                         Swal.fire({
                             icon: 'success',
                             title: 'Adicionado!',
                             text: 'O produto foi adicionado com sucesso.',
                             confirmButtonText: 'OK'
                         });
                     } else {
                         Swal.fire({
                             icon: "error",
                             title: "Oops...",
                             text: response.message 
                         });
                     }
                 },
                 error: function(xhr, status, error) {
                     console.error("Erro na requisição:", xhr, status, error);
                     Swal.fire({
                         icon: "error",
                         title: "Oops...",
                         text: "Erro ao adicionar o produto."
                     });
                 }
             });
         });
         });
      </script>
   </body>
</html>