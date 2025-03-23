@extends('backend.layouts.master')

@section('main-content')
<div class="container">
    <h2 class="text-center my-4">Point of Sale (POS)</h2>
    
    <!-- Categories Slider Section -->
    <div class="categories mb-4">
        <h4>Categories</h4>
        <!-- Container for the slider with fixed height -->
        <div class="category-slider-container" style="position: relative; height: 74px;">
            <!-- Swiper Container -->
            <div class="swiper-container category-slider" style="height: 100%;">
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                        <div class="swiper-slide">
                            <a href="{{ route('pos.index', ['category' => $category->id]) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                {{ $category->title }}
                            </a>
                        </div>
                    @endforeach
                    <!-- All link slide -->
                    <div class="swiper-slide">
                        <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary btn-sm">
                            All
                        </a>
                    </div>
                </div>
            </div>
            <!-- Navigation arrows positioned outside the slides -->
            <div class="swiper-button-prev" style="position: absolute; top: 50%; left: -2%; transform: translate(-50%, -50%); z-index: 10;"></div>
            <div class="swiper-button-next" style="position: absolute; top: 50%; right: 0; transform: translate(50%, -50%); z-index: 10;"></div>
        </div>
    </div>
    
    <!-- POS Grid -->
    <div class="row">
        @foreach ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card text-center">
                    <img src="{{ explode(',', $product->photo)[0] }}" 
                         class="card-img-top" 
                         alt="{{ $product->title }}" 
                         style="width: 100px; height: 100px; object-fit: cover; margin: 0 auto;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text">QAR {{ number_format($product->price - ($product->price * $product->discount) / 100, 2) }}</p>
                        <div class="quantity-controls">
                            <button class="btn btn-sm btn-danger decrease-qty" data-id="{{ $product->id }}">-</button>
                            <input type="number" class="form-control text-center quantity" 
                                   id="qty-{{ $product->id }}" value="1" min="1" 
                                   style="width: 60px; display: inline-block;">
                            <button class="btn btn-sm btn-success increase-qty" data-id="{{ $product->id }}">+</button>
                        </div>
                        <button class="btn btn-primary mt-2 add-to-cart" 
                                data-id="{{ $product->id }}" 
                                data-name="{{ $product->title }}" 
                                data-price="{{ $product->price }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- POS Cart -->
    <div class="pos-cart mt-4">
        <h3>Cart</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                <!-- Cart items will be added dynamically -->
            </tbody>
        </table>
        <h4 class="text-right">Total: <span id="cart-total">QAR 0.00</span></h4>
        <button class="btn btn-success btn-lg float-right mt-3" id="checkout-btn">Checkout</button>
    </div>
</div>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Swiper for categories slider with 7 slides per view
    var categorySlider = new Swiper('.category-slider', {
        slidesPerView: 6,
        spaceBetween: 4,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        // Responsive breakpoints (optional)
        breakpoints: {
            640: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 5,
                spaceBetween: 10,
            },
            1024: {
                slidesPerView: 6,
                spaceBetween: 4,
            },
        }
    });
    
    let cart = [];

    function updateCart() {
        let cartTable = document.getElementById("cart-items");
        cartTable.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            let row = `<tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>QAR ${item.price.toFixed(2)}</td>
                <td>QAR ${(item.price * item.quantity).toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm remove-item" data-index="${index}">Remove</button></td>
            </tr>`;
            cartTable.innerHTML += row;
            total += item.price * item.quantity;
        });

        document.getElementById("cart-total").textContent = `QAR ${total.toFixed(2)}`;
    }

    // Increase quantity button listener
    document.querySelectorAll(".increase-qty").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let input = document.getElementById(`qty-${id}`);
            let currentVal = parseInt(input.value) || 1;
            input.value = currentVal + 1;
        });
    });

    // Decrease quantity button listener
    document.querySelectorAll(".decrease-qty").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let input = document.getElementById(`qty-${id}`);
            let currentVal = parseInt(input.value) || 1;
            if (currentVal > 1) {
                input.value = currentVal - 1;
            }
        });
    });

    // Add to cart button listener
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let name = this.getAttribute("data-name");
            let price = parseFloat(this.getAttribute("data-price"));
            let quantity = parseInt(document.getElementById(`qty-${id}`).value);

            let existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({ id, name, price, quantity });
            }
            updateCart();
        });
    });

    // Remove item listener for the cart
    document.getElementById("cart-items").addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-item")) {
            let index = event.target.getAttribute("data-index");
            cart.splice(index, 1);
            updateCart();
        }
    });

    // Checkout button listener
    document.getElementById("checkout-btn").addEventListener("click", function() {
        alert("Proceeding to checkout...");
        // Implement AJAX request to send cart data to backend
    });
});
</script>
@endsection
