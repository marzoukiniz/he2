<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountStatement;
use Carbon\Carbon; 

class AccountStatementController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountStatement::query();


        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $statements = $query->orderBy('id', 'desc')->paginate(10);

        foreach ($statements as $statement) {
            $statement->profit = $statement->total_sales - $statement->total_expense;
        }

        return view('backend.account-statement.index', compact('statements'));
    }

    public function create()
    {
        return view('backend.account-statement.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_sales' => 'required|numeric|min:0',
            'total_expense' => 'required|numeric|min:0',
            'type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $statement = AccountStatement::create([
            'total_sales' => $request->total_sales,
            'total_expense' => $request->total_expense,
            'type' => $request->type,
            'notes' => $request->notes,
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('account-statement.index')->with('success', 'Account statement added successfully.');
    }

        public function edit($id)
        {
            $statement = AccountStatement::findOrFail($id);
            return view('backend.account-statement.edit', compact('statement'));
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'total_sales' => 'required|numeric|min:0',
                'total_expense' => 'required|numeric|min:0',
                'type' => 'required|string',
                'notes' => 'nullable|string',
            ]);
    
            $statement = AccountStatement::findOrFail($id);
            $statement->update([
                'total_sales' => $request->total_sales,
                'total_expense' => $request->total_expense,
                'type' => $request->type,
                'notes' => $request->notes,
            ]);
    
            return redirect()->route('account-statement.index')->with('success', 'Account statement updated successfully.');
        }
    
       
        public function destroy($id)
        {
            $statement = AccountStatement::findOrFail($id);
            $statement->delete();
    
            return redirect()->route('account-statement.index')->with('success', 'Account statement deleted successfully.');
        }

}
