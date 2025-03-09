@extends('backend.layouts.master')
@section('title', 'E-SHOP || Brand Page')

@section('main-content')
<div class="container-fluid">
  <div class="card mb-4">
    <div class="row">
      <div class="col-md-12">
        @include('backend.layouts.notification')
      </div>
    </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Brands List</h6>
      <a href="{{route('brand.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add Brand">
        <i class="fas fa-plus"></i> Add Brand
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($brands) > 0)
        <table class="table table-bordered" id="brand-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>S.N.</th>
              <th>Title</th>
              <th>Slug</th>
              <th>Image</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>S.N.</th>
              <th>Title</th>
              <th>Slug</th>
              <th>Image</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </tfoot>
          <tbody>
            @foreach($brands as $brand)   
            <tr>
              <td>{{$brand->id}}</td>
              <td>{{$brand->title}}</td>
              <td>{{$brand->slug}}</td>
              <td>
                @if($brand->image)
                  <img src="{{$brand->image}}" class="img-fluid zoom" style="max-width:80px" alt="{{$brand->image}}">
                @else
                  <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid zoom" style="max-width:100%" alt="avatar.png">
                @endif
              </td>
              <td>
                @if($brand->status == 'active')
                  <span class="badge badge-success">{{$brand->status}}</span>
                @else
                  <span class="badge badge-warning">{{$brand->status}}</span>
                @endif
              </td>
              <td>
                <a href="{{route('brand.edit', $brand->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px; border-radius:50%" data-toggle="tooltip" title="Edit" data-placement="bottom">
                  <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="{{route('brand.destroy', [$brand->id])}}">
                  @csrf 
                  @method('delete')
                  <button class="btn btn-danger btn-sm dltBtn" data-id="{{$brand->id}}" style="height:30px; width:30px; border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </td>
            </tr>  
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$brands->links()}}</span>
        @else
          <h6 class="text-center">No brands found!!! Please create a brand</h6>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .zoom {
        transition: transform .2s;
      }
      .zoom:hover {
        transform: scale(3.2);
      }
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>
      $('#brand-dataTable').DataTable({
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[3,4,5]
                }
            ]
        });

      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.dltBtn').click(function(e){
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
            e.preventDefault();
            swal({
                  title: "Are you sure?",
                  text: "Once deleted, you will not be able to recover this data!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willDelete) => {
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
