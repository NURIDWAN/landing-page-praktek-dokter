@props(['compact' => false])

<aside
    aria-label="Disclaimer Medis"
    {{ $attributes->merge(['class' => 'rounded-2xl border border-[#c97a3d]/30 bg-[#fdf7f2] text-[#13221d] '.($compact ? 'p-4 text-xs leading-relaxed' : 'p-5 sm:p-6 text-sm leading-relaxed')]) }}
>
    <div class="flex items-start gap-3">
        <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#c97a3d]/15 text-[#9a5521]" aria-hidden="true">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" x2="12" y1="8" y2="12"/>
                <line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>
        </span>
        <div>
            <p class="font-semibold text-[#7d4317]">Catatan &amp; Disclaimer Medis</p>
            <p class="mt-1 text-[#3d4d47]">
                {{ $siteSettings->medical_disclaimer }}
            </p>
        </div>
    </div>
</aside>
