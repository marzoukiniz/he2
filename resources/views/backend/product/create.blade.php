@extends('backend.layouts.master')

@section('main-content')
<div class="container-fluid">
  <div class="card">
    <h5 class="card-header">Add Product</h5>
    <div class="card-body">
      <form method="post" action="{{ route('product.store') }}">
        {{ csrf_field() }}

        <div class="row">
          <!-- Column 1: Basic Information -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
              <input id="inputTitle" type="text" name="title" placeholder="Enter title" value="{{ old('title') }}" class="form-control">
              @error('title')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
              <textarea class="form-control" id="summary" name="summary">{{ old('summary') }}</textarea>
              @error('summary')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="description" class="col-form-label">Description</label>
              <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
              @error('description')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="guide" class="col-form-label">Guide</label>
              <textarea class="form-control" id="guide" name="guide">{{ old('guide') }}</textarea>
              @error('guide')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Column 2: Category & Pricing -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="is_featured">Is Featured</label><br>
              <input type="checkbox" name="is_featured" id="is_featured" value="1" checked> Yes
            </div>

            <div class="form-group">
              <label for="cat_id">Category <span class="text-danger">*</span></label>
              <select name="cat_id" id="cat_id" class="form-control">
                <option value="">--Select any category--</option>
                @foreach($categories as $key => $cat_data)
                  <option value="{{ $cat_data->id }}">{{ $cat_data->title }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group d-none" id="child_cat_div">
              <label for="child_cat_id">Sub Category</label>
              <select name="child_cat_id" id="child_cat_id" class="form-control">
                <option value="">--Select any category--</option>
              </select>
            </div>

            <div class="form-group">
              <label for="price" class="col-form-label">Price (QAR) <span class="text-danger">*</span></label>
              <input id="price" type="number" name="price" placeholder="Enter price" value="{{ old('price') }}" class="form-control">
              @error('price')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="discount" class="col-form-label">Discount (%)</label>
              <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount" value="{{ old('discount') }}" class="form-control">
              @error('discount')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="expense" class="col-form-label">Expense (QAR)</label>
              <input id="expense" type="number" name="expense" min="0" placeholder="Enter expense" value="{{ old('expense') }}" class="form-control">
              @error('expense')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Column 3: Branding, Media & Variations -->
          <div class="col-md-4">
            <div class="form-group">
              <label for="brand_id">Brand</label>
              <select name="brand_id" class="form-control">
                <option value="">--Select Brand--</option>
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="condition">Condition</label>
              <select name="condition" class="form-control">
                <option value="">--Select Condition--</option>
                <option value="default">Default</option>
                <option value="new">New</option>
                <option value="hot">Hot</option>
              </select>
            </div>

            <div class="form-group">
              <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> Choose
                  </a>
                </span>
                <input id="thumbnail" class="form-control" type="text" name="photo" value="{{ old('photo') }}">
              </div>
              <div id="holder" style="margin-top:15px;max-height:100px;"></div>
              @error('photo')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
              <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
              @error('status')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <!-- Colors (Optional) -->
            <div class="form-group">
              <label>الألوان (اختياري)</label>
              <div id="color-container">
                <div class="color-row d-flex">
                  <input type="text" name="colors[]" class="form-control m-1" placeholder="أدخل لونًا">
                  <button type="button" class="btn btn-success add-color">+</button>
                </div>
              </div>
            </div>

            <!-- Lengths, Additional Cost & Stock (Optional) -->
            <div class="form-group">
              <label>الأطوال لكل لون (اختياري)</label>
              <div id="length-container">
                <div class="length-row d-flex">
                  <select name="lengths[0][color]" class="form-control m-1 color-select">
                    <option value="">اختر لونًا</option>
                  </select>
                  <input type="number" name="lengths[0][length]" class="form-control m-1" placeholder="الطول">
                  <input type="number" name="lengths[0][additional_cost]" class="form-control m-1" placeholder="تكلفة إضافية (اختياري)">
                  <input type="number" name="lengths[0][stock]" class="form-control m-1" placeholder="المخزون" required>
                  <button type="button" class="btn btn-success add-length">+</button>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- End row -->

        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
          <button class="btn btn-success" type="submit">Submit</button>
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
<script>
document.addEventListener("DOMContentLoaded", function() {
  let lengthIndex = 1;

  // Adding new color
  document.querySelector('.add-color').addEventListener('click', function() {
    let newColorRow = document.createElement('div');
    newColorRow.classList.add('color-row', 'd-flex');
    newColorRow.innerHTML = `
      <input type="text" name="colors[]" class="form-control m-1" placeholder="أدخل لونًا">
      <button type="button" class="btn btn-danger remove-color">-</button>
    `;
    document.getElementById('color-container').appendChild(newColorRow);
    updateColorDropdowns();
  });

  // Removing a color
  document.getElementById('color-container').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-color')) {
      event.target.parentElement.remove();
      updateColorDropdowns();
    }
  });

  // Adding new length row
  document.querySelector('.add-length').addEventListener('click', function() {
    let newLengthRow = document.createElement('div');
    newLengthRow.classList.add('length-row', 'd-flex');
    newLengthRow.innerHTML = `
      <select name="lengths[${lengthIndex}][color]" class="form-control m-1 color-select">
        <option value="">اختر لونًا</option>
      </select>
      <input type="number" name="lengths[${lengthIndex}][length]" class="form-control m-1" placeholder="الطول">
      <input type="number" name="lengths[${lengthIndex}][additional_cost]" class="form-control m-1" placeholder="تكلفة إضافية (اختياري)">
      <input type="number" name="lengths[${lengthIndex}][stock]" class="form-control m-1" placeholder="المخزون" required>
      <button type="button" class="btn btn-danger remove-length">-</button>
    `;
    document.getElementById('length-container').appendChild(newLengthRow);
    updateColorDropdowns();
    lengthIndex++;
  });

  // Removing a length row
  document.getElementById('length-container').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-length')) {
      event.target.parentElement.remove();
    }
  });

  // Update color dropdowns based on entered colors
  function updateColorDropdowns() {
    let colors = Array.from(document.querySelectorAll('[name="colors[]"]'))
                      .map(input => input.value.trim())
                      .filter(value => value !== "");
    document.querySelectorAll('.color-select').forEach(select => {
      let selectedValue = select.value;
      select.innerHTML = '<option value="">اختر لونًا</option>';
      colors.forEach(color => {
        let option = new Option(color, color, false, color === selectedValue);
        select.appendChild(option);
      });
    });
  }

  document.getElementById('color-container').addEventListener('input', updateColorDropdowns);
});
</script>

