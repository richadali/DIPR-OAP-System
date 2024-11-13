<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IssueRegisterExport implements WithMultipleSheets
{
    protected $advertisements;

    public function __construct($advertisements)
    {
        $this->advertisements = $advertisements;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Add all data to one sheet
        $sheets[] = new IssueRegisterSheet($this->advertisements, 'All Issues');

        // Group advertisements by month and year
        $groupedAdvertisements = $this->advertisements->groupBy(function ($advertisement) {
            return Carbon::parse($advertisement->issue_date)->format('F Y'); // Group by month and year
        });

        // Create a sheet for each month
        foreach ($groupedAdvertisements as $month => $ads) {
            $sheets[] = new IssueRegisterSheet($ads, $month); // Use month name for sheet title
        }

        return $sheets;
    }
}

class IssueRegisterSheet implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $advertisements;
    protected $sheetTitle;

    public function __construct($advertisements, $sheetTitle)
    {
        $this->advertisements = $advertisements;
        $this->sheetTitle = $sheetTitle;
    }

    public function collection()
    {
        return $this->advertisements->map(function ($advertisement) {
            return [
                'MIPR No' => $advertisement->mipr_no,
                'Date of issue' => Carbon::parse($advertisement->issue_date)->format('d-m-Y'),
                'Name of Department Concerned' => $advertisement->department->dept_name,
                'Size/Seconds' => !empty($advertisement->cm) && !empty($advertisement->columns) ?
                    $advertisement->cm . ' x ' . $advertisement->columns : (!empty($advertisement->seconds) ? $advertisement->seconds . ' s' : ''),
                'Subject' => $advertisement->subject->subject_name ?? '',
                'Ref. No & Date' => $advertisement->ref_no . ' Dt. ' . Carbon::parse($advertisement->ref_date)->format('d-m-Y'),
                'Positively on' => $advertisement->positively_on,
                'No of Insertion' => $advertisement->no_of_entries,
                'Issued to Organization' => $this->getIssuedToOrganization($advertisement),
                'Remarks' => $advertisement->remarks ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'MIPR No',
            'Date of issue',
            'Name of Department Concerned',
            'Size/Seconds',
            'Subject',
            'Ref. No & Date',
            'Positively on',
            'No of Insertion',
            'Issued to Organization',
            'Remarks',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // MIPR No
            'B' => 20,  // Date of issue
            'C' => 30,  // Name of Department Concerned
            'D' => 25,  // Size in cm x col
            'E' => 25,  // Subject
            'F' => 30,  // Ref. No & Date
            'G' => 25,  // Positively on
            'H' => 20,  // No of Insertion
            'I' => 40,  // Issued to Organization
            'J' => 30,  // Remarks
        ];
    }

    private function getIssuedToOrganization($advertisement)
    {
        return $advertisement->assigned_news->map(function ($assignedNews) {
            return $assignedNews->empanelled->news_name;
        })->implode(', ') ?? 'N/A';
    }
}
