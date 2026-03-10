<x-layouts.app title="Kalender Akademik">
    <x-slot:header>
        <x-layouts.page-header title="Kalender Akademik" description="Jadwal kuliah dan deadline tugas dalam satu tampilan" />
    </x-slot:header>

    <div x-data="kalenderApp()" x-init="init()">
        {{-- Navigation --}}
        <x-ui.card class="mb-6">
            <div class="flex items-center justify-between">
                <button class="btn btn-ghost btn-sm" @click="prevMonth()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h3 class="text-lg font-bold" x-text="monthYear"></h3>
                <button class="btn btn-ghost btn-sm" @click="nextMonth()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-4 mt-3 text-sm">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-primary"></span>
                    <span>Jadwal Kuliah</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-error"></span>
                    <span>Deadline Tugas</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-base-300"></span>
                    <span>Hari Ini</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Calendar Grid --}}
        <x-ui.card>
            {{-- Day Headers --}}
            <div class="grid grid-cols-7 gap-px mb-1">
                <template x-for="day in ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']" :key="day">
                    <div class="text-center text-sm font-semibold text-base-content/60 py-2" x-text="day"></div>
                </template>
            </div>

            {{-- Date Grid --}}
            <div class="grid grid-cols-7 gap-px bg-base-200 border border-base-200 rounded-lg overflow-hidden">
                <template x-for="(cell, index) in calendarCells" :key="index">
                    <div class="bg-base-100 min-h-[100px] p-1.5 relative"
                         :class="{
                            'bg-primary/5': cell.isToday,
                            'opacity-40': !cell.currentMonth
                         }">
                        <div class="text-xs font-medium mb-1 flex items-center gap-1"
                             :class="cell.isToday ? 'text-primary font-bold' : 'text-base-content/70'">
                            <span x-text="cell.day"></span>
                            <span x-show="cell.isToday" class="badge badge-primary badge-xs">Hari Ini</span>
                        </div>

                        {{-- Events --}}
                        <div class="space-y-0.5">
                            <template x-for="(event, ei) in cell.events" :key="ei">
                                <div class="text-[10px] leading-tight px-1 py-0.5 rounded truncate cursor-default"
                                     :class="event.type === 'jadwal'
                                        ? 'bg-primary/15 text-primary'
                                        : 'bg-error/15 text-error'"
                                     :title="event.title"
                                     x-text="event.title">
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </x-ui.card>

        {{-- Upcoming list --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            {{-- Jadwal Hari Ini --}}
            <x-ui.card title="📅 Jadwal Minggu Ini">
                <div class="space-y-2">
                    <template x-for="j in weekSchedule" :key="j.title + j.hari">
                        <div class="flex items-center gap-3 p-2 rounded-lg bg-base-200/50">
                            <div class="w-2 h-2 rounded-full bg-primary shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium truncate" x-text="j.title"></div>
                                <div class="text-xs text-base-content/60">
                                    <span x-text="j.hari"></span> •
                                    <span x-text="j.jam_mulai + ' - ' + j.jam_selesai"></span> •
                                    <span x-text="j.ruangan"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="weekSchedule.length === 0" class="text-center py-4 text-base-content/50 text-sm">
                        Tidak ada jadwal minggu ini
                    </div>
                </div>
            </x-ui.card>

            {{-- Deadline Mendatang --}}
            <x-ui.card title="⏰ Deadline Mendatang">
                <div class="space-y-2">
                    <template x-for="d in upcomingDeadlines" :key="d.title + d.date">
                        <div class="flex items-center gap-3 p-2 rounded-lg bg-base-200/50">
                            <div class="w-2 h-2 rounded-full bg-error shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium truncate" x-text="d.title"></div>
                                <div class="text-xs text-base-content/60">
                                    <span x-text="d.mata_kuliah"></span> •
                                    <span x-text="formatDate(d.date)"></span>
                                </div>
                            </div>
                            <div class="badge badge-sm"
                                 :class="d.status === 'progress' ? 'badge-warning' : 'badge-error'"
                                 x-text="d.progress + '%'"></div>
                        </div>
                    </template>
                    <div x-show="upcomingDeadlines.length === 0" class="text-center py-4 text-base-content/50 text-sm">
                        Tidak ada deadline mendatang
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
    function kalenderApp() {
        return {
            currentDate: new Date(),
            jadwal: @json($jadwal),
            deadlines: @json($tugas),
            calendarCells: [],
            monthYear: '',

            init() {
                this.render();
            },

            get weekSchedule() {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                return this.jadwal.sort((a, b) => {
                    const dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    return dayOrder.indexOf(a.hari) - dayOrder.indexOf(b.hari);
                });
            },

            get upcomingDeadlines() {
                const now = new Date();
                return this.deadlines
                    .filter(d => new Date(d.date) >= now)
                    .sort((a, b) => new Date(a.date) - new Date(b.date))
                    .slice(0, 5);
            },

            render() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                this.monthYear = months[month] + ' ' + year;

                const firstDay = new Date(year, month, 1);
                let startDay = firstDay.getDay() - 1;
                if (startDay < 0) startDay = 6;

                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrevMonth = new Date(year, month, 0).getDate();
                const today = new Date();

                const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const cells = [];

                // Previous month days
                for (let i = startDay - 1; i >= 0; i--) {
                    cells.push({
                        day: daysInPrevMonth - i,
                        currentMonth: false,
                        isToday: false,
                        events: []
                    });
                }

                // Current month days
                for (let d = 1; d <= daysInMonth; d++) {
                    const date = new Date(year, month, d);
                    const dayName = dayNames[date.getDay()];
                    const isToday = date.toDateString() === today.toDateString();
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;

                    const events = [];

                    // Add jadwal events
                    this.jadwal.forEach(j => {
                        if (j.hari === dayName) {
                            events.push({ title: j.title, type: 'jadwal' });
                        }
                    });

                    // Add deadline events
                    this.deadlines.forEach(dl => {
                        if (dl.date && dl.date.substring(0, 10) === dateStr) {
                            events.push({ title: dl.title, type: 'deadline' });
                        }
                    });

                    cells.push({
                        day: d,
                        currentMonth: true,
                        isToday: isToday,
                        events: events.slice(0, 3)
                    });
                }

                // Next month days
                const remaining = 42 - cells.length;
                for (let i = 1; i <= remaining; i++) {
                    cells.push({
                        day: i,
                        currentMonth: false,
                        isToday: false,
                        events: []
                    });
                }

                this.calendarCells = cells;
            },

            prevMonth() {
                this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
                this.render();
            },

            nextMonth() {
                this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                this.render();
            },

            formatDate(dateStr) {
                const d = new Date(dateStr);
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
            }
        };
    }
    </script>
    @endpush
</x-layouts.app>
