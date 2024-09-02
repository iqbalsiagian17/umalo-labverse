@extends('layouts.customer.master')

@section('content')

<style>
    /* Styling untuk nav-tabs */
    .nav-tabs {
        border-bottom: none; /* Menghilangkan garis bawah tab */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Menambahkan shadow pada nav */
        background-color: #fff; /* Memberikan background putih */
        padding: 10px 15px;
        border-radius: 4px; /* Membuat sudut sedikit melengkung */
    }
    
    /* Styling untuk tab aktif dan hover */
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: #494c57;
        background-color: #f8f9fa;
        font-weight: bold;
        transition: background-color 0.3s ease-in-out;
        border: none; /* Menghilangkan border */
        box-shadow: none; /* Menghilangkan shadow ekstra */
    }
    
    /* Styling untuk tab yang tidak aktif */
    .nav-tabs .nav-link {
        border: none; /* Menghilangkan border pada tab */
        padding: 12px 20px;
        color: #413937;
        transition: color 0.3s ease-in-out;
    }
    
    /* Styling untuk hover pada tab */
    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
        color: #42378C;
    }
    
    /* Styling untuk konten tab */
    .tab-content > .tab-pane {
        padding: 20px;
        border: none; /* Menghilangkan garis border */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Menambahkan shadow */
        background-color: #fff; /* Memberikan background putih */
        border-radius: 4px; /* Membuat sudut konten sedikit melengkung */
    }
    
    
    </style>

    
