<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Tugas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalTugas = Tugas::where('user_id', $user->id)->count();
        $tugasSelesai = Tugas::where('user_id', $user->id)->where('status', 'selesai')->count();
        $tugasBelum = Tugas::where('user_id', $user->id)->where('status', 'belum')->count();
        $tugasProgress = Tugas::where('user_id', $user->id)->where('status', 'progress')->count();

        // Deadline terdekat (5 tugas belum selesai paling dekat deadlinenya)
        $deadlineTerdekat = Tugas::where('user_id', $user->id)
            ->whereIn('status', ['belum', 'progress'])
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc')
            ->with('mataKuliah')
            ->take(5)
            ->get();

        // Jadwal hari ini
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
        $jadwalHariIni = MataKuliah::where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Reminders (tugas deadline <= 3 hari)
        $reminders = Tugas::where('user_id', $user->id)
            ->whereIn('status', ['belum', 'progress'])
            ->whereBetween('deadline', [now(), now()->addDays(3)])
            ->with('mataKuliah')
            ->orderBy('deadline', 'asc')
            ->get();

        return view('dashboard.index', compact(
            'totalTugas',
            'tugasSelesai',
            'tugasBelum',
            'tugasProgress',
            'deadlineTerdekat',
            'jadwalHariIni',
            'reminders'
        ));
    }
}
