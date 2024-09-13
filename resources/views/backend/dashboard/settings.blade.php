@extends('backend.base_dashboard')
@section('dashboard')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Settings</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
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
                            <input type="hidden" id="setting_id" name="setting_id" value="{{ $setting->id ?? '' }}">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $setting->company_name ?? '' }}">
                                <div class="invalid-feedback" id="company_name-error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="company_address" class="form-label">Company Address</label>
                                <textarea class="form-control" id="company_address" name="company_address" rows="5" cols="5">{{ $setting->company_address ?? '' }}</textarea>
                                <div class="invalid-feedback" id="company_address-error"></div>
                            </div>
                           
                            <div class="mb-3">
                                <label for="company_phone" class="form-label">Company Phone</label>
                                <input type="tel" class="form-control" id="company_phone" name="company_phone" placeholder="+6285152044823" pattern="\+62[0-9]{10,13}" value="{{ $setting->company_phone ?? '' }}">
                                <div class="invalid-feedback" id="company_phone-error"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Logo</label>
                                <input class="form-control" name="logo_path" type="file" id="logo_path">
                                <div id="logo_path_preview" class="mt-2">
                                    <img src="{{ $setting->logo_path ? Storage::url($setting->logo_path) : asset('storage/default/company_logo.png') }}" alt="Company Logo" style="max-width: 200px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Membership Card Design</label>
                                <input id="member_card_path" class="form-control" name="member_card_path" type="file">
                                <div id="member_card_path_preview" class="mt-2">
                                    <img src="{{ $setting->member_card_path ? Storage::url($setting->member_card_path) : asset('storage/default/card_member.png') }}" alt="Membership Card" style="max-width: 200px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="member_discount" class="form-label">Member Discount (%)</label>
                                <input type="number" class="form-control" id="member_discount" name="member_discount" min="0" max="100" value="{{ $setting->member_discount ?? 0 }}">
                                <div class="invalid-feedback" id="member_discount-error"></div>
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
            $('.invalid-feedback').text('');

            let formData = new FormData(this);        

            let settingId = $('#setting_id').val();
            let url = settingId ? "{{ url('settings') }}/" + settingId : "{{ route('settings.store') }}";
            let method = 'POST';

            if (settingId) {
                formData.append('id', $('#setting_id').val());
                formData.append('_method','PUT');
            }

            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
                        

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    $('#setting_id').val(response.setting.id);
                    
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

        // Image preview functionality
        function readURL(input, previewDiv) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $(previewDiv).html('<img src="' + e.target.result + '" alt="Preview" style="max-width: 200px;">');
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