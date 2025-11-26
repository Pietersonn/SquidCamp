<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventGroupController extends Controller
{
  public function index(Event $event)
  {
    $groups = $event->groups()
      ->with(['mentor', 'captain', 'cocaptain'])
      ->withCount('members')
      ->latest()
      ->get();

    return view('admin.events.groups.index', compact('event', 'groups'));
  }

  public function show(Event $event, Group $group)
  {
    // Load relasi lengkap untuk halaman detail
    $group->load(['mentor', 'captain', 'cocaptain', 'members.user']);

    return view('admin.events.groups.show', compact('event', 'group'));
  }

  public function create(Event $event)
  {
    $mentors = User::where('role', 'mentor')->get();
    
    $usersInGroups = GroupMember::whereHas('group', function ($q) use ($event) {
      $q->where('event_id', $event->id);
    })->pluck('user_id');

    $candidates = User::where('role', 'user')
      ->whereNotIn('id', $usersInGroups)
      ->orderBy('name')
      ->get();

    return view('admin.events.groups.create', compact('event', 'mentors', 'candidates'));
  }

  public function store(Request $request, Event $event)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'mentor_id' => 'nullable|exists:users,id',
      'captain_id' => 'nullable|exists:users,id',
      'cocaptain_id' => 'nullable|exists:users,id',
      'squid_dollar' => 'required|integer|min:0',
      'member_ids' => 'nullable|array',
      'member_ids.*' => 'exists:users,id',
    ]);

    DB::transaction(function () use ($request, $event) {
      $group = $event->groups()->create([
        'name' => $request->name,
        'mentor_id' => $request->mentor_id,
        'captain_id' => $request->captain_id,
        'cocaptain_id' => $request->cocaptain_id,
        'squid_dollar' => $request->squid_dollar,
      ]);

      if ($request->has('member_ids')) {
        foreach ($request->member_ids as $userId) {
          $exists = GroupMember::where('event_id', $event->id)->where('user_id', $userId)->exists();

          if (!$exists) {
            $group->members()->create([
              'user_id' => $userId,
              'event_id' => $event->id
            ]);
          }
        }
      }
    });

    return redirect()->route('admin.events.groups.index', $event->id)
      ->with('success', 'Kelompok berhasil dibuat beserta anggotanya.');
  }

  public function edit(Event $event, Group $group)
  {
    $mentors = User::where('role', 'mentor')->get();

    $usersInOtherGroups = GroupMember::whereHas('group', function ($q) use ($event, $group) {
      $q->where('event_id', $event->id)
        ->where('id', '!=', $group->id);
    })->pluck('user_id');

    $candidates = User::where('role', 'user')
      ->whereNotIn('id', $usersInOtherGroups)
      ->orderBy('name')
      ->get();

    $currentMemberIds = $group->members()->pluck('user_id')->toArray();

    return view('admin.events.groups.edit', compact('event', 'group', 'mentors', 'candidates', 'currentMemberIds'));
  }

  public function update(Request $request, Event $event, Group $group)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'mentor_id' => 'nullable|exists:users,id',
      'captain_id' => 'nullable|exists:users,id',
      'cocaptain_id' => 'nullable|exists:users,id',
      'squid_dollar' => 'required|integer|min:0',
      'member_ids' => 'nullable|array',
      'member_ids.*' => 'exists:users,id',
    ]);

    DB::transaction(function () use ($request, $group, $event) {
      $group->update([
        'name' => $request->name,
        'mentor_id' => $request->mentor_id,
        'captain_id' => $request->captain_id,
        'cocaptain_id' => $request->cocaptain_id,
        'squid_dollar' => $request->squid_dollar,
      ]);

      $submittedIds = $request->input('member_ids', []);
      $group->members()->whereNotIn('user_id', $submittedIds)->delete();
      $currentIds = $group->members()->pluck('user_id')->toArray();
      $newIds = array_diff($submittedIds, $currentIds);

      foreach ($newIds as $userId) {
        $group->members()->create([
          'user_id' => $userId,
          'event_id' => $event->id
        ]);
      }
    });

    return redirect()->route('admin.events.groups.index', $event->id)
      ->with('success', 'Kelompok dan anggota berhasil diperbarui.');
  }

  public function destroy(Event $event, Group $group)
  {
    $group->delete();
    return back()->with('success', 'Kelompok berhasil dihapus.');
  }
}
