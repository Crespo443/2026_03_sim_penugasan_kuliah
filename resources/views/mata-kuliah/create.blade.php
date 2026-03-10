<x-layouts.app title="Tambah Mata Kuliah">
    <x-slot:header>
        <x-layouts.page-header title="Tambah Mata Kuliah" description="Tambahkan jadwal mata kuliah baru">
            <x-slot:actions>
                <x-ui.button type="ghost" size="sm" :href="route('mata-kuliah.index')">
                    ← Kembali
                </x-ui.button>
            </x-slot:actions>
        </x-layouts.page-header>
    </x-slot:header>

    <x-ui.card class="max-w-2xl">
        <form method="POST" action="{{ route('mata-kuliah.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-ui.input name="kode" label="Kode Mata Kuliah" placeholder="Contoh: IF101" :required="true" />
                <x-ui.input name="nama" label="Nama Mata Kuliah" placeholder="Contoh: Kecerdasan Buatan" :required="true" />
            </div>

            <x-ui.input name="dosen" label="Dosen Pengampu" placeholder="Nama dosen" :required="true" />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-ui.input name="ruangan" label="Ruangan" placeholder="Contoh: Lab 3" :required="true" />
                <x-ui.select name="hari" label="Hari" :searchable="false" placeholder="Pilih hari" :required="true"
                    :options="[
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                    ]" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-ui.input name="jam_mulai" label="Jam Mulai" type="time" :required="true" />
                <x-ui.input name="jam_selesai" label="Jam Selesai" type="time" :required="true" />
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <x-ui.button type="ghost" :href="route('mata-kuliah.index')" :isSubmit="false">Batal</x-ui.button>
                <x-ui.button type="primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</x-layouts.app>
