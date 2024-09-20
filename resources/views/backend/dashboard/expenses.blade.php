@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Expenses</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Expenses</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn  btn-md btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#add-expense-modal"><i class="fe fe-plus"
                                aria-label="fe fe-plus"></i>Expense</button>
        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable-expenses table table-stripped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Nominal</th>
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

    {{-- modal add expense --}}


    <div class="modal fade" id="add-expense-modal" tabindex="-1" role="dialog" aria-labelledby="add-expense-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-expense-modal">Add Expense</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-expense-form" class="px-3">
                        @csrf
                
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" cols="5" required></textarea>
                            <div class="invalid-feedback" id="description-error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal" step="0.01" min="0">
                            <div class="invalid-feedback" id="nominal-error"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- end modal add expense --}}

    {{-- modal edit expense --}}
    <div class="modal fade" id="edit-expense-modal" tabindex="-1" role="dialog" aria-labelledby="edit-expense-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit-expense-modal">Edit Expense</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-expense-form" class="px-3">
                        @csrf

                        <input type="hidden" id="edit_expense_id" name="id">
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="edit_description" rows="5" cols="5" required></textarea>
                            <div class="invalid-feedback" id="edit_description-error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_nominal" class="form-label">Nominal</label>
                            <input type="number" class="form-control" id="edit_nominal" name="edit_nominal" step="0.01" min="0">
                            <div class="invalid-feedback" id="edit_nominal-error"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal edit expense --}}

    @push('scripts')
        <script>
            //// fetch & populate datatable
            let table;
            $(document).ready(function() {
                table = $('.datatable-expenses').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('expense-data') }}",
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
                            data: 'description',
                            name: 'description',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'nominal',
                            name: 'nominal',
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

             //// add expense ajax
             $(document).ready(function() {
                $('#add-expense-form').on('submit', function(e) {
                    e.preventDefault();

                    // Reset previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('expense-data.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            toastr.success(response.message);
                            $('#add-expense-modal').modal('hide');
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
            //// end add expense ajax

            ////delete expense ajax

            $('body').on("click", '.expense-delete-btn', function() {
                var expenseId = $(this).data('id');

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
                            url: "{{ url('expense-data') }}/" + expenseId,
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

            ////end delete expense ajax

            ///// update expense
            // Event handler untuk tombol edit
            $('body').on('click', '.expense-edit-btn', function() {
                let expenseId = $(this).data('id');

                // Ambil data menggunakan AJAX
                $.ajax({
                    url: "{{ url('expense-data') }}/" + expenseId + "/edit",
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data
                        $('#edit_expense_id').val(response.id);
                        $('#edit_description').val(response.description);
                        $('#edit_nominal').val(response.nominal);
               
                        // Tampilkan modal
                        $('#edit-expense-modal').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            });

            // Handle form submission untuk edit expense
            $('#edit-expense-form').on('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                let expenseId = $('#edit_expense_id').val();

                let data = {
                    description: $('#edit_description').val(),
                    nominal: $('#edit_nominal').val(),
                }

                $.ajax({
                    url: "{{ url('expense-data') }}/" + expenseId,
                    type: 'PUT',
                    data: data,
                    success: function(response) {
                        toastr.success(response.message);
                        $('#edit-expense-modal').modal('hide');
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
            ///// end update expense

        </script>
    @endpush
@endsection
