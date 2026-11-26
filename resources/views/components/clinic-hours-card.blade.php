@props(['openingHours', 'upcomingClosures' => collect()])

@php
    $todayIso = now('Asia/Jakarta')->dayOfWeekIso;
@endphp

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-[#176b55]/15 bg-white p-6 shadow-sm']) }}>
    <div class="flex items-center justify-between gap-3 border-b border-[#176b55]/10 pb-4">
        <div>
            <p class="text-xs font-semibold tracking-wider text-[#176b55] uppercase">Jadwal Praktek Reguler</p>
            <h3 class="font-display mt-1 text-xl font-semibold text-[#13221d]">Jam Buka Mingguan</h3>
        </div>
        <span class="rounded-full bg-[#eef6f3] px-3 py-1 text-xs font-semibold text-[#0f4c3c]">WIB</span>
    </div>

    <dl class="mt-4 divide-y divide-[#176b55]/8 text-sm">
        @foreach($openingHours as $hour)
            @php
                $isToday = (int) $hour->day_of_week === $todayIso;
                $openFormatted = $hour->open_time ? substr(str_replace(':', '.', $hour->open_time), 0, 5) : '-';
                $closeFormatted = $hour->close_time ? substr(str_replace(':', '.', $hour->close_time), 0, 5) : '-';
            @endphp
            <div class="flex items-center justify-between gap-3 py-2.5 {{ $isToday ? 'rounded-xl bg-[#eef6f3]/80 px-3 -mx-3 font-semibold text-[#0f4c3c]' : 'text-[#13221d]' }}">
                <dt class="flex items-center gap-2">
                    <span>{{ $hour->day_name }}</span>
                    @if($isToday)
                        <span class="rounded-full bg-[#176b55] px-2 py-0.5 text-[11px] font-medium text-white">Hari ini</span>
                    @endif
                </dt>
                <dd class="tabular-nums {{ $hour->is_closed ? 'text-[#9a5521]' : '' }}">
                    @if($hour->is_closed)
                        Tutup
                    @else
                        {{ $openFormatted }} – {{ $closeFormatted }} WIB
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>

    @if($upcomingClosures->isNotEmpty())
        <div class="mt-5 rounded-2xl border border-[#c97a3d]/30 bg-[#fdf7f2] p-4 text-xs text-[#3d4d47]">
            <p class="font-semibold text-[#7d4317]">Jadwal Khusus / Libur Terdekat</p>
            <ul class="mt-2 space-y-1.5">
                @foreach($upcomingClosures as $closure)
                    <li>
                        <strong class="text-[#13221d]">{{ \Carbon\Carbon::parse($closure->date)->translatedFormat('d M Y') }}:</strong>
                        {{ $closure->title }}
                        @if(! $closure->is_closed && $closure->custom_open_time && $closure->custom_close_time)
                            ({{ substr(str_replace(':', '.', $closure->custom_open_time), 0, 5) }}–{{ substr(str_replace(':', '.', $closure->custom_close_time), 0, 5) }} WIB)
                        @elseif($closure->is_closed)
                            (Praktek Tutup)
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
