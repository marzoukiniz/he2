@extends('frontend.layouts.master')
@section('title','HE-SHOP || HOME PAGE')
@section('main-content')
<!-- Slider Area -->
<div id="cursor"></div>
@if(count($banners)>0)
    <section id="Gslider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($banners as $key=>$banner)
        <li data-target="#Gslider" data-slide-to="{{$key}}" class="{{(($key==0)? 'active' : '')}}"></li>
            @endforeach

        </ol>
        <div class="carousel-inner" role="listbox">
                @foreach($banners as $key=>$banner)
                <div class="carousel-item {{(($key==0)? 'active' : '')}}">
                @php
                                        $photo=explode(',',$banner->photo);
                                    
                                    @endphp
                    <img class="first-slide" src="{{$banner->photo}}" alt="First slide">
                    <div class="carousel-caption d-none d-md-block ">
                        <h1 class="wow fadeInDown">{{$banner->title}}</h1>
                        <p>{!! html_entity_decode($banner->description) !!}</p>
                        <a class="btn btn-lg ws-btn wow fadeInUpBig" href="{{route('product-grids')}}" role="button">Shop Now<i class="far fa-arrow-alt-circle-right"></i></i></a>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#Gslider" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#Gslider" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
        </a>
    </section>
@endif

<!--/ End Slider Area -->

<!-- Start Product Area -->
<div class="product-area section">
        <div class="container">
        <div class="section-header">
            <h2>LATEST</h2>
            <h3>ARRIVALS</h3>
        </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-info">
                        <div class="nav-main">
                            <!-- Tab Nav -->
                            <ul class="nav nav-tabs filter-tope-group" id="myTab" role="tablist">
                                @php
                                    $categories=DB::table('categories')->where('status','active')->where('is_parent',1)->get();
                                    // dd($categories);
                                @endphp
                                @if($categories)
                                <button class=" btn-item"  data-filter="*">
                                    All Products
                                </button>
                                    @foreach($categories as $key=>$cat)

                                    <button class=" btn-item"  data-filter=".{{$cat->id}}">
                                        {{$cat->title}}
                                    </button>
                                    @endforeach
                                @endif
                            </ul>
                            <!--/ End Tab Nav -->
                        </div>
                        
                        <div class="tab-content isotope-grid" id="myTabContent">
    @if($product_lists)
        @foreach($product_lists as $key=>$product)
        <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{$product->cat_id}}">
            <div class="single-product">
                <div class="product-img">
                    <a href="{{ route('product-detail', $product->slug) }}">
                        @php
                            $photo = explode(',', $product->photo);
                        @endphp
                        <img class="default-img" src="{{ $photo[0] }}" alt="{{ $product->title }}">
                        <img class="hover-img" src="{{ $photo[0] }}" alt="{{ $product->title }}">
                        
                        @if($product->stock <= 0)
                            <span class="out-of-stock">Sale out</span>
                        @elseif($product->condition == 'new')
                            <span class="new">New</span>
                        @elseif($product->condition == 'new')
                            <span class="hot">Hot</span>
                        @else
                            <span class="price-dec">{{ $product->discount }}% Off</span>
                        @endif
                    </a>
                    <div class="button-head">
                        <div class="product-action">
                            <a data-toggle="modal" data-target="#{{ $product->id }}" title="Quick View" href="#">
                                <i class="ti-eye"></i><span>Quick Shop</span>
                            </a>
                            <a title="Wishlist" href="{{ route('add-to-wishlist', $product->slug) }}">
                                <i class="ti-heart"></i><span>Add to Wishlist</span>
                            </a>
                        </div>
                        <div class="product-action-2">
                            <a title="Add to cart" href="{{ route('add-to-cart', $product->slug) }}">Add to cart</a>
                        </div>
                    </div>
                </div>
                <div class="product-content">
                    <h3><a href="{{ route('product-detail', $product->slug) }}">{{ $product->title }}</a></h3>
                    <div class="product-price">
                        @php
                            $after_discount = ($product->price - ($product->price * $product->discount) / 100);
                        @endphp
                        <span>Qar {{ number_format($after_discount, 2) }}</span>
                        <del style="padding-left:4%;">Qar {{ number_format($product->price, 2) }}</del>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>


                    </div>
                </div>
            </div>
        </div>
