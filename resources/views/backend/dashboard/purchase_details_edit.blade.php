@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Purchase Transactions</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Purchase Transactions Edit</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Supplier Name</td>
                                    <td>:</td>
                                    <td>{{ $purchase['supplier']['name'] }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>:</td>
                                    <td>{{ $purchase['supplier']['address'] }}</td>
                                </tr>
                                <tr>
                                    <td><input type="hidden" name="purchase_id" value="{{ $purchase['id'] }}">
                                    <td><input type="hidden" name="supplier_id" value="{{ $purchase['supplier']['id'] }}">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                            data-bs-target="#add-purchase-product-modal"><i class="fe fe-plus"></i> Add Product</button>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable-purchases table table-stripped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseTransactionsBody">
                                    <!-- This will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-5 col-sm-4 col-12">
                <div>
                    <div class="card bg-success mb-1">
                        <div class="card-body ">
                            <h1 class="text-white ">Rp. <span id="total-paid"
                                    value="{{ $purchase['paid'] }}">{{ $purchase['paid'] }}</span></h1>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body ">
                            <p id="number-to-words"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 col-sm-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Total</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control form-control-sm" disabled="disabled"
                                    id="total-amount" value="{{ $purchase['total_price'] }}">
                            </div>
                        </div>
                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Discount</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control form-control-sm" id="discount" min="0"
                                    value="{{ $purchase['discount'] }}">
                            </div>
                        </div>
                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Pay</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control form-control-sm" disabled="disabled"
                                    id="total-payment" value="{{ $purchase['paid'] }}">
                            </div>
                        </div>
                        <div class="form-group mb-1 row text-end">
                            <div class="col-md-12 ">
                                <button type="button" class="btn btn-success" id="submit-purchase">
                                    <i class="fe fe-file-plus"></i>
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- modal add purchase product --}}
    <div class="modal fade" id="add-purchase-product-modal" tabindex="-1" role="dialog"
        aria-labelledby="add-purchase-product-modal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-purchase-product-modal">Choose Products</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="datatable-purchase-product table table-stripped">
                            <thead>
                                <tr>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Product Brand</th>
                                    <th>Purchase Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal add purchase product --}}

    @push('scripts')
        <script>
            let table;
            let selectedProducts = [];
            let initialPurchaseDetails = @json($purchase['purchaseDetails'] ?? []);

            function initializeSelectedProducts() {
                initialPurchaseDetails.forEach(detail => {
                    const product = {
                        product_id: detail.product_id,
                        purchase_id: detail.purchase_id,
                        code: detail.product.code,
                        name: detail.product.name,
                        purchase_price: detail.purchase_price,
                        quantity: detail.quantity,
                        subtotal: detail.subtotal
                    };
                    selectedProducts.push(product);
                });
            }


            $(document).ready(function() {
                // Initialize DataTable for available products
                table = $('.datatable-purchase-product').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('purchase-products') }}",
                    columns: [{
                            data: 'code',
                            name: 'code',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'category_name',
                            name: 'category.name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'brand',
                            name: 'brand',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'purchase_price',
                            name: 'purchase_price',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });

                //fill initial selected data
                initializeSelectedProducts();
                updatePurchaseTransactionsTable();
                updateTotalPayment();

                // Handle product selection
                $('body').on('click', '.purchase-select-supplier-btn', function() {
                    const productId = $(this).data('id');
                    const row = $(this).closest('tr');
                    const purchasePrice = parseFloat(row.find('td:eq(4)').text().replace('Rp. ', '').replace(
                        ',', ''));
                    const quantity = 1;
                    const product = {
                        product_id: productId,
                        purchase_id: $("input[name='purchase_id']").val(),
                        code: row.find('td:eq(0)').text(),
                        name: row.find('td:eq(1)').text(),
                        purchase_price: purchasePrice,
                        quantity: quantity,
                        subtotal: parseFloat((purchasePrice * quantity).toFixed(2)),
                    };

                    addProductToSelection(product);
                    updatePurchaseTransactionsTable();
                    $('#add-purchase-product-modal').modal('hide');
                });

                // Handle quantity change
                $('body').on('change', '.product-quantity', function() {
                    const productId = $(this).data('id');
                    let quantity = parseInt($(this).val());
                    if (isNaN(quantity) || quantity < 1) {
                        quantity = 1;
                        $(this).val(1);
                    }
                    updateProductQuantity(productId, quantity);
                    updatePurchaseTransactionsTable();
                });

                // Handle product deletion
                $('body').on('click', '.delete-selected-product', function() {
                    const productId = $(this).data('id');
                    removeProductFromSelection(productId);
                    updatePurchaseTransactionsTable();
                });

                $('#discount').on('input', function() {
                    updateTotalPayment();
                });
            });

            function addProductToSelection(product) {
                const existingProductIndex = selectedProducts.findIndex(p => p.product_id === product.product_id);

                if (existingProductIndex !== -1) {
                    toastr.warning('product already selected');
                } else {
                    selectedProducts.push(product);
                }
            }

            function removeProductFromSelection(productId) {
                selectedProducts = selectedProducts.filter(p => p.product_id !== productId);
            }

            function updateProductQuantity(productId, quantity) {
                const product = selectedProducts.find(p => p.product_id === productId);
                if (product) {
                    product.quantity = quantity;
                    product.subtotal = Math.round(product.purchase_price * quantity * 100) / 100;
                }
            }

            function updatePurchaseTransactionsTable() {
                const tbody = $('#purchaseTransactionsBody');
                tbody.empty();

                selectedProducts.forEach((product, index) => {
                    tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${product.code}</td>
                        <td>${product.name}</td>
                        <td>Rp. ${product.purchase_price}</td>
                        <td>
                            <input type="number" class="form-control product-quantity" 
                                   value="${product.quantity}" min="1" data-id="${product.product_id}">
                        </td>
                        <td>Rp. ${product.subtotal}</td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-selected-product" data-id="${product.product_id}">
                                <i class="fe fe-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                `);
                });

                updateTotalAmount();
            }

            function updateTotalAmount() {
                const total = selectedProducts.reduce((sum, product) => sum + product.subtotal, 0);
                $('#total-amount').val(total.toFixed(2));
                updateTotalPayment();
            }

            function updateTotalPayment() {
                const totalAmount = parseFloat($('#total-amount').val()) || 0;
                const discountPercentage = parseFloat($('#discount').val()) || 0;
                if (discountPercentage < 0) {
                    discountPercentage = 0;
                }
                const discountAmount = (totalAmount * discountPercentage) / 100;
                const totalPayment = Math.max(totalAmount - discountAmount, 0);

                $('#total-payment').val(totalPayment.toFixed(2));
                $('#total-paid').text(totalPayment.toFixed(2));

                // Update number to words
                const words = angkaTerbilang(totalPayment.toFixed(2));
                $('#number-to-words').text(words + ' rupiah');
            }

            // Setup ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Create new purchase
            $('#submit-purchase').on('click', function(e) {
                e.preventDefault();

                // Calculate the total quantity of items
                let totalItems = selectedProducts.reduce((sum, product) => sum + product.quantity, 0);

                let data = {
                    purchase: {
                        purchase_id: $("input[name='purchase_id']").val(),
                        supplier_id: $('input[name="supplier_id"]').val(),
                        total_items: totalItems,
                        total_price: $('#total-amount').val(),
                        discount: parseFloat($('#discount').val()) || 0,
                        paid: $('#total-paid').text(),
                    },
                    purchase_detail: selectedProducts
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, update it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('purchase-detail') }}/" + data.purchase.purchase_id,
                            type: 'PATCH',
                            data: JSON.stringify(data),
                            processData: false,
                            contentType: 'application/json',
                            success: function(response) {
                                if (response.message) {
                                    toastr.success(response.message);
                                }
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                }
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
        </script>
    @endpush
@endsection
