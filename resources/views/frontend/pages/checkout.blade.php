@extends('frontend.layouts.master')

@section('title','Checkout page')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
            
    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
                <form class="form" method="POST" action="{{route('cart.order')}}">
                    @csrf
                    <div class="row"> 

                        <div class="col-lg-8 col-12">
                            <div class="checkout-form">
                                <h2>Make Your Checkout Here</h2>
                                <p>Please register in order to checkout more quickly</p>
                                <!-- Form -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>First Name<span>*</span></label>
                                            <input type="text" name="first_name" placeholder="" value="{{old('first_name')}}" value="{{old('first_name')}}">
                                            @error('first_name')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Last Name<span>*</span></label>
                                            <input type="text" name="last_name" placeholder="" value="{{old('lat_name')}}">
                                            @error('last_name')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Email Address<span>*</span></label>
                                            <input type="email" name="email" placeholder="" value="{{old('email')}}">
                                            @error('email')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12" >
                                        <div class="form-group">
                                            <label>Phone Number <span>*</span></label>
                                            <input type="number" name="phone" placeholder="" required value="{{old('phone')}}">
                                            @error('phone')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12" hidden>
                                            <div class="form-group">
                                                <label>Country<span>*</span></label>
                                                <input type="text" name="country" id="country" placeholder="" required value="Qatar" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="form-group">
                                            <label>Shipping Cost<span>*</span></label>
                                        <li class="shipping">
                                                
                                                @if(count(Helper::shipping()) > 0 && Helper::cartCount() > 0)
                                                    <select name="shipping" class="nice-select" id="shippingSelect" required>
                                                        <option value="">Select your address</option>
                                                        @foreach(Helper::shipping() as $shipping)
                                                            <option value="{{$shipping->id}}" class="shippingOption" data-price="{{$shipping->price}}">
                                                                {{$shipping->type}}: ${{$shipping->price}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span id="shippingError" style="color: red; display: none;">Please select a shipping option.</span>
                                                @else 
                                                    <span>Free</span>
                                                @endif
                                            </li>
                                            </div>
                                            </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Address Line 1<span>*</span></label>
                                            <input type="text" name="address1" placeholder="" value="{{old('address1')}}">
                                            @error('address1')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                 
                                    
                                </div>
                                <!--/ End Form -->
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="order-details">
                                <!-- Order Widget -->
                                <div class="single-widget">
                                    <h2>CART  TOTALS</h2>
                                    <div class="content">
                                        <ul>
										    <li class="order_subtotal" data-price="{{Helper::totalCartPrice()}}">Cart Subtotal<span>${{number_format(Helper::totalCartPrice(),2)}}</span></li>
                                    

                                            
                                            @if(session('coupon'))
                                            <li class="coupon_price" data-price="{{session('coupon')['value']}}">You Save<span>${{number_format(session('coupon')['value'],2)}}</span></li>
                                            @endif
                                            @php
                                                $total_amount=Helper::totalCartPrice();
                                                if(session('coupon')){
                                                    $total_amount=$total_amount-session('coupon')['value'];
                                                }
                                            @endphp
                                            @if(session('coupon'))
                                                <li class="last"  id="order_total_price">Total<span>${{number_format($total_amount,2)}}</span></li>
                                            @else
                                                <li class="last"  id="order_total_price">Total<span>${{number_format($total_amount,2)}}</span></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <!--/ End Order Widget -->
                                <!-- Order Widget -->
                                <div class="single-widget">
                                    <h2>Payments</h2>
                                    <div class="content">
                                        <div class="checkbox">
                                            {{-- <label class="checkbox-inline" for="1"><input name="updates" id="1" type="checkbox"> Check Payments</label> --}}
                                            <form-group>
                                                <input name="payment_method"  type="radio" value="cod"> <label> Cash On Delivery</label><br>
                                               
                                            </form-group>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Order Widget -->
                                <!-- Payment Method Widget -->
                               
                                <!--/ End Payment Method Widget -->
                                <!-- Button Widget -->
                                <div class="single-widget get-button">
                                    <div class="content">
                                        <div class="button">
                                            <button type="submit" class="btn btn-primary">proceed to checkout</button>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Button Widget -->
                            </div>
                        </div>
                    </div>
                </form>
        </div>
    </section>
    <!--/ End Checkout -->
    
   <!-- Start Shop Services Area -->
<section class="shop-services section home">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <div class="serv-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" id="Acute--Streamline-Outlined-Material" height="24" width="24"><desc>Acute Streamline Icon: https://streamlinehq.com</desc><g id="acute"><path id="Vector" d="M15 20c-2.23335 0 -4.125 -0.775 -5.675 -2.325C7.775 16.125 7 14.23335 7 12c0 -2.23335 0.775 -4.125 2.325 -5.675C10.875 4.775 12.76665 4 15 4c2.23335 0 4.125 0.775 5.675 2.325C22.225 7.875 23 9.76665 23 12c0 2.23335 -0.775 4.125 -2.325 5.675C19.125 19.225 17.23335 20 15 20Zm-0.006 -1.5c1.804 0 3.33935 -0.63135 4.606 -1.894 1.26665 -1.26285 1.9 -2.79615 1.9 -4.6 0 -1.804 -0.63135 -3.33935 -1.894 -4.606 -1.26285 -1.26665 -2.79615 -1.9 -4.6 -1.9 -1.804 0 -3.33935 0.63135 -4.606 1.894 -1.26665 1.26285 -1.9 2.79615 -1.9 4.6 0 1.804 0.63135 3.33935 1.894 4.606 1.26285 1.26665 2.79615 1.9 4.6 1.9Zm2.556 -2.925 1.05 -1.05 -2.85 -2.85V8h-1.5v4.3l3.3 3.275ZM2 8.75v-1.5h4v1.5H2Zm-1 4v-1.5h5v1.5H1Zm1 4v-1.5h4v1.5H2Z" fill="#000000" stroke-width="0.5"></path></g></svg>
                    </div>
                    <div class="serv-desc">
                        <h4 class="title">Fast delivery</h4>
                        <p class="details">Orders over $100</p>
                    </div>
                </div>
                <!-- End Single Service -->
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <div class="serv-icon">
                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" id="Local-Convenience-Store--Streamline-Outlined-Material" height="24" width="24"><desc>Local Convenience Store Streamline Icon: https://streamlinehq.com</desc><path fill="#000000" d="M7.95 18.125h2.95v-0.95h-2v-1.15h2v-2.95h-2.95v0.95h2v1.05h-2v3.05Zm7.15 0h0.95v-5.05h-0.95v2h-1.05v-2h-0.95v2.95h2v2.1ZM21 11.025V19.5c0 0.4 -0.15 0.75 -0.45 1.05 -0.3 0.3 -0.65 0.45 -1.05 0.45H4.47498c-0.4 0 -0.75 -0.15 -1.05 -0.45 -0.3 -0.3 -0.45 -0.65 -0.45 -1.05V11.025c-0.466665 -0.4 -0.775 -0.89165 -0.925 -1.475 -0.15 -0.58335 -0.133335 -1.16665 0.05 -1.75l1.075 -3.375c0.133335 -0.45 0.366665 -0.8 0.7 -1.05C4.208315 3.125 4.591645 3 5.025 3H18.85c0.46665 0 0.875 0.129165 1.225 0.3875 0.35 0.258335 0.59165 0.604165 0.725 1.0375l1.1 3.375c0.1833 0.58335 0.1958 1.16665 0.0375 1.75 -0.15835 0.58335 -0.47085 1.075 -0.9375 1.475ZM14.25 10.25c0.4833 0 0.89165 -0.15835 1.225 -0.475 0.3333 -0.31665 0.46665 -0.7 0.4 -1.15L15.25 4.5h-2.5v4.125c0 0.43335 0.14165 0.8125 0.425 1.1375 0.2833 0.325 0.64165 0.4875 1.075 0.4875Zm-4.675 0c0.46665 0 0.8625 -0.15835 1.1875 -0.475 0.325 -0.31665 0.4875 -0.7 0.4875 -1.15V4.5h-2.5l-0.625 4.125c-0.0667 0.43335 0.05 0.8125 0.35 1.1375 0.3 0.325 0.66665 0.4875 1.1 0.4875Zm-4.55 0c0.4 0 0.7458 -0.1375 1.0375 -0.4125 0.29165 -0.275 0.4625 -0.6125 0.5125 -1.0125L7.225 4.5H4.72498l-1.15 3.65c-0.166665 0.51665 -0.1 0.99585 0.2 1.4375 0.3 0.44165 0.716665 0.6625 1.25002 0.6625Zm13.925 0c0.5333 0 0.95415 -0.21665 1.2625 -0.65 0.3083 -0.43335 0.37915 -0.91665 0.2125 -1.45L19.275 4.5h-2.5l0.65 4.325c0.05 0.4 0.2208 0.7375 0.5125 1.0125 0.29165 0.275 0.62915 0.4125 1.0125 0.4125ZM4.47498 19.5H19.5V11.725c0.01665 0.01665 -0.0375 0.025 -0.1625 0.025H18.95c-0.4167 0 -0.8125 -0.0875 -1.1875 -0.2625 -0.375 -0.175 -0.74585 -0.44585 -1.1125 -0.8125 -0.2667 0.33335 -0.6 0.59585 -1 0.7875s-0.8417 0.2875 -1.325 0.2875c-0.5 0 -0.9292 -0.07085 -1.2875 -0.2125 -0.35835 -0.14165 -0.7042 -0.37915 -1.0375 -0.7125 -0.25 0.3 -0.5667 0.52915 -0.95 0.6875 -0.38335 0.15835 -0.8167 0.2375 -1.3 0.2375 -0.5167 0 -0.975 -0.09165 -1.375 -0.275s-0.7417 -0.45 -1.025 -0.8c-0.4 0.35 -0.7917 0.61665 -1.175 0.8 -0.38335 0.18335 -0.7667 0.275 -1.15 0.275h-0.33752c-0.108335 0 -0.179165 -0.00835 -0.2125 -0.025V19.5Z" stroke-width="0.5"></path></svg>
                    </div>
                    <div class="serv-desc">
                        <h4 class="title">24 X 7 service</h4>
                        <p class="details">Online service</p>
                    </div>
                </div>
                <!-- End Single Service -->
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <div class="serv-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" id="Heap-Snapshot-Thumbnail--Streamline-Outlined-Material" height="24" width="24"><desc>Heap Snapshot Thumbnail Streamline Icon: https://streamlinehq.com</desc><path fill="#000000" d="M2.5 19c-0.4125 0 -0.765585 -0.1469 -1.05925 -0.44075C1.146915 18.2656 1 17.9125 1 17.5V6.5c0 -0.4125 0.146915 -0.76565 0.44075 -1.0595C1.734415 5.14685 2.0875 5 2.5 5h11c0.4125 0 0.76565 0.14685 1.0595 0.4405 0.29365 0.29385 0.4405 0.647 0.4405 1.0595v11c0 0.4125 -0.14685 0.7656 -0.4405 1.05925C14.26565 18.8531 13.9125 19 13.5 19H2.5Zm15.5 -8c-0.28335 0 -0.52085 -0.09585 -0.7125 -0.2875S17 10.28335 17 10v-4c0 -0.28335 0.09585 -0.52085 0.2875 -0.7125S17.71665 5 18 5h4c0.28335 0 0.52085 0.09585 0.7125 0.2875S23 5.71665 23 6v4c0 0.28335 -0.09585 0.52085 -0.2875 0.7125S22.28335 11 22 11h-4Zm0.5 -1.5h3v-3h-3v3ZM2.5 17.5h11V6.5H2.5v11Zm15.5 1.5c-0.28335 0 -0.52085 -0.09585 -0.7125 -0.2875S17 18.28335 17 18v-4c0 -0.28335 0.09585 -0.52085 0.2875 -0.7125S17.71665 13 18 13h4c0.28335 0 0.52085 0.09585 0.7125 0.2875S23 13.71665 23 14v4c0 0.28335 -0.09585 0.52085 -0.2875 0.7125S22.28335 19 22 19h-4Zm0.5 -1.5h3v-3h-3v3Zm-7.9875 -1.75c0.34165 0 0.63335 -0.125 0.875 -0.375 0.24165 -0.25 0.3625 -0.54585 0.3625 -0.8875s-0.1215 -0.63335 -0.3645 -0.875c-0.24315 -0.24165 -0.53835 -0.3625 -0.8855 -0.3625 -0.33335 0 -0.625 0.1215 -0.875 0.3645 -0.25 0.24315 -0.375 0.53835 -0.375 0.8855 0 0.33335 0.125 0.625 0.375 0.875s0.54585 0.375 0.8875 0.375ZM5.45 15.6l6.15 -6.15 -1.05 -1.05L4.4 14.55l1.05 1.05Zm0.0625 -4.85c0.34165 0 0.63335 -0.125 0.875 -0.375 0.24165 -0.25 0.3625 -0.54585 0.3625 -0.8875s-0.1215 -0.63335 -0.3645 -0.875c-0.24315 -0.24165 -0.53835 -0.3625 -0.8855 -0.3625 -0.33335 0 -0.625 0.1215 -0.875 0.3645 -0.25 0.24315 -0.375 0.53835 -0.375 0.8855 0 0.33335 0.125 0.625 0.375 0.875s0.54585 0.375 0.8875 0.375Z" stroke-width="0.5"></path></svg>
                    </div>
                    <div class="serv-desc">
                        <h4 class="title">Specal offers</h4>
                        <p class="details">New online special offers</p>
                    </div>
                </div>
                <!-- End Single Service -->
            </div>
        </div>
    </div>
</section>
<!-- End Shop Services Area -->
    
  
@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
        .list{
            top:30px!important;
        }
		.list li:hover{
			background:#EBCDBF !important;
			color:black !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		function showMe(box){
			var checkbox=document.getElementById('shipping').style.display;
			// alert(checkbox);
			var vis= 'none';
			if(checkbox=="none"){
				vis='block';
			}
			if(checkbox=="block"){
				vis="none";
			}
			document.getElementById(box).style.display=vis;
		}
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') ); 
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0; 
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>

@endpush