</div>
<!-- End Product Area -->

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
<!-- collection style2 -->
<div class="container coll2">
    <div class="row">
        <div class="section-header col-12">
            <h2>EXPLORE</h2>
            <h3>COLLECTIONS</h3>
        </div>

        <div class="col-md-6 col-lg-3 mb-4 n-c">
            <div class="news-card jsFollowerContainer">
                <div class="img-here jsTopProjects">
                    <img src="{{ asset('backend/img/1.png') }}" alt="Ministry of Justice" class="img-fluid">
                </div>
                <div class="news-content">
                    <h3>Summer Extensions</h3>
                    <p>These summer extensions pull out all the stops.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4 n-c">
            <div class="news-card jsFollowerContainer">
                <div class="img-here jsTopProjects">
                    <img src="{{ asset('backend/img/1.png') }}" alt="Ministry of Justice" class="img-fluid">
                </div>
                <div class="news-content">
                    <h3>Summer Extensions</h3>
                    <p>These summer extensions pull out all the stops.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4 n-c">
            <div class="news-card jsFollowerContainer">
                <div class="img-here jsTopProjects">
                    <img src="{{ asset('backend/img/1.png') }}" alt="Ministry of Justice" class="img-fluid">
                </div>
                <div class="news-content">
                    <h3>Summer Extensions</h3>
                    <p>These summer extensions pull out all the stops.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4 n-c">
            <div class="news-card jsFollowerContainer">
                <div class="img-here jsTopProjects">
                    <img src="{{ asset('backend/img/1.png') }}" alt="Ministry of Justice" class="img-fluid">
                </div>
                <div class="news-content">
                    <h3>Summer Extensions</h3>
                    <p>These summer extensions pull out all the stops.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- collection section ends here -->






