@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Purchases</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Purchases</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn  btn-md btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#add-purchase-modal"><i class="fe fe-plus" aria-label="fe fe-plus"></i>New
                            Transactions</button>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable-purchases table table-stripped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>Total Items</th>
                                        <th>Total Price</th>
                                        <th>Discount</th>
                                        <th>Total Paid</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add purchase --}}
    <div class="modal fade" id="add-purchase-modal" tabindex="-1" role="dialog" aria-labelledby="add-purchase-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-purchase-modal">Choose Supplier</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="datatable-suppliers-purchase table table-stripped">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal add purchase --}}

    {{-- modal show purchased product --}}
    <div class="modal fade" id="show-purchase-product-modal" tabindex="-1" role="dialog"
        aria-labelledby="show-purchase-product-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="show-purchase-product-modal">Choose Products</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="purchasedProductsTable" class="datatable-show-purchased-products table table-stripped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi oleh DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal show purchased product --}}

    @push('scripts')
        <script>
            //// fetch & populate datatable
            let table;
            $(document).ready(function() {
                table = $('.datatable-purchases').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('purchase-data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'supplier_name',
                            name: 'supplier_name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'total_items',
                            name: 'total_items',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'total_price',
                            name: 'total_price',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'discount',
                            name: 'discount',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: 'paid',
                            name: 'paid',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },

                    ],
                });
            });
            //// end fetch & populate datatable

            ///fetch & populate datatable supplier
            let supplierTable;
            $(document).ready(function() {
                supplierTable = $('.datatable-suppliers-purchase').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('purchase-supplier') }}",
                    columns: [{
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'address',
                            name: 'address',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'phone',
                            name: 'phone',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },

                    ],
                });
            });
            ///end fetch & populate datatable supplier

            ////setup ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ////end setup ajax

            ///create new purchase
            $('.datatable-suppliers-purchase').on('click', '.purchase-select-supplier-btn', function(e) {
                e.preventDefault();
                let supplierId = $(this).data('id');

                let data = {
                    supplier_id: supplierId,
                    total_items: 0,
                    total_price: 0,
                    discount: 0,
                    paid: 0,
                };

                $.ajax({
                    url: "{{ route('purchase-data.store') }}",
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        // Check if the response is a redirect
                        if (response.redirect) {
                            // Open the redirect URL in a new tab
                            window.open(response.redirect, '_blank');
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON);
                    }
                });
            });

            ///end new purchase

            ////delete purchase ajax

            $('body').on("click", '.purchase-delete-btn', function() {
                let purchaseId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('purchase-data') }}/" + purchaseId,
                            type: 'DELETE',
                            success: function(response) {
                                toastr.success(response.message);
                                table.ajax.reload(); // Refetch DataTable
                            },
                            error: function(xhr) {
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    toastr.error(xhr.responseJSON.message);
                                } else {
                                    toastr.error('An error occurred while processing the request');
                                }
                            }
                        });
                    }
                });

            });

            ////end delete purchase ajax

            ///show purchased products
            let purchasedProductsTable;

            $(document).ready(function() {
                // Attach click event to the button
                $(document).on('click', '.purchase-show-btn', function() {
                    let purchaseId = $(this).data('id');

                    // If DataTable already exists, destroy it
                    if ($.fn.DataTable.isDataTable('#purchasedProductsTable')) {
                        $('#purchasedProductsTable').DataTable().destroy();
                    }

                    // Clear only the table body contents
                    $('#purchasedProductsTable tbody').empty();

                    // Reinitialize DataTable for available products
                    purchasedProductsTable = $('#purchasedProductsTable').DataTable({
                        processing: true,
                        serverSide: true,
                        dom: 'lrtip',
                        ajax: "{{ url('purchase-detail') }}/" + purchaseId + "/products",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'code',
                                name: 'code',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'name',
                                name: 'name',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'purchase_price',
                                name: 'purchase_price',
                                orderable: true,
                                searchable: false,
                            },
                            {
                                data: 'quantity',
                                name: 'quantity',
                                orderable: true,
                                searchable: false,
                            },
                            {
                                data: 'subtotal',
                                name: 'subtotal',
                                orderable: true,
                                searchable: false,
                            }
                        ],
                    });

                    // Show the modal
                    $('#show-purchase-product-modal').modal('show');
                });
            });

            ///end show purchased products
        </script>
    @endpush
@endsection
