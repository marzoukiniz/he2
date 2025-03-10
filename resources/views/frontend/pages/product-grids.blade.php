@extends('frontend.layouts.master')

@section('title','HE-SHOP || PRODUCT PAGE')

@section('main-content')
	<!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="blog-single.html">Shop Grid</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
 

  

<!-- Color Filter -->

 
<main style="transform: none; margin-bottom: 389.991px;">
	    <div class="container shop-page-container margin_30" style="transform: none;">
	        <div class="row" style="transform: none;">
	            <aside class="col-lg-3" id="sidebar_fixed" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
	                
	            <div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static; transform: none; top: 0px; left: 128.429px;"><div class="filter_col">
	                    <div class="inner_bt"><a href="#" class="open_filters"><i class="ti-close"></i></a></div>
	                    <div class="filter_type version_2">
	                        <div class="filter-title"><a href="#filter_1" data-bs-toggle="collapse" class="opened">Colors</a>
                            <svg  class="SvgCaret" viewBox="0 0 24 24" fill="currentColor"><path d="M11.9999 13.1714L16.9497 8.22168L18.3639 9.63589L11.9999 15.9999L5.63599 9.63589L7.0502 8.22168L11.9999 13.1714Z"></path></svg>
                        
                        </div>
	                        <div class="collapse show" id="filter_1">
                            @csrf
                            <div class="form-group">
    <div class="d-flex flex-wrap">
    @foreach ($colors as $color)
    @if (!is_null($color) && $color !== '')  {{-- Ensure color is not null or empty --}}
        <div class="form-check me-2">
            <input type="checkbox" id="color-{{ $loop->index }}" 
                   name="color[]" 
                   value="{{ $color }}" 
                   class="form-check-input filter-option color-filter"
                   {{ in_array($color, request('color', [])) ? 'checked' : '' }}>
            <label for="color-{{ $loop->index }}" class="form-check-label">
                {{ ucfirst($color) }}
            </label>
        </div>
    @endif
@endforeach

    </div>
