<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function displayProduct()
    {
        return view('backend.dashboard.products');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('category_name', function ($product) {
                return $product->category->name;
            })
            ->addColumn('action', function ($product) {
                return '
                    <button data-id="' . $product->id . '" class="btn btn-warning btn-sm product-edit-btn">Edit</button>
                    <button data-id="' . $product->id . '"  class="btn btn-danger btn-sm product-delete-btn">Delete</button>
                ';
            })
            ->addColumn('checkbox', '<input type="checkbox" name="products_checkbox[]" class="products_checkbox" value="{{$id}}" />')
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $searchValue = $request->get('search')['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('products.code', 'like', "%{$searchValue}%")
                            ->orWhere('products.name', 'like', "%{$searchValue}%")
                            ->orWhere('products.brand', 'like', "%{$searchValue}%")
                            ->orWhereHas('category', function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%{$searchValue}%");
                            });
                    });
                }
            })
            ->rawColumns(['checkbox', 'action'])
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
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        $product = Product::create([
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category_id'],
            'brand' => $validatedData['brand'],
            'purchase_price' => $validatedData['purchase_price'],
            'selling_price' => $validatedData['selling_price'],
            'discount' => $validatedData['discount'],
            'stock' => $validatedData['stock'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully'
        ]);
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
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return response()->json([
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'category_id' => $product->category_id,
            'brand' => $product->brand,
            'purchase_price' => $product->purchase_price,
            'selling_price' => $product->selling_price,
            'discount' => $product->discount,
            'stock' => $product->stock,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $validatedData = $request->validated();

            $product->update($validatedData);
            return response()->json(['message' => 'Product updated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the product. Please try again.'
            ], 500);
        }
    }

    function destroyAll(Request $request)
    {
        $product_id_array = $request->input('id');
        $product = Product::whereIn('id', $product_id_array);
        if ($product->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'All selected products deleted successfully'
            ], 200);
        }
    }

    /**
     * Print products barcode.
     */
    public function printProductsBarcode(Request $request)
    {
        $product_id_array = $request->query('id');
        
        if (!is_array($product_id_array)) {
            $product_id_array = explode(',', $product_id_array);
        }

        $products = Product::whereIn('id', $product_id_array)->get();

        $generator = new BarcodeGeneratorPNG();

        $barcodes = $products->map(function ($product) use ($generator) {
            $barcode = base64_encode($generator->getBarcode($product->code, $generator::TYPE_CODE_128));
            return [
                'name' => $product->name,
                'selling_price' => $product->selling_price,
                'code' => $product->code,
                'barcode' => $barcode
            ];
        });

        $pdf = Pdf::loadView('backend.components.print-product-barcode', ['barcodes' => $barcodes])->setPaper('A4','potrait');
        
        return $pdf->stream('products-barcode.pdf');

        // return view('backend.components.print-product-barcode', ['barcodes' => $barcodes]);

    }
}
