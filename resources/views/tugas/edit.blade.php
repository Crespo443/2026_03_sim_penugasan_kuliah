<x-layouts.app title="Edit Tugas">
    <x-slot:header>
        <x-layouts.page-header title="Edit Tugas" description="Edit {{ $tugas->judul }}">
            <x-slot:actions>
                <x-ui.button type="ghost" size="sm" :href="route('tugas.index')">← Kembali</x-ui.button>
            </x-slot:actions>
        </x-layouts.page-header>
    </x-slot:header>

    <x-ui.card class="max-w-2xl">
        <form method="POST" action="{{ route('tugas.update', $tugas->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-ui.select name="mata_kuliah_id" label="Mata Kuliah" :required="true" placeholder="Pilih mata kuliah"
                :options="$mataKuliah->pluck('nama', 'id')->toArray()"
                :value="$tugas->mata_kuliah_id" />

            <x-ui.input name="judul" label="Judul Tugas" placeholder="Judul tugas" :required="true"
                :value="$tugas->judul" />

            <x-ui.textarea name="deskripsi" label="Deskripsi" placeholder="Deskripsi tugas (opsional)" :rows="4"
                :value="$tugas->deskripsi" />

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-ui.input name="deadline" label="Deadline" type="date" :required="true"
                    :value="$tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->format('Y-m-d') : ''" />
                <x-ui.select name="status" label="Status" :searchable="false" :required="true" placeholder="Pilih status"
                    :options="['belum' => 'Belum', 'progress' => 'Progress', 'selesai' => 'Selesai']"
                    :value="$tugas->status" />
                <x-ui.input name="progress" label="Progress (%)" type="number" placeholder="0" :required="true"
                    :value="$tugas->progress" />
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <x-ui.button type="ghost" :href="route('tugas.index')" :isSubmit="false">Batal</x-ui.button>
                <x-ui.button type="primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Tugas
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layouts.app>
