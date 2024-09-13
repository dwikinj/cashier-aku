@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Members</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Members</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn  btn-md btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#add-member-modal"><i class="fe fe-plus"
                                aria-label="fe fe-plus"></i>Member</button>

                        <button type="button" href="{{ route('member-data.printbarcode') }}" id="print_members_barcode"
                            class="btn  btn-md btn-outline-secondary"><i class="fe fe-printer" data-bs-toggle="tooltip"
                                title="Print Membership Card" aria-label="fe fe-printer"></i>Print
                            Membership Card</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable-members table table-stripped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Code</th>
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

    {{-- modal add member --}}
    <div class="modal fade" id="add-member-modal" tabindex="-1" role="dialog" aria-labelledby="add-member-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-member-modal">Add Member</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-member-form" class="px-3">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code">
                            <div class="invalid-feedback" id="code-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="col-form-label col-md-2">Address</label>
                            <textarea id="address" name="address" rows="5" cols="5" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="phone" class="form-control" id="phone" name="phone" placeholder="+6285152044823" pattern="\+62[0-9]{10,13}" required>
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
    {{-- end modal add member --}}

    {{-- modal edit member --}}
    <div class="modal fade" id="edit-member-modal" tabindex="-1" role="dialog" aria-labelledby="edit-member-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit-member-modal">Edit Member</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-member-form" class="px-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_member_id" name="id">
                        <div class="mb-3">
                            <label for="edit_code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="edit_code" name="code" required>
                            <div class="invalid-feedback" id="edit_code-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback" id="edit_name-error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_address" class="col-form-label col-md-2">Address</label>
                            <textarea id="edit_address" name="address" rows="5" cols="5" class="form-control"></textarea>
                            <div class="invalid-feedback" id="edit_address-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="phone" class="form-control" id="edit_phone" name="phone" placeholder="+6285152044823" required>
                            <div class="invalid-feedback" id="edit_phone-error"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal edit member --}}

    @push('scripts')
        <script>
            //// fetch & populate datatable
            let table;
            $(document).ready(function() {
                table = $('.datatable-members').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('member-data') }}",
                    columns: [{
                            data: 'checkbox',
                            name: 'checkbox',
                            orderable: false,
                            searchable: false
                        },
                        {
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

            ////delete member ajax

            $('body').on("click", '.member-delete-btn', function() {
                var memberId = $(this).data('id');

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
                            url: "{{ url('member-data') }}/" + memberId,
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

            ////end delete member ajax

            //// add member ajax
            //handle submit
            $(document).ready(function() {
                $('#add-member-form').on('submit', function(e) {
                    e.preventDefault();

                    // Reset previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    let data = {
                        code: $('#code').val(),
                        name: $('#name').val(),
                        phone: $('#phone').val(),
                        address: $('#address').val(),
                    };
                    
                    $.ajax({
                        url: "{{ route('member-data.store') }}",
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            toastr.success(response.message);
                            $('#add-member-modal').modal('hide');
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
            //// end add member ajax


            ///// update member
            // Event handler untuk tombol edit
            $('body').on('click', '.member-edit-btn', function() {
                let memberId = $(this).data('id');

                // Ambil data produk menggunakan AJAX
                $.ajax({
                    url: "{{ url('member-data') }}/" + memberId + "/edit",
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data produk
                        $('#edit_member_id').val(response.id);
                        $('#edit_code').val(response.code);
                        $('#edit_name').val(response.name);
                        $('#edit_phone').val(response.phone);
                        $('#edit_address').val(response.address);

                        // Tampilkan modal
                        $('#edit-member-modal').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            });

            // Handle form submission untuk edit member
            $('#edit-member-form').on('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                let memberId = $('#edit_member_id').val();

                let data = {
                        id: memberId,
                        code: $('#edit_code').val(),
                        name: $('#edit_name').val(),
                        phone: $('#edit_phone').val(),
                        address: $('#edit_address').val(),
                };

                $.ajax({
                    url: "{{ url('member-data') }}/" + memberId,
                    type: 'PUT',
                    data: data,
                    success: function(response) {
                        toastr.success(response.message);
                        $('#edit-member-modal').modal('hide');
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
            ///// end update member

            // Print members barcode
            $(document).on('click', '#print_members_barcode', function() {
                var id = [];
                $('.members_checkbox:checked').each(function() {
                    id.push($(this).val());
                });

                if (id.length < 1) {
                    Swal.fire({
                        title: "Oops...",
                        text: "Please select members that you want to print barcode for!",
                        icon: "error",
                    });
                    return;
                }

                // Construct the URL with query parameters
                var url = "{{ route('member-data.printbarcode') }}?" + $.param({
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
