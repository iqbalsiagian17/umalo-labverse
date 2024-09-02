<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('userDetail')->where('role', 0)->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:0,1', // 0 for customer, 1 for admin
        ]);
    
        // Hash the password
        $validatedData['password'] = bcrypt($validatedData['password']);
    
        $user = User::create($validatedData);
        UserDetail::create([
            'user_id' => $user->id,
            'no_telepone' => $request->no_telepone,
            'perusahaan' => $request->perusahaan,  // Save perusahaan field
            'lahir' => $request->lahir,
            'jenis_kelamin' => $request->jenis_kelamin,

        ]);

        UserAddress::create([
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'tambahan' => $request->tambahan,

        ]);
    
        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
        $user = User::with('userDetail')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed', // Password is optional in update
            'role' => 'required|in:0,1', // 0 for customer, 1 for admin
        ]);
    
        $user = User::findOrFail($id);
    
        // If password is provided, hash it; otherwise, keep the existing password
        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']); // Remove password from $validatedData if not provided
        }
    
        // Update the user data
        $user->update($validatedData);
    
        // Update user details
        $user->userDetail->update([
            'no_telepone' => $request->no_telepone,
            'perusahaan' => $request->perusahaan,
            'lahir' => $request->lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);
    
        // Update or create user address
        $user->addresses()->updateOrCreate(
            ['user_id' => $user->id], // Criteria for matching the existing record
            [
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'provinsi' => $request->provinsi,
                'kode_pos' => $request->kode_pos,
                'tambahan' => $request->tambahan,
            ]
        );
    
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    




    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
