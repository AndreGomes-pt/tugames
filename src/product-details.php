<?php
   include "dashboard/assets/db/db.php";
   session_start();
   $logedIn = false;
   if(isset($_SESSION['user_id'])){
     $userId = $_SESSION['user_id'];
     $userName = $_SESSION['username'];
     $logedIn = true;
   }
   //Coleta dados basicos do produto
   if(isset($_GET['id']) && isset($_GET['nome'])){
      $id_produto = $_GET['id'];
      $nome_produto = $_GET['nome'];
   
      //Coleta dos dados restantes
      $sql = "SELECT descricao,preco,todas_fotos,nome_categoria,stock,	id_categoria FROM view_produtos WHERE id_produto = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i",$id_produto);
      $stmt->execute();
      $stmt->bind_result($descricao,$preco,$fotos,$nomecat,$stock,$id_categoria);
      $stmt->fetch();
      $stmt->close();
      $conn->close();
    }else{
      //Redireciona para 404
      header("Location: 404.php");
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
      <style>
         .carousel-control-prev-icon,
         .carousel-control-next-icon {
         background-color: black; 
         border-radius: 50%; 
         padding: auto;
         }
         .carousel-control-prev {
         left: -50px; 
         }
         .carousel-control-next {
         right: -50px; 
         }
         .page-heading::after {
         content: ""; 
         position: absolute; 
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background-color: rgba(0, 0, 0, 0.6); 
         border-radius: 0px 0px 150px 150px; 
         z-index: 0; 
         }
         .container {
         position: relative; 
         z-index: 1; 
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
                     <img src="assets/images/logo.png" alt="" style="width: 258px;" >
                     </a>
                     <!-- ***** Logo End ***** -->
                     <!-- ***** Menu Start ***** -->
                     <ul class="nav">
                        <li><a href="index.php">Início</a></li>
                        <li><a href="shop.php?cat=<?php echo $id_categoria; ?>" class="active">Loja</a></li>
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
      <?php
         $fotosArray = explode(',', $fotos);
         $fotoBackground = isset($fotosArray[1]) && !empty(trim($fotosArray[1])) 
         ? trim($fotosArray[1]) 
         : (isset($fotosArray[0]) ? trim($fotosArray[0]) : 'placeholder.png');
         ?>
      <div class="page-heading header-text dark-overlay" style="background-image: url(dashboard/assets/img/produtos/<?php echo $fotoBackground; ?>);">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <h3><?php echo $nome_produto; ?></h3>
                  <span class="breadcrumb"><a href="index.php">Início</a>  >  <a href="shop.php?cat=<?php echo $id_categoria; ?>">Loja</a>  >  <?php echo $nome_produto; ?></span>
               </div>
            </div>
         </div>
      </div>
      <div class="single-product section">
         <!-- Prencher dados restantes atraves jquery -->
         <div class="container">
            <div class="row">
               <div class="col-lg-6">
                  <div class="left-image">
                     <div id="produto-carousel" class="carousel slide">
                        <div class="carousel-inner">
                           <?php
                              $imagemPadrao = "placeholder.png";
                              // Verifica se o array de fotos não está vazio
                              if (!empty($fotosArray[0])) {
                                  foreach ($fotosArray as $index => $foto):
                              ?>
                           <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                              <img src="dashboard/assets/img/produtos/<?php echo trim($foto); ?>" class="d-block mx-auto img-fluid" alt="Produto Imagem" style="max-height:560px; width:auto;">
                           </div>
                           <?php
                              endforeach;
                              } else {
                              ?>
                           <div class="carousel-item active">
                              <img src="dashboard/assets/img/produtos/<?php echo $imagemPadrao; ?>" class="d-block mx-auto img-fluid" alt="Imagem Padrão" style="max-height:560px; width:auto;">
                           </div>
                           <?php
                              }
                              ?>
                        </div>
                        <a class="carousel-control-prev" href="#produto-carousel" role="button" data-bs-target="#produto-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next" href="#produto-carousel" role="button" data-bs-target="#produto-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6 align-self-center">
                  <h4><?php echo $nome_produto; ?></h4>
                  <span class="price"><?php echo $preco;?>€</span>
                  <p><?php echo $descricao;?></p>
                  <form id="qty" action="#">
                     <?php 
                        // Verifica se o produto está fora de stock
                        $foraDeStock = $stock == 0; 
                        ?>
                     <input type="number" name="quantity" class="form-control" id="quantity" aria-describedby="quantity" 
                        placeholder="1" value ="1" min="1" <?php echo $foraDeStock ? 'disabled' : ''; ?>>
                     <button type="submit" id="checkout" data-id="<?php echo $id_produto; ?>"
                        class="btn <?php echo $foraDeStock ? 'btn-danger' : ($logedIn ? 'btn-primary' : 'btn-secondary'); ?>" 
                        <?php echo $foraDeStock || !$logedIn ? 'disabled' : ''; ?>>
                     <i class="fa fa-shopping-bag"></i>
                     <?php 
                        if (!$logedIn) {
                            echo 'FAÇA LOGIN PARA ADICIONAR AO CARRINHO';
                        } elseif ($foraDeStock) {
                            echo 'FORA DE STOCK';
                        } else {
                            echo 'ADICIONAR AO CARRINHO';
                        }
                        ?>
                     </button>
                  </form>
                  <ul>
                     <li><span>Categoria:</span><a href="shop.php?cat=<?php echo $id_categoria;?>"><?php echo $nomecat;?></a></li>
                  </ul>
               </div>
               <div class="col-lg-12">
                  <div class="sep"></div>
               </div>
            </div>
         </div>
      </div>
      <div class="more-info">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <div class="tabs-content">
                     <div class="row">
                        <div class="nav-wrapper">
                           <ul class="nav nav-tabs" role="tablist">
                              <li class="nav-item" role="presentation">
                                 <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#produto-descricao-b" type="button" role="tab" aria-controls="description" aria-selected="true">Descrição</button>
                              </li>
                           </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
                           <div class="tab-pane fade show active" id="produto-descricao-b" role="tabpanel" aria-labelledby="description-tab">
                              <p><?php echo $descricao;?></p>
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
      <!-- Sweet Alert -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <!-- Adicionar Carrinho -->
      <script>
         $(document).ready(function() {
             $('#qty').on('submit', function(event) {
                 event.preventDefault(); 
         
                 const quantity = $('#quantity').val(); 
                 const productId = $('#checkout').data('id'); 
         
                 // Valida a quantidade antes de enviar
                 if (quantity <= 0) {
                     Swal.fire({
                         title: 'Quantidade Inválida!',
                         text: 'Por favor, insira uma quantidade válida.',
                         icon: 'warning',
                         confirmButtonText: 'OK',
                         confirmButtonColor: '#007cf8',
                     });
                     return;
                 }
         
                 // Chamada AJAX para enviar os dados ao PHP
                 $.ajax({
                     url: 'assets/php/add_to_cart.php', 
                     type: 'POST',
                     data: {
                         id: productId,
                         quantity: quantity
                     },
                     success: function(response) {
                        if (response.success) {
                             Swal.fire({
                                 title: 'Sucesso!',
                                 text: response.message, 
                                 icon: 'success',
                                 confirmButtonText: 'OK',
                                 confirmButtonColor: '#007cf8',
                             });
                         } else {
                             Swal.fire({
                                 title: 'Erro!',
                                 text: response.message || 'Ocorreu um erro ao adicionar o produto ao carrinho.',
                                 icon: 'error',
                                 confirmButtonText: 'OK',
                                 confirmButtonColor: '#007cf8',
                             });
                         }
                     },
                     error: function(xhr, status, error) {
                         console.error('Erro na requisição:', error);
                         Swal.fire({
                             title: 'Erro!',
                             text: 'Ocorreu um erro ao adicionar ao carrinho.',
                             icon: 'error',
                             confirmButtonText: 'OK',
                             confirmButtonColor: '#007cf8',
                         });
                     }
                 });
             });
         }); 
      </script>
   </body>
</html>