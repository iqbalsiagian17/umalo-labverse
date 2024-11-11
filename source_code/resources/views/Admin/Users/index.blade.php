@extends('layouts.admin.master')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">User Management</h4>
            <div>
                <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari Nama User" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </div>
        

        <!-- Filter Dropdown and Success Message -->
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Filter Users Dropdown on the left -->
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter Users
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item {{ request('role', '0') == '0' ? 'active' : '' }}" href="{{ route('users.index', ['role' => 0]) }}">Customers</a></li>
                        <li><a class="dropdown-item {{ request('role', '0') == '1' ? 'active' : '' }}" href="{{ route('users.index', ['role' => 1]) }}">Admins</a></li>
                    </ul>
                </div>
            
                <!-- Add New User Button on the right -->
                <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
            </div>
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            

            <!-- User Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Last Online</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    {{ $user->name }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->company ?? 'N/A' }}</td>
                                <td>{{ $user->role == 1 ? 'Admin' : 'Customer' }}</td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if(auth()->check() && auth()->user()->id === $user->id)
                                        <span class="badge bg-primary">Online (You)</span>
                                    @elseif($user->last_login_at && $user->last_login_at->gt(now()->subSeconds(5)))
                                        <span class="badge bg-primary">Online</span>
                                    @elseif($user->last_login_at)
                                        <span class="badge bg-success">{{ $user->last_login_at->diffForHumans() }}</span>
                                    @else
                                        <span class="badge bg-danger">Never Logged In</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-header text-white">
                    <h4 class="card-title">
                        <i class="fas fa-comments me-2"></i> Chat Live by Tawk
                    </h4>
                </div>
                <div class="card-body">
                    <h5 class="text-muted">Tawk.to Account</h5>
                    <p class="mb-1 text-start"><strong>Email:</strong> <a href="mailto:labserveags@gmail.com">labserveags@gmail.com</a></p>
                    <p class="mb-3 text-start"><strong>Password:</strong> <span class="text-danger">ags123.</span></p>
                    <!-- Button to Tawk.to Dashboard -->
                    <div class="text-center">
                        <button onclick="window.open('https://dashboard.tawk.to/login', 'newwindow', 'width=1200,height=600'); return false;" class="btn btn-success">
                            <i class="fas fa-external-link-alt me-2"></i> Go to Tawk.to Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-header text-white">
                    <h4 class="card-title">
                        <i class="fas fa-envelope me-2"></i> Google Account
                    </h4>
                </div>
                <div class="card-body">
                    <h5 class="text-muted">Google Account</h5>
                    <p class="mb-1 text-start"><strong>Email:</strong> <a href="mailto:labserveags@gmail.com">labserveags@gmail.com</a></p>
                    <p class="mb-3 text-start"><strong>Password:</strong> <span class="text-danger">labverse123123</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection
