<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{

    /**
     * Display Supplier page.
     */
    public function displaySupplier()
    {
        return view('backend.dashboard.suppliers');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($supplier) {
                return '
                    <button data-id="' . $supplier->id . '" class="btn btn-warning btn-sm supplier-edit-btn">Edit</button>
                    <button data-id="' . $supplier->id . '"  class="btn btn-danger btn-sm supplier-delete-btn">Delete</button>
                ';
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $searchValue = $request->get('search')['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('suppliers.name', 'like', "%{$searchValue}%")
                            ->orWhere('suppliers.address', 'like', "%{$searchValue}%")
                            ->orWhere('suppliers.phone', 'like', "%{$searchValue}%");
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
            'name' => 'required|string|min:3|max:255',
            'address' => 'nullable|string',
            'phone' => 'required|string|max:255|regex:/^\+62[0-9]{10,13}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Create a new supplier
        Supplier::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Supplier created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json([
            'id' => $supplier->id,
            'name' => $supplier->name,
            'address' => $supplier->address,
            'phone' => $supplier->phone,
            
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'address' => 'nullable|string',
            'phone' => 'required|string|max:255|regex:/^\+62[0-9]{10,13}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Find the supplier
        $supplier = Supplier::findOrFail($id);

        // Update the supplier
        $supplier->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Supplier updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Supplier deleted successfully'
            ]);
    }
}
