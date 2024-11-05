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
        <div class="col-md-8">
        <div class="card">
                <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <h5 class="mb-3">Informasi Pribadi</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Sebagai</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="0" {{ $user->role === 'costumer' ? 'selected' : '' }}>Customer</option>
                            <option value="1" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr>

                    <!-- Contact Information Section -->
                    <h5 class="mb-3">Informasi Kontak</h5>

                    <div class="mb-3">
                        <label for="no_telepone" class="form-label">Nomor Telephone / WhatsApp</label>
                        <input type="text" class="form-control" id="no_telepone" name="no_telepone" value="{{ optional($user->userDetail)->no_telepone }}">
                        @error('no_telepone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ optional($user->addresses->first())->alamat }}">
                        @error('alamat')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kota" class="form-label">Kota</label>
                        <input type="text" class="form-control" id="kota" name="kota" value="{{ optional($user->addresses->first())->kota }}">
                        @error('kota')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ optional($user->addresses->first())->provinsi }}">
                        @error('provinsi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tambahan" class="form-label">Detail Alamat</label>
                        <input type="text" class="form-control" id="tambahan" name="tambahan" value="{{ optional($user->addresses->first())->tambahan }}">
                        @error('tambahan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kode_pos" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="{{ optional($user->addresses->first())->kode_pos }}">
                        @error('kode_pos')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="perusahaan" class="form-label">Perusahaan</label>
                        <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ optional($user->userDetail)->perusahaan }}">
                        @error('perusahaan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr>

                    <!-- Personal Details Section -->
                    <h5 class="mb-3">Detail Pribadi</h5>

                    <div class="mb-3">
                        <label for="lahir" class="form-label">Tanggal lahir</label>
                        <input type="date" class="form-control" id="lahir" name="lahir" value="{{ optional($user->userDetail)->lahir }}">
                        @error('lahir')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="Laki-Laki" {{ optional($user->userDetail)->jenis_kelamin === 'Laki-Laki' ? 'selected' : '' }}>Male</option>
                            <option value="Perempuan" {{ optional($user->userDetail)->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('jenis_kelamin')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </form>
            </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ubah Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update.password', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
    
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                        </div>
    
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                        </div>
    
                        <button type="submit" class="btn btn-success">Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
