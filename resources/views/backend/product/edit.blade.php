@extends('backend.layouts.master')

@section('main-content')
<div class="container-fluid">
  <div class="card">
    <h5 class="card-header">Edit Product</h5>
    <div class="card-body">
      <form method="post" action="{{ route('product.update', $product->id) }}">
        @csrf 
        @method('PATCH')

        <div class="row">
          <!-- Column 1: Basic Info -->
          <div class="col-md-4">
            {{-- Title --}}
            <div class="form-group">
              <label for="inputTitle">Title <span class="text-danger">*</span></label>
              <input id="inputTitle" type="text" name="title" value="{{ $product->title }}" class="form-control">
              @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            {{-- Summary --}}
            <div class="form-group">
              <label for="summary">Summary <span class="text-danger">*</span></label>
              <textarea class="form-control" id="summary" name="summary">{{ $product->summary }}</textarea>
              @error('summary') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            {{-- Description --}}
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
              @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            {{-- Guide --}}
            <div class="form-group">
              <label for="guide">Guide</label>
              <textarea class="form-control" id="guide" name="guide">{{ $product->guide }}</textarea>
              @error('guide') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
          </div>

          <!-- Column 2: Category & Pricing -->
          <div class="col-md-4">
            {{-- Is Featured --}}
            <div class="form-group">
              <label for="is_featured">Is Featured</label><br>
              <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}> Yes                        
            </div>
            {{-- Category --}}
            <div class="form-group">
              <label for="cat_id">Category <span class="text-danger">*</span></label>
              <select name="cat_id" id="cat_id" class="form-control">
                <option value="">--Select any category--</option>
                @foreach($categories as $cat_data)
                  <option value="{{ $cat_data->id }}" {{ $product->cat_id == $cat_data->id ? 'selected' : '' }}>
                    {{ $cat_data->title }}
                  </option>
                @endforeach
              </select>
            </div>
            {{-- Subcategory --}}
            <div class="form-group {{ $product->child_cat_id ? '' : 'd-none' }}" id="child_cat_div">
              <label for="child_cat_id">Sub Category</label>
              <select name="child_cat_id" id="child_cat_id" class="form-control">
                <option value="">--Select any sub category--</option>
                @foreach($subcategories as $sub_cat)
                  <option value="{{ $sub_cat->id }}" {{ $product->child_cat_id == $sub_cat->id ? 'selected' : '' }}>
                    {{ $sub_cat->title }}
                  </option>
                @endforeach
              </select>
            </div>
            {{-- Sell Price --}}
            <div class="form-group">
              <label for="sell_price">Sell Price (QAR) <span class="text-danger">*</span></label>
              <input id="sell_price" type="number" name="price" value="{{ $product->price }}" class="form-control">
            </div>
            {{-- Cost Price --}}
            <div class="form-group">
              <label for="cost_price">Cost Price (QAR) <span class="text-danger">*</span></label>
              <input id="cost_price" type="number" name="expense" value="{{ $product->expense }}" class="form-control">
            </div>
            {{-- Discount --}}
            <div class="form-group">
              <label for="discount">Discount (%)</label>
              <input id="discount" type="number" name="discount" min="0" max="100" value="{{ $product->discount }}" class="form-control">
            </div>
          </div>

          <!-- Column 3: Branding, Photo & Status -->
          <div class="col-md-4">
            {{-- Brand --}}
            <div class="form-group">
              <label for="brand_id">Brand</label>
              <select name="brand_id" class="form-control">
                <option value="">--Select Brand--</option>
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                    {{ $brand->title }}
                  </option>
                @endforeach
              </select>
            </div>
            {{-- Condition --}}
            <div class="form-group">
              <label for="condition">Condition</label>
              <select name="condition" class="form-control">
                <option value="default" {{ $product->condition == 'default' ? 'selected' : '' }}>Default</option>
                <option value="new" {{ $product->condition == 'new' ? 'selected' : '' }}>New</option>
                <option value="hot" {{ $product->condition == 'hot' ? 'selected' : '' }}>Hot</option>
              </select>
            </div>
            {{-- Photo --}}
            <div class="form-group">
              <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                    <i class="fas fa-image"></i> Choose
                  </a>
                </span>
                <input id="thumbnail" class="form-control" type="text" name="photo" value="{{ $product->photo }}">
              </div>
              <div id="holder" style="margin-top:15px;max-height:100px;"></div>
              @error('photo')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            {{-- Status --}}
            <div class="form-group">
              <label for="status">Status <span class="text-danger">*</span></label>
              <select name="status" class="form-control">
                <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
          </div>
        </div> <!-- End row -->

        <!-- Variations -->
        <div class="form-group">
          <label>Product Variations</label>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Color</th>
                <th>Length</th>
                <th>Stock</th>
                <th>Additional Cost</th>
                <th><button type="button" class="btn btn-success add-variation">+</button></th>
              </tr>
            </thead>
            <tbody id="variationTable">
              @forelse($product_colors as $index => $color)
                @php 
                  // Get the corresponding length record by index if available
                  $length = isset($product_lengths[$index]) ? $product_lengths[$index] : null;
                @endphp
                <tr>
                  <td>
                    <input type="text" name="product_colors[{{ $index }}][color]" value="{{ $color->color }}" class="form-control" placeholder="Enter color">
                  </td>
                  <td>
                    <input type="text" name="product_lengths[{{ $index }}][length]" value="{{ $length ? $length->length : '' }}" class="form-control" placeholder="Enter length">
                  </td>
                  <td>
                    <input type="number" name="product_colors[{{ $index }}][stock]" value="{{ $length ? $length->stock : '' }}" class="form-control" placeholder="Enter stock">
                  </td>
                  <td>
                    <input type="number" step="0.01" name="product_lengths[{{ $index }}][additional_cost]" value="{{ $length ? $length->additional_cost : '' }}" class="form-control" placeholder="Enter additional cost">
                  </td>
                  <td>
                    <button type="button" class="btn btn-danger remove-variation">-</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5">No variations available for this product.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Submit Button -->
        <div class="form-group mb-3">
          <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

{{-- JS to Add & Remove Variations --}}
<script>
  $('#lfm').filemanager('image');

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.add-variation').addEventListener('click', function () {
      let row = `<tr>
                    <td>
                      <input type="text" name="variations[][color]" class="form-control" placeholder="Enter color">
                    </td>
                    <td>
                      <input type="text" name="variations[][length]" class="form-control" placeholder="Enter length">
                    </td>
                    <td>
                      <input type="number" name="variations[][stock]" class="form-control" placeholder="Enter stock">
                    </td>
                    <td>
                      <input type="number" step="0.01" name="variations[][additional_cost]" class="form-control" placeholder="Enter additional cost">
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger remove-variation">-</button>
                    </td>
                  </tr>`;
      document.getElementById('variationTable').insertAdjacentHTML('beforeend', row);
    });

    document.addEventListener('click', function (event) {
      if (event.target.classList.contains('remove-variation')) {
        event.target.closest('tr').remove();
      }
    });
  });
</script>
@endpush