</div>

	                        </div>
	                        <!-- /filter_type -->
	                    </div>
	                    <!-- /filter_type -->
	                    <div class="filter_type version_2">
                        <div class="filter-title"><a href="#filter_2" data-bs-toggle="collapse" class="opened">Categories</a>
                            <svg  class="SvgCaret" viewBox="0 0 24 24" fill="currentColor"><path d="M11.9999 13.1714L16.9497 8.22168L18.3639 9.63589L11.9999 15.9999L5.63599 9.63589L7.0502 8.22168L11.9999 13.1714Z"></path></svg>
                        
                        </div>
	                        <div class="collapse show" id="filter_2">
	                              <!-- Single Widget -->
                                  <div class="single-widget category">
                                    <h3 class="title">Categories</h3>
                                    <ul class="categor-list">
										@php
											// $category = new Category();
											$menu=App\Models\Category::getAllParentWithChild();
										@endphp
										@if($menu)
										<li>
											@foreach($menu as $cat_info)
													@if($cat_info->child_cat->count()>0)
														<li><a href="{{route('product-cat',$cat_info->slug)}}">{{$cat_info->title}}</a>
															<ul>
																@foreach($cat_info->child_cat as $sub_menu)
																	<li><a href="{{route('product-sub-cat',[$cat_info->slug,$sub_menu->slug])}}">{{$sub_menu->title}}</a></li>
																@endforeach
															</ul>
														</li>
													@else
														<li><a href="{{route('product-cat',$cat_info->slug)}}">{{$cat_info->title}}</a></li>
													@endif
											@endforeach
										</li>
										@endif
                                        {{-- @foreach(Helper::productCategoryList('products') as $cat)
                                            @if($cat->is_parent==1)
												<li><a href="{{route('product-cat',$cat->slug)}}">{{$cat->title}}</a></li>
											@endif
                                        @endforeach --}}
                                    </ul>
                                </div>
                                <!--/ End Single Widget -->
	                        </div>
	                    </div>


                          <!-- /filter_type -->
	                    <div class="filter_type version_2">
                        <div class="filter-title"><a href="#filter_4" data-bs-toggle="collapse" class="opened">Brands</a>
                            <svg  class="SvgCaret" viewBox="0 0 24 24" fill="currentColor"><path d="M11.9999 13.1714L16.9497 8.22168L18.3639 9.63589L11.9999 15.9999L5.63599 9.63589L7.0502 8.22168L11.9999 13.1714Z"></path></svg>
                        
                        </div>
	                        <div class="collapse show" id="filter_4">


                                   <!-- Single Widget -->
                                   <div class="single-widget category">
                                    <h3 class="title">Brands</h3>
                                    <ul class="categor-list">
                                        @php
                                            $brands=DB::table('brands')->orderBy('title','ASC')->where('status','active')->get();
                                        @endphp
                                        @foreach($brands as $brand)
                                            <li><a href="{{route('product-brand',$brand->slug)}}">{{$brand->title}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!--/ End Single Widget -->
	                        </div>
	                    </div>
	                    <!-- /filter_type -->
	                    <div class="filter_type version_2">
                        <div class="filter-title"><a href="#filter_3" data-bs-toggle="collapse" class="opened">Lengths</a>
                            <svg  class="SvgCaret" viewBox="0 0 24 24" fill="currentColor"><path d="M11.9999 13.1714L16.9497 8.22168L18.3639 9.63589L11.9999 15.9999L5.63599 9.63589L7.0502 8.22168L11.9999 13.1714Z"></path></svg>
                        
                        </div>
	                        <div class="collapse show" id="filter_3" style="">
	                           <!-- Length Filter -->
<div class="form-group mt-3">
    
    <div class="d-flex flex-wrap">
        @foreach ($lengths as $length)
            @if (!is_null($length) && $length !== '')
                <div class="form-check me-2">
                    <input type="checkbox" id="length-{{ $loop->index }}" 
                           name="length[]" 
                           value="{{ $length }}" 
                           class="form-check-input filter-option length-filter"
                           {{ in_array($length, request('length', [])) ? 'checked' : '' }}>
                    <label for="length-{{ $loop->index }}" class="form-check-label">
                        {{ ucfirst($length) }} cm
                    </label>
                </div>
            @endif
        @endforeach
    </div>
</div>
	                        </div>
	                    </div>
	                  
	                  
	                    <div class="buttons">
	                        <a href="#0" class="btn_1">Filter</a> <a href="#0" class="btn_1 gray">Reset</a>
	                    </div>
	                </div>
           
            </div>
                </aside>
	            <!-- /col -->
	            <div class="col-lg-9">
	                
	                <!-- /top_banner -->
	                <div id="stick_here" style="height: 0px;"></div>
	                <div class="toolbox elemento_stick add_bottom_30">
	                    <div class="container">
	                        <ul class="clearfix">
	                            <li>
	                                <div class="sort_select">
                                    <select class='sortBy' name='sortBy' onchange="this.form.submit();" id="sort">
                                                <option value="">Default</option>
                                                <option value="title" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='title') selected @endif>Name</option>
                                                <option value="sell_price" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='sell_price') selected @endif>Price</option>
                                                <option value="category" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='category') selected @endif>Category</option>
                                                <option value="brand" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='brand') selected @endif>Brand</option>
                                            </select>
	                                </div>
	                            </li>
	                            <li>
	                                <a href="#0"><i class="ti-view-grid"></i></a>
	                                <a href="listing-row-1-sidebar-left.html"><i class="ti-view-list"></i></a>
	                            </li>
	                            
	                        </ul>
	                    </div>
	                </div>
	                <!-- /toolbox -->
	                <div class="row row_item">
                    <div id="product-container">
                    @include('frontend.partials.shop-products', ['products' => $products])
                    </div>
	                </div>
	             
	               
	            </div>
	            <!-- /col -->
	        </div>
	        <!-- /row -->
	    </div>
	    <!-- /container -->
	</main>


 


     
       
     
  
    <!--/ End Product Style 1  -->



    
@endsection
@push('styles')
<style>
    .pagination{
        display:inline-flex;
    }
    .filter_button{
        /* height:20px; */
        text-align: center;
        background:#F7941D;
        padding:8px 16px;
        margin-top:10px;
        color: white;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
   .swiper {
    margin-top: 30px;
  position: relative;
  width: 270px;
  height: 340px;
}

.swiper-slide {
  border-radius: 10;
}

.swiper-slide img {
  width: 100%;
  object-fit: cover;
  border-radius: 10px;
  height: -webkit-fill-available;
}

     

</style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <script>
    var colorFilterRoute = "{{ route('color.filter') }}";
</script>

    <script src="{{ asset('frontend/js/products.js') }}"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get all filter headers
    const filterHeaders = document.querySelectorAll(".filter_type .filter-title ");

    filterHeaders.forEach(header => {
        header.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default anchor behavior

            // Find the closest parent .filter_type and then the collapse div inside it
            let filterContent = this.closest(".filter_type").querySelector(".collapse");
            let svgIcon = this.querySelector(".SvgCaret"); // Only find the SVG inside the clicked header

            if (filterContent) {
                filterContent.classList.toggle("show");
            }

            if (svgIcon) {
                svgIcon.classList.toggle("rotateSvg"); // Add rotation class only to the clicked element's SVG
            }
        });
    });
});



</script>

@endpush
