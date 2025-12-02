<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaseSubmissionController extends Controller
{
    // Menampilkan SEMUA submisi dalam Event ini (dari berbagai Case)
    public function index(Event $event)
    {
        $submissions = DB::table('case_submissions')
            ->join('groups', 'case_submissions.group_id', '=', 'groups.id')
            ->join('users', 'case_submissions.user_id', '=', 'users.id')
            ->join('cases', 'case_submissions.case_id', '=', 'cases.id') // Join ke tabel cases untuk ambil nama case
            ->where('case_submissions.event_id', $event->id)
            ->select(
                'case_submissions.*',
                'groups.name as group_name',
                'users.name as submitter_name',
                'cases.title as case_title',    // Judul Case
                'cases.id as case_id'
            )
            ->orderBy('case_submissions.created_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        // Kita bisa reuse view yang lama atau buat baru.
        // Disini saya asumsikan viewnya sama tapi kita sesuaikan sedikit kolomnya nanti.
        return view('admin.events.case-submissions.index', compact('event', 'submissions'));
    }
}
