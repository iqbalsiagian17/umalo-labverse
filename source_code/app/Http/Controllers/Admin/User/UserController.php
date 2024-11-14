<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserDetail;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
{
    $role = $request->query('role', '0'); // Default to '0' if no role is provided
    $search = $request->input('search');

    // Inisialisasi query
    $query = User::query();

    // Filter berdasarkan role
    if (!is_null($role)) {
        $query->where('role', $role);
    }

    // Filter pencarian berdasarkan nama
    if (!is_null($search)) {
        $query->where('name', 'like', '%' . $search . '%');
    }

    // Paginate hasil pencarian
    $users = $query->paginate(10);

    return view('admin.users.index', compact('users'));
}

    

    public function create()
    {
        return view('admin.users.create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        // Mark the user as seen by the current admin
        if(!$user->seenByAdmins()->where('admin_id', Auth::id())->exists()) {
            $user->seenByAdmins()->attach(Auth::id());
        }

        return view('admin.users.show', compact('user'));
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:t_users,email,' . $id,
        'role' => 'required|in:0,1', // 0 for customer, 1 for admin
    ]);

    $user = User::findOrFail($id);

    // Update the user data
    $user->update($validatedData);

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}


    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('users.index')->with('success', 'Password updated successfully.');
    }





    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function visits()
    {
         // Daily visits for the last 30 days
         $dailyVisits = Visit::selectRaw('DATE(visited_at) as date, COUNT(*) as total')
         ->where('visited_at', '>=', now()->subDays(30))
         ->groupBy('date')
         ->orderBy('date', 'asc')
         ->get();

     // Monthly visits for the last 12 months
     $monthlyVisits = Visit::selectRaw('DATE_FORMAT(visited_at, "%Y-%m") as month, COUNT(*) as total')
         ->where('visited_at', '>=', now()->subYear())
         ->groupBy('month')
         ->orderBy('month', 'asc')
         ->get();

     // Hourly visits for the last 24 hours
     $hourlyVisits = Visit::selectRaw('HOUR(visited_at) as hour, COUNT(*) as total')
         ->where('visited_at', '>=', now()->subDay())
         ->groupBy('hour')
         ->orderBy('hour', 'asc')
         ->get();

        return view('admin.users.visits', compact('dailyVisits', 'monthlyVisits', 'hourlyVisits'));
    }
}
