<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    /**
     * Display expenses page.
     */
    public function displayExpense()
    {
        return view('backend.dashboard.expenses');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($expense) {
                return '
                    <button data-id="' . $expense->id . '" class="btn btn-warning btn-sm expense-edit-btn">Edit</button>
                    <button data-id="' . $expense->id . '"  class="btn btn-danger btn-sm expense-delete-btn">Delete</button>
                ';
            })
            ->editColumn('nominal', function ($expense) {
                return 'Rp ' . number_format($expense->nominal, 0, ',', '.');
            })
            ->editColumn('created_at', function ($expense) {
                return Carbon::parse($expense->created_at)->format('d F Y');
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $searchValue = $request->get('search')['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('expenses.created_at', 'like', "%{$searchValue}%")
                            ->orWhere('expenses.description', 'like', "%{$searchValue}%")
                            ->orWhere('expenses.nominal', 'like', "%{$searchValue}%");
                    });
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|min:3|max:500',
            'nominal' => 'sometimes|decimal:0,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Create a new supplier
        Expense::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Expense created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::findOrFail($id);
        return response()->json([
            'id' => $expense->id,
            'description' => $expense->description,
            'nominal' => $expense->nominal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|min:3|max:500',
            'nominal' => 'sometimes|decimal:0,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the expense
        $expense = Expense::findOrFail($id);

        // Update the expense
        $expense->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Expense updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Supplier deleted successfully'
        ]);
    }
}
