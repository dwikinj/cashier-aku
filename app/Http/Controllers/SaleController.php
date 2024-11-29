<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Helpers;

class SaleController extends Controller
{
    /**
     * Display sale page.
     */
    public function showSalesPage()
    {
        return view('backend.dashboard.sales');
    }
    /**
     * Display Gui POS page.
     */
    public function showGuiPos()
    {
        return view('backend.dashboard.gui_pos');
    }

    /**
     * Display a listing of the resource.
     */
    public function dataTable(Request $request)
    {
        $query = Sale::query()
            ->leftJoin('members', 'sales.member_id', '=', 'members.id')
            ->leftJoin('users', 'sales.user_id', '=', 'users.id')
            ->select('sales.*', 'members.code as member_code', 'users.name as cashier');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($sale) {
                return Carbon::parse($sale->created_at)->format('d F Y');
            })
            ->addColumn('action', function ($sale) {
                return '
                    <button data-id="' . $sale->id . '" class="btn btn-primary btn-sm sale-show-btn" data-bs-toggle="modal" data-bs-target="#show-sale-product-modal" title="show sale"><i class="fe fe-eye"></i></button>
                    <button data-id="' . $sale->id . '" class="btn btn-danger btn-sm sale-delete-btn" title="delete sale"><i class="fe fe-trash-2"></i></button>
                ';
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->get('search')['value']) {
                    $searchValue = $request->get('search')['value'];
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('sales.total_price', 'like', "%{$searchValue}%")
                            ->orWhere('sales.total_items', 'like', "%{$searchValue}%")
                            ->orWhere('sales.discount', 'like', "%{$searchValue}%")
                            ->orWhereRaw("DATE_FORMAT(sales.created_at,'%d %M %Y') like ?", ["%$searchValue%"])
                            ->orWhere('members.code', 'like', "%{$searchValue}%")
                            ->orWhere('users.name', 'like', "%{$searchValue}%");
                    });
                }
            })
            ->make(true);
    }

    /**
     * Display a listing all avaible products to purchase.
     */
    public function saleProductsDatatable(Request $request)
    {
        $query = Product::with('category');

        return DataTables::of($query)
            ->addColumn('category_name', function ($product) {
                return $product->category->name;
            })
            ->addColumn('formatted_selling_price', function ($product) {
                return '<span data-selling-price="'.$product->selling_price.'">'.format_idr($product->selling_price, 2).'</span>';            })
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
            ->rawColumns(['formatted_selling_price','action'])
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
