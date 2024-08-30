<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'lahir' => $request->lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
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
    // Validate the input data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|string|min:8|confirmed', // Make password nullable to allow optional update
        'role' => 'required|in:0,1', // 0 for customer, 1 for admin
    ]);

    // Find the user
    $user = User::findOrFail($id);

    // Check if a new password is being set and hash it
    if ($request->filled('password')) {
        $validatedData['password'] = bcrypt($request->password);
    } else {
        // Remove the password field if it wasn't filled, so it won't be updated
        unset($validatedData['password']);
    }

    // Update the user's data
    $user->update($validatedData);

    // Find or create the UserDetail record
    $userDetail = UserDetail::firstOrNew(['user_id' => $id]);

    // Update the UserDetail data
    $userDetail->no_telepone = $request->no_telepone;
    $userDetail->alamat = $request->alamat;
    $userDetail->kota = $request->kota;
    $userDetail->provinsi = $request->provinsi;
    $userDetail->perusahaan = $request->perusahaan;
    $userDetail->kode_pos = $request->kode_pos;
    $userDetail->lahir = $request->lahir;
    $userDetail->jenis_kelamin = $request->jenis_kelamin;
    $userDetail->save(); // Save the record (create or update)

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}




    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
