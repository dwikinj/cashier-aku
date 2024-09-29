<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a purchase page.
     */
    public function displayPurchase()
    {
        return view('backend.dashboard.purchases');
    }

    /**
     * Display a purchase page.
     */
    public function purchaseSupplier(Request $request)
    {
        $query = Supplier::query();

        return DataTables::of($query)
            ->addColumn('action', function ($supplier) {
                return '
                    <button data-id="' . $supplier->id . '" class="btn btn-primary btn-sm purchase-select-supplier-btn"><i class="fe fe-check"></i> Select</button>
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with('supplier')
            ->select('purchases.*', 'suppliers.name as supplier_name')
            ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('supplier_name', function ($purchase) {
                return $purchase->supplier->name;
            })
            ->orderColumn('supplier_name', function ($query, $order) {
                $query->orderBy('suppliers.name', $order);
            })
            ->addColumn('action', function ($purchase) {
                return '
                    <button data-id="' . $purchase->id . '" class="btn btn-primary btn-sm purchase-show-btn" data-bs-toggle="modal"
                            data-bs-target="#show-purchase-product-modal"><i class="fe fe-eye"  title="show purchase" ></i></button>
                    <a href="' . url("purchase-detail/{$purchase->id}/edit") . '" class="btn btn-warning btn-sm" ><i class="fe fe-edit" title="edit purchase" ></i></a>
                    <button data-id="' . $purchase->id . '" class="btn btn-danger btn-sm purchase-delete-btn"><i class="fe fe-trash-2"  title="delete purchase" ></i></button>
                ';
            })
            ->editColumn('paid', function ($purchase) {
                return 'Rp ' . number_format($purchase->paid, 2, ',', '.');
            })
            ->editColumn('total_price', function ($purchase) {
                return 'Rp ' . number_format($purchase->total_price, 2, ',', '.');
            })
            ->editColumn('discount', function ($purchase) {
                return $purchase->discount . '%';
            })
            ->editColumn('created_at', function ($purchase) {
                return Carbon::parse($purchase->created_at)->format('d F Y');
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $searchValue = $request->get('search')['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('purchases.created_at', 'like', "%{$searchValue}%")
                            ->orWhere('purchases.discount', 'like', "%{$searchValue}%")
                            ->orWhere('purchases.paid', 'like', "%{$searchValue}%")
                            ->orWhere('purchases.total_price', 'like', "%{$searchValue}%")
                            ->orWhereHas('supplier', function ($q) use ($searchValue) {
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
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'total_items' => 'required|integer|min:0',
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'paid' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Create a new purchase
        $purchase = Purchase::create($validator->validated());

        //redirect to route('purchase-detail.index') and send $purchase
        return response()->json([
            'redirect' => route('purchase-detail.index', ['purchase' => $purchase])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Hapus semua PurchaseDetail terkait
            $purchase->purchaseDetails()->delete();

            // Hapus Purchase itu sendiri
            $purchase->delete();

            // Commit transaksi jika semuanya sukses
            DB::commit();

            return response()->json([
                'message' => 'Purchase history deleted successfully',
            ]);
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while deleting the purchase',
            ], 500);
        }
    }
}
