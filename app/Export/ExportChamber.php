<?php

namespace App\Export;
use App\Models\AirlineWater;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportChamber implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('valve_chambers as vc')
            ->leftJoin('settings_chamber as sc','sc.id','=','vc.chamber_no')
            ->leftJoin('grading_result as gr1','gr1.id','=','vc.emergency_access')
            ->leftJoin('grading_result as gr2','gr2.id','=','vc.standing_liquid_and_debris')
            ->leftJoin('grading_result as gr3','gr3.id','=','vc.verify_operation_of_all_valves')
            ->leftJoin('grading_result as gr4','gr4.id','=','vc.general_condition')
            ->select('vc.*','sc.chamber_no as sc_chamber_no',
                'gr1.result as gr1_result','gr1.color as gr1_color',
                'gr2.result as gr2_result','gr2.color as gr2_color',
                'gr3.result as gr3_result','gr3.color as gr3_color',
                'gr4.result as gr4_result','gr4.color as gr4_color'
            )
            ->get();
        else return \DB::table('valve_chambers as vc')
            ->leftJoin('settings_chamber as sc','sc.id','=','vc.chamber_no')
            ->leftJoin('grading_result as gr1','gr1.id','=','vc.emergency_access')
            ->leftJoin('grading_result as gr2','gr2.id','=','vc.standing_liquid_and_debris')
            ->leftJoin('grading_result as gr3','gr3.id','=','vc.verify_operation_of_all_valves')
            ->leftJoin('grading_result as gr4','gr4.id','=','vc.general_condition')
            ->select('vc.*','sc.chamber_no as sc_chamber_no',
                'gr1.result as gr1_result','gr1.color as gr1_color',
                'gr2.result as gr2_result','gr2.color as gr2_color',
                'gr3.result as gr3_result','gr3.color as gr3_color',
                'gr4.result as gr4_result','gr4.color as gr4_color'
            )
            ->where('vc.status',1)
            ->whereDate('vc.checked_at',$this->date)
            ->orderby('vc.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('valve_chambers as vc')
            ->leftJoin('settings_chamber as sc','sc.id','=','vc.chamber_no')
            ->leftJoin('grading_result as gr1','gr1.id','=','vc.emergency_access')
            ->leftJoin('grading_result as gr2','gr2.id','=','vc.standing_liquid_and_debris')
            ->leftJoin('grading_result as gr3','gr3.id','=','vc.verify_operation_of_all_valves')
            ->leftJoin('grading_result as gr4','gr4.id','=','vc.general_condition')
            ->select('vc.*','sc.chamber_no as sc_chamber_no',
                'gr1.result as gr1_result','gr1.color as gr1_color',
                'gr2.result as gr2_result','gr2.color as gr2_color',
                'gr3.result as gr3_result','gr3.color as gr3_color',
                'gr4.result as gr4_result','gr4.color as gr4_color'
            )->first());
    }
}