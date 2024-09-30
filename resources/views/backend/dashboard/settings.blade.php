@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Settings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form id="setting-form" class="px-3" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" id="setting_id" name="setting_id" value="{{ $setting->id }}">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name"
                                    value="{{ $setting->company_name ?? '' }}">
                                <span class="text-danger" id="company_name-error"></span>
                            </div>
                            <div class="mb-3">
                                <label for="company_address" class="form-label">Company Address</label>
                                <textarea class="form-control" id="company_address" name="company_address" rows="5" cols="5">{{ $setting->company_address ?? '' }}</textarea>
                                <span class="text-danger" id="company_address-error"></span>
                            </div>
                            <div class="mb-3">
                                <label for="company_phone" class="form-label">Company Phone</label>
                                <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                    placeholder="+6285152044823" pattern="\+62[0-9]{10,13}"
                                    value="{{ $setting->company_phone ?? '' }}">
                                <span class="text-danger" id="company_phone-error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company Logo</label>
                                <input class="form-control" name="logo_path" type="file" id="logo_path">
                                <span class="text-danger" id="logo_path-error"></span>
                                <div id="logo_path_preview" class="mt-2">
                                    <img src="{{ asset($setting->logo_path) }}" alt="Company Logo"
                                        style="max-width: 200px;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Membership Card Design</label>
                                <input id="member_card_path" class="form-control" name="member_card_path" type="file">
                                <span class="text-danger" id="member_card_path-error"></span>
                                <div id="member_card_path_preview" class="mt-2">
                                    <img src="{{ asset($setting->member_card_path) }}" alt="Membership Card"
                                        style="max-width: 200px;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="member_discount" class="form-label">Member Discount (%)</label>
                                <input type="number" class="form-control" id="member_discount" name="member_discount"
                                    min="0" max="100" value="{{ $setting->member_discount ?? 0 }}">
                                <span class="text-danger" id="member_discount-error"></span>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle form submission
            $('#setting-form').on('submit', function(e) {
                e.preventDefault();

                // Reset previous errors
                $('.is-invalid').removeClass('is-invalid');
                $('.text-danger').text('');

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.settings.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message);
                        window.location.reload()
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                        } else {
                            toastr.error(xhr.responseJSON.message);
                        }
                    }
                });
            });

            // Image preview functionality
            function readURL(input, previewDiv) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $(previewDiv).html('<img src="' + e.target.result +
                            '" alt="Preview" style="max-width: 200px;">');
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#logo_path").change(function() {
                readURL(this, "#logo_path_preview");
            });

            $("#member_card_path").change(function() {
                readURL(this, "#member_card_path_preview");
            });
        });
    </script>
@endpush
