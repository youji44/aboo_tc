<?php

namespace App\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportHPD implements FromCollection,WithHeadings {
    private $report;
    private $date;
    public function __construct($report,$date) {
        $this->report = $report;
        $this->date = $date==''?date('Y-m-d'):$date;
    }
    public function collection()
    {
        if($this->report == '0')
        return \DB::table('hpd')
            ->LeftJoin('settings_hpd as sh','sh.id','=','hpd.hpd_no')
            ->LeftJoin('grading_result as gr1','gr1.id','=','hpd.pit_clean')
            ->LeftJoin('grading_result as gr2','gr2.id','=','hpd.air_released')
            ->LeftJoin('grading_result as gr3','gr3.id','=','hpd.valve_condition')
            ->LeftJoin('grading_result as gr4','gr4.id','=','hpd.dust_cover_on')
            ->select('hpd.*',
                'gr1.result as gr1_result','gr1.color as gr1_color',
                'gr2.result as gr2_result','gr2.color as gr2_color',
                'gr3.result as gr3_result','gr3.color as gr3_color',
                'gr4.result as gr4_result','gr4.color as gr4_color',
                'sh.hpd_no as sh_hpd_no')
            ->orderby('hpd.created_at','DESC')
            ->get();
        else return \DB::table('hpd')
            ->LeftJoin('settings_hpd as sh','sh.id','=','hpd.hpd_no')
            ->LeftJoin('grading_result as gr1','gr1.id','=','hpd.pit_clean')
            ->LeftJoin('grading_result as gr2','gr2.id','=','hpd.air_released')
            ->LeftJoin('grading_result as gr3','gr3.id','=','hpd.valve_condition')
            ->LeftJoin('grading_result as gr4','gr4.id','=','hpd.dust_cover_on')
            ->where('hpd.status',1)
            ->whereDate('hpd.checked_at',$this->date)
            ->select('hpd.*',
                'gr1.result as gr1_result','gr1.color as gr1_color',
                'gr2.result as gr2_result','gr2.color as gr2_color',
                'gr3.result as gr3_result','gr3.color as gr3_color',
                'gr4.result as gr4_result','gr4.color as gr4_color',
                'sh.hpd_no as sh_hpd_no')

            ->orderby('hpd.created_at','DESC')
            ->get();
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return array_keys((array)\DB::table('hpd')
            ->LeftJoin('settings_hpd as sh','sh.id','=','hpd.hpd_no')
            ->LeftJoin('grading_result as gr1','gr1.id','=','hpd.pit_clean')
            ->LeftJoin('grading_result as gr2','gr2.id','=','hpd.air_released')
            ->LeftJoin('grading_result as gr3','gr3.id','=','hpd.valve_condition')
            ->LeftJoin('grading_result as gr4','gr4.id','=','hpd.dust_cover_on')
            ->select('hpd.*',
                'gr1.result as gr1_result','gr1.color as gr1_color',
                'gr2.result as gr2_result','gr2.color as gr2_color',
                'gr3.result as gr3_result','gr3.color as gr3_color',
                'gr4.result as gr4_result','gr4.color as gr4_color',
                'sh.hpd_no as sh_hpd_no')->first());
    }
}