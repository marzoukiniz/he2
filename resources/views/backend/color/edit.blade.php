@extends('backend.layouts.master')

@section('title', 'E-SHOP || Edit Color')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Color</h5>
    <div class="card-body">
        <form method="POST" action="{{ route('color.update', $color->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Color Name -->
            <div class="form-group">
                <label for="name">Color Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $color->name }}" required>
            </div>

            <!-- Color Image Upload -->
            <div class="form-group">
                <label for="image">Color Image</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm_color" data-input="color_thumbnail" data-preview="color_holder" class="btn btn-primary">
                            <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                    <input id="color_thumbnail" class="form-control" type="text" name="image" value="{{ $color->image }}">
                </div>
                <div id="color_holder" style="margin-top:15px; max-height:100px;">
                    @if($color->image)
                        <img src="{{ $color->image }}" alt="Color Image" style="max-height:100px;">
                    @endif
                </div>
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ $color->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $color->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button class="btn btn-success" type="submit">Update</button>
        </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/summernote/summernote.min.css') }}">
@endpush

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{ asset('backend/summernote/summernote.min.js') }}"></script>

<script>
    // Activate file manager for color image
    $('#lfm_color').filemanager('image');

    // Update preview when a new image is selected
    $('#color_thumbnail').on('change', function() {
        var imageUrl = $(this).val();
        $('#color_holder').html('<img src="' + imageUrl + '" alt="Color Image" style="max-height:100px;">');
    });
</script>
@endpush
