<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RequisitionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $requisitions;

    public function __construct($requisitions)
    {
        $this->requisitions = $requisitions;
    }

    public function collection()
    {
        return $this->requisitions;
    }

    public function headings(): array
    {
        return [
            'Requisition Number',
            'Title',
            'Department',
            'Requested By',
            'Date Added',
            'Status',
            'Priority',
            'Reference',
        ];
    }

    public function map($requisition): array
    {
        return [
            $requisition->req_number ?? '',
            $requisition->req_title ?? '',
            $requisition->dept_name ?? '',
            ($requisition->user_name ?? '') . ' ' . ($requisition->user_surname ?? ''),
            $requisition->req_date_added ?? '',
            $requisition->status_name ?? '',
            $requisition->req_priority ?? '',
            $requisition->req_ref ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
