@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Products</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Products</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn  btn-md btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#add-product-modal"><i class="fe fe-plus"
                                aria-label="fe fe-plus"></i>Product</button>
                        <button type="button" name="bulk_delete_products" id="bulk_delete_products"
                            class="btn btn-md btn-outline-danger">
                            <i class="fe fe-trash-2" aria-label="fe fe-trash-2"></i>Delete
                        </button>

                        <button type="button" href="{{ route('product-data.printbarcode') }}" id="print_products_barcode"
                            class="btn  btn-md btn-outline-secondary"><i class="fe fe-printer" data-bs-toggle="tooltip"
                                title="Print Products Barcode" aria-label="fe fe-printer"></i>Print
                            Barcode</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable-products table table-stripped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Purchase Price</th>
                                        <th>Selling Price</th>
                                        <th>Discount</th>
                                        <th>Stock</th>
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

    {{-- modal add product --}}


    <div class="modal fade" id="add-product-modal" tabindex="-1" role="dialog" aria-labelledby="add-product-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-product-modal">Add Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-product-form" class="px-3">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                            <div class="invalid-feedback" id="code-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <!-- Options will be populated by jQuery -->
                            </select>
                            <div class="invalid-feedback" id="category_id-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" required>
                            <div class="invalid-feedback" id="brand-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" class="form-control" id="purchase_price" name="purchase_price" required>
                            <div class="invalid-feedback" id="purchase_price-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="selling_price" name="selling_price" required>
                            <div class="invalid-feedback" id="selling_price-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount</label>
                            <input type="number" class="form-control" id="discount" name="discount" value="0">
                            <div class="invalid-feedback" id="discount-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="1"
                                required>
                            <div class="invalid-feedback" id="stock-error"></div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- end modal add product --}}

    {{-- modal edit product --}}
    <div class="modal fade" id="edit-product-modal" tabindex="-1" role="dialog" aria-labelledby="edit-product-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit-product-modal">Edit Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-product-form" class="px-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_product_id" name="id">
                        <div class="mb-3">
                            <label for="edit_code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="edit_code" name="code">
                            <div class="invalid-feedback" id="edit_code-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                            <div class="invalid-feedback" id="edit_name-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Category</label>
                            <select class="form-select" id="edit_category_id" name="category_id">
                                <!-- Options will be populated by jQuery -->
                            </select>
                            <div class="invalid-feedback" id="edit_category_id-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="edit_brand" name="brand">
                            <div class="invalid-feedback" id="edit_brand-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_purchase_price" class="form-label">Purchase Price</label>
                            <input type="number" class="form-control" id="edit_purchase_price" name="purchase_price"
                                step=".01">
                            <div class="invalid-feedback" id="edit_purchase_price-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="edit_selling_price" name="selling_price"
                                step=".01">
                            <div class="invalid-feedback" id="edit_selling_price-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_discount" class="form-label">Discount</label>
                            <input type="number" class="form-control" id="edit_discount" name="discount"
                                value="0">
                            <div class="invalid-feedback" id="edit_discount-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock">
                            <div class="invalid-feedback" id="edit_stock-error"></div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal edit product --}}

    @push('scripts')
        <script>
            //// fetch & populate datatable
            let table;
            $(document).ready(function() {
                table = $('.datatable-products').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('product-data') }}",
                    columns: [{
                            data: 'checkbox',
                            name: 'checkbox',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
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
                            data: 'selling_price',
                            name: 'selling_price',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: 'discount',
                            name: 'discount',
                            orderable: true,
                            searchable: false
                        },
                        {
                            data: 'stock',
                            name: 'stock',
                            orderable: true,
                            searchable: false
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

            ////setup ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ////end setup ajax

            ////delete product ajax

            $('body').on("click", '.product-delete-btn', function() {
                var productId = $(this).data('id');

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
                            url: "{{ url('product-data') }}/" + productId,
                            type: 'DELETE',
                            success: function(response) {
                                toastr.success(response.message);
                                table.ajax.reload(); // Refetch DataTable
                            },
                            error: function(xhr) {
                                toastr.error('An error occurred. Please try again.');
                            }
                        });
                    }
                });

            });

            ////end delete product ajax

            //// add product ajax
            //populate category  
            $(document).ready(function() {
                $.ajax({
                    url: "{{ route('category-data.index') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.data) {
                            let options;
                            $.each(response.data, function(index, category) {
                                options += '<option value="' + category.id + '">' + category.name +
                                    '</option>';
                            });
                            $('#category_id').html(options);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching categories:', error);
                    }
                });
            });
            //end populate category  

            //handle submit
            $(document).ready(function() {
                $('#add-product-form').on('submit', function(e) {
                    e.preventDefault();

                    // Reset previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('product-data.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            toastr.success(response.message);
                            $('#add-product-modal').modal('hide');
                            // Assuming you have a function to refresh your product list
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    $('#' + key + '-error').text(value[0]);
                                });
                            } else {
                                toastr.error('An error occurred. Please try again.');
                            }
                        }
                    });
                });
            });
            //end handle submit
            //// end add product ajax

            ////bulk delete products
            $(document).on('click', '#bulk_delete_products', function() {

                var id = [];
                $('.products_checkbox:checked').each(function() {
                    id.push($(this).val());
                });

                if (id.length < 1) {
                    Swal.fire({
                        title: "Oopss...",
                        text: `Please select product that you wanna delete!`,
                        icon: "error",

                    })

                    return
                }
                Swal.fire({
                    title: "Are you sure?",
                    text: `Wanna delete this ${id.length} items ? You won't be able to revert this!`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('product-data.destroyall') }}",
                            type: 'DELETE',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                table.ajax.reload(); // Refetch DataTable
                            },
                            error: function(xhr) {
                                toastr.error('An error occurred. Please try again.');
                                table.ajax.reload();
                            }
                        });
                    }
                });

            });
            ////end bulk delete products

            ///// update product
            // Event handler untuk tombol edit
            $('body').on('click', '.product-edit-btn', function() {
                let productId = $(this).data('id');

                // Ambil data produk menggunakan AJAX
                $.ajax({
                    url: "{{ url('product-data') }}/" + productId + "/edit",
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data produk
                        $('#edit_product_id').val(response.id);
                        $('#edit_code').val(response.code);
                        $('#edit_name').val(response.name);
                        $('#edit_brand').val(response.brand);
                        $('#edit_purchase_price').val(response.purchase_price);
                        $('#edit_selling_price').val(response.selling_price);
                        $('#edit_discount').val(response.discount);
                        $('#edit_stock').val(response.stock);

                        // Populate category dropdown
                        let categoryOptions = '';
                        $.each(response.categories, function(index, category) {
                            let selected = (category.id == response.category_id) ? 'selected' : '';
                            categoryOptions +=
                                `<option value="${category.id}" ${selected}>${category.name}</option>`;
                        });
                        $('#edit_category_id').html(categoryOptions);

                        // Tampilkan modal
                        $('#edit-product-modal').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            });

            // Handle form submission untuk edit product
            $('#edit-product-form').on('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                let productId = $('#edit_product_id').val();

                let data = {
                    id: productId,
                    code: $('#edit_code').val(),
                    name: $('#edit_name').val(),
                    category_id: $('#edit_category_id').val(),
                    brand: $('#edit_brand').val(),
                    purchase_price: $('#edit_purchase_price').val(),
                    selling_price: $('#edit_selling_price').val(),
                    discount: $('#edit_discount').val(),
                    stock: $('#edit_stock').val()
                }


                $.ajax({
                    url: "{{ url('product-data') }}/" + productId,
                    type: 'PUT',
                    data: data,
                    success: function(response) {
                        toastr.success('Product updated successfully');
                        $('#edit-product-modal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#edit_' + key).addClass('is-invalid');
                                $('#edit_' + key + '-error').text(value[0]);
                            });
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }

                    }
                });
            });
            ///// end update product

            // Print products barcode
            $(document).on('click', '#print_products_barcode', function() {
                var id = [];
                $('.products_checkbox:checked').each(function() {
                    id.push($(this).val());
                });

                if (id.length < 1) {
                    Swal.fire({
                        title: "Oops...",
                        text: "Please select products that you want to print barcode for!",
                        icon: "error",
                    });
                    return;
                }

                // Construct the URL with query parameters
                var url = "{{ route('product-data.printbarcode') }}?" + $.param({
                    id: id
                });

                // Open a new window
                var printWindow = window.open(url, 'PrintWindow',
                    'width=1000,height=800,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');

                // Focus on the new window
                if (printWindow) {
                    printWindow.focus();
                }
            });
        </script>
    @endpush
@endsection
