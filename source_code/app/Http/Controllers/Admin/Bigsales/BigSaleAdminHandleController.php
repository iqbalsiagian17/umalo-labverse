<?php

namespace App\Http\Controllers\Admin\Bigsales;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BigSaleAdminHandleController extends Controller
{
    public function index()
    {
        $bigSales = BigSale::paginate(10); // Adjust number per page as desired
        return view('admin.bigsales.index', compact('bigSales'));
    }
    

    public function create()
    {
        $products = Product::with('images')
        ->where('negotiable', 'no')
        ->orderBy('name', 'asc')
        ->get();

        return view('admin.bigsales.create', compact('products'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'banner' => 'nullable|image',
            'modal_image' => 'nullable|image',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'discount_amount' => 'nullable|numeric|min:0|required_without:discount_percentage',
            'discount_percentage' => 'nullable|numeric|min:0|max:100|required_without:discount_amount',
            'status' => 'required|boolean',
            'products' => 'nullable|array', // Validation for product selection
        ]);

        if ($request->input('status') == true && BigSale::where('status', true)->exists()) {
            return back()->withErrors(['status' => 'Only one Big Sale can be active at a time.']);
        }

        if (!empty($request->products)) {
            $negotiableProducts = Product::whereIn('id', $request->products)
                                          ->where('negotiable', 'yes')
                                          ->pluck('name')
                                          ->toArray();
        
            if (!empty($negotiableProducts)) {
                $productNames = implode(', ', $negotiableProducts);
                return back()->withErrors(['products' => "The following negotiable products cannot be included in a Big Sale: $productNames"]);
            }
        }
        

        $data = $request->all();

        // Generate a unique slug based on title
        $data['slug'] = Str::slug($request->title);
        $slugCount = BigSale::where('slug', 'like', "{$data['slug']}%")->count();
        if ($slugCount > 0) {
            $data['slug'] .= '-' . ($slugCount + 1);
        }

        // Handle banner upload if provided
        if ($request->hasFile('banner')) {
            $bannerPath = public_path('bigsales');
            $bannerName = time() . '_' . $request->file('banner')->getClientOriginalName();
            $request->file('banner')->move($bannerPath, $bannerName);
            $data['banner'] = 'bigsales/' . $bannerName;
        }

        if($request->hasFile('modal_image')) {
            $modalPath = public_path('bigsales');
            $modalName = time() . '_' . $request->file('modal_image')->getClientOriginalName();
            $request->file('modal_image')->move($modalPath, $modalName);
            $data['modal_image'] = 'bigsales/' . $modalName;
        }

        // Default status to active if not provided
        $data['status'] = $request->input('status', true);

        $bigSale = BigSale::create($data);

        if (!empty($request->products)) {
            $bigSale->products()->sync($request->products);
        }

        return redirect()->route('admin.bigsales.index')
            ->with('success', 'Big Sale created successfully.');
    }


    public function show(BigSale $bigSale)
    {
        return view('admin.bigsales.show', compact('bigSale'));
    }

    public function edit(BigSale $bigSale)
    {
        $products = Product::with('images')
        ->where('negotiable', 'no')
        ->orderBy('name', 'asc')
        ->get();
            
        return view('admin.bigsales.edit', compact('bigSale', 'products'));
    }

    public function update(Request $request, BigSale $bigSale)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'banner' => 'nullable|image',
            'modal_image' => 'nullable|image',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'discount_amount' => 'nullable|numeric|min:0|required_without:discount_percentage',
            'discount_percentage' => 'nullable|numeric|min:0|max:100|required_without:discount_amount',
            'status' => 'required|boolean',
            'products' => 'nullable|array', // Validation for product selection
        ]);

        if ($request->input('status') == true && BigSale::where('status', true)->where('id', '!=', $bigSale->id)->exists()) {
            return back()->withErrors(['status' => 'Only one Big Sale can be active at a time.']);
        }
    

        if (!empty($request->products)) {
            $negotiableProducts = Product::whereIn('id', $request->products)
                                          ->where('negotiable', 'yes')
                                          ->pluck('name')
                                          ->toArray();
        
            if (!empty($negotiableProducts)) {
                $productNames = implode(', ', $negotiableProducts);
                return back()->withErrors(['products' => "The following negotiable products cannot be included in a Big Sale: $productNames"]);
            }
        }
        

        $data = $request->all();

        // Generate a unique slug based on title if it has changed
        if ($bigSale->title !== $request->title) {
            $data['slug'] = Str::slug($request->title);
            $slugCount = BigSale::where('slug', 'like', "{$data['slug']}%")->count();
            if ($slugCount > 0) {
                $data['slug'] .= '-' . ($slugCount + 1);
            }
        }

        // Handle banner upload if provided
        if ($request->hasFile('banner')) {
            // Delete the old banner if it exists
            if ($bigSale->banner && file_exists(public_path($bigSale->banner))) {
                unlink(public_path($bigSale->banner));
            }

            $bannerPath = public_path('bigsales');
            $bannerName = time() . '_' . $request->file('banner')->getClientOriginalName();
            $request->file('banner')->move($bannerPath, $bannerName);
            $data['banner'] = 'bigsales/' . $bannerName;
        }

        if($request->hasFile('modal_image')) {
            // Delete the old banner if it exists
            if ($bigSale->modal_image && file_exists(public_path($bigSale->modal_image))) {
                unlink(public_path($bigSale->modal_image));
            }

            $modalPath = public_path('bigsales');
            $modalName = time() . '_' . $request->file('modal_image')->getClientOriginalName();
            $request->file('modal_image')->move($modalPath, $modalName);
            $data['modal_image'] = 'bigsales/' . $modalName;
        }

        // Update status, defaulting to active if not provided
        $data['status'] = $request->input('status', true);

        $discountAmount = $request->input('discount_amount');
        $discountPercentage = $request->input('discount_percentage');
    
        $bigSale->discount_amount = $discountAmount !== '' ? $discountAmount : null;
        $bigSale->discount_percentage = $discountPercentage !== '' ? $discountPercentage : null;
    

        // Update the Big Sale record
        $bigSale->update($data);

        // Sync the associated products if provided
        if (!empty($request->products)) {
            $bigSale->products()->sync($request->products);
        } else {
            $bigSale->products()->detach();
        }

        return redirect()->route('admin.bigsales.index')
            ->with('success', 'Big Sale updated successfully.');
    }


    public function destroy(BigSale $bigSale)
    {
        // Delete the banner image from public path if it exists
        if ($bigSale->banner && file_exists(public_path($bigSale->banner))) {
            unlink(public_path($bigSale->banner));
        }

        if($bigSale->modal_image && file_exists(public_path($bigSale->modal_image))) {
            unlink(public_path($bigSale->modal_image));
        }

        $bigSale->delete();

        return redirect()->route('admin.bigsales.index')
            ->with('success', 'Big Sale deleted successfully.');
    }

}
