<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\ShippingService;
use Illuminate\Http\Request;

class ShippingServiceController extends Controller
{
    public function index()
    {
        $shippingServices = ShippingService::paginate(10); // Adjust the number as needed
        return view('admin.masterdata.shippingservice.index', compact('shippingServices'));
    }
    

    public function create()
    {
        return view('admin.masterdata.shippingservice.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:t_shipping_services,name',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        // Process the image upload
        $imagePath = null;
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/shipping_services'), $imageName);
            $imagePath = 'uploads/shipping_services/' . $imageName;
        }

        // Save data to the database with the 'images' column
        ShippingService::create([
            'name' => $request->name,
            'images' => $imagePath, // Ensure this matches the 'images' column in your database
        ]);

        return redirect()->route('admin.masterdata.shippingservice.index')->with('success', 'Shipping Service created successfully.');
    }



    public function edit($id)
    {
        $shippingService = ShippingService::findOrFail($id);
        return view('admin.masterdata.shippingservice.edit', compact('shippingService'));
    }

    public function update(Request $request, $id)
    {
        $shippingService = ShippingService::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:t_shipping_services,name,' . $shippingService->id,
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        // Process image upload if a new image is provided
        if ($request->hasFile('images')) {
            // Delete the old image if it exists
            if ($shippingService->images && file_exists(public_path($shippingService->images))) {
                unlink(public_path($shippingService->images));
            }

            // Upload and save new image
            $image = $request->file('images');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/shipping_services'), $imageName);
            $imagePath = 'uploads/shipping_services/' . $imageName;
        } else {
            // Keep the existing image path if no new image is uploaded
            $imagePath = $shippingService->images;
        }

        // Update the shipping service record
        $shippingService->update([
            'name' => $request->name,
            'images' => $imagePath, // Ensure it matches the 'images' column in your database
        ]);

        return redirect()->route('admin.masterdata.shippingservice.index')->with('success', 'Shipping Service updated successfully.');
    }


    public function destroy($id)
    {
        $shippingService = ShippingService::findOrFail($id);

        if ($shippingService->image && file_exists(public_path($shippingService->image))) {
            unlink(public_path($shippingService->image));
        }

        $shippingService->delete();

        return redirect()->route('admin.masterdata.shippingservice.index')->with('success', 'Shipping Service deleted successfully.');
    }
}

