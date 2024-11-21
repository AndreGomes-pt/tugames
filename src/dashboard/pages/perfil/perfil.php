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
   
     //Obtem os dados do utilizador
     $sql = "SELECT email FROM utilizadores WHERE id_utilizador = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("i",$userId);
     $stmt->execute();
     $stmt->bind_result($email);
     $stmt->fetch();
     $stmt->close();
   
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Recebe os dados do formulário
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $passwordC = $_POST['passwordC'];
   
      // Verifica se o nome de utilizador ou email já está em uso
      $sql = "SELECT id_utilizador FROM utilizadores WHERE (nome = ? OR email = ?) AND id_utilizador != ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssi", $username, $email, $userId);
      $stmt->execute();
      $stmt->store_result();
   
      if ($stmt->num_rows > 0) {
          // Nome de utilizador ou email já em uso
          $_SESSION['error'] = "Nome de utilizador ou email já está em uso.";
          header('Location: register.php');
          exit();
      }
   
      // Atualização condicional da palavra-passe
      if (!empty($password) && !empty($passwordC)) {
          if ($password !== $passwordC) {
              $_SESSION['error'] = "As palavras-passe não coincidem.";
              header('Location: perfil.php');
              exit();
          }
   
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
          $update_sql = "UPDATE utilizadores SET nome = ?, email = ?, palavra_passe = ? WHERE id_utilizador = ?";
          $update_stmt = $conn->prepare($update_sql);
          $update_stmt->bind_param("sssi", $username, $email, $hashed_password, $userId);
      } else {
          $update_sql = "UPDATE utilizadores SET nome = ?, email = ? WHERE id_utilizador = ?";
          $update_stmt = $conn->prepare($update_sql);
          $update_stmt->bind_param("ssi", $username, $email, $userId);
      }
   
      // Executar a atualização
      if ($update_stmt->execute()) {
          $_SESSION['success'] = "success";
          $_SESSION['username'] = $username;
          header('Location: perfil.php');
          exit();
      } else {
          $_SESSION['error'] = "Erro ao atualizar os dados do utilizador. Por favor, tente novamente.";
          header('Location: register.php');
          exit();
      }
   
      $stmt->close();
      $update_stmt->close();
   }
   
   $conn->close();  
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
         .shipping-card {
         border: 1px solid #ccc;
         border-radius: 5px;
         padding: 10px;
         margin: 10px;
         flex: 1 0 30%; /* Faz com que os mini cards ocupem 30% do espaço */
         cursor: pointer; /* Mostra que é clicável */
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
               <li class="nav-item">
                  <a class="nav-link" href="../logout/logout.php" role="button">
                  <i class="fas fa-sign-out-alt mr-2"></i>
                  </a>
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
                        <h1 class="m-0">Perfil de <?php echo $username; ?></h1>
                     </div>
                     <!-- /.col -->
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="../../">Início</a></li>
                           <li class="breadcrumb-item active">Perfil</li>
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
                     <!-- CONTEUDO PERFIL -->
                     <div class="col-md-12">
                        <!-- Formulário de Informações do Utilizador -->
                        <div class="card">
                           <div class="card-header">
                              <h3 class="card-title">Informações Pessoais</h3>
                           </div>
                           <div class="card-body">
                              <form action="perfil.php" method="POST" class="mt-4">
                                 <div class="mb-3">
                                    <label for="username" class="form-label">Nome de Utilizador</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Digite o seu nome de utilizador" value="<?php echo $username;?>" required>
                                 </div>
                                 <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite o seu email" value ="<?php echo $email;?>"required>
                                 </div>
                                 <div class="mb-3 position-relative">
                                    <label for="password" class="form-label">Nova Palavra Passe</label>
                                    <div class="input-group">
                                       <input type="password" class="form-control" id="password" name="password" placeholder="Digite a sua palavra passe">
                                       <span class="input-group-text togglePassword"  style="cursor: pointer;">
                                       <i class="fas fa-eye"></i>
                                       </span>
                                    </div>
                                    <label for="passwordC" class="form-label mt-3">Confirmar Nova Palavra Passe</label>
                                    <div class="input-group">
                                       <input type="password" class="form-control" id="passwordC" name="passwordC" placeholder="Digite novamente a sua palavra passe">
                                       <span class="input-group-text togglePassword" style="cursor: pointer;">
                                       <i class="fas fa-eye"></i>
                                       </span>
                                    </div>
                                 </div>
                                 <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Atualizar</button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <!-- Formulário de Dados de Envio -->
                        <div class="card">
                           <div class="card-header">
                              <h3 class="card-title">Dados de Envio</h3>
                              <button id="addShippingDataBtn" class="btn btn-success float-right " onclick="openShippingModal()">+</button>
                           </div>
                           <div class="card-body">
                              <div id="shippingDataList" class="row">
                                 <!-- Mini cards para dados de envio existentes serão adicionados aqui -->
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Modal para adicionar ou editar dados de envio -->
                     <div class="modal fade" id="shippingModal" tabindex="-1" role="dialog" aria-labelledby="shippingModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="shippingModalLabel">Adicionar Dados de Envio</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                                 <form id="shippingForm">
                                    <input type="hidden" id="shippingId" name="id">
                                    <div class="form-group">
                                       <label for="shippingName">Nome Completo</label>
                                       <input type="text" class="form-control" id="shippingName" name="shippingName" placeholder="Nome Completo" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="shippingAddress">Morada</label>
                                       <input type="text" class="form-control" id="shippingAddress" name="shippingAddress" placeholder="Morada" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="shippingBirthDate">Data Nascimento</label>
                                       <input type="date" class="form-control" id="shippingBirthDate" name="shippingBirthDate" placeholder="Data Nascimento" required>
                                       <small class="form-text text-danger" id="birthdateError" style="display:none;"></small>
                                    </div>
                                    <button type="submit" class="btn btn-success" id="shippingFormSubmitButton">Salvar</button>
                                 </form>
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
                     <a href="../checkout/checkOut.php" class="btn btn-primary">Finalizar Compra</a>
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
      <script src="../../assets/plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap 4 -->
      <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="../../assets/js/adminlte.min.js"></script>
      <!-- Sweet Alert -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <!-- Cart -->
      <script src="../../assets/js/cart.js"></script>
      <!-- Dados de Envio -->
      <script>
         let shippingData = [];
         // Define o máximo como a data atual
         document.addEventListener('DOMContentLoaded', function() {
         const today = new Date();
         const formattedDate = today.toISOString().split('T')[0];
         document.getElementById('shippingBirthDate').setAttribute('max', formattedDate);
         
         // Adicionando o evento de verificação de maioridade
         document.getElementById('shippingBirthDate').addEventListener('input', function() {
         const birthdateInput = this;
         const birthdate = new Date(birthdateInput.value);
         const age = today.getFullYear() - birthdate.getFullYear();
         const monthDiff = today.getMonth() - birthdate.getMonth();
         
         // Verifica se a pessoa tem menos de 18 anos
         if (age < 18 || (age === 18 && monthDiff < 0)) {
            document.getElementById('birthdateError').textContent = 'Você deve ter pelo menos 18 anos.';
            document.getElementById('birthdateError').style.display = 'block';
         } else {
            document.getElementById('birthdateError').style.display = 'none';
         }
         });
         });
         
         
         // Função para carregar dados de envio
         function loadShippingData() {
             fetch('../../assets/php/get_shipping_data.php')
                 .then(response => response.json())
                 .then(data => {
                     shippingData = data;
                     renderShippingData();
                 })
                 .catch(error => {
                     console.error('Erro ao carregar dados de envio:', error);
                 });
         }
         
         // Função para renderizar os dados de envio como mini cards
         function renderShippingData() {
             const shippingDataList = document.getElementById("shippingDataList");
             shippingDataList.innerHTML = ""; 
         
             if (shippingData.length === 0) {
                 const noDataMessage = document.createElement("div");
                 noDataMessage.className = "alert alert-info";
                 noDataMessage.innerText = "Não existem dados de envio disponíveis. Adicione novos dados.";
                 shippingDataList.appendChild(noDataMessage);
                 return; 
             }
         
             shippingData.forEach(data => {
                 const card = document.createElement("div");
                 card.className = "shipping-card col-md-4"; 
         card.innerHTML = `
             <div class="card p-3" style="position: relative;">
                 <button class="btn btn-danger btn-sm" style="position: absolute; top: 10px; right: 10px;" onclick="deleteShipping(${data.id_dadosenvio})"><i class="fas fa-trash"></i></button>
                 <h5 class="card-title">${data.nome_cliente}</h5>
                 <p class="card-text">${data.morada}</p>
                 <p class="card-text">${data.data_nascimento}</p>
                 <button class="btn btn-primary" onclick="openShippingModal(${data.id_dadosenvio})">Editar</button>
             </div>
         `;
         
         
                 shippingDataList.appendChild(card);
             });
         }
         
         // Função para abrir o modal de adição ou edição
         function openShippingModal(id = null) {
             if (id) {
                 // Editar dados
                 const dataToEdit = shippingData.find(data => data.id_dadosenvio === id);
                 if (dataToEdit) {
                     document.getElementById("shippingId").value = id;
                     document.getElementById("shippingName").value = dataToEdit.nome_cliente;
                     document.getElementById("shippingAddress").value = dataToEdit.morada;
                     document.getElementById("shippingBirthDate").value = dataToEdit.data_nascimento;
                     document.getElementById("shippingModalLabel").innerText = "Editar Dados de Envio";
                     document.getElementById("shippingFormSubmitButton").innerText = "Atualizar"; 
                 }
             } else {
                 // Adicionar novos dados
                 document.getElementById("shippingId").value = ""; 
                 document.getElementById("shippingName").value = "";
                 document.getElementById("shippingAddress").value = "";
                 document.getElementById("shippingBirthDate").value = "";
                 document.getElementById("shippingModalLabel").innerText = "Adicionar Dados de Envio"; 
                 document.getElementById("shippingFormSubmitButton").innerText = "Salvar"; 
             }
             $('#shippingModal').modal('show');
         }
         
         function deleteShipping (id){
             Swal.fire({
           title: "Tem certeza que quer eliminar?",
           text: "Todas as encomendas associadas a estes dados serão canceladas!",
           icon: "warning",
           showCancelButton: true,
           confirmButtonColor: "#3085d6",
           cancelButtonColor: "#d33",
           confirmButtonText: "Sim, eliminar!"
         }).then((result) => {
           if (result.isConfirmed) {
         
             $.ajax({
                 url: '../../assets/php/shipping_handler.php', 
                 method: 'POST',
                 data: {
                     id: id,
                     action: 'delete',
                 },
                 dataType: 'json',
                 contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
                 success: function(data) {
                     const index = shippingData.findIndex(data => data.id_dadosenvio === parseInt(id));
                     // Atualiza os dados locais
                     shippingData.splice(index, 1);
                     renderShippingData();
                     Swal.fire({
                       title: "Eliminado!",
                       text: "Os dados de envio foram eliminados com sucesso!",
                       icon: "success"
                     });
                 },
                 error: function(jqXHR, textStatus, errorThrown) {
                     console.error('Erro ao eliminar dados de envio:', textStatus, errorThrown);
                     Swal.fire({
                         icon: 'error',
                         title: 'Erro!',
                         text: 'Ocorreu um problema ao eliminar enviar os dados.'
                     });
                 }
             });
           }
         });
         }
         
         // Função para submeter o formulário de adicionar ou editar dados de envio
         $('#shippingForm').on('submit', function(event) {
         event.preventDefault();
         
         // Pega a data de nascimento do campo
         const birthdate = new Date($('#shippingBirthDate').val());
         const today = new Date();
         
         // Calcula a idade
         let age = today.getFullYear() - birthdate.getFullYear();
         const monthDiff = today.getMonth() - birthdate.getMonth();
         
         if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
         age--;
         }
         
         // Verifica se a idade é inferior a 18 anos
         if (age < 18) {
         Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'É necessário ter 18 anos'
         });
         return; 
         }
         
         // Serializa os dados do formulário como uma string para envio
         let formData = $(this).serialize(); 
         
         const id = $('#shippingId').val();
         const action = id ? "edit" : "add";
         
         formData += `&action=${action}`; 
         if (id) {
         formData += `&id=${id}`;
         }
         
         $.ajax({
         url: '../../assets/php/shipping_handler.php', 
         method: 'POST',
         data: formData, 
         dataType: 'json',
         contentType: 'application/x-www-form-urlencoded; charset=UTF-8', 
         success: function(data) {
            if (data.success) {
                if (action === "edit") {
                    const index = shippingData.findIndex(data => data.id_dadosenvio === parseInt(id));
                    // Atualiza os dados locais
                    shippingData[index] = {
                        id_dadosenvio: data.newShippingData.id_dadosenvio, 
                        nome_cliente: data.newShippingData.nome_cliente,
                        morada: data.newShippingData.morada,
                        data_nascimento: data.newShippingData.data_nascimento
                    };
                } else {
                    shippingData.push(data.newShippingData); 
                }
                renderShippingData(); 
                $('#shippingModal').modal('hide'); 
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: `Dados de envio ${action === "edit" ? "atualizados" : "adicionados"} com sucesso.`
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: `Falha ao ${action === "edit" ? "atualizar" : "adicionar"} dados de envio.`
                });
            }
         },
         error: function(jqXHR, textStatus, errorThrown) {
            console.error('Erro ao enviar dados:', textStatus, errorThrown);
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Ocorreu um problema ao enviar os dados.'
            });
         }
         });
         });
         
         loadShippingData();
         
         document.querySelectorAll('.togglePassword').forEach(function(toggle) {
             toggle.addEventListener('click', function () {
                 const passwordField = document.querySelector('#password');
                 const passwordFieldConfirm = document.querySelector('#passwordC');
         
                 // Verifica qual campo está associado ao ícone clicado
                 if (this.previousElementSibling === passwordField) {
                     const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                     passwordField.setAttribute('type', type);
         
                 } else if (this.previousElementSibling === passwordFieldConfirm) {
                     const type = passwordFieldConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                     passwordFieldConfirm.setAttribute('type', type);
                 }
                 this.querySelector('i').classList.toggle('fa-eye');
                 this.querySelector('i').classList.toggle('fa-eye-slash');
             });
         });
                  
         // Alerta o utilizador caso necessário utilizando o sweet alert
         document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['error'])) {
                echo "Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: '{$_SESSION['error']}'
                });";
                unset($_SESSION['error']);
            }else if(isset($_SESSION['success'])) {
               echo "Swal.fire({
                  icon: 'success',
                  title: 'Dados de utilizador atualizados com sucesso!'
              });";
               unset($_SESSION['success']);
            }
            ?>
         });
      </script>
   </body>
</html>