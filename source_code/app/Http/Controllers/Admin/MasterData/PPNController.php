<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\PPN;
use Illuminate\Http\Request;

class PPNController extends Controller
{
    public function index()
    {
        $ppns = PPN::all();
        return view('admin.masterdata.ppn.index', compact('ppns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.masterdata.ppn.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ppn' => 'required|numeric|min:0|max:100',
        ]);

        PPN::create($request->all());

        return redirect()->route('admin.masterdata.ppn.index')
            ->with('success', 'PPN created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PPN $ppn)
    {
        return view('admin.masterdata.ppn.show', compact('ppn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PPN $ppn)
    {
        return view('admin.masterdata.ppn.edit', compact('ppn'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PPN $ppn)
    {
        $request->validate([
            'ppn' => 'required|numeric|min:0|max:100',
        ]);

        $ppn->update($request->all());

        return redirect()->route('admin.masterdata.ppn.index')
            ->with('success', 'PPN updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PPN $ppn)
    {
        $ppn->delete();

        return redirect()->route('admin.masterdata.ppn.index')
            ->with('success', 'PPN deleted successfully.');
    }
}
