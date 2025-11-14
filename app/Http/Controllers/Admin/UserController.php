<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  // LIST DATA
  public function index(Request $request)
  {
    $query = User::query();

    // FILTER ROLE
    if ($request->role) {
      $query->where('role', $request->role);
    }

    // FILTER SEARCH GLOBAL
    if ($request->search) {
      $search = $request->search;

      $query->where(function ($q) use ($search) {
        $q->where('name', 'LIKE', "%$search%")
          ->orWhere('email', 'LIKE', "%$search%")
          ->orWhere('role', 'LIKE', "%$search%");
      });
    }

    $users = $query->paginate(10)->appends($request->query());

    return view('admin.users.index', compact('users'));
  }


  // FORM CREATE
  public function create()
  {
    return view('admin.users.create');
  }

  // SIMPAN DATA
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
      'role' => 'required'
    ]);

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => $request->role,
    ]);

    return redirect()->route('admin.users.index')->with('success', 'User created successfully');
  }

  // FORM EDIT
  public function edit(User $user)
  {
    return view('admin.users.edit', compact('user'));
  }

  // UPDATE DATA
  public function update(Request $request, User $user)
  {
    $request->validate([
      'name' => 'required',
      'email' => "required|email|unique:users,email,$user->id",
      'role' => 'required'
    ]);

    $data = $request->only(['name', 'email', 'role']);

    if ($request->password) {
      $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
  }

  // HAPUS
  public function destroy(Request $request, User $user)
  {
    try {
      $user->delete();

      if ($request->wantsJson()) {
        return response()->json([
          'success' => true,
          'message' => 'User berhasil dihapus.'
        ]);
      }

      return redirect()->route('admin.users.index')->with('success', 'User deleted');
    } catch (\Exception $e) {
      if ($request->wantsJson()) {
        return response()->json([
          'success' => false,
          'message' => 'Gagal menghapus user.'
        ]);
      }
      return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus user');
    }
  }
}
