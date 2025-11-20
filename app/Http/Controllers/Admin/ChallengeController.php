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
        $challenges = Challenge::latest()->paginate(10);
        return view('admin.challenges.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required|in:300,500,700',
            'file_pdf' => 'nullable|mimes:pdf|max:5000',
            'deskripsi' => 'nullable'
        ]);

        $path = null;

        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('challenges');
        }

        Challenge::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'file_pdf' => $path,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $challenge = Challenge::findOrFail($id);
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'kategori' => 'required|in:300,500,700',
            'file_pdf' => 'nullable|mimes:pdf|max:5000',
            'deskripsi' => 'nullable'
        ]);

        $path = $challenge->file_pdf;

        if ($request->hasFile('file_pdf')) {
            if ($path) Storage::delete($path);
            $path = $request->file('file_pdf')->store('challenges');
        }

        $challenge->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'file_pdf' => $path,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil diupdate!');
    }

    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);

        if ($challenge->file_pdf) {
            Storage::delete($challenge->file_pdf);
        }

        $challenge->delete();

        return response()->json([
            'success' => true,
            'message' => 'Challenge berhasil dihapus!'
        ]);
    }
}
