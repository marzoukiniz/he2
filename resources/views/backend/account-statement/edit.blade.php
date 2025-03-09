@extends('backend.layouts.master')

@section('main-content')
<div class="card">
    <div class="card-header">Edit Account Statement</div>
    <div class="card-body">
        <form method="POST" action="{{ route('account-statement.update', $statement->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="total_sales">Total Sales:</label>
                <input type="number" name="total_sales" class="form-control" value="{{ $statement->total_sales }}" required>
            </div>

            <div class="form-group">
                <label for="total_expense">Total Expense:</label>
                <input type="number" name="total_expense" class="form-control" value="{{ $statement->total_expense }}" required>
            </div>

            <div class="form-group">
                <label for="type">Type:</label>
                <select name="type" class="form-control" required>
                    <option value="in" {{ $statement->type == 'in' ? 'selected' : '' }}>IN</option>
                    <option value="out" {{ $statement->type == 'out' ? 'selected' : '' }}>OUT</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea name="notes" class="form-control">{{ $statement->notes }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Update Statement</button>
            <a href="{{ route('account-statement.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
