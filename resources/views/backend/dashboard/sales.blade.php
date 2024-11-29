@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Sales</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Sales</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable-sales table table-stripped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Member Code</th>
                                        <th>Total Items</th>
                                        <th>Total Price</th>
                                        <th>Discount</th>
                                        <th>Paid</th>
                                        <th>Received</th>
                                        <th>Cashier</th>
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


    {{-- modal edit sale --}}
    <div class="modal fade" id="edit-sale-modal" tabindex="-1" role="dialog" aria-labelledby="edit-sale-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit-sale-modal">Edit Sale</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-sale-form" class="px-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_sale_id" name="id">
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
    {{-- end modal edit sale --}}

    @push('scripts')
        <script>
            //// fetch & populate datatable
            let table;
            $(document).ready(function() {
                table = $('.datatable-sales').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.sales.datatable') }}",
                    columns: [
                        {
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
                            data: 'member_code',
                            name: 'member_code',
                            orderable: false,
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
                            searchable: true
                        },
                        {
                            data: 'paid',
                            name: 'paid',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'received',
                            name: 'received',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'cashier',
                            name: 'cashier',
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

            ////setup ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ////end setup ajax

            ////delete sale ajax

            $('body').on("click", '.sale-delete-btn', function() {
                var saleId = $(this).data('id');

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
                            url: "{{ url('sale-data') }}/" + saleId,
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

            ////end delete sale ajax

        </script>
    @endpush
@endsection
