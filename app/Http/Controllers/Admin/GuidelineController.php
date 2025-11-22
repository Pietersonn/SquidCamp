<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guideline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuidelineController extends Controller
{
    public function index()
    {
        $guidelines = Guideline::latest()->paginate(10);
        return view('admin.guidelines.index', compact('guidelines'));
    }

    public function create()
    {
        return view('admin.guidelines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'nullable|integer',
            'description' => 'nullable|string',
            'file_pdf'    => 'nullable|mimes:pdf|max:5120' // Max 5MB
        ]);

        $path = null;

        if ($request->hasFile('file_pdf')) {
            // Simpan di storage/app/public/guidelines
            $path = $request->file('file_pdf')->store('guidelines', 'public');
        }

        Guideline::create([
            'title'       => $request->title,
            'price'       => $request->price ?? 0,
            'description' => $request->description,
            'file_pdf'    => $path
        ]);

        return redirect()
            ->route('admin.guidelines.index')
            ->with('success', 'Guideline berhasil ditambahkan!');
    }

    public function edit(Guideline $guideline)
    {
        return view('admin.guidelines.edit', compact('guideline'));
    }

    public function update(Request $request, Guideline $guideline)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'nullable|integer',
            'description' => 'nullable|string',
            'file_pdf'    => 'nullable|mimes:pdf|max:5120'
        ]);

        $path = $guideline->file_pdf;

        // Jika upload file baru, hapus file lama & simpan yang baru
        if ($request->hasFile('file_pdf')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('file_pdf')->store('guidelines', 'public');
        }

        $guideline->update([
            'title'       => $request->title,
            'price'       => $request->price ?? 0,
            'description' => $request->description,
            'file_pdf'    => $path
        ]);

        return redirect()
            ->route('admin.guidelines.index')
            ->with('success', 'Guideline berhasil diperbarui!');
    }

    public function destroy(Guideline $guideline)
    {
        // Hapus file fisik jika ada
        if ($guideline->file_pdf && Storage::disk('public')->exists($guideline->file_pdf)) {
            Storage::disk('public')->delete($guideline->file_pdf);
        }

        $guideline->delete();

        // Return JSON untuk AJAX SweetAlert di index
        return response()->json([
            'success' => true,
            'message' => 'Guideline berhasil dihapus!'
        ]);
    }
}
