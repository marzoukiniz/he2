

<div class="row">
    {{-- {{$products}} --}}
    @if(count($products) > 0)
        @foreach($products as $product)
                                                                                                                                                                                                                                                                                                        <div class="col-lg-4 col-md-6 col-12">
                <div class="single-product">
                    <div class="product-img">
                        <a href="{{route('product-detail', $product->slug)}}">
                            @php
        $photo = explode(',', $product->photo);
                            @endphp
                            <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                            <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                            @if($product->discount)
                                <span class="price-dec">{{$product->discount}} % Off</span>
                            @endif

                        </a>
                        <div class="button-head">
                            <div class="product-action">
                                <a data-toggle="modal" data-target="#{{$product->id}}" title="Quick View" href="#">

                                    <svg viewBox="-0.5 -0.5 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        id="Eye--Streamline-Iconoir" height="16" width="16">
                                        <desc>Eye Streamline Icon: https://streamlinehq.com</desc>
                                        <path
                                            d="M0.7153750000000001 8.253875c2.713875 -6.0308125 10.855375 -6.0308125 13.56925 0"
                                            stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1">
                                        </path>
                                        <path
                                            d="M7.5 11.26925c-1.2490625 0 -2.2615625 -1.0125000000000002 -2.2615625 -2.2615625s1.0125000000000002 -2.2615 2.2615625 -2.2615 2.2615625 1.0124374999999999 2.2615625 2.2615 -1.0125000000000002 2.2615625 -2.2615625 2.2615625Z"
                                            stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1">
                                        </path>
                                    </svg>
                                    <span>Quick Shop</span></a>
                                <a title="Wishlist" href="{{route('add-to-wishlist', $product->slug)}}" class="wishlist"
                                    data-id="{{$product->id}}">

                                    <svg viewBox="-0.5 -0.5 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        id="Heart--Streamline-Iconoir" height="16" width="16">
                                        <desc>Heart Streamline Icon: https://streamlinehq.com</desc>
                                        <path
                                            d="M14.337187499999999 5.3546249999999995c0 1.057375 -0.40599999999999997 2.073 -1.131 2.8242499999999997 -1.6688749999999999 1.7298749999999998 -3.2876250000000002 3.53375 -5.018875 5.2009375 -0.3968125 0.3765625 -1.0263125 0.3628125 -1.4060625 -0.03075l-4.98775 -5.1701875c-1.507625 -1.5628125000000002 -1.507625 -4.0856875 0 -5.6484375 1.5224375 -1.578125 4.002625 -1.578125 5.525 0l0.181375 0.1879375 0.1811875 -0.1878125c0.7299374999999999 -0.7570625 1.7240625 -1.1840625 2.7625624999999996 -1.1840625s2.0325625 0.4269375 2.7625624999999996 1.1839375c0.7251249999999999 0.7513124999999999 1.131 1.766875 1.131 2.8241875Z"
                                            stroke="#000000" stroke-linejoin="round" stroke-width="1"></path>
                                    </svg>

                                    <span>Add to Wishlist</span></a>
                            </div>

                        </div>
                    </div>
                    <div class="product-content">
                        <h3><a href="{{route('product-detail', $product->slug)}}">{{$product->title}}</a></h3>
                        @php
        $after_discount = ($product->sell_price - ($product->sell_price * $product->discount) / 100);
                        @endphp

                        <div class="variations">
                            @php
        $colors = DB::table('product_colors')
            ->where('product_id', $product->id)
            ->select('color')
            ->distinct()
            ->get();
                            @endphp

                            @if($colors->count())

                                <ul class="color-options">
                                    @foreach($colors as $color)
                                                                                                                                                                                                                                                                                                                                                                                                                                            <li>
                                            <button class="color-btn" data-product="{{ $product->id }}" data-color="{{ $color->color }}"
                                                style="background-color: {{ $color->color }};" onclick="handleColorButtonClick(this)">
                                            </button>
                                        </li>
                                    @endforeach
                                                                                                                                                                                                                                                                                                                                                                                                                                </ul>

                                <!-- Lengths and Stock will be updated dynamically -->
                                <div class="length-stock-container" id="length-stock-{{ $product->id }}"></div>
                            @endif

                        </div>

                        <div class="cta-price">
                            <div class="addtocart-action">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.75 3.25L4.83 3.61L5.793 15.083C5.87 16.02 6.653 16.739 7.593 16.736H18.502C19.399 16.738 20.16 16.078 20.287 15.19L21.236 8.632C21.342 7.899 20.833 7.219 20.101 7.113C20.037 7.104 5.164 7.099 5.164 7.099"
                                        stroke="#191919" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.125 10.7949H16.898" stroke="#191919" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.15435 20.2021C7.45535 20.2021 7.69835 20.4461 7.69835 20.7461C7.69835 21.0471 7.45535 21.2911 7.15435 21.2911C6.85335 21.2911 6.61035 21.0471 6.61035 20.7461C6.61035 20.4461 6.85335 20.2021 7.15435 20.2021Z"
                                        fill="#191919" stroke="#191919" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M18.4346 20.2021C18.7356 20.2021 18.9796 20.4461 18.9796 20.7461C18.9796 21.0471 18.7356 21.2911 18.4346 21.2911C18.1336 21.2911 17.8906 21.0471 17.8906 20.7461C17.8906 20.4461 18.1336 20.2021 18.4346 20.2021Z"
                                        fill="#191919" stroke="#191919" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                                <a title="Add to cart" href="{{route('add-to-cart', $product->slug)}}">Add to cart</a>
                            </div>
                            <div class="price-here">
                                <span>Qar{{number_format($after_discount, 1)}}</span>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else

        <h4 class="text-primary" style="margin:100px auto;">There are no products.</h4>
    @endif




