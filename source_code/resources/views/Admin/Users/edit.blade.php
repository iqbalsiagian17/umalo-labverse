@extends('layouts.admin.master')

@section('content')
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<div class="row">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <h5 class="mb-3">Personal Information</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        @if ($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        @if ($errors->has('email'))
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="0" {{ $user->role === "costumer" ? 'selected' : '' }}>Customer</option>
                            <option value="1" {{ $user->role === "admin" ? 'selected' : '' }}>Admin</option>
                        </select>
                        @if ($errors->has('role'))
                            <small class="text-danger">{{ $errors->first('role') }}</small>
                        @endif
                    </div>

                    <hr>

                    <!-- Password Section -->
                    <h5 class="mb-3">Change Password</h5>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        @if ($errors->has('password'))
                            <small class="text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Leave blank to keep current password">
                        @if ($errors->has('password_confirmation'))
                            <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                        @endif
                    </div>

                    <hr>

                    <!-- Contact Information Section -->
                    <h5 class="mb-3">Contact Information</h5>

                    <div class="mb-3">
                        <label for="no_telepone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="no_telepone" name="no_telepone" value="{{ optional($user->userDetail)->no_telepone }}">
                        @if ($errors->has('no_telepone'))
                            <small class="text-danger">{{ $errors->first('no_telepone') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Address</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ optional($user->userDetail)->alamat }}">
                        @if ($errors->has('alamat'))
                            <small class="text-danger">{{ $errors->first('alamat') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="kota" class="form-label">City</label>
                        <input type="text" class="form-control" id="kota" name="kota" value="{{ optional($user->userDetail)->kota }}">
                        @if ($errors->has('kota'))
                            <small class="text-danger">{{ $errors->first('kota') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="provinsi" class="form-label">Province</label>
                        <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ optional($user->userDetail)->provinsi }}">
                        @if ($errors->has('provinsi'))
                            <small class="text-danger">{{ $errors->first('provinsi') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="perusahaan" class="form-label">Perusahaan</label>
                        <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ optional($user->userDetail)->perusahaan }}">
                        @if ($errors->has('perusahaan'))
                            <small class="text-danger">{{ $errors->first('perusahaan') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="kode_pos" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ optional($user->userDetail)->kode_pos }}">
                        @if ($errors->has('kode_pos'))
                            <small class="text-danger">{{ $errors->first('kode_pos') }}</small>
                        @endif
                    </div>

                    <hr>

                    <!-- Personal Details Section -->
                    <h5 class="mb-3">Personal Details</h5>

                    <div class="mb-3">
                        <label for="lahir" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="lahir" name="lahir" value="{{ optional($user->userDetail)->lahir }}">
                        @if ($errors->has('lahir'))
                            <small class="text-danger">{{ $errors->first('lahir') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Gender</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-Laki" {{ optional($user->userDetail)->jenis_kelamin === 'Laki-Laki' ? 'selected' : '' }}>Male</option>
                            <option value="Perempuan" {{ optional($user->userDetail)->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Female</option>
                        </select>
                        @if ($errors->has('jenis_kelamin'))
                            <small class="text-danger">{{ $errors->first('jenis_kelamin') }}</small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
@endsection
