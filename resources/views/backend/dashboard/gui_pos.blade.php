@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">POS New Transactions</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">POS New Transactions</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">

                        <div class="input-group">
                            <label for="product-code" style="margin-right: 10px;">Product Code:</label>
                            <input type="text" id="product-code" class="form-control" placeholder="Product Code"
                                aria-label="Product Code" aria-describedby="search-product" style="height: 40px;">
                            <button type="button" class="btn btn-outline-primary" style="height: 40px;"
                                data-bs-toggle="modal" data-bs-target="#add-purchase-product-modal">
                                <i class="fe fe-search" id="search-product" title="search product"></i>
                            </button>
                        </div>

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
                            <h1 class="text-white "><span id="total-paid">Rp. 0</span></h1>
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
                                    id="total-amount">
                            </div>
                        </div>
                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Member</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="buyer-member">
                                    <button type="button" class="btn btn-outline-primary" style="height: 40px"
                                        data-bs-toggle="modal" data-bs-target="#select-mmeber-buyer">
                                        <i class="fe fe-search" id="search-member" title="search member"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Discount</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control form-control-sm" id="discount" min="0"
                                    disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Pay</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control form-control-sm" disabled="disabled"
                                    id="total-payment">
                            </div>
                        </div>
                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Received</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control form-control-sm" id="received">
                            </div>
                        </div>
                        <div class="form-group mb-1 row">
                            <label class="col-form-label col-md-2">Change</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control form-control-sm" disabled="disabled"
                                    id="change">
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
            $(document).ready(function() {
                let selectedProducts = [];
                let table;

                // Initialize DataTable
                table = $('.datatable-purchase-product').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('sales.products') }}",
                    columns: [{
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'category_name',
                            name: 'category.name'
                        },
                        {
                            data: 'brand',
                            name: 'brand'
                        },
                        {
                            data: 'formatted_selling_price',
                            name: 'formatted_selling_price'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                // Handle product selection
                $(document).on('click', '.purchase-select-supplier-btn', function() {
                    const productId = $(this).data('id');
                    const row = $(this).closest('tr');
                    const product = {
                        id: productId,
                        code: row.find('td:eq(0)').text(),
                        name: row.find('td:eq(1)').text(),
                        selling_price: row.find('span[data-selling-price]').data('selling-price'),
                        quantity: 1
                    };
                    addProduct(product);
                    updateTable();
                    $('#add-purchase-product-modal').modal('hide');
                });

                // Handle quantity change
                $(document).on('change', '.product-quantity', function() {
                    const productId = $(this).data('id');
                    const quantity = parseInt($(this).val());
                    updateProductQuantity(productId, quantity);
                });

                // Handle product deletion
                $(document).on('click', '.delete-selected-product', function() {
                    const productId = $(this).data('id');
                    removeProduct(productId);
                });

                // Handle discount change
                $('#discount').on('input', function() {
                    updateTotals();
                });

                // Handle received amount change
                $('#received').on('input', function() {
                    updateChange();
                });

                function addProduct(product) {
                    const existingProduct = selectedProducts.find(p => p.id === product.id);
                    if (existingProduct) {
                        existingProduct.quantity += 1;
                    } else {
                        selectedProducts.push(product);
                    }
                    updateTable();
                }

                function removeProduct(productId) {
                    selectedProducts = selectedProducts.filter(p => p.id !== productId);
                    updateTable();
                }

                function updateProductQuantity(productId, quantity) {
                    const product = selectedProducts.find(p => p.id === productId);
                    if (product) {
                        product.quantity = quantity;
                        updateTable();
                    }
                }

                function updateTable() {
                    const tbody = $('#purchaseTransactionsBody');
                    tbody.empty();

                    selectedProducts.forEach((product, index) => {
                        const subtotal = product.selling_price * product.quantity;
                        tbody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${product.code}</td>
                                <td>${product.name}</td>
                                <td>${formatCurrency(product.selling_price)}</td>
                                <td>
                                    <input type="number" class="form-control product-quantity" 
                                        value="${product.quantity}" min="1" data-id="${product.id}">
                                </td>
                                <td>${formatCurrency(subtotal)}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-selected-product" data-id="${product.id}">
                                        <i class="fe fe-trash"></i> Delete </button>
                                </td>
                            </tr>
                        `);
                    });

                    updateTotals();
                }

                function updateTotals() {
                    const totalAmount = calculateTotalAmount();
                    const discountPercentage = parseFloat($('#discount').val()) || 0;
                    const discountAmount = (totalAmount * discountPercentage) / 100;
                    const totalPayment = Math.max(totalAmount - discountAmount, 0);

                    $('#total-amount').val(formatCurrency(totalAmount));
                    $('#total-payment').val(formatCurrency(totalPayment));
                    $('#total-paid').text(formatCurrency(totalPayment));

                    // Update number to words
                    const words = angkaTerbilang(totalPayment);
                    $('#number-to-words').text(words + ' rupiah');

                    updateChange();
                }

                function calculateTotalAmount() {
                    return selectedProducts.reduce((sum, product) => sum + (product.selling_price * product.quantity),
                        0);
                }

                function updateChange() {
                    const totalPayment = parseFloat($('#total-payment').val().replace(/[^\d.-]/g, ''));
                    const receivedAmount = parseFloat($('#received').val()) || 0;
                    const change = Math.max(receivedAmount - totalPayment, 0);

                    $('#change').val(formatCurrency(change));
                }

                function formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(amount);
                }

                // Fungsi angkaTerbilang diimplementasikan di sini (Anda perlu menambahkan implementasinya)

                // Handle form submission
                $('#submit-purchase').on('click', function(e) {
                    e.preventDefault();

                    const totalAmount = calculateTotalAmount();
                    const discountPercentage = parseFloat($('#discount').val()) || 0;
                    const discountAmount = (totalAmount * discountPercentage) / 100;
                    const totalPayment = Math.max(totalAmount - discountAmount, 0);
                    const receivedAmount = parseFloat($('#received').val()) || 0;
                    const change = Math.max(receivedAmount - totalPayment, 0);

                    const data = {
                        purchase: {
                            total_items: selectedProducts.reduce((sum, product) => sum + product.quantity,
                                0),
                            total_price: totalAmount,
                            discount: discountPercentage,
                            discount_amount: discountAmount,
                            paid: totalPayment,
                            received: receivedAmount,
                            change: change,
                            buyer_member: $('#buyer-member').val()
                        },
                        purchase_detail: selectedProducts.map(product => ({
                            product_id: product.id,
                            quantity: product.quantity,
                            selling_price: product.selling_price,
                            subtotal: product.selling_price * product.quantity
                        }))
                    };

                    // Kirim data ke server (implementasi AJAX di sini)
                    console.log('Data to be sent:', data);
                    // Tambahkan kode AJAX untuk mengirim data ke server
                });

                // Enable discount input when a member is selected
                $('#buyer-member').on('input', function() {
                    $('#discount').prop('disabled', $(this).val().trim() === '');
                });
            });
        </script>
    @endpush
@endsection
