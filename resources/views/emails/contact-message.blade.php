<!DOCTYPE html>
<html lang="id">
<body style="font-family: sans-serif; color: #13221d; line-height: 1.6;">
    <h2 style="color: #176b55;">Pesan Baru dari Formulir Website</h2>
    <p><strong>Nama:</strong> {{ $contactMessage->name }}</p>
    <p><strong>No. HP / WhatsApp:</strong> {{ $contactMessage->phone }}</p>
    <p><strong>Waktu Masuk:</strong> {{ $contactMessage->created_at?->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</p>
    <p><strong>Pesan:</strong></p>
    <blockquote style="border-left: 3px solid #176b55; padding-left: 12px; margin: 12px 0;">
        {{ $contactMessage->message }}
    </blockquote>
    <p style="font-size: 13px; color: #4b615a;">
        Silakan buka panel admin (/admin) untuk menandai pesan sebagai dibaca atau ditindaklanjuti.
    </p>
</body>
</html>
