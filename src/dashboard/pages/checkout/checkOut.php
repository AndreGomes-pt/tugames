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
   <body class="hold-transition sidebar-mini">
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
                        <h1 class="m-0">Checkout</h1>
                     </div>
                     <!-- /.col -->
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="../../">Início</a></li>
                           <li class="breadcrumb-item active">Checkout</li>
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
                     <div class="col-md-8 offset-md-2">
                        <!-- Resumo da Encomenda -->
                        <div class="box">
                           <div class="box-header with-border">
                              <h3 class="box-title">Resumo da Encomenda</h3>
                           </div>
                           <div class="box-body">
                              <ul id="cartSummary" class="list-group">
                                 <!-- Preenchido atraves do jquery -->
                              </ul>
                              <p class="text-right font-weight-bold totalPrice"></p>
                           </div>
                        </div>
                        <!-- Endereços Salvos -->
                        <div class="box mt-3">
                           <div class="box-header with-border">
                              <h3 class="box-title">Endereços Salvos</h3>
                           </div>
                           <div class="box-body">
                              <div class="form-group">
                                 <label for="savedAddresses">Selecione um Endereço Salvo</label>
                                 <select id="savedAddresses" class="form-control" onchange="fillAddress(this)">
                                    <option value="">Selecione...</option>
                                    <!-- As opções serão carregadas aqui -->
                                 </select>
                              </div>
                           </div>
                        </div>
                        <!-- Dados de Entrega -->
                        <div class="box mt-3">
                           <div class="box-header with-border">
                              <h3 class="box-title">Dados de Entrega</h3>
                           </div>
                           <div class="box-body">
                              <form id="checkoutForm">
                                 <div class="form-group">
                                    <label for="fullName">Nome Completo</label>
                                    <input type="text" class="form-control" id="fullName" required>
                                 </div>
                                 <div class="form-group">
                                    <label for="address">Morada</label>
                                    <input type="text" class="form-control" id="address" required>
                                 </div>
                                 <div class="form-group">
                                    <label for="birthdate">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="birthdate" required>
                                    <small class="form-text text-danger" id="birthdateError" style="display:none;">Você deve ter pelo menos 18 anos.</small>
                                 </div>
                                 <!-- Método de Pagamento -->
                                 <div class="form-group">
                                    <label for="paymentMethod">Método de Pagamento</label>
                                    <select id="paymentMethod" class="form-control" required>
                                       <option value="">Selecione...</option>
                                       <option value="visa">Visa</option>
                                       <option value="mastercard">Mastercard</option>
                                    </select>
                                 </div>
                                 <div class="form-group">
                                    <label for="cardNumber">Número do Cartão de Crédito</label>
                                    <input type="text" class="form-control" id="cardNumber" maxlength="19" placeholder="xxxx-xxxx-xxxx-xxxx" required>
                                    <small class="form-text text-muted">Insira um número de cartão de crédito com 16 dígitos.</small>
                                    <small class="form-text text-danger" id="cardNumberError" style="display:none;">Número do cartão inválido.</small>
                                 </div>
                                 <div class="form-group">
                                    <label for="expirationDate">Data de Validade</label>
                                    <input type="text" class="form-control" id="expirationDate" maxlength="5" pattern="^(0[1-9]|1[0-2])\/?([0-9]{2})$" placeholder="MM/AA" required>
                                    <small class="form-text text-muted">Formato: MM/AA</small>
                                    <small class="form-text text-danger" id="expirationDateError" style="display:none;">Data de validade inválida.</small>
                                 </div>
                                 <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <input type="text" class="form-control" id="cvv" maxlength="3" pattern="^[0-9]{3}$" required>
                                    <small class="form-text text-muted">Insira um CVV de 3 dígitos.</small>
                                    <small class="form-text text-danger" id="cvvError" style="display:none;">CVV inválido.</small>
                                 </div>
                                 <button type="submit" class="btn btn-primary btn-block">Finalizar Compra</button>
                              </form>
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
      <!-- Form Validation -->
      <script>
         function fillAddress(select) {
                 const selectedOption = select.options[select.selectedIndex];
                 
                 // Verifica se uma opção válida foi selecionada
                 if (selectedOption.value) {
                     const morada = selectedOption.getAttribute('data-morada');
                     const nome = selectedOption.getAttribute('data-nome');
                     const data_nascimento = selectedOption.getAttribute('data-nascimento');
         
                     document.getElementById('fullName').value = nome;  
                     document.getElementById('address').value = morada; 
                     document.getElementById('birthdate').value = data_nascimento;
                 } else {
                     document.getElementById('fullName').value = '';
                     document.getElementById('address').value = '';
                     document.getElementById('birthdate').value = '';
                 }
             }
         
             document.getElementById('birthdate').addEventListener('input', function() {
                 const birthdateInput = this;
                 const birthdate = new Date(birthdateInput.value);
                 const today = new Date();
                 const age = today.getFullYear() - birthdate.getFullYear();
                 const monthDiff = today.getMonth() - birthdate.getMonth();
                 
                 // Verifica se a pessoa tem menos de 18 anos
                 if (age < 18 || (age === 18 && monthDiff < 0)) {
                     document.getElementById('birthdateError').style.display = 'block';
                 } else {
                     document.getElementById('birthdateError').style.display = 'none';
                 }
             });
         
             document.getElementById('cardNumber').addEventListener('input', function() {
                 const cardNumberInput = this;
                 // Remove caracteres não numéricos
                 let cleaned = cardNumberInput.value.replace(/\D/g, ''); 
                         
                 if (cleaned.length < 16) {
                     document.getElementById('cardNumberError').style.display = 'block';
                 } else {
                     document.getElementById('cardNumberError').style.display = 'none';
                 }
         
                 let formatted = '';
                 for (let i = 0; i < cleaned.length; i += 4) {
                     formatted += cleaned.substring(i, i + 4) + (i + 4 < cleaned.length ? '-' : '');
                 }
                 
                 cardNumberInput.value = formatted;
         
                 if (formatted.length > 19) {
                     cardNumberInput.value = formatted.slice(0, 19);
                 }
             });
         
             document.getElementById('expirationDate').addEventListener('input', function() {
             const expirationInput = this;
             let value = expirationInput.value.replace(/\D/g, ''); 
             if (value.length > 2) {
                 value = value.slice(0, 2) + '/' + value.slice(2);
             }
             expirationInput.value = value;
         
             // Verifica se a data de validade está no formato correto
             const isValid = /^(0[1-9]|1[0-2])\/?([0-9]{2})$/.test(expirationInput.value);
             
             if (!isValid) {
                 document.getElementById('expirationDateError').textContent = 'Data de validade inválida.';
                 document.getElementById('expirationDateError').style.display = 'block';
             } else {
                 const [month, year] = expirationInput.value.split('/').map(num => parseInt(num, 10));
                 const currentYear = new Date().getFullYear() % 100; 
                 const currentMonth = new Date().getMonth() + 1; 
         
                 // Verifica se a data de validade está no futuro
                 if (year < currentYear || (year === currentYear && month < currentMonth)) {
                     document.getElementById('expirationDateError').textContent = 'O cartão está fora da validade.';
                     document.getElementById('expirationDateError').style.display = 'block';
                 } else {
                     document.getElementById('expirationDateError').style.display = 'none';
                 }
             }
         });
         
             document.getElementById('cvv').addEventListener('input', function() {
                 const cvvInput = this;
                 // Verifica se o CVV é válido
                 const isValid = /^[0-9]{3}$/.test(cvvInput.value);
                 if (!isValid) {
                     document.getElementById('cvvError').style.display = 'block';
                 } else {
                     document.getElementById('cvvError').style.display = 'none';
                 }
             });
      </script>
      <!-- Preenchimento dos campos atraves do Jquery -->
      <script>
         $(document).ready(function() {
             // Carregar Produtos
             $.ajax({
                 url: '../../assets/php/get_cart.php',
                 type: 'GET',
                 dataType: 'json',
                 success: function(products) {
                     displayProducts(products);
                 },
                 error: function() {
                     alert('Falha ao carregar produtos');
                 }
             });
         
              // Carrega métodos de pagamento
              $.ajax({
                 url: '../../assets/php/get_shipping_data.php',
                 type: 'GET',
                 dataType: 'json',
                 success: function(methods) {
                     populateSavedAddresses(methods);
                 },
                 error: function() {
                     alert('Falha ao carregar métodos de pagamento');
                 }
             });
             
             function displayProducts(products) {
                 const cartSummary = $('#cartSummary');
                 cartSummary.empty(); 
         
                 let total = 0; 
         
                 $.each(products, function(index, product) {
                     const listItem = `
                         <li class="list-group-item">
                             <div class="row">
                                 <div class="col-md-6">
                                     <strong>${product.name}</strong><br>
                                     <small>${product.description}</small>
                                 </div>
                                 <div class="col-md-2 text-center"><strong>Qtn</strong>: ${product.quantity}</div>
                                 <div class="col-md-4 text-right">${product.price} €</div>
                             </div>
                         </li>
                     `;
                     cartSummary.append(listItem);
         
                     total += product.price * product.quantity;
                 });
         
                 // Exibe o total
                 $('.totalPrice').text(`Total: ${total.toFixed(2)} €`);
             }
         
             function populateSavedAddresses(addresses) {
                 const addressSelect = $('#savedAddresses');
                 addressSelect.empty(); 
                 addressSelect.append('<option value="">Selecione...</option>');
         
                 $.each(addresses, function(index, address) {
                     addressSelect.append(`<option value="${address.id_dadosenvio}" data-morada="${address.morada}" data-nome="${address.nome_cliente}" data-nascimento="${address.data_nascimento}">${address.nome_cliente} - ${address.morada} - ${address.data_nascimento}  </option>`);
                 });
             }
         });
      </script>
      <!-- Invoice --->
      <script>
         // Valida o formulário e envia os dados para inserir a encomenda
         $('#checkoutForm').on('submit', function(e) {
             e.preventDefault();
         
             // Valida o formulário (caso os campos estejam corretos)
             const errors = document.querySelectorAll('.form-text[style*="display: block"]');
             if (errors.length > 0) {
                 alert("Por favor, corrija os erros antes de finalizar a compra.");
                 return;
             }
         
             // Serializa os dados do formulário para enviar ao servidor
             const formData = {
                 fullName: $('#fullName').val(),
                 address: $('#address').val(),
                 birthdate: $('#birthdate').val(),
                 shippingId: $('#savedAddresses').val()
             };
         
             // Envia os dados para o servidor
             $.ajax({
             url: 'inserir_encomenda.php',
             method: 'POST',
             data: formData,
             dataType: 'json',
             success: function(response) {
                 if (response.status === 'success') {
                     Swal.fire({
                         title: 'Sucesso!',
                         text: 'Compra finalizada com sucesso.',
                         icon: 'success',
                         confirmButtonText: 'OK'
                     }).then((result) => {
                         if (result.isConfirmed) {
                             window.location.href = '../encomendas/encomendas.php?id='+response.id_encomenda; 
                         }
                     });
                 } else if (response.error){
                     Swal.fire({
                         title: 'Erro!',
                         text: response.message,
                         icon: 'error',
                         confirmButtonText: 'OK'
                     });
                 }
             },
             error: function(jqXHR, textStatus, errorThrown) {
                 console.error('Erro ao enviar dados:', textStatus, errorThrown);
                 console.log('Resposta do servidor:', jqXHR.responseText);
                 Swal.fire({
                     title: 'Erro!',
                     text: 'Ocorreu um erro ao finalizar a compra. Tente novamente.',
                     icon: 'error',
                     confirmButtonText: 'OK'
                 });
             }
         });
         
         });
      </script>
   </body>
</html>