<div class="container mt-5 mb-5">

    <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="personal-profile-tab" data-bs-toggle="tab" data-bs-target="#personal-profile" type="button" role="tab" aria-controls="personal-profile" aria-selected="true">{{ __('messages.personal_profile') }}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="address-list-tab" data-bs-toggle="tab" data-bs-target="#address-list" type="button" role="tab" aria-controls="address-list" aria-selected="false">{{ __('messages.address_list') }}</button>
        </li>
    </ul>

    <div class="tab-content" id="profileTabsContent">
        <!-- Personal Profile Tab -->
        <div class="tab-pane fade show active" id="personal-profile" role="tabpanel" aria-labelledby="personal-profile-tab">
            <div class="card mb-5 shadow rounded border-0">
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Photo and Actions Section -->
                        <div class="col-md-6 text-center">
                            <div class="card text-center mb-4 shadow rounded border-0">
                                <div class="card border-0">
                                    <div class="card-body text-center">
                                        <img id="profilePhoto" src="{{ $user->foto_profile ? asset('/umalo/umalo-labverse/source_code/public/'.$user->foto_profile) : asset('/umalo/umalo-labverse/source_code/public/assets/images/logo-nobg.png') }}" class="rounded-circle mb-3" alt="{{ __('messages.user_photo') }}" style="width: 250px;">
                                        
                                        <br>
                                
                                        <form id="uploadForm" method="POST" action="{{ route('user.uploadProfilePhoto') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <input type="file" name="foto_profile" class="form-control-file" id="fotoProfileInput" style="display: none;">
                                                @if ($errors->has('foto_profile'))
                                                    <small class="text-danger">{{ $errors->first('foto_profile') }}</small>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-sm text-white" style="background: #42378C;" onclick="document.getElementById('fotoProfileInput').click();">{{ __('messages.choose_photo') }}</button>
                                        </form>
                                
                                        <p class="text-muted mt-3">{{ __('messages.photo_file_requirements') }}</p>
                                    </div>
                                </div>
                                
                                <script>
                                    document.getElementById('fotoProfileInput').addEventListener('change', function() {
                                        var form = document.getElementById('uploadForm');
                                        form.submit(); // Automatically submit the form when a file is selected
                                    });
                                
                                    document.getElementById('fotoProfileInput').addEventListener('change', function(event) {
                                        var reader = new FileReader();
                                        reader.onload = function(){
                                            var output = document.getElementById('profilePhoto');
                                            output.src = reader.result; // Update the image src to the selected file
                                        };
                                        reader.readAsDataURL(event.target.files[0]);
                                    });
                                </script>
                                
                            </div>
                            <div class="d-flex flex-column mt-4">
                                @if (is_null($user->password) || $user->password === '')
                                    <button type="button" class="btn text-white mb-2" style="background: #42378C;" data-bs-toggle="modal" data-bs-target="#createPasswordModal">{{ __('messages.create_password') }}</button>
                                @else
                                    <button type="button" class="btn text-white mb-2" style="background: #42378C;" data-bs-toggle="modal" data-bs-target="#changePasswordModal">{{ __('messages.change_password') }}</button>
                                @endif
                                <a href="{{ route('user.edit') }}" class="btn text-white" style="background: #42378C;">{{ __('messages.edit_data') }}</a>
                            </div>
                        </div>
        
                        <!-- Account Information Section -->
                        <div class="col-md-6">
                            <h4 class="mb-3">{{ __('messages.account_bio') }}</h4>
                            <p><strong>{{ __('messages.name') }}:</strong> {{ $user->name }}</p>
                            <p><strong>{{ __('messages.email') }}:</strong> {{ $user->email }} <span class="badge bg-success">{{ __('messages.verified') }}</span></p>
                            <p><strong>{{ __('messages.phone_number') }}:</strong> {{ $userDetail->no_telepone }}</p>
                            <p><strong>{{ __('messages.date_of_birth') }}:</strong> {{ $userDetail->lahir }}</p>
                            <p><strong>{{ __('messages.gender') }}:</strong> {{ $userDetail->jenis_kelamin }}</p>
                            <p><strong>{{ __('messages.company') }}:</strong> {{ $userDetail->perusahaan }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Address List Tab -->
        <div class="tab-pane fade" id="address-list" role="tabpanel" aria-labelledby="address-list-tab">
            <div class="card mb-5 shadow rounded border-0">
                <div class="card-body">
                    @foreach($userAddresses as $index => $userAddress)
                    <div class="card p-3 mb-3" style="border: 1px solid {{ $userAddress->status == 'aktif' ? '#28a745' : '#dc3545' }}; background-color: {{ $userAddress->status == 'aktif' ? '#f0fff4' : '#f8d7da' }};">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">
                                    <strong>Alamat {{ $index + 1 }}</strong> 
                                    <span class="badge {{ $userAddress->status == 'aktif' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        {{ $userAddress->status }}
                                    </span>
                                </h5>
                                <p class="mb-0">{{ $user->name }}, {{ $userDetail->perusahaan }}</p>
                                <p class="mb-0">{{ $userDetail->no_telepone }}</p>
                                <p class="mb-0">{{ $userAddress->alamat }}, {{ $userAddress->kota }}, {{ $userAddress->provinsi }} {{ $userAddress->kode_pos }}, {{ $userAddress->tambahan }}</p>
                            </div>
                            <div>
                                <i class="bi bi-check-circle" style="color: #28a745; font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('user.editAddress', $userAddress->id) }}" class="btn text-white" style="background: #42378C;">{{ __('messages.edit_address') }}</a>
                            <form method="POST" action="{{ route('user.toggleAddressStatus', $userAddress->id) }}" style="margin-left: 10px;">
                                @csrf
                                <button type="submit" class="btn {{ $userAddress->status == 'aktif' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $userAddress->status == 'aktif' ? __('messages.deactivate') : __('messages.activate') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
        
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('user.createAddress') }}" class="btn btn-success">{{ __('messages.add_new_address') }}</a>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<!-- Modal for Creating Password -->
<div class="modal fade" id="createPasswordModal" tabindex="-1" aria-labelledby="createPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createPasswordModalLabel">{{ __('messages.create_password') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn text-white" style="background: #42378C;">{{ __('messages.save_password') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Changing Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="changePasswordForm" method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">{{ __('messages.change_password') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">{{ __('messages.current_password') }}</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">{{ __('messages.new_password') }}</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">{{ __('messages.confirm_new_password') }}</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                    <div id="passwordMismatchAlert" class="alert alert-danger d-none">
                        {{ __('messages.password_mismatch') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="submit" class="btn text-white" style="background: #42378C;">{{ __('messages.change_password') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('changePasswordForm').addEventListener('submit', function(event) {
        var newPassword = document.getElementById('new_password').value;
        var confirmPassword = document.getElementById('new_password_confirmation').value;
        var alertBox = document.getElementById('passwordMismatchAlert');

        if (newPassword !== confirmPassword) {
            event.preventDefault(); // Prevent form submission
            alertBox.classList.remove('d-none'); // Show the alert
        } else {
            alertBox.classList.add('d-none'); // Hide the alert if passwords match
        }
    });
</script>
@endsection
