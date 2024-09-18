<?php

namespace App\Http\Controllers\Costumer\User;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserDetailController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userDetail = $user->userDetail;
        $userAddresses = $user->addresses; // This should return a collection, not null

        if (!$userDetail) {
            return redirect()->route('user.create')
                ->with('warning', 'Please complete your details.');
        }

        return view('customer.user.show', compact('userDetail', 'user', 'userAddresses'));
    }


    public function create()
    {
        return view('customer.user.create'); // Pastikan "Customer" dan "User" menggunakan huruf besar pada "C" dan "U"
    }


    public function store(Request $request)
    {
        $request->validate([
            'no_telepone' => 'required|string|max:15',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string|size:5',
            'lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        $userDetail = new UserDetail([
            'user_id' => Auth::id(),
            'no_telepone' => $request->get('no_telepone'),
            'perusahaan' => $request->get('perusahaan'),
            'lahir' => $request->get('lahir'),
            'jenis_kelamin' => $request->get('jenis_kelamin'),
        ]);

        $userAddress = new UserAddress([
            'user_id' => Auth::id(),
            'alamat' => $request->get('alamat'),
            'kota' => $request->get('kota'),
            'provinsi' => $request->get('provinsi'),
            'kode_pos' => $request->get('kode_pos'),
            'tambahan' => $request->get('tambahan'),
            'status' => 'aktif', // Default status
        ]);
        $userAddress->save();

        $userDetail->save();

        return redirect()->route('user.show')->with('success', 'Detail has been added');
    }

    public function edit()
    {
        $userDetail = Auth::user()->userDetail;
        $userAddress = Auth::user()->addresses->where('status', 'aktif')->first(); // Get the first active address

        if (!$userDetail) {
            return redirect()->route('user.create') // Menggunakan nama rute yang benar
                ->with('warning', 'Please complete your details.');
        }

        return view('customer.user.edit', compact('userDetail','userAddress'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'no_telepone' => 'required|string|max:15',
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string|size:5',
            'lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ]);

        $userDetail = Auth::user()->userDetail;

        if (!$userDetail) {
            return redirect()->route('user-detail.create')
                ->with('warning', 'Please complete your details.');
        }

        $userDetail->update([
            'no_telepone' => $request->get('no_telepone'),
            'perusahaan' => $request->get('perusahaan'),
            'lahir' => $request->get('lahir'),
            'jenis_kelamin' => $request->get('jenis_kelamin'),
        ]);

        $userAddress = Auth::user()->address;
        if ($userAddress) {
            $userAddress->update([
                'alamat' => $request->get('alamat'),
                'kota' => $request->get('kota'),
                'provinsi' => $request->get('provinsi'),
                'kode_pos' => $request->get('kode_pos'),
                'tambahan' => $request->get('tambahan'),
                'status' => 'aktif', // Keep the status active
            ]);
        } else {
            // Create a new address if it doesn't exist
            UserAddress::create([
                'user_id' => Auth::id(),
                'alamat' => $request->get('alamat'),
                'kota' => $request->get('kota'),
                'provinsi' => $request->get('provinsi'),
                'kode_pos' => $request->get('kode_pos'),
                'tambahan' => $request->get('tambahan'),
                'status' => 'aktif',
            ]);
        }

        return redirect()->route('user.show')->with('success', 'Detail has been updated');
    }

    public function createPassword(Request $request)
    {
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            $user->password = Hash::make($request->password);
            $user->save();
        } else {
            dd('User is not an instance of User model');
        }

        return redirect()->route('user.show')->with('success', 'Password has been created successfully.');
    }

public function changePassword(Request $request)
{
    // Validasi input dari pengguna
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();  // Ambil pengguna yang sedang login

    // Cek apakah objek $user adalah instance dari model User
    if ($user instanceof \App\Models\User) {

        // Cek apakah password saat ini cocok dengan yang di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        // Perbarui password dengan password baru yang di-hash
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('user.show')->with('success', 'Password has been changed successfully.');
    } else {
        dd('User is not an instance of User model');
    }
}

public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'foto_profile' => 'required|image|max:2048', // 2048 KB = 2 MB
        ]);

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            $imagePath = null;
            if ($request->hasFile('foto_profile')) {
                // Get the uploaded file
                $image = $request->file('foto_profile');
                $slug = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
                $newImageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();

                // Move the image to the desired directory
                $image->move(public_path('uploads/user/'), $newImageName);

                // Path to be saved in the database
                $imagePath = 'uploads/user/' . $newImageName;

                // Delete old photo if it exists
                if ($user->foto_profile) {
                    Storage::delete('public/' . $user->foto_profile);
                }

                // Update the user's profile photo path
                $user->foto_profile = $imagePath;
                $user->save();
            }

            return redirect()->route('user.show')->with('success', 'Profile photo updated successfully.');
        } else {
            dd('User is not an instance of User model');
        }
    }

    public function editAddress()
    {
        $userAddress = Auth::user()->address;

        if (!$userAddress) {
            return redirect()->route('user.create') // Redirect to create if address doesn't exist
                ->with('warning', 'Please add your address details first.');
        }

        return view('customer.user.edit_address', compact('userAddress'));
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string|size:5',
            'tambahan' => 'nullable|string',
        ]);

        $userAddress = Auth::user()->address;

        if (!$userAddress) {
            return redirect()->route('user.create')
                ->with('warning', 'Please complete your address details.');
        }

        $userAddress->update([
            'alamat' => $request->get('alamat'),
            'kota' => $request->get('kota'),
            'provinsi' => $request->get('provinsi'),
            'kode_pos' => $request->get('kode_pos'),
            'tambahan' => $request->get('tambahan'),
            'status' => 'aktif', // Keep the status active
        ]);

        return redirect()->route('user.show')->with('success', 'Address has been updated successfully.');
    }

    public function toggleAddressStatus($id)
{
    $user = Auth::user();

    // Deactivate all addresses for the user
    UserAddress::where('user_id', $user->id)->update(['status' => 'tidak aktif']);

    // Find the specific address by id and toggle its status
    $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

    $address->status = 'aktif';
    $address->save();

    return redirect()->back()->with('success', 'Address status updated successfully.');
}

public function createAddress()
{
    return view('customer.user.create_address');
}

public function storeAddress(Request $request)
{
    $request->validate([
        'alamat' => 'required|string',
        'kota' => 'required|string',
        'provinsi' => 'required|string',
        'kode_pos' => 'required|string|size:5',
        'tambahan' => 'nullable|string',
    ]);

    // Deactivate existing addresses
    UserAddress::where('user_id', Auth::id())->update(['status' => 'tidak aktif']);

    // Create a new address and set it as active
    UserAddress::create([
        'user_id' => Auth::id(),
        'alamat' => $request->get('alamat'),
        'kota' => $request->get('kota'),
        'provinsi' => $request->get('provinsi'),
        'kode_pos' => $request->get('kode_pos'),
        'tambahan' => $request->get('tambahan'),
        'status' => 'aktif',
    ]);

    return redirect()->route('user.show')->with('success', 'New address has been added.');
}





}
