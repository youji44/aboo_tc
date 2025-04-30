<?php

namespace App\Export;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUser implements FromCollection,WithHeadings {
    private $report;
    public function __construct($report) {
        $this->report = $report;
    }
    public function collection()
    {
        return \DB::table('users as u')
            ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
            ->join('roles as r', 'r.id', '=', 'ru.role_id')
            ->join('activations as a', 'a.user_id', '=', 'u.id')
            ->select('u.name','u.email','a.completed','r.name as role_name')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('users as u')
            ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
            ->join('roles as r', 'r.id', '=', 'ru.role_id')
            ->join('activations as a', 'a.user_id', '=', 'u.id')
            ->select('u.name','u.email','a.completed','r.name as role_name')->first());
    }
}