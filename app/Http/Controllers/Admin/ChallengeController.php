<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChallengeController extends Controller
{
    /**
     * Menampilkan daftar challenge (Master Data)
     */
    public function index()
    {
        $challenges = Challenge::latest()->paginate(10);
        return view('admin.challenges.index', compact('challenges'));
    }

    /**
     * Halaman tambah challenge
     */
    public function create()
    {
        return view('admin.challenges.create');
    }

    /**
     * Proses simpan challenge baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            // Validasi nominal sesuai value integer dari form (300000, 500000, 700000)
            'kategori' => 'required|integer|in:300000,500000,700000',
            'file_pdf' => 'nullable|mimes:pdf|max:5120', // Max 5MB
            'deskripsi' => 'nullable|string'
        ]);

        $path = null;

        if ($request->hasFile('file_pdf')) {
            // Simpan di folder: storage/app/public/challenges
            $path = $request->file('file_pdf')->store('challenges', 'public');
        }

        Challenge::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'file_pdf' => $path,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil ditambahkan!');
    }

    /**
     * Halaman edit challenge
     */
    public function edit($id)
    {
        $challenge = Challenge::findOrFail($id);
        return view('admin.challenges.edit', compact('challenge'));
    }

    /**
     * Proses update challenge
     */
    public function update(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|integer|in:300000,500000,700000',
            'file_pdf' => 'nullable|mimes:pdf|max:5120',
            'deskripsi' => 'nullable|string'
        ]);

        $path = $challenge->file_pdf;

        if ($request->hasFile('file_pdf')) {
            // Hapus file lama jika ada
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            // Upload file baru
            $path = $request->file('file_pdf')->store('challenges', 'public');
        }

        $challenge->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'file_pdf' => $path,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil diupdate!');
    }

    /**
     * Hapus challenge
     */
    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);

        // Hapus file fisik jika ada
        if ($challenge->file_pdf && Storage::disk('public')->exists($challenge->file_pdf)) {
            Storage::disk('public')->delete($challenge->file_pdf);
        }

        $challenge->delete();

        // Return JSON untuk AJAX SweetAlert
        return response()->json([
            'success' => true,
            'message' => 'Challenge berhasil dihapus!'
        ]);
    }
}

