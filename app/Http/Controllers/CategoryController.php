<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function categoryProduct() {
        return view('backend.dashboard.category-product');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data  = Category::query();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return view('backend.components.edit-delete-btn')->with('data', $data);
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
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100|unique:categories,name',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Membuat kategori baru
            $category = new Category();
            $category->name = $request->name;
            $category->save();

            // Mengembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            // Mengembalikan respons error jika terjadi kesalahan
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the category. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100|unique:categories,name,' . $id,
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update kategori
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the category. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the category. Please try again.'
            ], 500);
        }
    }
}
