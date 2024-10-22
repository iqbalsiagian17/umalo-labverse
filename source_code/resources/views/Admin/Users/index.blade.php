@extends('layouts.Admin.master')

@section('content')

@php
    $userId = Auth::id();

    // Fetch unseen orders count
    $unseenCount = \App\Models\Order::whereDoesntHave('seen_by_users', function($query) use ($userId) {
        $query->where('user_id', $userId);
    })->count();

    // Fetch unseen users (customers) count
    $unseenUserCount = \App\Models\User::where('role', 0) // Assuming role 0 is for customers
        ->whereDoesntHave('seenByAdmins', function($query) use ($userId) {
            $query->where('admin_id', $userId);
        })
        ->count();

    // Fetch unseen users (customers) list
    $unseenUsers = \App\Models\User::where('role', 0)
        ->whereDoesntHave('seenByAdmins', function($query) use ($userId) {
            $query->where('admin_id', $userId);
        })
        ->get()->pluck('id')->toArray();
@endphp

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
                                    @if(in_array($user->id, $unseenUsers))
                                        <span class="badge bg-warning text-dark">New</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->userDetail->perusahaan ?? 'N/A' }}</td>
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
@endsection
