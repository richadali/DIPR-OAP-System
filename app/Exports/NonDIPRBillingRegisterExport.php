<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class NonDIPRBillingRegisterExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell
{
    protected $bills;
    protected $rowCount;

    public function __construct($bills)
    {
        $this->bills = $bills;
        $this->rowCount = 0;
    }

    public function collection()
    {
        return $this->bills;
    }

    public function headings(): array
    {
        return [
            'Sl. No',
            'Branch of the Department',
            'Organizations issued',
            'Release Order No',
            'Release Order Date',
            'Bill No',
            'Bill Date',
            'Size/Seconds',
            'Amount',
        ];
    }

    public function map($bill): array
    {
        $this->rowCount++; // Increment row count for each bill

        return [
            $this->rowCount, // Use the row count for Sl. No
            $bill->dept_name,
            $bill->news_name,
            $bill->release_order_no,
            !empty($bill->release_order_date) ? \Carbon\Carbon::parse($bill->release_order_date)->format('d-m-Y') : '',
            $bill->bill_no,
            !empty($bill->bill_date) ? \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') : '',
            !empty($bill->cm) && !empty($bill->columns) ? $bill->cm . 'x' . $bill->columns : ($bill->seconds ? $bill->seconds . 's' : ''),
            $bill->amount,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Add title in A1 and merge cells for title row
        $sheet->setCellValue('A1', 'Bills not paid by DIPR');
        $sheet->mergeCells('A1:I1');

        // Style the title
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => Color::COLOR_BLACK],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style the headings in A3
        $sheet->getStyle('A3:I3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);

        return [];
    }

    public function startCell(): string
    {
        return 'A3'; // Start headings from A3, so title stays at A1
    }
}
