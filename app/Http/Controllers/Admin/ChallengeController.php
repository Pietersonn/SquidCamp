<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::latest()->get();
        return view('admin.challenges.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'price' => 'required|numeric|in:300000,500000,700000', // Validasi Harga
            'deskripsi' => 'required|string',
            'file_pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_pdf')) {
            $data['file_pdf'] = $request->file('file_pdf')->store('challenges', 'public');
        }

        // Set default kategori jika tidak ada di form (karena kita ganti logika kategori jadi price)
        $data['kategori'] = 1;

        Challenge::create($data);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil dibuat');
    }

    public function edit(Challenge $challenge)
    {
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'price' => 'required|numeric|in:300000,500000,700000', // Validasi Harga
            'deskripsi' => 'required|string',
            'file_pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_pdf')) {
            // Hapus file lama jika ada
            if ($challenge->file_pdf) {
                Storage::disk('public')->delete($challenge->file_pdf);
            }
            $data['file_pdf'] = $request->file('file_pdf')->store('challenges', 'public');
        }

        $challenge->update($data);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil diperbarui');
    }

    public function destroy(Challenge $challenge)
    {
        if ($challenge->file_pdf) {
            Storage::disk('public')->delete($challenge->file_pdf);
        }
        $challenge->delete();
        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil dihapus');
    }
}
