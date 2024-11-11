<?php

namespace App\Http\Controllers\Admin\Faq;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faq = Faq::paginate(10);
        return view('admin.faq.index', compact('faq'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'answer' => 'required|string|max:255',
            'question' => 'required|string',
        ]);

        Faq::create($request->all());

        return redirect()->route('faq.index')->with('success', 'Q&A berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        $faq = Faq::findOrFail($faq->id);
        return view('admin.faq.show', compact('faq'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'answer' => 'required|string|max:255',
            'question' => 'required|string',
        ]);

        $faq->update($request->all());

        return redirect()->route('faq.index')->with('success', 'Q&A berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('faq.index')->with('success', 'Q&A berhasil dihapus.');
    }
}
