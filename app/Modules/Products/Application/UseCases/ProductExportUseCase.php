<?php

namespace App\Modules\Products\Application\UseCases;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExportUseCase implements FromCollection, WithHeadings
{

    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'Name (En)',
            'Name (Ar)',
            'Description (En)',
            'Description (Ar)',
            'Code',
            'Stock',
            'Disclaimer',
        ];
    }
}
