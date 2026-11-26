<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_csv')
                ->label('Ekspor CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->action(fn (): StreamedResponse => $this->exportCsv()),
        ];
    }

    private function exportCsv(): StreamedResponse
    {
        $escapeFormula = static function (?string $value): string {
            $str = (string) $value;
            if ($str !== '' && in_array($str[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
                return "'".$str;
            }

            return $str;
        };

        return response()->streamDownload(function () use ($escapeFormula): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Nama', 'No HP', 'Pesan', 'Dibaca', 'Ditindaklanjuti', 'Masuk Pada']);

            ContactMessage::query()
                ->orderByDesc('created_at')
                ->chunk(200, function ($rows) use ($handle, $escapeFormula): void {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->id,
                            $escapeFormula($row->name),
                            $escapeFormula($row->phone),
                            $escapeFormula($row->message),
                            $row->read_at?->toDateTimeString() ?? '-',
                            $row->handled_at?->toDateTimeString() ?? '-',
                            $row->created_at?->toDateTimeString() ?? '-',
                        ]);
                    }
                });

            fclose($handle);
        }, 'pesan-masuk-'.now('Asia/Jakarta')->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
