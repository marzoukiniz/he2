<?php
namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\PostTag;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\Cart;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use App\User;
use Auth;
use Session;
use Newsletter;
use DB;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class FrontendController extends Controller
{
   

// new code starts here
public function placeOrder(Request $request) {
    $this->validate($request, [
        'user_id' => 'required|exists:users,id',
        'items' => 'required|array',
        'items.*.variation_id' => 'required|exists:product_colors,variation_id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();
    try {
        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => 'pending'
        ]);

        foreach ($request->items as $item) {
            $variation = ProductVariation::find($item['variation_id']);
            OrderItem::create([
                'order_id' => $order->order_id,
                'variation_id' => $item['variation_id'],
                'quantity' => $item['quantity'],
                'price' => $variation->price,
            ]);
        }

        DB::commit();
        return response()->json(['success' => true, 'message' => 'Order placed successfully.', 'order_id' => $order->order_id]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['success' => false, 'message' => 'Order placement failed.', 'error' => $e->getMessage()], 500);
    }
}

public function getOrderDetails($order_id) {
    $order = Order::with('orderItems.variation')->find($order_id);
    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
    }
    return response()->json(['success' => true, 'order' => $order]);
}

public function updateOrderStatus(Request $request, $order_id) {
    $this->validate($request, [
        'status' => 'required|string',
    ]);

    $order = Order::find($order_id);
    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
    }

    $order->status = $request->status;
    $order->save();

    return response()->json(['success' => true, 'message' => 'Order status updated successfully.']);
}


