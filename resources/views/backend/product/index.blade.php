@extends('backend.layouts.master')

@section('main-content')
<div class="container-fluid">
    <div class="card mb-4">
        <div class="row">
            <div class="col-md-12">
                @include('backend.layouts.notification')
            </div>
        </div>
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">Product Lists</h6>
            <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" title="Add Product">
                <i class="fas fa-plus"></i> Add Product
            </a>
             

        </div>
        <div class="card-body">
    <div class="table-responsive">
        @if($products->count() > 0)
        <table class="table table-bordered" id="product-dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Is Featured</th>
                    <th>Sell Price</th>
                    <th>Cost Price</th>
                    <th>Discount</th>
                    <th>Variations</th>
                    <th>Condition</th>
                    <th>Brand</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->title }}</td>
                    <td>
                        {{ optional($product->category)->title }}
                        @if($product->subCategory)
                            <sub>/ {{ optional($product->subCategory)->title }}</sub>
                        @endif
                    </td>
                    <td>{{ $product->is_featured ? 'Yes' : 'No' }}</td>
                    <td>QAR {{ number_format($product->price, 2) }}</td>
                    <td>QAR {{ number_format($product->expense, 2) }}</td>
                    <td>{{ $product->discount }}% OFF</td>
                    <td>
                        @if($product->colors->count() > 0 || $product->lengths->count() > 0)
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Color</th>
                                        <th>Length</th>
                                        <th>Stock</th>
                                        <th>Additional Cost</th> <!-- Added Additional Cost -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->colors as $color)
                                    @foreach($product->lengths as $length)
                                    <tr>
                                        <td style="background-color: {{ $color->color }}; color: white; padding: 5px;">
                                            {{ ucfirst($color->color) }}
                                        </td>
                                        <td>{{ $length->length }}</td>
                                        <td>
                                            <span class="badge 
                                                {{ $length->stock > 5 ? 'badge-success' : ($length->stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                                                {{ $length->stock }}
                                            </span>
                                        </td>
                                        <td>QAR {{ number_format($length->additional_cost, 2) }}</td> <!-- Display Additional Cost -->
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <span class="text-muted">No Variations</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($product->condition) }}</td>
                    <td>{{ optional($product->brand)->title }}</td>
                    <td>
                        @php
                            $photo = explode(',', $product->photo)[0] ?? asset('backend/img/thumbnail-default.jpg');
                        @endphp
                        <img src="{{ $photo }}" class="img-fluid zoom" style="max-width:80px" alt="Product Image">
                    </td>
                    <td>
                        <span class="badge {{ $product->status == 'active' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-sm" style="border-radius:50%" data-toggle="tooltip" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('product.destroy', $product->id) }}" class="d-inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm dltBtn" style="border-radius:50%" data-id="{{ $product->id }}" data-toggle="tooltip" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination justify-content-end">
            {{ $products->links() }}
        </div>
        @else
        <h6 class="text-center">No Products found! Please create a Product.</h6>
        @endif
    </div>
</div>


    </div>
</div>

 

 

@endsection

@push('styles')
<link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    div.dataTables_wrapper div.dataTables_paginate {
        display: none;
    }
    .zoom {
        transition: transform .2s;
    }
    .zoom:hover {
        transform: scale(1.5);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="{{ asset('backend/js/demo/datatables-demo.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#csvImportForm').submit(function(event) {
            let fileInput = $('#csvFile');
            if (fileInput.get(0).files.length === 0) {
                alert("Please select a CSV file before submitting.");
                event.preventDefault();
            }
        });
    });
</script>
<script>
    $('#product-dataTable').DataTable({
        "scrollX": false,
        "columnDefs": [{
            "orderable": false,
            "targets": [7, 10, 11]
        }]
    });

    $(document).ready(function() {
        $('.dltBtn').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                } else {
                    swal("Your data is safe!");
                }
            });
        });
    });
</script>
@endpush
