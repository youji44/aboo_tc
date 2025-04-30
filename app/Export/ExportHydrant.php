<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportHydrant implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('hydrant_pit_checks as hc')
            ->LeftJoin('settings_hydrant as sh','sh.id','=','hc.gate_pit')
            ->LeftJoin('grading_result as gr1','gr1.id','=','hc.valve_condition')
            ->LeftJoin('grading_result as gr2','gr2.id','=','hc.dust_covers')
            ->LeftJoin('grading_result as gr3','gr3.id','=','hc.boot_seal')
            ->LeftJoin('grading_result as gr4','gr4.id','=','hc.cover_seal')
            ->LeftJoin('grading_result as gr5','gr5.id','=','hc.esd_condition')
            ->LeftJoin('grading_result as gr6','gr6.id','=','hc.level_of_liquids')
            ->LeftJoin('grading_result as gr7','gr7.id','=','hc.liquids_removed')
            ->select('hc.*','sh.gate','sh.pit',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'gr3.grade as gr3_grade',
                'gr4.grade as gr4_grade',
                'gr5.grade as gr5_grade',
                'gr6.grade as gr6_grade',
                'gr7.grade as gr7_grade'
            )
            ->get();
        else return \DB::table('hydrant_pit_checks as hc')
            ->LeftJoin('settings_hydrant as sh','sh.id','=','hc.gate_pit')
            ->LeftJoin('grading_result as gr1','gr1.id','=','hc.valve_condition')
            ->LeftJoin('grading_result as gr2','gr2.id','=','hc.dust_covers')
            ->LeftJoin('grading_result as gr3','gr3.id','=','hc.boot_seal')
            ->LeftJoin('grading_result as gr4','gr4.id','=','hc.cover_seal')
            ->LeftJoin('grading_result as gr5','gr5.id','=','hc.esd_condition')
            ->LeftJoin('grading_result as gr6','gr6.id','=','hc.level_of_liquids')
            ->LeftJoin('grading_result as gr7','gr7.id','=','hc.liquids_removed')
            ->select('hc.*','sh.gate','sh.pit',
                'gr1.grade as gr1_grade','gr1.color as gr1_color',
                'gr2.grade as gr2_grade','gr2.color as gr2_color',
                'gr3.grade as gr3_grade','gr3.color as gr3_color',
                'gr4.grade as gr4_grade','gr4.color as gr4_color',
                'gr5.grade as gr5_grade','gr5.color as gr5_color',
                'gr6.grade as gr6_grade','gr6.color as gr6_color',
                'gr7.grade as gr7_grade','gr7.color as gr7_color'
            )
            ->where('hc.status',1)
            ->whereDate('hc.date',$this->date)
            ->orderby('hc.gate_pit','ASC')
            ->orderby('hc.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('hydrant_pit_checks as hc')
            ->LeftJoin('settings_hydrant as sh','sh.id','=','hc.gate_pit')
            ->LeftJoin('grading_result as gr1','gr1.id','=','hc.valve_condition')
            ->LeftJoin('grading_result as gr2','gr2.id','=','hc.dust_covers')
            ->LeftJoin('grading_result as gr3','gr3.id','=','hc.boot_seal')
            ->LeftJoin('grading_result as gr4','gr4.id','=','hc.cover_seal')
            ->LeftJoin('grading_result as gr5','gr5.id','=','hc.esd_condition')
            ->LeftJoin('grading_result as gr6','gr6.id','=','hc.level_of_liquids')
            ->LeftJoin('grading_result as gr7','gr7.id','=','hc.liquids_removed')
            ->select('hc.*','sh.gate','sh.pit',
                'gr1.grade as gr1_grade',
                'gr2.grade as gr2_grade',
                'gr3.grade as gr3_grade',
                'gr4.grade as gr4_grade',
                'gr5.grade as gr5_grade',
                'gr6.grade as gr6_grade',
                'gr7.grade as gr7_grade'
            )->first());
    }
}