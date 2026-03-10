<x-layouts.app title="Tambah Tugas">
    <x-slot:header>
        <x-layouts.page-header title="Tambah Tugas" description="Buat tugas baru">
            <x-slot:actions>
                <x-ui.button type="ghost" size="sm" :href="route('tugas.index')">← Kembali</x-ui.button>
            </x-slot:actions>
        </x-layouts.page-header>
    </x-slot:header>

    <x-ui.card class="">
        <form method="POST" action="{{ route('tugas.store') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />

            <x-ui.select name="mata_kuliah_id" label="Mata Kuliah" :required="true" placeholder="Pilih mata kuliah"
                :options="$mataKuliah->pluck('nama', 'id')->toArray()" />

            <x-ui.input name="judul" label="Judul Tugas" placeholder="Contoh: Makalah Kecerdasan Buatan" :required="true" />

            <x-ui.textarea name="deskripsi" label="Deskripsi" placeholder="Deskripsi tugas (opsional)" :rows="4" />

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-ui.input name="deadline" label="Deadline" type="date" :required="true" />
                <x-ui.select name="status" label="Status" :searchable="false" :required="true" placeholder="Pilih status"
                    :options="['belum' => 'Belum', 'progress' => 'Progress', 'selesai' => 'Selesai']"
                    value="belum" />
                <x-ui.input name="progress" label="Progress (%)" type="number" placeholder="0" value="0" :required="true" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-ui.select name="prioritas" label="Prioritas" :required="true" placeholder="Pilih prioritas"
                    :options="['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi']"
                    value="sedang" />
                <x-ui.input name="file" label="Upload File (PDF/IMG)" type="file" accept="application/pdf,image/*" />
            </div>

            <x-ui.textarea name="catatan" label="Catatan" placeholder="Catatan tambahan (opsional)" />

            <div class="flex justify-end gap-2 pt-4">
                <x-ui.button type="ghost" :href="route('tugas.index')" :isSubmit="false">Batal</x-ui.button>
                <x-ui.button type="primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Tugas
                </x-ui.button>
            </div>
            </div>
        </form>
    </x-ui.card>
</x-layouts.app>
