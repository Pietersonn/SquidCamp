<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CaseController extends Controller
{
    public function index()
    {
        $cases = Cases::latest()->paginate(10);
        return view('admin.cases.index', compact('cases'));
    }

    public function create()
    {
        return view('admin.cases.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'difficulty'  => 'required|in:Easy,Medium,Hard',
            'file_pdf'    => 'nullable|mimes:pdf|max:5120',
            'description' => 'nullable|string'
        ]);

        $path = null;
        if ($request->hasFile('file_pdf')) {
            $path = $request->file('file_pdf')->store('cases', 'public');
        }

        Cases::create([
            'title'       => $request->title,
            'difficulty'  => $request->difficulty,
            'file_pdf'    => $path,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.cases.index')->with('success', 'Case berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $case = Cases::findOrFail($id);
        return view('admin.cases.edit', compact('case'));
    }

    public function update(Request $request, $id)
    {
        $case = Cases::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'difficulty'  => 'required|in:Easy,Medium,Hard',
            'file_pdf'    => 'nullable|mimes:pdf|max:5120',
            'description' => 'nullable|string'
        ]);

        $path = $case->file_pdf;

        if ($request->hasFile('file_pdf')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('file_pdf')->store('cases', 'public');
        }

        $case->update([
            'title'       => $request->title,
            'difficulty'  => $request->difficulty,
            'file_pdf'    => $path,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.cases.index')->with('success', 'Case berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $case = Cases::findOrFail($id);

        if ($case->file_pdf && Storage::disk('public')->exists($case->file_pdf)) {
            Storage::disk('public')->delete($case->file_pdf);
        }

        $case->delete();

        return response()->json(['success' => true, 'message' => 'Case berhasil dihapus!']);
    }
}