<script>
  $('#lfm').filemanager('image');

  $(document).ready(function() {
    $('#summary').summernote({
      placeholder: "Write short description.....",
      tabsize: 1,
      height: 80
    });
  });

  $(document).ready(function() {
    $('#description').summernote({
      placeholder: "Write detail description.....",
      tabsize: 1,
      height: 80
    });
  });

  $(document).ready(function() {
    $('#guide').summernote({
      placeholder: "Write guide details.....",
      tabsize: 1,
      height: 80
    });
  });
</script>

<script>
  $('#cat_id').change(function(){
    var cat_id = $(this).val();
    if(cat_id != null){
      $.ajax({
        url: "/admin/category/" + cat_id + "/child",
        data: {
          _token: "{{ csrf_token() }}",
          id: cat_id
        },
        type: "POST",
        success: function(response){
          if(typeof(response) != 'object'){
            response = $.parseJSON(response);
          }
          var html_option = "<option value=''>----Select sub category----</option>";
          if(response.status){
            var data = response.data;
            if(response.data){
              $('#child_cat_div').removeClass('d-none');
              $.each(data, function(id, title){
                html_option += "<option value='" + id + "'>" + title + "</option>";
              });
            }
          } else {
            $('#child_cat_div').addClass('d-none');
          }
          $('#child_cat_id').html(html_option);
        }
      });
    }
  });
</script>
@endpush
