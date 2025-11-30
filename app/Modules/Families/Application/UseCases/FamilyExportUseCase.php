<?php

namespace App\Modules\Families\Application\UseCases;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class FamilyExportUseCase implements FromCollection, WithHeadings
{

    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description (En)',
            'Description (Ar)',
        ];
    }
}
