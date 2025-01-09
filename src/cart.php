<?php
   include "dashboard/assets/db/db.php";
   session_start();
   $logedIn = false;
   if(isset($_SESSION['user_id'])){
     $userId = $_SESSION['user_id'];
     $userName = $_SESSION['username'];
     $logedIn = true;
   }else{
       header("Location: dashboard/");
       exit();
   }
   ?>
<!DOCTYPE html>
<html lang="pt">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
      <link rel="icon" href="dashboard/assets/img/tugameslogo-512x512.png">
      <title>Tugames</title>
      <!-- Bootstrap core CSS -->
      <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <!-- Additional CSS Files -->
      <link rel="stylesheet" href="assets/css/fontawesome.css">
      <link rel="stylesheet" href="assets/css/templatemo-lugx-gaming.css">
      <link rel="stylesheet" href="assets/css/owl.css">
      <link rel="stylesheet" href="assets/css/animate.css">
      <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
   </head>
   <body>
      <!-- ***** Preloader Start ***** -->
      <div id="js-preloader" class="js-preloader">
         <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
               <span></span>
               <span></span>
               <span></span>
            </div>
         </div>
      </div>
      <!-- ***** Preloader End ***** -->
      <!-- ***** Header Area Start ***** -->
      <header class="header-area header-sticky">
         <div class="container">
            <div class="row">
               <div class="col-12">
                  <nav class="main-nav">
                     <!-- ***** Logo Start ***** -->
                     <a href="index.php" class="logo">
                     <img src="assets/images/logo.png" alt="" style="width: 258px;">
                     </a>
                     <!-- ***** Logo End ***** -->
                     <!-- ***** Menu Start ***** -->
                     <ul class="nav">
                        <li><a href="index.php">Início</a></li>
                        <li><a href="shop.php">Loja</a></li>
                        <li><a href="contact.php">Contacte-nos</a></li>
                        <?php if ($logedIn): ?>
                        <li><a href="cart.php" class="active"><i class="fa fa-shopping-cart"></i></a></li>
                        <li><a href="dashboard/pages/perfil/perfil.php"><i class="fa fa-user"></i></a></li>
                        <?php else: ?>
                        <li><a href="dashboard/">Entrar</a></li>
                        <?php endif; ?>
                     </ul>
                     <a class='menu-trigger'>
                     <span>Menu</span>
                     </a>
                     <!-- ***** Menu End ***** -->
                  </nav>
               </div>
            </div>
         </div>
      </header>
      <!-- ***** Header Area End ***** -->
      <div class="page-heading header-text">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <h3>Carrinho</h3>
                  <span class="breadcrumb"><a href="index.php">Início</a>  >  Carrinho</span>
               </div>
            </div>
         </div>
      </div>
      <div class="section">
         <div class="container">
            <div class="row justify-content-md-center">
               <!-- Carrinho -->
               <div class="col-md-auto p-4 bg-primary bg-gradient border border-primary-subtle rounded-3">
                  <div class="text-center mb-3">
                     <h3 class="text-light">Items Carrinho</h3>
                  </div>
                  <div class="table-responsive text-center bg-light border border-primary-subtle rounded-3">
                     <table class="table">
                        <thead>
                           <tr>
                              <th class="text-secondary-emphasis">Produto</th>
                              <th class="text-secondary-emphasis">Preço</th>
                              <th class="text-secondary-emphasis">Quantidade</th>
                           </tr>
                        </thead>
                        <tbody class="text-light">
                           <!-- Loader -->
                           <div class="dots-container" id="dots-cart">
                              <div class="dot"></div>
                              <div class="dots">
                                 <span></span>
                                 <span></span>
                                 <span></span>
                              </div>
                           </div>
                        </tbody>
                     </table>
                  </div>
               </div>
               <!-- CheckOut -->
               <div class="col-md-auto p-4 ms-md-4 bg-primary bg-gradient border border-primary-subtle rounded-3">
                  <div class="text-center  mb-3">
                     <h3 class="text-light">Checkout</h3>
                  </div>
                  <div class="text-center mb-3">
                     <!-- TOTAL -->
                     <div class="mt-3">
                        <!--<p class="text-light text-start">Total: <span class="ms-2" id = "total">25 €</span></p>-->
                        <div class="input-group mt-5 mb-3">
                           <span class="input-group-text">Total</span>
                           <input type="text" class="form-control ps-0 text-sm-end" style="width:auto;" aria-label="Total" value="0.00" id="total" disabled>
                           <span class="input-group-text">€</span>
                        </div>
                     </div>
                     <a class="text-light text-decoration-none mt-3 checkoutbtn" id="checkout-button" href="dashboard/pages/checkout/checkOut.php">CHECKOUT</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <footer>
         <div class="container">
            <div class="col-lg-12">
               <p>Copyright ©  2022-2024 Tugames. All rights reserved. &nbsp;&nbsp;</p>
            </div>
         </div>
      </footer>
      <!-- Scripts -->
      <!-- Bootstrap core JavaScript -->
      <script src="vendor/jquery/jquery.min.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
      <script src="assets/js/isotope.min.js"></script>
      <script src="assets/js/owl-carousel.js"></script>
      <script src="assets/js/counter.js"></script>
      <script src="assets/js/custom.js"></script>
      <!-- Sweet Alert -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <!-- Cart -->
      <script>
         $(document).ready(function() {
             function fetchCartItems() {
                 $('#dots-cart').show(); 
         
                 $.ajax({
                     url: 'dashboard/assets/php/get_cart.php', 
                     method: 'GET',
                     dataType: 'json',
                     success: function(data) {
                         $('#dots-cart').hide();
         
                         if (data.error) {
                             console.error(data.error);
                             return;
                         }
                         fillCartTable(data);
                     },
                     error: function(jqXHR, textStatus, errorThrown) {
                         $('#dots-cart').hide(); 
                         console.error('Erro ao buscar dados do carrinho:', textStatus, errorThrown);
                     }
                 });
             }
         
             // Função para preencher a tabela do carrinho
             function fillCartTable(items) {
             const tbody = $('tbody');
             const checkoutButton = $('#checkout-button'); 
             tbody.empty(); 
             let total = 0;
         
             // Verifica se a lista de itens está vazia
             if (items.length === 0) {
                 const tr = `
                     <tr>
                         <td colspan="4" class="text-center">Carrinho Vazio</td>
                     </tr>
                 `;
                 tbody.append(tr);
                 $('#total').val('0.00'); 
                 checkoutButton.hide(); 
                 return; 
             }
         
             // Preenche a tabela com os itens
             items.forEach(item => {
                 const tr = $(`
                     <tr>
                         <td style="padding: 10px;">${item.name}</td>
                         <td style="padding: 10px;">${item.price}€</td>
                         <td style="padding: 10px;">
                             <div class="d-flex align-items-center">
                                 <button class="btn btn-outline-dark btn-sm me-2" onclick="dim('${item.id}', ${item.quantity})">
                                     <i class="fas fa-minus"></i>
                                 </button>
                                 <input type="number" value="${item.quantity}" min="1" class="form-control form-control-sm text-center" style="width: 50px;" disabled>
                                 <button class="btn btn-outline-dark btn-sm ms-2" onclick="aum('${item.id}', ${item.quantity})">
                                     <i class="fas fa-plus"></i>
                                 </button>
                                 <button class="btn btn-danger btn-sm ms-3" onclick="removeItem('${item.id}')">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         </td>
                     </tr>
                 `);
                 tbody.append(tr);
                 total += item.price * item.quantity; 
             });
         
             $('#total').val(total.toFixed(2)); 
             checkoutButton.show(); 
         }
         
         
          // Função para aumentar a quantidade
         window.aum = function(cartId, currentQuantity) {
             const newQuantity = currentQuantity + 1;
         
             $.ajax({
                 url: 'dashboard/assets/php/update_cart.php', 
                 method: 'POST',
                 data: { cartId: cartId, newQuantity: newQuantity },
                 dataType: 'json',
                 success: function(response) {
                     if (response.success) {
                         fetchCartItems(); 
                     } else {
                        Swal.fire({
         title: "Erro!",
         text: response.message,
         icon: "error",
         confirmButtonText: "OK",
         confirmButtonColor: "#007cf8" 
         });
                        fetchCartItems(); 
                     }
                 },
                 error: function() {
                  Swal.fire({
         title: "Erro!",
         text: "Erro ao aumentar a quantidade.",
         icon: "error",
         confirmButtonText: "OK",
         confirmButtonColor: "#007cf8" 
         });
                 }
             });
         };
         
         // Função para diminuir a quantidade
         window.dim = function(cartId, currentQuantity) {
             if (currentQuantity > 1) {
                 const newQuantity = currentQuantity - 1;
         
                 $.ajax({
                     url: 'dashboard/assets/php/update_cart.php', 
                     method: 'POST',
                     data: { cartId: cartId, newQuantity: newQuantity },
                     dataType: 'json',
                     success: function(response) {
                        if (response.success) {
                         fetchCartItems(); 
                     } else {
         
                        Swal.fire({
         title: "Erro!",
         text: response.message,
         icon: "error",
         confirmButtonText: "OK",
         confirmButtonColor: "#007cf8" 
         });
                        fetchCartItems(); 
                     }
                     },
                     error: function() {
                        Swal.fire({
         title: "Erro!",
         text: "Erro ao diminuir a quantidade.",
         icon: "error",
         confirmButtonText: "OK",
         confirmButtonColor: "#007cf8" 
         });
                     }
                 });
             } else {
                 removeItem(cartId);
             }
         };
         
         // Função para remover o item
         window.removeItem = function(cartId) {
             Swal.fire({
                 title: "Tem certeza?",
                 text: "Deseja remover este item do carrinho?",
                 icon: "warning",
                 showCancelButton: true, 
                 confirmButtonText: "Sim, remover!",
                 cancelButtonText: "Não, cancelar",
                 confirmButtonColor: '#d33',  
                 cancelButtonColor: '#007cf8',
                 dangerMode: true,
             }).then((result) => {
                 if (result.isConfirmed) { 
                     $.ajax({
                         url: 'dashboard/assets/php/update_cart.php', 
                         method: 'POST',
                         data: { cartId: cartId, newQuantity: 0 },
                         dataType: 'json',
                         success: function(response) {
                             if (response.success) {
                              Swal.fire({
         title: "Sucesso!",
         text: response.message,
         icon: "success",
         confirmButtonText: "OK",
         confirmButtonColor: "#007cf8" 
         });
         
                                 fetchCartItems(); 
                             } else {
                                 Swal.fire({
         title: "Erro!",
         text: response.message,
         icon: "error",
         confirmButtonText: "OK",
         confirmButtonColor: "#007cf8" 
         });
         
                             }
                         },
                         error: function() {
                             Swal.fire({
         title: "Erro!",
         text: "Erro ao remover o item do carrinho.",
         icon: "error",
         confirmButtonText: "OK",
         confirmButtonColor: "#007cf8" 
         });
                         }
                     });
                 } 
             });
         };
             // Chama a função para buscar os itens do carrinho ao carregar a página
             fetchCartItems();
         });
      </script>
   </body>
</html>