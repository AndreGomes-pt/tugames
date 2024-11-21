<?php
   include "dashboard/assets/db/db.php";
   session_start();
   $logedIn = false;
   if(isset($_SESSION['user_id'])){
     $userId = $_SESSION['user_id'];
     $userName = $_SESSION['username'];
     $logedIn = true;
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
      <style>
         .catgr {
         display: flex; 
         justify-content: center; 
         align-items: center; 
         width: 235px; 
         height: 220px; 
         margin: 0 auto; 
         overflow: hidden; 
         border-radius: 8px; 
         border: 1px solid #ddd;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
         }
         .catgr img {
         width: 100%; 
         height: 100%; 
         object-fit: contain; 
         }
      </style>
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
                        <li><a href="index.php" class="active">Início</a></li>
                        <li><a href="shop.php">Loja</a></li>
                        <li><a href="contact.php">Contacte-nos</a></li>
                        <?php if ($logedIn): ?>
                        <li><a href="cart.php"><i class="fa fa-shopping-cart"></i></a></li>
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
      <div class="main-banner">
         <div class="container">
            <div class="row">
               <div class="col-lg-6 align-self-center">
                  <div class="caption header-text">
                     <h6>Bem-vindo à Tugames</h6>
                     <h2>A MELHOR LOJA DE JOGOS!</h2>
                     <p>A Tugames é o destino ideal para os fãs de jogos! Aqui encontrarás uma vasta seleção de títulos incríveis. Explora as últimas novidades e ofertas exclusivas. Junta-te à comunidade e descobre o teu próximo jogo favorito!</p>
                  </div>
               </div>
               <div class="col-lg-4 offset-lg-2">
                  <div class="right-image">
                     <img src="assets/images/banner-image.gif" alt="">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="section most-played">
         <div class="container">
            <div class="row">
               <div class="col-lg-6">
                  <div class="section-heading">
                     <h6>JOGOS EM DESTAQUE</h6>
                     <h2>Mais Jogados</h2>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="main-button">
                     <a href="shop.php">Ver Todos</a>
                  </div>
               </div>
            </div>
            <div class="dots-container" id="dots-most-played">
               <div class="dot"></div>
               <div class="dots">
                  <span></span>
                  <span></span>
                  <span></span>
               </div>
            </div>
            <div class="row" id="most-played-container">
               <!-- Preenchido atraves do jquery -->
            </div>
         </div>
      </div>
      <div class="section categories">
         <div class="container">
            <div class="row">
               <div class="col-lg-12 text-center">
                  <div class="section-heading">
                     <h6>Categorias</h6>
                     <h2>Categorias em Destaque</h2>
                  </div>
               </div>
               <div class="dots-container" id="dots-catg">
                  <div class="dot"></div>
                  <div class="dots">
                     <span></span>
                     <span></span>
                     <span></span>
                  </div>
               </div>
               <div class="row" id="catg-container">
                  <!-- Preenchido atraves do jquery -->
               </div>
            </div>
         </div>
      </div>
      <div class="section cta">
         <div class="container">
            <div class="row">
               <div class="col-lg-5">
                  <div class="shop">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="section-heading">
                              <h6>A Nossa Loja</h6>
                              <h2>Pré-encomenda e obtém os melhores <em>preços</em> para ti!</h2>
                           </div>
                           <p>Descobre uma vasta seleção de jogos e promoções imperdíveis. Junta-te à comunidade Tugames e encontra o jogo que mais gostas!</p>
                           <div class="main-button">
                              <a href="shop.php">Visita a Loja</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-5 offset-lg-2 align-self-end">
                  <div class="subscribe">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="section-heading">
                              <h6>NEWSLETTER</h6>
                              <h2>Recebe até 100€ de desconto ao <em>subscrever</em> a nossa newsletter!</h2>
                           </div>
                           <div class="search-input">
                              <form id="subscribe" action="#">
                                 <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="O teu email..." required>
                                 <button type="submit" id="subscrever-button">Subscrever Agora</button>
                              </form>
                           </div>
                        </div>
                     </div>
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
      <script src="dashboard/assets/js/cart.js"></script>
      <!-- Sweet Alert -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
         $(document).ready(function() { 
             //Mais Jogados
             $.ajax({
                 url: 'assets/php/get_produtos.php', 
                 type: 'GET',
                 data: {
                     condition: 'random' 
                 },
                 success: function(response) {
                     const produtos = response.produtos; 
                     let htmlContent = ''; 
                     const imagemPadrao = 'placeholder.png'; 
                     // Monta o HTML para os produtos
                     produtos.forEach(produto => {
                        
                         const fotosArray = produto.fotos ? produto.fotos.split(',') : [];
                         const imagemProduto = fotosArray.length > 0 && fotosArray[0].trim() ? fotosArray[0].trim() : imagemPadrao;
         
                         htmlContent += `
                         <div class="col-lg-2 col-md-6 col-sm-6">
                             <div class="item">
                                 <div class="thumb">
                                     <a href="product-details.php?id=${produto.id}&nome=${produto.nome}"><img src="dashboard/assets/img/produtos/${imagemProduto}" alt="${produto.nome}" style="width: 190px;height: 190px;object-fit: cover;"></a>
                                 </div>
                                 <div class="down-content">
                                     <span class="category">${produto.nomecat}</span>
                                     <h4>${produto.nome}</h4>
                                     <span>${produto.preco} €</span> <!-- Exibindo o preço -->
                                     <a href="product-details.php?id=${produto.id}&nome=${produto.nome}">Explore</a>
                                 </div>
                             </div>
                         </div>`;
                     });
                     $('#most-played-container').html(htmlContent);
                     $('#dots-most-played').hide();
                 },
                 error: function(response) {
                     console.error('Erro na requisição:', response);
                     $('#dots-most-played').hide(); 
                 }
             });
         
             //Categorias em destaque 
             $.ajax({
                 url: 'assets/php/get_categorias.php', 
                 type: 'GET',
                 success: function(response) {
                     const categorias = response; 
                     let htmlContent = ''; 
         
                     const imagemPadrao = 'placeholder.png'; 
         
                     // Monta o HTML para os produtos
                     categorias.forEach(categoria => {
                         const imagemCat = categoria.capa || imagemPadrao; 
         
                         htmlContent += `
                                   <div class="col-lg-2 col-md-6 col-sm-6 ms-4">
                                     <div class="item catgr">
                                       <h4>${categoria.nome}</h4>
                                       <div class="thumb">
                                         <a href="shop.php?cat=${categoria.id}"><img src="dashboard/assets/img/categorias/${imagemCat}" alt="" style="width: 235px;height: 220px; object-fit: cover;"></a>
                                       </div>
                                     </div>
                                   </div>
                         `;
                     });
         
                     $('#catg-container').html(htmlContent);
                     $('#dots-catg').hide();
                 },
                 error: function(xhr, status, error) {
                     console.error('Erro na requisição:', error);
                     $('#dots-catg').hide(); 
                 }
             });
         
             //Form subscrever
             $('#subscrever-button').click(function(event) {
                     event.preventDefault(); 
         
                     var form = $('#subscribe')[0];
                  
                     // Verifica se o formulário é válido
                     if (form.checkValidity() === false) {
                        form.reportValidity();
                        return; 
                     }
                     // Exibe o alerta SweetAlert
                     Swal.fire({
                         title: "Subscrever!",
                         text: "Obrigado por Subscrever.",
                         icon: "success",
                         button: "OK",
                     });
             });
         });
      </script>
   </body>
</html>