<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TasksExport implements FromCollection, ShouldQueue, WithStyles, Responsable, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    // private $fileName = 'tasks.xlsx';  


    public function headings(): array
    {
        return [
            'Id',
            'Title',
            'Status',
            'Due_date',
            'User\'s name'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Task::select('id', 'title', 'status', 'due_date', 'user_id')->with('user:id,name')->get();
    }

    public function map($task): array
    {
        return [
            $task->id,
            $task->title,
            $task->status->value, // status is an enum so we use ->value
            $task->due_date->format('Y-m-d'),
            $task->user->name
        ];
    }
}
