<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseDetailRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Purchase $purchase)
    {
        // Pass the purchase data to the view
        return view('backend.dashboard.purchase_details', ['purchase' => $purchase]);
    }

    /**
     * Display a listing all avaible products to purchase.
     */
    public function purchaseProducts(Request $request)
    {
        $query = Product::with('category');

        return DataTables::of($query)
            ->addColumn('category_name', function ($product) {
                return $product->category->name;
            })
            ->addColumn('action', function ($product) {
                return '
                    <button data-id="' . $product->id . '" class="btn btn-primary btn-sm purchase-select-supplier-btn"><i class="fe fe-check"></i> Select</button>
                ';
            })
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
    public function store(StorePurchaseDetailRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            // Update Purchase
            $purchase = Purchase::findOrFail($validatedData['purchase']['purchase_id']);
            $purchase->update($validatedData['purchase']);

            // Create new PurchaseDetails and update product stock
            foreach ($validatedData['purchase_detail'] as $detailData) {
                $purchase->purchaseDetails()->create($detailData);

                // Update product stock
                $product = Product::findOrFail($detailData['product_id']);
                $product->stock += $detailData['quantity'];
                $product->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Purchase created successfully',
                'redirect' => route('displayPurchase')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while processing the purchase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showPurchasedProducts(string $purchase)
    {
        $query = PurchaseDetail::where('purchase_id', $purchase)->with('product');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('code', function ($purchaseDetail) {
                return $purchaseDetail->product->code;
            })
            ->addColumn('name', function ($purchaseDetail) {
                return $purchaseDetail->product->name;
            })
            ->editColumn('purchase_price', function ($purchaseDetail) {
                return 'Rp ' . number_format($purchaseDetail->purchase_price, 2, ',', '.');
            })
            ->editColumn('subtotal', function ($purchaseDetail) {
                return 'Rp ' . number_format($purchaseDetail->subtotal, 2, ',', '.');
            })
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $purchase = $purchase->load(['supplier', 'purchaseDetails.product']);
        return view('backend.dashboard.purchase_details_edit', ['purchase' => $purchase]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePurchaseDetailRequest $request, Purchase $purchase)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            // Update Purchase
            $purchase->update($validatedData['purchase']);

            // Reduce stock for old PurchaseDetails
            foreach ($purchase->purchaseDetails as $oldDetail) {
                $product = Product::findOrFail($oldDetail->product_id);
                $product->stock -= $oldDetail->quantity;
                $product->save();
            }

            // Delete old PurchaseDetails
            $purchase->purchaseDetails()->delete();

            // Create new PurchaseDetails and update product stock
            foreach ($validatedData['purchase_detail'] as $detailData) {
                $purchaseDetail = $purchase->purchaseDetails()->create($detailData);

                // Update product stock
                $product = Product::findOrFail($detailData['product_id']);
                $product->stock += $detailData['quantity'];
                $product->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Purchase updated successfully',
                'redirect' => route('displayPurchase')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while processing the purchase',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseDetail $purchaseDetail)
    {
        //
    }
}
