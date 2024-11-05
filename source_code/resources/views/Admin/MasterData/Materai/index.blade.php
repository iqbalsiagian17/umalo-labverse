@extends('layouts.admin.master')

@section('content')
<div class="row justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-md-6">
        <div class="card d-flex justify-content-center align-items-center p-5">
            <!-- Logo Robot dari W3Schools -->
            <svg class="error-icon" class="text-center" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="currentColor" style="width: 150px; height: auto;" class="mb-4">
                <!-- Body of the robot -->
                <rect x="16" y="16" width="32" height="32" rx="4" ry="4" fill="#343a40"/>
                <!-- Robot's eyes -->
                <circle cx="24" cy="24" r="4" fill="#007bff"/>
                <circle cx="40" cy="24" r="4" fill="#007bff"/>
                <!-- Robot's antenna -->
                <line x1="32" y1="16" x2="32" y2="10" stroke="#343a40" stroke-width="2"/>
                <circle cx="32" cy="8" r="2" fill="#343a40"/>
                <!-- Robot's arms -->
                <line x1="16" y1="32" x2="6" y2="32" stroke="#343a40" stroke-width="2"/>
                <line x1="48" y1="32" x2="58" y2="32" stroke="#343a40" stroke-width="2"/>
                <!-- Robot's mouth (broken) -->
                <path d="M 24 40 Q 32 36, 40 40" stroke="#e3342f" stroke-width="2" fill="none"/>
                <!-- Additional broken lines -->
                <line x1="26" y1="36" x2="24" y2="38" stroke="#e3342f" stroke-width="2"/>
                <line x1="38" y1="36" x2="40" y2="38" stroke="#e3342f" stroke-width="2"/>
            </svg>
            <h1>Dalam Pengembangan</h1>
        </div>
    </div>
</div>
@endsection
