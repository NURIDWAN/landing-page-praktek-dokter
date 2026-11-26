@props(['compact' => false])

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-[#176b55]/15 bg-white shadow-sm '.($compact ? 'p-6' : 'p-6 sm:p-8')]) }}>
    @if(session('contact_success'))
        <div class="mb-6 rounded-2xl border border-[#176b55]/30 bg-[#eef6f3] p-4 text-sm text-[#0f4c3c]" role="status">
            <p class="font-semibold">Pesan Terkirim</p>
            <p class="mt-1">{{ session('contact_success') }}</p>
        </div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4" novalidate>
        @csrf

        {{-- Anti-spam honeypot field --}}
        <div class="hidden" aria-hidden="true">
            <label for="website_url">Website</label>
            <input type="text" id="website_url" name="website_url" value="" tabindex="-1" autocomplete="off">
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="contact_name" class="block text-sm font-semibold text-[#13221d]">
                    Nama Lengkap <span class="text-[#b45309]">*</span>
                </label>
                <input
                    id="contact_name"
                    name="name"
                    type="text"
                    required
                    maxlength="100"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Ibu Rahmawati"
                    class="mt-1.5 w-full rounded-xl border border-[#176b55]/20 bg-[#fbfcfa] px-3.5 py-2.5 text-sm text-[#13221d] placeholder:text-[#6c7f78] focus:border-[#176b55] focus:bg-white focus:outline-none"
                >
                @error('name')
                    <p class="mt-1 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact_phone" class="block text-sm font-semibold text-[#13221d]">
                    Nomor HP / WhatsApp <span class="text-[#b45309]">*</span>
                </label>
                <input
                    id="contact_phone"
                    name="phone"
                    type="tel"
                    required
                    maxlength="25"
                    value="{{ old('phone') }}"
                    placeholder="08xx-xxxx-xxxx"
                    class="mt-1.5 w-full rounded-xl border border-[#176b55]/20 bg-[#fbfcfa] px-3.5 py-2.5 text-sm text-[#13221d] placeholder:text-[#6c7f78] focus:border-[#176b55] focus:bg-white focus:outline-none"
                >
                @error('phone')
                    <p class="mt-1 text-xs font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="contact_message" class="block text-sm font-semibold text-[#13221d]">
                Pertanyaan Singkat <span class="text-[#b45309]">*</span>
            </label>
            <textarea
                id="contact_message"
                name="message"
                rows="{{ $compact ? 3 : 4 }}"
                required
                maxlength="1500"
                placeholder="Contoh: Menanyakan ketersediaan jadwal praktek sore ini atau layanan konsultasi KB..."
                class="mt-1.5 w-full rounded-xl border border-[#176b55]/20 bg-[#fbfcfa] px-3.5 py-2.5 text-sm text-[#13221d] placeholder:text-[#6c7f78] focus:border-[#176b55] focus:bg-white focus:outline-none"
            >{{ old('message') }}</textarea>
            <p class="mt-1.5 text-xs text-[#4b615a]">
                Demi privasi Anda (UU PDP), mohon <strong>tidak menuliskan rincian keluhan medis sensitif</strong> melalui formulir ini.
            </p>
            @error('message')
                <p class="mt-1 text-xs font-medium text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="flex items-start gap-2.5 text-xs leading-relaxed text-[#3d4d47]">
                <input
                    type="checkbox"
                    name="privacy_agreed"
                    value="1"
                    {{ old('privacy_agreed') ? 'checked' : '' }}
                    required
                    class="mt-0.5 h-4 w-4 rounded border-[#176b55]/40 text-[#176b55] focus:ring-[#176b55]"
                >
                <span>
                    Saya menyetujui data nama dan nomor kontak digunakan untuk membalas pertanyaan ini sesuai
                    <a href="{{ route('pages.privacy') }}" class="font-semibold text-[#176b55] underline underline-offset-2 hover:text-[#0f4c3c]">
                        Kebijakan Privasi
                    </a>.
                </span>
            </label>
            @error('privacy_agreed')
                <p class="mt-1 text-xs font-medium text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-1">
            <button
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#176b55] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0f4c3c] sm:w-auto"
            >
                Kirim Pertanyaan
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>
