@extends('backend.layouts.master')

@section('title', 'E-SHOP || Color List')

@section('main-content')

<div class="card">
    <h5 class="card-header">Colors</h5>
    <div class="card-body">
        <a href="{{ route('color.create') }}" class="btn btn-primary mb-3">Add New Color</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($colors as $color)
                <tr>
                    <td>{{ $color->name }}</td>
                    <td><img src="{{ $color->image }}" width="50"></td>
                    <td>{{ ucfirst($color->status) }}</td>
                    <td>
                        <a href="{{ route('color.edit', $color->id) }}" class="btn btn-primary btn-sm" style="border-radius:50%" data-toggle="tooltip" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('color.destroy', $color->id) }}" class="d-inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm dltBtn" style="border-radius:50%" data-id="{{ $color->id }}" data-toggle="tooltip" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