</div>




<!-- Modal -->
@if($products)
        @foreach($products as $key => $product)
                                            <div class="quick-view-modal modal fade" id="{{$product->id}}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close"
                                                        aria-hidden="true"></span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row no-gutters">
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <!-- Product Slider -->
                                                        <div class="product-gallery">
                                                            <div class="quickview-slider-active">

                                                                                </div>
                                                        </div>


            <div class="swiper Slider-container" style="width: 270px; height: 340px;">
                <div class="swiper-wrapper" style="width: 270px; height: 340px;">
                    <div class="swiper-slide" style="width: 270px; height: 340px;">
                        <img src="{{ asset('storage/photos/1/11.png') }}" alt="Slide Image" />
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/photos/1/3.png') }}" alt="Slide Image" />
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/photos/1/11.png') }}" alt="Slide Image" />
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/photos/1/3.png') }}" alt="Slide Image" />
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/photos/1/11.png') }}" alt="Slide Image" />
                    </div>
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/photos/1/3.png') }}" alt="Slide Image" />
                    </div>
                </div>

            </div>

                                                        <!-- End Product slider -->
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="quickview-content">
                                                            <h2>{{$product->title}}</h2>
                                                            <div class="quickview-ratting-review">
                                                                <div class="quickview-ratting-wrap">
                                                                    <div class="quickview-ratting">

                                                                        @php
                    $rate = DB::table('product_reviews')->where('product_id', $product->id)->avg('rate');
                    $rate_count = DB::table('product_reviews')->where('product_id', $product->id)->count();
                                                                        @endphp
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            @if($rate >= $i)
                                                                                <i class="yellow fa fa-star"></i>
                                                                            @else

                                                                                <i class="fa fa-star"></i>
                                                                            @endif

                                                                        @endfor
                                                                    </div>
                                                                    <a href="#"> ({{$rate_count}} customer review)</a>
                                                                </div>
                                                                <div class="quickview-stock">
                                                                    @if($product->stock > 0)
                                                                        <span><i class="fa fa-check-circle-o"></i> {{$product->stock}} in stock</span>
                                                                    @else

                                                                        <span><i class="fa fa-times-circle-o text-danger"></i> {{$product->stock}} out
                                                                            stock</span>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                            @php
                    $after_discount = ($product->sell_price - ($product->sell_price * $product->discount) / 100);
                                                            @endphp
                                                            <h3><small><del class="text-muted">${{number_format($product->sell_price, 2)}}</del></small>
                                                                ${{number_format($after_discount, 2)}} </h3>
                                                            <div class="quickview-peragraph">
                                                                <p>{!! html_entity_decode($product->summary) !!}</p>
                                                            </div>
                                                            @if($product->size)
                                                                                <div class="size">
                                                                                    <h4>Size</h4>
                                                                                    <ul>
                                                                                        @php
                        $sizes = explode(',', $product->size);
                        // dd($sizes);
                                                                                        @endphp
                                                                                        @foreach($sizes as $size)
                                                                                            <li><a href="#" class="one">{{$size}}</a></li>
                                                                                        @endforeach
                                                                                                        </ul>
                                                                                </div>
                                                            @endif

                                                            <div class="size">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-12">
                                                                        <h5 class="title">Size</h5>
                                                                        <select>
                                                                            @php
                    $sizes = explode(',', $product->size);
                    // dd($sizes);
                                                                            @endphp
                                                                            @foreach($sizes as $size)
                                                                                <option>{{$size}}</option>
                                                                            @endforeach
                                                                                        </select>
                                                                    </div>
                                                                    {{-- <div class="col-lg-6 col-12">
                                                                        <h5 class="title">Color</h5>
                                                                        <select>
                                                                            <option selected="selected">orange</option>
                                                                            <option>purple</option>
                                                                            <option>black</option>
                                                                            <option>pink</option>
                                                                        </select>
                                                                    </div> --}}
                                                                </div>
                                                            </div>
                                                            <form action="{{route('single-add-to-cart')}}" method="POST">
                                                                @csrf
                                                                <div class="quantity">
                                                                    <!-- Input Order -->
                                                                    <div class="input-group">
                                                                        <div class="button minus">
                                                                            <button type="button" class="btn btn-primary btn-number" disabled="disabled"
                                                                                data-type="minus" data-field="quant[1]">
                                                                                <i class="ti-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="hidden" name="slug" value="{{$product->slug}}">
                                                                        <input type="text" name="quant[1]" class="input-number" data-min="1"
                                                                            data-max="1000" value="1">
                                                                        <div class="button plus">
                                                                            <button type="button" class="btn btn-primary btn-number" data-type="plus"
                                                                                data-field="quant[1]">
                                                                                <i class="ti-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <!--/ End Input Order -->
                                                                </div>
                                                                <div class="add-to-cart">
                                                                    <button type="submit" class="btn">Add to cart</button>
                                                                    <a href="{{route('add-to-wishlist', $product->slug)}}" class="btn min"><i
                                                                            class="ti-heart"></i></a>
                                                                </div>
                                                            </form>
                                                            <div class="default-social">
                                                                <!-- ShareThis BEGIN -->
                                                                <div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        @endforeach
@endif

<!-- Modal end -->


  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
var swiper = new Swiper('.Slider-container', {
    effect: 'cards',
    grabCursor: true,
    centeredSlides: true,
    loop: true,
});

 
  </script>
