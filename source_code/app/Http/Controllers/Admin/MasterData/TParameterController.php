<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\TParameter;
use Illuminate\Http\Request;

class TParameterController extends Controller
{
    public function index()
    {
        $parameters = TParameter::all();
        return view('admin.masterdata.parameter.index', compact('parameters'));
    }
    
    
    

    public function create()
    {
        return view('admin.masterdata.parameter.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
    
        // Handle logo uploads
        foreach (['logo1', 'logo2', 'logo3'] as $logo) {
            if ($request->hasFile($logo)) {
                // Define the path and save the file manually to public_path
                $filename = time() . '_' . $request->file($logo)->getClientOriginalName();
                $path = public_path('logos');
                $request->file($logo)->move($path, $filename);
                $data[$logo] = 'logos/' . $filename;
            }
        }
    
        TParameter::create($data);
        return redirect()->route('admin.masterdata.parameter.index')->with('success', 'Parameter created successfully.');
    }

    public function show($id)
    {
        $parameter = TParameter::findOrFail($id);
        return view('admin.masterdata.parameter.show', compact('parameter'));
    }

    public function edit($id)
    {
        $parameter = TParameter::findOrFail($id);
        return view('admin.masterdata.parameter.edit', compact('parameter'));
    }

    public function update(Request $request, $id)
    {
        $parameter = TParameter::findOrFail($id);
        $data = $request->all();

        // Handle logo uploads
        foreach (['logo1', 'logo2', 'logo3'] as $logo) {
            if ($request->hasFile($logo)) {
                // Define the path and save the file manually to public_path
                $filename = time() . '_' . $request->file($logo)->getClientOriginalName();
                $path = public_path('logos');
                $request->file($logo)->move($path, $filename);
                $data[$logo] = 'logos/' . $filename;
            }
        }

        $parameter->update($data);
        return redirect()->route('admin.masterdata.parameter.index')->with('success', 'Parameter updated successfully.');
    }

    public function destroy($id)
    {
        $parameter = TParameter::findOrFail($id);
        $parameter->delete();
        return redirect()->route('admin.masterdata.parameter.index')->with('success', 'Parameter deleted successfully.');
    }
}
