<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Tugas;

class KalenderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jadwal = MataKuliah::all()->map(function ($mk) {
            return [
                'title' => $mk->nama,
                'hari' => $mk->hari,
                'jam_mulai' => $mk->jam_mulai,
                'jam_selesai' => $mk->jam_selesai,
                'ruangan' => $mk->ruangan,
                'dosen' => $mk->dosen,
                'type' => 'jadwal',
            ];
        });

        $tugas = Tugas::where('user_id', $user->id)
            ->whereIn('status', ['belum', 'progress'])
            ->with('mataKuliah')
            ->get()
            ->map(function ($t) {
                return [
                    'title' => $t->judul,
                    'date' => $t->deadline,
                    'mata_kuliah' => $t->mataKuliah->nama ?? '-',
                    'status' => $t->status,
                    'progress' => $t->progress,
                    'type' => 'deadline',
                ];
            });

        return view('kalender.index', compact('jadwal', 'tugas'));
    }
}
