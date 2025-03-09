@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Account Statement</h5>
    <div class="card-body">
        <form method="post" action="{{ route('account-statement.store') }}">
            @csrf
            <div class="form-group">
                <label for="total_sales" class="col-form-label">Total Sales <span class="text-danger">*</span></label>
                <input id="total_sales" type="number" name="total_sales" placeholder="Enter total sales" value="0" class="form-control">
                @error('total_sales')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="total_expense" class="col-form-label">Total Expense <span class="text-danger">*</span></label>
                <input id="total_expense" type="number" name="total_expense" placeholder="Enter total expense" value="0" class="form-control">
                @error('total_expense')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>


            <div class="form-group">
              <label for="type" class="col-form-label">Type <span class="text-danger">*</span></label>
              <select name="type" class="form-control">
                  <option value="in">In</option>
                  <option value="out">Out</option>
              </select>
              @error('type')
              <span class="text-danger">{{ $message }}</span>
              @enderror
          </div>


            <div class="form-group">
                <label for="notes" class="col-form-label">Notes</label>
                <textarea id="notes" name="notes" class="form-control" placeholder="Enter notes">{{ old('notes') }}</textarea>
                @error('notes')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection
