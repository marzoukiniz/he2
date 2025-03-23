@extends('backend.layouts.master')

@section('title', 'E-SHOP || Add Color')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Color</h5>
    <div class="card-body">
        <form method="POST" action="{{ route('color.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Color Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="image">Color Image</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                        <i class="fa fa-picture-o"></i> Choose
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="image">
                </div>
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button class="btn btn-success" type="submit">Submit</button>
        </form>
    </div>
</div>

@endsection
@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>

<script>
    $('#lfm').filemanager('image');
</script>
@endpush