<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Tugas;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Tugas per status
        $tugasPerStatus = Tugas::where('user_id', $user->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Tugas per mata kuliah
        $tugasPerMataKuliah = Tugas::where('user_id', $user->id)
            ->join('mata_kuliahs', 'tugas.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->select('mata_kuliahs.nama', DB::raw('count(*) as total'))
            ->groupBy('mata_kuliahs.nama')
            ->pluck('total', 'nama')
            ->toArray();

        // Rata-rata progress
        $avgProgress = Tugas::where('user_id', $user->id)->avg('progress') ?? 0;

        // Total stats
        $totalTugas = Tugas::where('user_id', $user->id)->count();
        $tugasSelesai = Tugas::where('user_id', $user->id)->where('status', 'selesai')->count();
        $tugasTerlambat = Tugas::where('user_id', $user->id)
            ->whereIn('status', ['belum', 'progress'])
            ->where('deadline', '<', now())
            ->count();

        return view('statistik.index', compact(
            'tugasPerStatus',
            'tugasPerMataKuliah',
            'avgProgress',
            'totalTugas',
            'tugasSelesai',
            'tugasTerlambat'
        ));
    }
}
