<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleService
{
    /**
     * Evaluate today's schedule status giving precedence to special closures.
     *
     * @param  Collection<int, object>  $openingHours
     * @param  Collection<int, object>  $specialClosures
     */
    public function todayStatus(Collection $openingHours, Collection $specialClosures, ?Carbon $now = null): object
    {
        $now ??= now('Asia/Jakarta');
        $todayDate = $now->toDateString();
        $isoDay = $now->dayOfWeekIso; // 1 (Mon) .. 7 (Sun)

        $special = $specialClosures->first(function ($item) use ($todayDate): bool {
            $dateValue = $item->date instanceof Carbon ? $item->date->toDateString() : (string) $item->date;

            return $dateValue === $todayDate;
        });

        if ($special) {
            if ($special->is_closed) {
                return (object) [
                    'is_open_now' => false,
                    'is_closed_today' => true,
                    'is_special' => true,
                    'badge_text' => 'Tutup Sementara',
                    'hours_text' => $special->title,
                    'note' => $special->reason,
                ];
            }

            $open = $this->formatTime($special->custom_open_time);
            $close = $this->formatTime($special->custom_close_time);
            $isOpenNow = $this->isWithinWindow($now, $special->custom_open_time, $special->custom_close_time);

            return (object) [
                'is_open_now' => $isOpenNow,
                'is_closed_today' => false,
                'is_special' => true,
                'badge_text' => $isOpenNow ? 'Buka Sekarang (Jadwal Khusus)' : 'Jadwal Khusus Hari Ini',
                'hours_text' => "{$open} – {$close} WIB",
                'note' => $special->reason ?: $special->title,
            ];
        }

        $regular = $openingHours->first(fn ($item) => (int) $item->day_of_week === $isoDay);

        if (! $regular || $regular->is_closed || ! $regular->open_time || ! $regular->close_time) {
            return (object) [
                'is_open_now' => false,
                'is_closed_today' => true,
                'is_special' => false,
                'badge_text' => 'Praktek Tutup Hari Ini',
                'hours_text' => 'Libur Rutin',
                'note' => $regular->notes ?? 'Silakan periksa jadwal hari berikutnya.',
            ];
        }

        $open = $this->formatTime($regular->open_time);
        $close = $this->formatTime($regular->close_time);
        $isOpenNow = $this->isWithinWindow($now, $regular->open_time, $regular->close_time);

        return (object) [
            'is_open_now' => $isOpenNow,
            'is_closed_today' => false,
            'is_special' => false,
            'badge_text' => $isOpenNow ? 'Sedang Buka' : 'Buka Hari Ini',
            'hours_text' => "{$open} – {$close} WIB",
            'note' => $regular->notes ?? 'Pendaftaran langsung di tempat selama jam praktek.',
        ];
    }

    /**
     * Build Schema.org OpeningHoursSpecification array from OpeningHour rows.
     *
     * @param  Collection<int, object>  $openingHours
     * @return array<int, array<string, mixed>>
     */
    public function openingHoursSpecification(Collection $openingHours): array
    {
        $schemaDays = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        return $openingHours
            ->filter(fn ($hour): bool => ! $hour->is_closed && filled($hour->open_time) && filled($hour->close_time))
            ->map(fn ($hour): array => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $schemaDays[(int) $hour->day_of_week] ?? 'Monday',
                'opens' => substr((string) $hour->open_time, 0, 5),
                'closes' => substr((string) $hour->close_time, 0, 5),
            ])
            ->values()
            ->all();
    }

    public function formatTime(?string $time): string
    {
        if (! $time) {
            return '-';
        }

        return substr(str_replace(':', '.', $time), 0, 5);
    }

    private function isWithinWindow(Carbon $now, ?string $openTime, ?string $closeTime): bool
    {
        if (! $openTime || ! $closeTime) {
            return false;
        }

        $start = Carbon::parse($now->toDateString().' '.$openTime, 'Asia/Jakarta');
        $end = Carbon::parse($now->toDateString().' '.$closeTime, 'Asia/Jakarta');

        return $now->betweenIncluded($start, $end);
    }
}
