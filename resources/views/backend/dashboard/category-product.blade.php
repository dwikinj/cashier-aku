@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Category</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Category</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn  btn-md btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#edit-category-modal"><i class="fe fe-plus" data-bs-toggle="tooltip"
                                title="Add Category" aria-label="fe fe-plus"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-stripped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
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

    {{-- modal edit category --}}
    <div id="edit-category-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <div class="auth-logo">
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-lg">
                                    <img src="assets/img/logo.png" alt="" height="42" />
                                </span>
                            </a>
                        </div>
                    </div>
                    <form class="px-3" id="edit-category-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input class="form-control" type="text" id="name" name="name" />
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-primary submit-btn" type="submit">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end modal edit category --}}

    @push('scripts')
        <script>
            let table;
            $(document).ready(function() {
                table = $('.datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('category-data') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'name',
                            name: 'Name',
                            orderable: true,
                            searchable: true,

                        },
                        {
                            data: 'action',
                            name: 'Action',
                            orderable: false,
                            searchable: false,
                        }
                    ]
                });


            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#edit-category-form').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ url('category-data') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Sertakan CSRF token
                        name: $('#name').val()
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        table.ajax.reload(); // Refetch DataTable

                        // Tutup modal
                        $('#edit-category-modal').modal('hide');

                        // Hapus nilai input
                        $('#edit-category-form')[0].reset();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            });

            $('body').on("click", '.category-delete-btn', function() {
                var categoryId = $(this).data('id');

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
                            url: "{{ url('category-data') }}/" + categoryId,
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

            $('body').on("click", '.category-edit-btn', function() {
            var row = $(this).closest('tr');
            var categoryId = $(this).data('id');
            var categoryName = row.find('td:eq(1)').text();

            // Ubah sel name menjadi input
            row.find('td:eq(1)').html('<input type="text" class="form-control" value="' + categoryName + '">');

            // Ubah tombol aksi menjadi update dan cancel
            row.find('td:eq(2)').html(
                '<button class="btn btn-success btn-sm category-update-btn" data-id="' + categoryId + '">Update</button> ' +
                '<button class="btn btn-secondary btn-sm category-cancel-btn">Cancel</button>'
            );
        });

        $('body').on("click", '.category-cancel-btn', function() {
            var row = $(this).closest('tr');
            var categoryName = row.find('input').val();

            // Kembalikan sel name ke teks asli
            row.find('td:eq(1)').text(categoryName);

            // Kembalikan tombol aksi ke edit dan delete
            row.find('td:eq(2)').html(
                '<a href="#" data-id="' + row.find('.category-update-btn').data('id') + '" class="btn btn-warning btn-sm category-edit-btn">Edit</a> ' +
                '<a href="#" data-id="' + row.find('.category-update-btn').data('id') + '" class="btn btn-danger btn-sm category-delete-btn">Delete</a>'
            );
        });

        $('body').on("click", '.category-update-btn', function() {
            var row = $(this).closest('tr');
            var categoryId = $(this).data('id');
            var categoryName = row.find('input').val();

            $.ajax({
                url: "{{ url('category-data') }}/" + categoryId,
                type: 'PUT',
                data: {
                    name: categoryName
                },
                success: function(response) {
                    toastr.success(response.message);
                    table.ajax.reload(); // Refetch DataTable
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                    table.ajax.reload(); // Refetch DataTable

                }
            });
        });
        </script>
    @endpush
@endsection
