@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Suppliers</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Suppliers</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn  btn-md btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#add-supplier-modal"><i class="fe fe-plus"
                                aria-label="fe fe-plus"></i>Supplier</button>
        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable-suppliers table table-stripped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
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
    </div>

    {{-- modal add supplier --}}


    <div class="modal fade" id="add-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="add-supplier-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-supplier-modal">Add Supplier</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-supplier-form" class="px-3">
                        @csrf
                       
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="5" cols="5"></textarea>
                            <div class="invalid-feedback" id="address-error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Company Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="+6285152044823" pattern="\+62[0-9]{10,13}">
                            <div class="invalid-feedback" id="phone-error"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- end modal add supplier --}}

    {{-- modal edit supplier --}}
    <div class="modal fade" id="edit-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="edit-supplier-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit-supplier-modal">Edit Supplier</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-supplier-form" class="px-3">
                        @csrf

                        <input type="hidden" id="edit_supplier_id" name="id">
                       
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback" id="edit_name-error"></div>
                        </div>
                
                        <div class="mb-3">
                            <label for="edit_address" class="form-label">Address</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="5" cols="5"></textarea>
                            <div class="invalid-feedback" id="edit_address-error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Company Phone</label>
                            <input type="tel" class="form-control" id="edit_phone" name="phone" placeholder="+6285152044823" pattern="\+62[0-9]{10,13}" required>
                            <div class="invalid-feedback" id="edit_phone-error"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal edit supplier --}}

    @push('scripts')
        <script>
            //// fetch & populate datatable
            let table;
            $(document).ready(function() {
                table = $('.datatable-suppliers').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('supplier-data') }}",
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
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
            //// end fetch & populate datatable

            ////setup ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ////end setup ajax

             //// add supplier ajax
             $(document).ready(function() {
                $('#add-supplier-form').on('submit', function(e) {
                    e.preventDefault();

                    // Reset previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('supplier-data.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            toastr.success(response.message);
                            $('#add-supplier-modal').modal('hide');
                            // Assuming you have a function to refresh your supplier list
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
            //// end add supplier ajax

            ////delete supplier ajax

            $('body').on("click", '.supplier-delete-btn', function() {
                var supplierId = $(this).data('id');

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
                            url: "{{ url('supplier-data') }}/" + supplierId,
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

            ////end delete supplier ajax

           


            ///// update supplier
            // Event handler untuk tombol edit
            $('body').on('click', '.supplier-edit-btn', function() {
                let supplierId = $(this).data('id');

                // Ambil data produk menggunakan AJAX
                $.ajax({
                    url: "{{ url('supplier-data') }}/" + supplierId + "/edit",
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data produk
                        $('#edit_supplier_id').val(response.id);
                        $('#edit_name').val(response.name);
                        $('#edit_address').val(response.address);
                        $('#edit_phone').val(response.phone);
               
                        // Tampilkan modal
                        $('#edit-supplier-modal').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            });

            // Handle form submission untuk edit supplier
            $('#edit-supplier-form').on('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                let supplierId = $('#edit_supplier_id').val();

                let data = {
                    name: $('#edit_name').val(),
                    address: $('#edit_address').val(),
                    phone: $('#edit_phone').val(),
                   
                }



                $.ajax({
                    url: "{{ url('supplier-data') }}/" + supplierId,
                    type: 'PUT',
                    data: data,
                    success: function(response) {
                        toastr.success('Product updated successfully');
                        $('#edit-supplier-modal').modal('hide');
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
            ///// end update supplier

        </script>
    @endpush
@endsection
