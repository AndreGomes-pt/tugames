const baseUrl = $('body').data('base-url');
let cartData = [];

// Função para buscar os dados do carrinho
function fetchCartData() {
    $.ajax({
        url: baseUrl + 'assets/php/get_cart.php', 
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.error) {
                console.error('Erro no servidor:', response.error);
                return;
            }
            cartData = response; 
            updateCartUI(); 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Erro ao buscar dados do carrinho:', textStatus, errorThrown);
        }
    });
}

// Função genérica para alterar a quantidade de itens no carrinho
function changeQuantity(productId, context, delta, event = null) {
    if (event) event.stopPropagation();
    
    const quantityInput = document.getElementById(`${productId}${context}`);
    let currentQuantity = parseInt(quantityInput.value);
    
    const newQuantity = Math.max(currentQuantity + delta, 1);
    quantityInput.value = newQuantity;
    
    updateCart(productId, newQuantity);
}

// Função para remover item do carrinho
function removeItem(productId, context, event = null) {
    if (event) event.stopPropagation();
    
    document.getElementById(`${productId}${context}`).closest('.list-group-item').remove();
    updateCart(productId, 0);
}

// Função para verificar se o carrinho está vazio e ajustar a interface
function checkIfCartIsEmpty() {
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const cartItemCount = document.getElementById('cartItemCount');
    const cartCheckOut = document.getElementById('checkOutBtn');
    const seeFullCart = document.getElementById('seeFullCart');
    
    const cartIsEmpty = cartData.length === 0;
    emptyCartMessage.style.display = cartIsEmpty ? 'block' : 'none';
    cartItemCount.style.display = cartIsEmpty ? 'none' : 'inline-block';
    cartCheckOut.style.display = cartIsEmpty ? 'none' : 'block';
    seeFullCart.style.display = cartIsEmpty ? 'none' : 'block';

    if (!cartIsEmpty) {
        cartItemCount.innerText = cartData.length;
    }
}

// Função para atualizar o carrinho via AJAX
async function updateCart(cartId, newQuantity) {
    try {
        const response = await $.ajax({
            url: baseUrl + 'assets/php/update_cart.php',
            type: 'POST',
            data: { cartId: cartId, newQuantity: newQuantity },
            dataType: 'json',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8'
        });

        if (response.success) {
            cartData = cartData.filter(item => item.id !== cartId || newQuantity > 0);
            fetchCartData();
        } else {
            console.error('Erro ao atualizar carrinho:', response.message);
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: response.message, 
                confirmButtonText: 'Ok'
            });
            fetchCartData();
        }
    } catch (error) {
        console.error('Erro na requisição AJAX:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Erro ao atualizar o carrinho. Tente novamente.',
            confirmButtonText: 'Ok'
        });
        fetchCartData();
    }
}



// Função para calcular e exibir o preço total
function updateTotalPrice() {
    const total = cartData.reduce((acc, item) => acc + (item.price * item.quantity), 0);
    
    document.querySelectorAll('.totalPrice').forEach(element => {
        element.textContent = `Total: ${total.toFixed(2)} €`;
    });
}

// Função para construir o HTML de itens do carrinho (dropdown e modal)
function buildCartItemHTML(item, context) {
    // Verifica se a imagem do produto existe
    const imgSrc = item.img && item.img.trim() !== '' 
                   ? `${baseUrl}assets/img/produtos/${item.img}` 
                   : `${baseUrl}assets/img/produtos/placeholder.png`; 

    return `
    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <img src="${imgSrc}" alt="${item.name}" class="img-fluid rounded" style="width: 30px; height: 30px;">
            <div class="ml-2">
                <h6 class="mb-0" style="white-space: normal;">${item.name}</h6>
                <small class="text-muted">${item.price} €</small>
            </div>
        </div>
        <div class="d-flex align-items-center mt-2 mt-md-0">
            <button class="btn btn-outline-secondary btn-sm mr-2" onclick="changeQuantity('${item.id}', '${context}', -1, event)">
                <i class="fas fa-minus"></i>
            </button>
            <input type="number" id="${item.id}${context}" value="${item.quantity}" min="1" class="form-control form-control-sm" style="width: 50px; text-align: center;" disabled>
            <button class="btn btn-outline-secondary btn-sm ml-2" onclick="changeQuantity('${item.id}', '${context}', 1, event)">
                <i class="fas fa-plus"></i>
            </button>
            <button class="btn btn-danger btn-sm ml-3" onclick="removeItem('${item.id}', '${context}', event)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </li>
    `;
}


// Função para atualizar a interface do carrinho (dropdown e modal)
function updateCartUI() {
    const cartDropdownItems = document.getElementById('cartDropdownItems');
    const cartModalItems = document.getElementById('cartModalItems');

    cartDropdownItems.innerHTML = '';
    cartModalItems.innerHTML = '';

    // Preenche os itens do dropdown (limitado a 3 itens) e modal (todos os itens)
    cartData.slice(0, 3).forEach(item => {
        cartDropdownItems.innerHTML += buildCartItemHTML(item, 'Dropdown');
    });
    cartData.forEach(item => {
        cartModalItems.innerHTML += buildCartItemHTML(item, 'Modal');
    });

    updateTotalPrice();
    checkIfCartIsEmpty();
}

// Inicializa a interface ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    const cartElement = document.getElementById('navbarDropdownCart');

    if (cartElement) {
        fetchCartData();
    }
});

