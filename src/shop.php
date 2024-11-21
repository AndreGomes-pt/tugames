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
                        <li><a href="shop.php" class="active">Loja</a></li>
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
      <div class="page-heading header-text">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <h3>Loja</h3>
                  <span class="breadcrumb"><a href="index.php">Início</a> > Loja</span>
               </div>
            </div>
         </div>
      </div>
      <!-- Produtos -->
      <div class="section trending">
         <div class="container">
            <!-- Barra Pesquisa -->
            <!-- Barra Pesquisa -->
            <div class="text-center">
               <form class="search-form" id="search" action="/search" method="GET">
                  <input type="text" name="query" placeholder="Pesquisar..." id="searchQuery">
                  <button type="submit" role="button"><i class="fa fa-search"></i></button>
               </form>
            </div>
            <!-- Fim barra pesquisa -->
            <!-- Filtro de categorias carregado via jQuery -->
            <ul class="trending-filter mt-4" id="filtros-produtos">
                  <!-- As categorias serão carregadas aqui via jQuery -->
            </ul>
            <!-- Spinner dos produtos -->
            <div class="dots-container" id="dots-most-played">
               <div class="dot"></div>
               <div class="dots">
                  <span></span>
                  <span></span>
                  <span></span>
               </div>
            </div>
            <!-- Produtos carregados via jQuery -->
            <div class="row trending-box">
               <!-- Produtos serão carregados aqui -->
            </div>
            <!-- Paginação -->
            <div class="row">
               <div class="col-lg-12">
                  <ul class="pagination">
                     <!-- Paginação será carregada aqui -->
                  </ul>
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
      <script>
  $(document).ready(function() {
    // Carrega categorias do servidor
    $.ajax({
        url: 'assets/php/get_categorias.php',
        type: 'GET',
        success: function(response) {
            let categorias = response; 
            let html = '<li><a class="is_active" id="catpadrao" href="#!" data-filter="0">Mostrar Todos</a></li>'; // Adicionado HTML para a opção padrão

            // Montar os links de filtro das categorias
            categorias.forEach(function(categoria) {
                html += `<li><a href="#!" data-filter="${categoria.id}">${categoria.nome}</a></li>`;
            });

            $('#filtros-produtos').html(html);

            // Verificar se existe o parâmetro 'cat' na URL
            const params = new URLSearchParams(window.location.search);
            const catId = params.get('cat'); // Obtém o valor do parâmetro 'cat'

            // Se 'cat' existe, selecionar a categoria correspondente
            if (catId) {
                if (catId === '0') {
                    $('#catpadrao').addClass('is_active'); // Seleciona "Mostrar Todos" se catId for 0
                    carregarProdutos(1, 0); // Carregar todos os produtos
                } else {
                    $('ul.trending-filter a[data-filter="' + catId + '"]').addClass('is_active'); // Ativa a categoria selecionada
                    $('#catpadrao').removeClass('is_active'); // Remove a classe is_active da opção padrão
                    carregarProdutos(1, catId); // Carregar produtos dessa categoria
                }
            } else {
                // Caso não exista, manter a opção padrão como ativa
                carregarProdutos(1, 0); 
            }
        },
        error: function() {
            console.error('Erro ao carregar categorias');
        }
    });

    // Evento de filtro de categorias
    $(document).on('click', 'ul.trending-filter a', function(e) {
        e.preventDefault();
        let idCategoria = $(this).data('filter'); 

        // Remove a classe is_active de todas as opções
        $('ul.trending-filter a').removeClass('is_active');
        
        // Adiciona a classe is_active à opção clicada
        $(this).addClass('is_active');

        // Carrega os produtos da categoria selecionada
        carregarProdutos(1, idCategoria); 
    });

    // Paginação: Carregar produtos ao clicar na página
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).text(); 
        let categoria = $('ul.trending-filter a.is_active').data('filter'); 

        carregarProdutos(page, categoria);
    });

    // Função para carregar os produtos
    function carregarProdutos(page, idCategoria, query = '') { 
        $('#dots-most-played').show(); 

        $.ajax({
            url: 'assets/php/get_produtos.php',
            type: 'GET',
            data: {
                page: page,
                id_categoria: idCategoria,
                query: query 
            },
            success: function(response) {
                let produtos = response.produtos; 
                let totalProducts = response.total; 
                let limit = response.limit; 
                let html = '';

                if (produtos.length > 0) {
                    produtos.forEach(function(produto) {
                        let imagem = produto.fotos && produto.fotos.split(',')[0] ? `dashboard/assets/img/produtos/${produto.fotos.split(',')[0]}` : 'dashboard/assets/img/produtos/placeholder.png';

                        html += `
                            <div class="col-lg-3 col-md-6 align-self-center mb-30 trending-items col-md-6 str">
                                <div class="item">
                                    <div class="thumb" style="text-align: center; height: 300px; overflow: hidden;">
                                        <a href="product-details.php?id=${produto.id}&nome=${produto.nome}&cat=${idCategoria}">
                                            <img src="${imagem}" alt="${produto.nome}" class="img-fluid" style="height: 100%; width: 100%; object-fit: cover;">
                                        </a>
                                        <span class="price">${produto.preco}€</span>
                                    </div>
                                    <div class="down-content">
                                        <span class="category">${produto.nomecat}</span>
                                        <h4>${produto.nome}</h4>
                                        <a href="product-details.php?id=${produto.id}&nome=${produto.nome}&cat=${idCategoria}">
                                            <i class="fa fa-shopping-bag"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<p>Nenhum produto encontrado.</p>';
                }

                $('.trending-box').html(html); 
                $('#dots-most-played').hide(); 

                let totalPages = response.total_pages; 
                let paginationHtml = '';
                for (let i = 1; i <= totalPages; i++) {
                    paginationHtml += `<li><a href="#" class="${i == page ? 'is_active' : ''}">${i}</a></li>`;
                }
                $('.pagination').html(paginationHtml); 
            },
            error: function(response) {
                console.error('Erro ao carregar produtos', response.responseText);
                $('#dots-most-played').hide();
            }
        });
    }

    // Evento para o envio do formulário de pesquisa
    $('#search').on('submit', function(e) {
        e.preventDefault(); 
        let query = $('#searchQuery').val().trim();

        if (query) {
            carregarProdutos(1, 0, query); 
        }
    });
});


      </script>
   </body>
</html>