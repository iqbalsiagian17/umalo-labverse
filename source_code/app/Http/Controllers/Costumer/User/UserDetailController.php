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
use Illuminate\Support\Facades\DB;


class UserDetailController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userAddresses = $user->userAddresses ?: collect(); // Ensure it's a collection
    
        // Check if userAddresses is empty
        if ($userAddresses->isEmpty()) {
            return redirect()->route('user.create')
                ->with('warning', 'Please complete your details.');
        }
    
        return view('customer.user.show', compact('user', 'userAddresses'));
    }




    public function create()
    {
        return view('customer.user.create'); // Pastikan "Customer" dan "User" menggunakan huruf besar pada "C" dan "U"
    }


    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'recipient_name' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'no_telephone' => 'required|string|max:15',
            'address_label' => 'required|string',
            'is_active' => 'boolean',
            'city' => 'required|string',
            'province' => 'required|string',
            'postal_code' => 'required|string|size:5',
            'perusahaan' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // Update data langsung di tabel t_users
        DB::table('t_users')->where('id', $userId)->update([
            'phone_number' => $request->get('no_telephone'),
            'company' => $request->get('perusahaan'),
        ]);

        // Insert data alamat baru ke tabel t_user_addresses
        DB::table('t_user_addresses')->insert([
            'user_id' => $userId,
            'address_label' => $request->get('address_label'),
            'recipient_name' => $request->get('recipient_name'),
            'phone_number' => $request->get('phone_number'),
            'is_active' => (bool) true,
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'province' => $request->get('province'),
            'postal_code' => $request->get('postal_code'),
            'additional_info' => $request->get('additional_info'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user.show')->with('success', 'Detail has been added');
    }

    public function edit()
    {
        $userId = Auth::id();
    
        // Ambil data pengguna langsung dari tabel t_users
        $user = DB::table('t_users')->where('id', $userId)->first();
    
        // Jika pengguna tidak ditemukan, arahkan ke halaman sebelumnya atau tampilkan pesan error
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
    
        return view('customer.user.edit', compact('user'));
    }
    


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'company' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id(); // Ambil ID pengguna yang sedang login

        // Update data pengguna di tabel t_users menggunakan DB facade
        DB::table('t_users')
            ->where('id', $userId)
            ->update([
                'name' => $request->get('name'),
                'phone_number' => $request->get('phone_number'),
                'company' => $request->get('company'),
                'updated_at' => now(), // Mengatur timestamp updated_at
            ]);

        return redirect()->route('user.show')->with('success', 'User details have been updated');
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

    public function editAddress($id)
    {
        // Mengambil alamat pengguna dari database langsung menggunakan DB facade
        $userAddress = DB::table('t_user_addresses')
                        ->where('user_id', Auth::id())
                        ->where('id', $id)
                        ->first();
    
    
        // Mengirim data alamat ke tampilan edit_address
        return view('customer.user.edit_address', compact('userAddress'));
    }
    

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string',
            'address_label' => 'required|string',
            'recipient_name' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'city' => 'required|string',
            'province' => 'required|string',
            'postal_code' => 'required|string|size:5',
            'additional_info' => 'nullable|string',
        ]);

        DB::table('t_user_addresses')
        ->where('user_id', Auth::id())
        ->update(['is_active' => false]);

        // Cek apakah alamat dengan ID yang diberikan milik pengguna yang sedang login
        $userAddress = DB::table('t_user_addresses')
                        ->where('id', $id)
                        ->where('user_id', Auth::id())
                        ->first();

        // Jika alamat tidak ditemukan, arahkan dengan pesan error
        if (!$userAddress) {
            return redirect()->route('user.show')->with('error', 'Address not found.');
        }

        // Update alamat secara langsung di database menggunakan DB facade
        DB::table('t_user_addresses')
            ->where('id', $id)
            ->update([
                'address' => $request->input('address'),
                'address_label' => $request->input('address_label'),
                'recipient_name' => $request->input('recipient_name'),
                'phone_number' => $request->input('phone_number'),
                'is_active' => true,
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'postal_code' => $request->input('postal_code'),
                'additional_info' => $request->input('additional_info'),
                'updated_at' => now(), // Update timestamp
            ]);

        return redirect()->route('user.show')->with('success', 'Address has been updated successfully.');
    }

    public function toggleAddressStatus($id)
{
    $user = Auth::user();

    // Temukan alamat spesifik berdasarkan id
    $address = UserAddress::where('user_id', $user->id)->findOrFail($id);

    // Periksa status saat ini dan toggle
    $address->is_active = !$address->is_active;
    $address->save();

    // Jika alamat diaktifkan, nonaktifkan alamat lainnya
    if ($address->is_active) {
        UserAddress::where('user_id', $user->id)
            ->where('id', '!=', $id)
            ->update(['is_active' => false]);
    }

    return redirect()->back()->with('success', 'Address status updated successfully.');
}



public function createAddress()
{
    return view('customer.user.create_address');
}

public function storeAddress(Request $request)
{
    
    $request->validate([
        'address' => 'required|string',
        'address_label' => 'required|string',  
        'recipient_name' => 'required|string',
        'phone_number' => 'required|string|max:15',
        'city' => 'required|string',
        'province' => 'required|string',
        'postal_code' => 'required|string|size:5',
        'additional_info' => 'nullable|string',
    ]);
    

    DB::table('t_user_addresses')
        ->where('user_id', Auth::id())
        ->update(['is_active' => false]);


    // Create a new address and set it as active
    UserAddress::create([
        'user_id' => Auth::id(),
        'address' => $request->get('address'),
        'address_label' => $request->get('address_label'),
        'recipient_name' => $request->get('recipient_name'),
        'phone_number' => $request->get('phone_number'),
        'is_active' => true,
        'city' => $request->get('city'),
        'province' => $request->get('province'),
        'postal_code' => $request->get('postal_code'),
        'additional_info' => $request->get('additional_info'),
    ]);

    return redirect()->route('user.show')->with('success', 'New address has been added.');
}

}
