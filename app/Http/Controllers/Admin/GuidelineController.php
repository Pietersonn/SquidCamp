<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guideline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuidelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guidelines = Guideline::latest()->paginate(10);

        return view('admin.guidelines.index', compact('guidelines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guidelines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'nullable|mimes:pdf|max:2048'
        ]);

        if ($request->hasFile('file_pdf')) {
            $validated['file_pdf'] = $request->file('file_pdf')->store('guidelines', 'public');
        }

        Guideline::create($validated);

        return redirect()
            ->route('admin.guidelines.index')
            ->with('success', 'Guideline berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guideline $guideline)
    {
        return view('admin.guidelines.edit', compact('guideline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guideline $guideline)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pdf' => 'nullable|mimes:pdf|max:2048'
        ]);

        // Jika upload file baru, hapus file lama
        if ($request->hasFile('file_pdf')) {
            if ($guideline->file_pdf && Storage::disk('public')->exists($guideline->file_pdf)) {
                Storage::disk('public')->delete($guideline->file_pdf);
            }

            $validated['file_pdf'] = $request->file('file_pdf')->store('guidelines', 'public');
        }

        $guideline->update($validated);

        return redirect()
            ->route('admin.guidelines.index')
            ->with('success', 'Guideline berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guideline $guideline)
    {
        if ($guideline->file_pdf && Storage::disk('public')->exists($guideline->file_pdf)) {
            Storage::disk('public')->delete($guideline->file_pdf);
        }

        $guideline->delete();

        return redirect()
            ->route('admin.guidelines.index')
            ->with('success', 'Guideline berhasil dihapus!');
    }
}
