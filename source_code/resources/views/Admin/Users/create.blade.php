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
            <div class="card-header">
                <h2 class="card-title">User Information</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @if ($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="perusahaan" class="form-label">Perusahaan</label>
                        <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ old('perusahaan') }}" required>
                        @if ($errors->has('perusahaan'))
                            <small class="text-danger">{{ $errors->first('perusahaan') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @if ($errors->has('password'))
                            <small class="text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        @if ($errors->has('password_confirmation'))
                            <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="0">Customer</option>
                            <option value="1">Admin</option>
                        </select>
                        @if ($errors->has('role'))
                            <small class="text-danger">{{ $errors->first('role') }}</small>
                        @endif
                    </div>
                    <hr>
                    <h3>Contact Information</h3>
                    <div class="form-group">
                        <label for="no_telepone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="no_telepone" name="no_telepone" value="{{ old('no_telepone') }}" required>
                        @if ($errors->has('no_telepone'))
                            <small class="text-danger">{{ $errors->first('no_telepone') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="form-label">Address</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                        @if ($errors->has('alamat'))
                            <small class="text-danger">{{ $errors->first('alamat') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="kota" class="form-label">City</label>
                        <input type="text" class="form-control" id="kota" name="kota" value="{{ old('kota') }}" required>
                        @if ($errors->has('kota'))
                            <small class="text-danger">{{ $errors->first('kota') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="provinsi" class="form-label">Province</label>
                        <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ old('provinsi') }}" required>
                        @if ($errors->has('provinsi'))
                            <small class="text-danger">{{ $errors->first('provinsi') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="tambahan" class="form-label">Detail Lebih Alamat</label>
                        <input type="text" class="form-control" id="tambahan" name="tambahan" value="{{ old('tambahan') }}" required>
                        @if ($errors->has('tambahan'))
                            <small class="text-danger">{{ $errors->first('tambahan') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="kode_pos" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required>
                        @if ($errors->has('kode_pos'))
                            <small class="text-danger">{{ $errors->first('kode_pos') }}</small>
                        @endif
                    </div>
                    <hr>
                    <h3>Personal Information</h3>
                    <div class="form-group">
                        <label for="lahir" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="lahir" name="lahir" value="{{ old('lahir') }}" required>
                        @if ($errors->has('lahir'))
                            <small class="text-danger">{{ $errors->first('lahir') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin" class="form-label">Gender</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-Laki">Male</option>
                            <option value="Perempuan">Female</option>
                        </select>
                        @if ($errors->has('jenis_kelamin'))
                            <small class="text-danger">{{ $errors->first('jenis_kelamin') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </form>
            </div>
        </div>
        </div>
@endsection
