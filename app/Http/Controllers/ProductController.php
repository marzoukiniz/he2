<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\ProductColor;
use App\Models\ProductLength;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::getAllProduct();
        // return $products;
        return view('backend.product.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = Brand::get();
        $category = Category::where('is_parent', 1)->get();
        $colors = Color::where('status', 1)->get(); // Fetch active colors
    
        return view('backend.product.create')
            ->with('categories', $category)
            ->with('brands', $brand)
            ->with('colors', $colors); // Pass colors to the view
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'guide' => 'string|nullable',
            'photo' => 'string|required',
            'cat_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'is_featured' => 'sometimes|in:1',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'expense' => 'nullable|numeric',
            'colors' => 'nullable|array',
            'colors.*' => 'string|nullable',
            'lengths' => 'nullable|array',
            'lengths.*.color' => 'string|nullable',
            'lengths.*.length' => 'numeric|nullable',
            'lengths.*.additional_cost' => 'numeric|nullable',
            'lengths.*.stock' => 'numeric|required'
        ]);
    
        $data = $request->all();
        $data['discount'] = $request->discount ?? 0;
        $data['expense'] = $request->expense ?? 0;
    
        $slug = Str::slug($request->title);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;
        $data['is_featured'] = $request->input('is_featured', 0);
        $data['size'] = $request->input('size') ? implode(',', $request->input('size')) : '';
    
        $product = Product::create($data);
    
        if ($product) {
            // ✅ تعيين الألوان أو استخدام اللون الافتراضي "non color"
            $colors = !empty($request->colors) ? $request->colors : ['no color'];
            $colorIds = []; // تخزين معرّفات الألوان لتجنب التكرار
    
            foreach ($colors as $color) {
                $productColor = ProductColor::firstOrCreate([
                    'product_id' => $product->id,
                    'color' => $color,
                ]);
    
                $colorIds[$color] = $productColor->id; // تخزين المعرف لاستخدامه لاحقًا
            }
    
            // ✅ إذا لم يكن هناك أطوال، إنشاء طول افتراضي مرتبط بـ "non color"
            if (empty($request->lengths)) {
                $defaultColorId = $colorIds['non color'] ?? null;
                if ($defaultColorId) {
                    ProductLength::create([
                        'product_id' => $product->id,
                        'color_id' => $defaultColorId, // التأكد من أن `color_id` ليس `NULL`
                        'length' => 0,
                        'additional_cost' => 0,
                        'stock' => $request->input('stock', 1),
                    ]);
                }
            } else {
                // ✅ معالجة وإدخال الأطوال
                foreach ($request->lengths as $length) {
                    $selectedColor = $length['color'] ?? 'non color';
    
                    // ✅ البحث عن اللون أو إنشاؤه إذا لم يكن موجودًا
                    if (!isset($colorIds[$selectedColor])) {
                        $productColor = ProductColor::firstOrCreate([
                            'product_id' => $product->id,
                            'color' => $selectedColor,
                        ]);
                        $colorIds[$selectedColor] = $productColor->id;
                    }
    
                    $colorId = $colorIds[$selectedColor] ?? null;
                    if ($colorId) {
                        ProductLength::create([
                            'product_id' => $product->id,
                            'color_id' => $colorId, // التأكد من إدخال color_id الصحيح
                            'length' => $length['length'] ?? 0,
                            'additional_cost' => $length['additional_cost'] ?? 0,
                            'stock' => $length['stock'],
                        ]);
                    }
                }
            }
    
            request()->session()->flash('success', 'تمت إضافة المنتج بنجاح');
        } else {
            request()->session()->flash('error', 'حدث خطأ، يرجى المحاولة مجددًا');
        }
    
        return redirect()->route('product.index');
    }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
{
    $brands = Brand::get();
    $product = Product::findOrFail($id);
    $categories = Category::where('is_parent', 1)->get();
    $subcategories = Category::where('parent_id', $product->cat_id)->get();

    // Assuming you have a separate table for colors and lengths
    $product_colors = ProductColor::where('product_id', $id)->get();
    $product_lengths = ProductLength::where('product_id', $id)->get();

    return view('backend.product.edit', compact('product', 'brands', 'categories', 'subcategories', 'product_colors', 'product_lengths'));
}

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'guide' => 'string|nullable',
            'photo' => 'string|required',
            'size' => 'nullable',
            'stock' => "required|numeric",
            'cat_id' => 'required|exists:categories,id',
            'child_cat_id' => 'nullable|exists:categories,id',
            'is_featured' => 'sometimes|in:1',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'expense' => 'nullable|numeric' // ✅ إضافة المصروف إلى التحقق
        ]);
    
        $data = $request->all();
        $data['is_featured'] = $request->input('is_featured', 0);
        $data['expense'] = $request->input('expense', 0); // ✅ تعيين قيمة المصروف
        $size = $request->input('size');
    
        if ($size) {
            $data['size'] = implode(',', $size);
        } else {
            $data['size'] = '';
        }
    
        $status = $product->fill($data)->save();
    
        if ($status) {
            request()->session()->flash('success', 'Product Successfully updated');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
    
        return redirect()->route('product.index');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $status=$product->delete();
        
        if($status){
            request()->session()->flash('success','Product successfully deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}
