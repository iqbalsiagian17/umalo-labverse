<?php

namespace App\Http\Controllers\Admin\QnA;

use App\Http\Controllers\Controller;
use App\Models\Qa;
use Illuminate\Http\Request;

class QaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qas = Qa::paginate(10);
        return view('admin.QnA.index', compact('qas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.QnA.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        Qa::create($request->all());

        return redirect()->route('qas.index')->with('success', 'Q&A berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Qa $qa)
    {
        $qa = Qa::findOrFail($qa->id);
        return view('admin.QnA.show', compact('qa'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Qa $qa)
    {
        return view('admin.Qna.edit', compact('qa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Qa $qa)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban' => 'required|string',
        ]);

        $qa->update($request->all());

        return redirect()->route('qas.index')->with('success', 'Q&A berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Qa $qa)
    {
        $qa->delete();

        return redirect()->route('qas.index')->with('success', 'Q&A berhasil dihapus.');
    }
}