//new code ends here

    public function index(Request $request){
        return redirect()->route($request->user()->role);
    }

    public function home() {
        // Fetch featured products with their colors and lengths
        $featured = Product::where('status', 'active')
            ->where('is_featured', 1)
            ->with(['colors.lengths']) // Use 'lengths' instead of 'productLengths'
            ->limit(2)
            ->get();
    
        $posts = Post::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(3)
            ->get();
    
        $banners = Banner::where('status', 'active')
            ->limit(3)
            ->orderBy('id', 'DESC')
            ->get();
    
        // Fetch products with their colors and lengths
        $products = Product::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->with(['colors.lengths']) // Fix relationship name
            ->get();
    
        $brands = Brand::where('status', 'active')
            ->orderBy('id', 'DESC')
            ->limit(8)
            ->get();
    
        $categories = Category::where('status', 'active')
            ->where('is_parent', 1)
            ->orderBy('title', 'ASC')
            ->get();
    
        return view('frontend.index')
            ->with('featured', $featured)
            ->with('posts', $posts)
            ->with('banners', $banners)
            ->with('product_lists', $products)
            ->with('brands', $brands)
            ->with('category_lists', $categories);
    }
    

    public function aboutUs(){
        return view('frontend.pages.about-us');
    }

    public function contact(){
        return view('frontend.pages.contact');
    }

    public function productDetail($slug){
        $product_detail= Product::getProductBySlug($slug);
        // dd($product_detail);
        return view('frontend.pages.product_detail')->with('product_detail',$product_detail);
    }

    public function productGrids() {
        $products = Product::query();
    
        // Filter by category
        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            $products->whereIn('cat_id', $cat_ids);
        }
    
        // Filter by brand
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            $products->whereIn('brand_id', $brand_ids);
        }
    
        // Sorting by title or price
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }
    
        // Filter by price range
        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            $products->whereBetween('price', $price);
        }
    
        // Get recent products
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
    
        // Sorting by number of items to show
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(9);
        }
    
        // Fetch unique colors from product_colors table
        $colors = DB::table('product_colors')->distinct()->pluck('color')->toArray();
    
        // Filter by selected colors
        if (!empty($_GET['color'])) {
            $selectedColors = is_array($_GET['color']) ? $_GET['color'] : explode(',', $_GET['color']);
            $products->whereHas('colors', function ($query) use ($selectedColors) {
                $query->whereIn('color', $selectedColors);
            });
        }
    
        // Fetch unique lengths from product_lengths table
        $lengths = DB::table('product_lengths')->distinct()->pluck('length')->toArray();
    
        // Filter by selected lengths
        if (!empty($_GET['length'])) {
            $selectedLengths = is_array($_GET['length']) ? $_GET['length'] : explode(',', $_GET['length']);
            $products->whereHas('lengths', function ($query) use ($selectedLengths) {
                $query->whereIn('length', $selectedLengths);
            });
        }
    
        return view('frontend.pages.product-grids')
            ->with('products', $products)
            ->with('recent_products', $recent_products)
            ->with('colors', $colors)
            ->with('lengths', $lengths);
    }
    
    public function productLists() {
        $products = Product::query(); // Initialize query builder
        // Filter by color
        if (!empty($_GET['color'])) {
            $selectedColors = explode(',', $_GET['color']);
            $products->whereHas('variations', function ($query) use ($selectedColors) {
                $query->whereIn('color', $selectedColors);
            });
        }
        // Filter by Category
        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            $cat_ids = Category::whereIn('slug', $slug)->pluck('id')->toArray();
            $products->whereIn('cat_id', $cat_ids);
        }
    
        // Filter by Brand
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::whereIn('slug', $slugs)->pluck('id')->toArray();
            $products->whereIn('brand_id', $brand_ids);
        }
    
        // Sorting
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') { // Ensure correct column name
                $products->orderBy('price', 'ASC');
            }
        }
    
        // Price Range Filter
        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            if (isset($price[0]) && isset($price[1]) && is_numeric($price[0]) && is_numeric($price[1])) {
                $products->whereBetween('price', [$price[0], $price[1]]);
            }
        }
    
        // Recent Products
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
    
        // Pagination (Apply once at the end)
        $products = $products->where('status', 'active')->paginate($_GET['show'] ?? 6);
    // Fetch unique colors for filter
    $colors = DB::table('product_colors')->distinct()->pluck('color');

        return view('frontend.pages.product-lists', compact('products', 'recent_products', 'colors'));
    }
    
    public function productFilter(Request $request){
            $data= $request->all();
            // return $data;
            $showURL="";
            if(!empty($data['show'])){
                $showURL .='&show='.$data['show'];
            }

            $sortByURL='';
            if(!empty($data['sortBy'])){
                $sortByURL .='&sortBy='.$data['sortBy'];
            }

            $catURL="";
            if(!empty($data['category'])){
                foreach($data['category'] as $category){
                    if(empty($catURL)){
                        $catURL .='&category='.$category;
                    }
                    else{
                        $catURL .=','.$category;
                    }
                }
            }

            $brandURL="";
            if(!empty($data['brand'])){
                foreach($data['brand'] as $brand){
                    if(empty($brandURL)){
                        $brandURL .='&brand='.$brand;
                    }
                    else{
                        $brandURL .=','.$brand;
                    }
                }
            }
            // return $brandURL;

            $priceRangeURL="";
            if(!empty($data['price_range'])){
                $priceRangeURL .='&price='.$data['price_range'];
            }
            if(request()->is('e-shop.loc/product-grids')){
                return redirect()->route('product-grids',$catURL.$brandURL.$priceRangeURL.$showURL.$sortByURL);
            }
            else{
                return redirect()->route('product-lists',$catURL.$brandURL.$priceRangeURL.$showURL.$sortByURL);
            }
    }
    public function colorFilter(Request $request) {
        if (!$request->ajax()) {
            return response()->json(['error' => true, 'message' => 'Invalid request'], 405);
        }
    
        try {
            $products = Product::query();
    
            // Check if categories, colors, lengths, and brands are selected
            $hasCategories = $request->has('category') && !empty($request->category);
            $hasColors = $request->has('color') && !empty($request->color);
            $hasLengths = $request->has('length') && !empty($request->length);
            $hasBrands = $request->has('brand') && !empty($request->brand);
    
            // Convert request parameters into arrays
            $selectedCategories = $hasCategories ? (is_array($request->category) ? $request->category : explode(',', $request->category)) : [];
            $selectedColors = $hasColors ? (is_array($request->color) ? $request->color : explode(',', $request->color)) : [];
            $selectedLengths = $hasLengths ? (is_array($request->length) ? $request->length : explode(',', $request->length)) : [];
            $selectedBrands = $hasBrands ? (is_array($request->brand) ? $request->brand : explode(',', $request->brand)) : [];
    
            // Apply filters based on the selected options
            if ($hasCategories) {
                $products->whereHas('category', function ($query) use ($selectedCategories) {
                    $query->whereIn('slug', $selectedCategories);
                });
            }
    
            if ($hasColors) {
                $products->whereHas('colors', function ($query) use ($selectedColors) {
                    $query->whereIn('color', $selectedColors);
                });
            }
    
            if ($hasLengths) {
                $products->whereHas('lengths', function ($query) use ($selectedLengths) {
                    $query->whereIn('length', $selectedLengths);
                });
            }
    
            // Apply brand filter if selected
            if ($hasBrands) {
                $products->whereHas('brand', function ($query) use ($selectedBrands) {
                    $query->whereIn('id', $selectedBrands);
                });
            }
    
            // Get the paginated results (with or without filters)
            $products = $products->paginate(9);
    
            // Return the filtered products or the default list of products
            return response()->json([
                'html' => view('frontend.partials.shop-products', compact('products'))->render(),
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
    // public function colorFilter(Request $request) {
    //     // Initialize query builder
    //     $products = Product::query();
    
    //     // Fetch Unique Colors from Variations
    //     $colors = DB::table('product_colors')->distinct()->pluck('color')->toArray();
    
    //     // **Filter by Color**
    //     if ($request->has('color')) {
    //         $selectedColors = is_array($request->input('color')) ? $request->input('color') : explode(',', $request->input('color'));
    
    //         $products->whereHas('variations', function ($query) use ($selectedColors) {
    //             $query->whereIn('color', $selectedColors);
    //         });
    //     }
    
    //     // Execute query (use get() or paginate())
    //     $products = $products->paginate(9); // Keeping the same variable name
    
    //     return view('frontend.pages.product-grids', compact('products', 'colors'));
    // }
    
    
    
    public function productSearch(Request $request){
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $products=Product::orwhere('title','like','%'.$request->search.'%')
                    ->orwhere('slug','like','%'.$request->search.'%')
                    ->orwhere('description','like','%'.$request->search.'%')
                    ->orwhere('summary','like','%'.$request->search.'%')
                    ->orwhere('price','like','%'.$request->search.'%')
                    ->orderBy('id','DESC')
                    ->paginate('9');
        return view('frontend.pages.product-grids')->with('products',$products)->with('recent_products',$recent_products);
    }

    public function productBrand(Request $request){
        $products=Brand::getProductByBrand($request->slug);
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$products->products)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$products->products)->with('recent_products',$recent_products);
        }

    }
    public function productCat(Request $request){
        $products=Category::getProductByCat($request->slug);
        // return $request->slug;
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$products->products)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$products->products)->with('recent_products',$recent_products);
        }

    }
    public function productSubCat(Request $request){
        $products=Category::getProductBySubCat($request->sub_slug);
        // return $products;
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$products->sub_products)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$products->sub_products)->with('recent_products',$recent_products);
        }

    }

    public function blog(){
        $post=Post::query();
        
        if(!empty($_GET['category'])){
            $slug=explode(',',$_GET['category']);
            // dd($slug);
            $cat_ids=PostCategory::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            return $cat_ids;
            $post->whereIn('post_cat_id',$cat_ids);
            // return $post;
        }
        if(!empty($_GET['tag'])){
            $slug=explode(',',$_GET['tag']);
            // dd($slug);
            $tag_ids=PostTag::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            // return $tag_ids;
            $post->where('post_tag_id',$tag_ids);
            // return $post;
        }

        if(!empty($_GET['show'])){
            $post=$post->where('status','active')->orderBy('id','DESC')->paginate($_GET['show']);
        }
        else{
            $post=$post->where('status','active')->orderBy('id','DESC')->paginate(9);
        }
        // $post=Post::where('status','active')->paginate(8);
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts',$post)->with('recent_posts',$rcnt_post);
    }

    public function blogDetail($slug){
        $post=Post::getPostBySlug($slug);
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // return $post;
        return view('frontend.pages.blog-detail')->with('post',$post)->with('recent_posts',$rcnt_post);
    }

    public function blogSearch(Request $request){
        // return $request->all();
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $posts=Post::orwhere('title','like','%'.$request->search.'%')
            ->orwhere('quote','like','%'.$request->search.'%')
            ->orwhere('summary','like','%'.$request->search.'%')
            ->orwhere('description','like','%'.$request->search.'%')
            ->orwhere('slug','like','%'.$request->search.'%')
            ->orderBy('id','DESC')
            ->paginate(8);
        return view('frontend.pages.blog')->with('posts',$posts)->with('recent_posts',$rcnt_post);
    }

    public function blogFilter(Request $request){
        $data=$request->all();
        // return $data;
        $catURL="";
        if(!empty($data['category'])){
            foreach($data['category'] as $category){
                if(empty($catURL)){
                    $catURL .='&category='.$category;
                }
                else{
                    $catURL .=','.$category;
                }
            }
        }

        $tagURL="";
        if(!empty($data['tag'])){
            foreach($data['tag'] as $tag){
                if(empty($tagURL)){
                    $tagURL .='&tag='.$tag;
                }
                else{
                    $tagURL .=','.$tag;
                }
            }
        }
        // return $tagURL;
            // return $catURL;
        return redirect()->route('blog',$catURL.$tagURL);
    }

    public function blogByCategory(Request $request){
        $post=PostCategory::getBlogByCategory($request->slug);
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts',$post->post)->with('recent_posts',$rcnt_post);
    }

    public function blogByTag(Request $request){
        // dd($request->slug);
        $post=Post::getBlogByTag($request->slug);
        // return $post;
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts',$post)->with('recent_posts',$rcnt_post);
    }

    // Login
    public function login(){
        return view('frontend.pages.login');
    }
    public function loginSubmit(Request $request){
        $data= $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'status'=>'active'])){
            Session::put('user',$data['email']);
            request()->session()->flash('success','Successfully login');
            return redirect()->route('home');
        }
        else{
            request()->session()->flash('error','Invalid email and password pleas try again!');
            return redirect()->back();
        }
    }

    public function logout(){
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success','Logout successfully');
        return back();
    }

    public function register(){
        return view('frontend.pages.register');
    }
    public function registerSubmit(Request $request){
        // return $request->all();
        $this->validate($request,[
            'name'=>'string|required|min:2',
            'email'=>'string|required|unique:users,email',
            'password'=>'required|min:6|confirmed',
        ]);
        $data=$request->all();
        // dd($data);
        $check=$this->create($data);
        Session::put('user',$data['email']);
        if($check){
            request()->session()->flash('success','Successfully registered');
            return redirect()->route('home');
        }
        else{
            request()->session()->flash('error','Please try again!');
            return back();
        }
    }
    public function create(array $data){
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'status'=>'active'
            ]);
    }
    // Reset password
    public function showResetForm(){
        return view('auth.passwords.old-reset');
    }

    public function subscribe(Request $request){
        if(! Newsletter::isSubscribed($request->email)){
                Newsletter::subscribePending($request->email);
                if(Newsletter::lastActionSucceeded()){
                    request()->session()->flash('success','Subscribed! Please check your email');
                    return redirect()->route('home');
                }
                else{
                    Newsletter::getLastError();
                    return back()->with('error','Something went wrong! please try again');
                }
            }
            else{
                request()->session()->flash('error','Already Subscribed');
                return back();
            }
    }
    
}
