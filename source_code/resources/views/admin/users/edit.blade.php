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

                    <hr>

                    <!-- Personal Details Section -->
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
