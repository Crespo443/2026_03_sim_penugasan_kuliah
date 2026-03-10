<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Tugas::where('user_id', $user->id)->with('mataKuliah');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mata_kuliah_id')) {
            $query->where('mata_kuliah_id', $request->mata_kuliah_id);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        $tugas = $query->orderBy('deadline', 'asc')->paginate(15);
        $mataKuliah = MataKuliah::orderBy('nama')->get();

        return view('tugas.index', compact('tugas', 'mataKuliah'));
    }

    public function create()
    {
        $mataKuliah = MataKuliah::orderBy('nama')->get();
        return view('tugas.create', compact('mataKuliah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date|after_or_equal:today',
            'status' => 'required|in:belum,progress,selesai',
            'progress' => 'required|integer|min:0|max:100',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'file' => 'nullable',
            'catatan' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('file')) {
            dd($request->file('file'));
            $validated['file'] = $request->file('file')->store('tugas', 'public');
        }

        Tugas::create($validated);

        return redirect()->route('tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show(Tugas $tugas)
    {
        $this->authorize($tugas);
        $tugas->load('mataKuliah', 'reminders');
        return view('tugas.show', compact('tugas'));
    }

    public function edit(Tugas $tugas)
    {
        $this->authorize($tugas);
        $mataKuliah = MataKuliah::orderBy('nama')->get();
        return view('tugas.edit', compact('tugas', 'mataKuliah'));
    }

    public function update(Request $request, Tugas $tugas)
    {
        $this->authorize($tugas);

        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
            'status' => 'required|in:belum,progress,selesai',
            'progress' => 'required|integer|min:0|max:100',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'file' => 'nullable',
            'catatan' => 'nullable|string',
        ]);



        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('tugas', 'public');
        }

        $tugas->update($validated);

        return redirect()->route('tugas.index')
            ->with('success', 'Tugas berhasil diupdate.');
    }

    public function destroy(Tugas $tugas)
    {
        $this->authorize($tugas);
        $tugas->delete();
        return redirect()->route('tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    public function updateProgress(Request $request, Tugas $tugas)
    {
        $this->authorize($tugas);

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $tugas->update([
            'progress' => $validated['progress'],
            'status' => $validated['progress'] >= 100 ? 'selesai' : ($validated['progress'] > 0 ? 'progress' : 'belum'),
        ]);

        return back()->with('success', 'Progress berhasil diupdate.');
    }

    private function authorize(Tugas $tugas)
    {
        if ($tugas->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