<!-- Start top seller -->
<div class="product-area most-popular section">
    <div class="container">
        <div class="row">
            <div class="section-header">
                 
                    <h2>Top </h2>
                    <h3>Seller</h3>
                 
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel popular-slider">
                    @foreach($product_lists as $product)
                        @if($product->condition=='hot')
                            <!-- Start Single Product -->
                        <div class="single-product">
                            <div class="product-img">
                                <a href="{{route('product-detail',$product->slug)}}">
                                    @php
                                        $photo=explode(',',$product->photo);
                                    // dd($photo);
                                    @endphp
                                    <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                    <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                    {{-- <span class="out-of-stock">Hot</span> --}}
                                </a>
                                <div class="button-head">
                                    <div class="product-action">
                                        <a data-toggle="modal" data-target="#{{$product->id}}" title="Quick View" href="#"><i class=" ti-eye"></i><span>Quick Shop</span></a>
                                        <a title="Wishlist" href="{{route('add-to-wishlist',$product->slug)}}" ><i class=" ti-heart "></i><span>Add to Wishlist</span></a>
                                    </div>
                                    <div class="product-action-2">
                                        <a href="{{route('add-to-cart',$product->slug)}}">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
                                <div class="product-price">
                                    <span class="old">Qar {{number_format($product->price,2)}}</span>
                                    @php
                                    $after_discount=($product->price-($product->price*$product->discount)/100)
                                    @endphp
                                    <span>Qar {{number_format($after_discount,2)}}</span>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Product -->
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End top seller  Area -->
<!-- Start Brands -->
<div class="product-area most-popular section">
    <div class="container">
        <div class="row">
            <div class="section-header">
                 
                    <h2>Top </h2>
                    <h3>Brands</h3>
                 
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel popular-slider">
                    @foreach($brands as $brand)
                        
                            <!-- Start Single Product -->
                        <div class="single-product">
                            <div class="product-img"> <a href="#"><h3>{{$brand->title}}</h3> </a></div>
                             
                        </div>
                        <!-- End Single Product -->
                      
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Brands  Area -->
<!-- Modal -->
@if($product_lists)
    @foreach($product_lists as $key=>$product)
        <div class="modal fade" id="{{$product->id}}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row no-gutters">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <!-- Product Slider -->
                                        <div class="product-gallery">
                                            <div class="quickview-slider-active">
                                                @php
                                                    $photo=explode(',',$product->photo);
                                                // dd($photo);
                                                @endphp
                                                @foreach($photo as $data)
                                                    <div class="single-slider">
                                                        <img src="{{$data}}" alt="{{$data}}">
                                                    </div>
                                                @endforeach
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
                                                    {{-- <i class="yellow fa fa-star"></i>
                                                    <i class="yellow fa fa-star"></i>
                                                    <i class="yellow fa fa-star"></i>
                                                    <i class="yellow fa fa-star"></i>
                                                    <i class="fa fa-star"></i> --}}
                                                    @php
                                                        $rate=DB::table('product_reviews')->where('product_id',$product->id)->avg('rate');
                                                        $rate_count=DB::table('product_reviews')->where('product_id',$product->id)->count();
                                                    @endphp
                                                    @for($i=1; $i<=5; $i++)
                                                        @if($rate>=$i)
                                                            <i class="yellow fa fa-star"></i>
                                                        @else
                                                        <i class="fa fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <a href="#"> ({{$rate_count}} customer review)</a>
                                            </div>
                                            <div class="quickview-stock">
                                                @if($product->stock >0)
                                                <span><i class="fa fa-check-circle-o"></i> {{$product->stock}} in stock</span>
                                                @else
                                                <span><i class="fa fa-times-circle-o text-danger"></i> {{$product->stock}} out stock</span>
                                                @endif
                                            </div>
                                        </div>
                                        @php
                                            $after_discount=($product->price-($product->price*$product->discount)/100);
                                        @endphp
                                        <h3><small><del class="text-muted">Qar {{number_format($product->price,2)}}</del></small>    ${{number_format($after_discount,2)}}  </h3>
                                        <div class="quickview-peragraph">
                                            <p>{!! html_entity_decode($product->summary) !!}</p>
                                        </div>
                                        @if($product->size)
                                            <div class="size">
                                                <div class="row">
                                                    <div class="col-lg-6 col-12">
                                                        <h5 class="title">Size</h5>
                                                        <select>
                                                            @php
                                                            $sizes=explode(',',$product->size);
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
                                        @endif
                                        <form action="{{route('single-add-to-cart')}}" method="POST" class="mt-4">
                                            @csrf
                                            <div class="quantity">
                                                <!-- Input Order -->
                                                <div class="input-group">
                                                    <div class="button minus">
                                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                                                            <i class="ti-minus"></i>
                                                        </button>
                                                    </div>
													<input type="hidden" name="slug" value="{{$product->slug}}">
                                                    <input type="text" name="quant[1]" class="input-number"  data-min="1" data-max="1000" value="1">
                                                    <div class="button plus">
                                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!--/ End Input Order -->
                                            </div>
                                            <div class="add-to-cart">
                                                <button type="submit" class="btn">Add to cart</button>
                                                <a href="{{route('add-to-wishlist',$product->slug)}}" class="btn min"><i class="ti-heart"></i></a>
                                            </div>
                                        </form>
                                        <div class="default-social">
                                        <!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->
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
@endsection

 
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.0/vanilla-tilt.min.js"></script>

    <script>

        /*==================================================================
        [ Isotope ]*/
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function () {
            $filter.on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({filter: filterValue});
            });

        });

        // init Isotope
        $(window).on('load', function () {
            var $grid = $topeContainer.each(function () {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine : 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function(){
            $(this).on('click', function(){
                for(var i=0; i<isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
    </script>
    <script>
         function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
// collection animation 
// projects cursor
function cursor() {
    const cursor = document.getElementById('cursor');

    document.addEventListener('mousemove', function (e) {
        cursor.style.transform = 'translate(' + e.clientX + 'px, ' + e.clientY + 'px)';
    });

    const setAction = document.querySelectorAll('.jsFollowerContainer');
    for (let i = 0; i < setAction.length; i++) {
        setAction[i].addEventListener('mouseover', function (e) {
            cursor.classList.add('is-hover');
        });
        setAction[i].addEventListener('mouseout', function (e) {
            cursor.classList.remove('is-hover');
        });
    }
}
VanillaTilt.init(document.querySelectorAll(".jsTopProjects"), {
            max: 10,
            speed: 2500,
            scale: 1.2,
            perspective: 1000,
        });
        cursor();
// collection animation end

    </script>

@endpush
