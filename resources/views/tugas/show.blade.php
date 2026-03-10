<x-layouts.app title="Detail Tugas">
    <x-slot:header>
        <x-layouts.page-header title="Detail Tugas" description="{{ $tugas->judul }}">
            <x-slot:actions>
                <x-ui.button type="ghost" size="sm" :href="route('tugas.index')">← Kembali</x-ui.button>
                <x-ui.button type="primary" size="sm" :href="route('tugas.edit', $tugas->id)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </x-ui.button>
            </x-slot:actions>
        </x-layouts.page-header>
    </x-slot:header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card title="{{ $tugas->judul }}">
                @php
                    $statusBadge = match($tugas->status) {
                        'belum' => 'error',
                        'progress' => 'warning',
                        'selesai' => 'success',
                        default => 'ghost',
                    };
                    $statusLabel = match($tugas->status) {
                        'belum' => 'Belum Dikerjakan',
                        'progress' => 'Sedang Dikerjakan',
                        'selesai' => 'Selesai',
                        default => $tugas->status,
                    };
                @endphp

                <div class="flex items-center gap-2 mb-4">
                    <x-ui.badge :type="$statusBadge">{{ $statusLabel }}</x-ui.badge>
                    <x-ui.badge type="info">{{ $tugas->mataKuliah->nama ?? '-' }}</x-ui.badge>
                </div>

                @if($tugas->deskripsi)
                    <div class="prose prose-sm max-w-none">
                        <h4 class="text-sm font-semibold text-base-content/70 uppercase tracking-wide mb-2">Deskripsi</h4>
                        <p class="text-base-content/80">{{ $tugas->deskripsi }}</p>
                    </div>
                @else
                    <p class="text-base-content/50 italic">Tidak ada deskripsi</p>
                @endif
            </x-ui.card>

            {{-- Progress Update Section --}}
            <x-ui.card title="📊 Progress">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium">Progress Tugas</span>
                        <span class="text-2xl font-bold text-primary">{{ $tugas->progress }}%</span>
                    </div>
                    <progress class="progress progress-primary w-full h-4" value="{{ $tugas->progress }}" max="100"></progress>
                </div>

                {{-- Quick update progress --}}
                <form method="POST" action="{{ route('tugas.progress', $tugas->id) }}" class="flex items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="flex-1">
                        <x-ui.input name="progress" label="Update Progress" type="range" :value="$tugas->progress"
                            class="range range-primary" />
                    </div>
                    <div x-data="{ val: {{ $tugas->progress }} }">
                        <input type="range" name="progress" min="0" max="100" step="5"
                            class="range range-primary w-full" x-model="val"
                            :style="'--range-fill: ' + val + '%'" />
                        <div class="text-center text-sm font-mono mt-1" x-text="val + '%'"></div>
                    </div>
                    <x-ui.button type="primary" size="sm">Update</x-ui.button>
                </form>
            </x-ui.card>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">

            {{-- Info Card --}}
            <x-ui.card title="📋 Informasi">
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-base-content/50 uppercase tracking-wide">Mata Kuliah</div>
                        <div class="font-medium mt-0.5">{{ $tugas->mataKuliah->nama ?? '-' }}</div>
                    </div>
                    <div class="divider my-0"></div>
                    <div>
                        <div class="text-xs text-base-content/50 uppercase tracking-wide">Dosen</div>
                        <div class="font-medium mt-0.5">{{ $tugas->mataKuliah->dosen ?? '-' }}</div>
                    </div>
                    <div class="divider my-0"></div>
                    <div>
                        @php
                            $daysLeft = now()->diffInDays($tugas->deadline, false);
                            $isOverdue = $daysLeft < 0 && $tugas->status !== 'selesai';
                        @endphp
                        <div class="text-xs text-base-content/50 uppercase tracking-wide">Deadline</div>
                        <div class="font-medium mt-0.5 {{ $isOverdue ? 'text-error' : '' }}">
                            {{ \Carbon\Carbon::parse($tugas->deadline)->format('d F Y') }}
                        </div>
                        @if($tugas->status !== 'selesai')
                            <div class="text-xs mt-1 {{ $isOverdue ? 'text-error' : ($daysLeft <= 3 ? 'text-warning' : 'text-base-content/60') }}">
                                {{ $isOverdue ? 'Terlambat ' . abs(ceil($daysLeft)) . ' hari' : ceil($daysLeft) . ' hari lagi' }}
                            </div>
                        @endif
                    </div>
                    <div class="divider my-0"></div>
                    <div>
                        <div class="text-xs text-base-content/50 uppercase tracking-wide">Dibuat</div>
                        <div class="text-sm mt-0.5">{{ $tugas->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </x-ui.card>

            {{-- Actions --}}
            <x-ui.card title="⚡ Aksi">
                <div class="space-y-2">
                    <x-ui.button type="primary" class="w-full" :href="route('tugas.edit', $tugas->id)" :isSubmit="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Tugas
                    </x-ui.button>
                    <x-ui.button type="error" class="w-full" :isSubmit="false" outline
                        @click="$dispatch('confirm-delete', { action: '{{ route('tugas.destroy', $tugas->id) }}', message: 'Hapus tugas {{ $tugas->judul }}?' })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Tugas
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.app>
