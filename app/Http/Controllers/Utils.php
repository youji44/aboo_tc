<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Utils
{
    public static $width = 565;

    public static function count($date = '', $pending = false, $userid = ''): array
    {
        $pid = Session::get('p_loc');

        $audit_count = DB::table('internal_audit as a')
            ->leftJoin('settings_audit as sa', 'sa.id', '=', 'a.audit_type')
            ->where(function($query) use ($pid) {$query->whereNull('a.pid')->orWhere('a.pid', $pid);})
            ->where('sa.plocation_id', $pid);
        $audit_count = self::get_recorded_count($audit_count, $date, $userid, $pending);

        $incident_count = DB::table('qcf_incident_report as a')
            ->where('a.pid',$pid);
        $incident_count = self::get_recorded_count($incident_count, $date, $userid, $pending);

        $count = array();
        $count['total'] = $incident_count + $audit_count;
        $count['qcf_incident'] = $incident_count;
        $count['audit'] = $audit_count;

        return $count;
    }

    public static function get_recorded_count($query, $date, $userid, $pending)
    {
        return $query->when($date!='', function($query) use ($date) {
            $query->whereDate('a.date', $date);
        })->when($userid!='', function($query) use ($userid) {
            $query->where('a.user_id', $userid);
        })->when(!$pending, function($query) use ($userid) {
            $query->where('a.status', '<', 2);
        }, function ($query) {
            $query->where('a.status', 0);
        })->count();
    }

    public static function convert_base64($url)
    {
        try{
            $type = pathinfo($url, PATHINFO_EXTENSION);
            $data = file_get_contents($url);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }catch (\Exception $e){
            Log::info($e->getMessage());
            return '';
        }
    }

    public static function logo(){
        try{
            $url = public_path().'/mark.png';
            $type = pathinfo($url, PATHINFO_EXTENSION);
            $data = file_get_contents($url);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }catch (\Exception $e){
            Log::info($e->getMessage());
            return '';
        }
    }

    public static function get_color($value)
    {
        return is_numeric($value)&&$value > 0?'alert alert-warning':'';
    }

    public static function get_location()
    {

        $user_locations = DB::table('user_locations')->where('user_id', Sentinel::getUser()->id)->first();
        $ids = array();
        if ($user_locations != null) {
            $ids = json_decode($user_locations->location_ids);
        }
        $locations = array();
        $plocations = DB::table('primary_location')->where('status','<',2)->orderBy('location')->get();
        foreach ($plocations as $item) {
            if (in_array($item->id, $ids) || Sentinel::inRole('superadmin'))
                $locations[] = $item;
        }

        return $locations;
    }

    public static function get_indexRoute($regulation_type)
    {

        $route['airline'] = 'settings.airline';
        $route['chamber'] = 'settings.chamber';
        $route['drain'] = 'settings.drain';
        $route['esd'] = 'settings.esd';
        $route['fire'] = 'settings.fire';
        $route['fuel'] = 'settings.fuel';
        $route['gasbar'] = 'settings.gasbar';

        $route['grading'] = 'settings.grading';
        $route['hazard'] = 'settings.hazard';
        $route['hpd'] = 'settings.hpd';
        $route['hydrant'] = 'settings.hydrant';
        $route['location'] = 'settings.location';
        $route['monitor'] = 'settings.monitor';
        $route['oil'] = 'settings.oil';
        $route['pit'] = 'settings.pit';
        $route['recycle'] = 'settings.recycle';

        $route['tf1_facility'] = 'tf1.settings.facility';

        $route['tf1_filter'] = 'tf1.settings.filter';
        $route['tf1_sloptank'] = 'tf1.settings.sloptank';
        $route['tf1_walk'] = 'tf1.settings.walk';

        $route['tf_dbb'] = 'settings.dbb';
        $route['tf_pipline'] = 'settings.pipline';
        $route['tf_totalizer'] = 'settings.totalizer';

        $route['gasbarm'] = 'settings.gasbarm';
        $route['gasbarw'] = 'settings.gasbarw';

        $route['vessel'] = 'settings.vessel';
        $route['tfesd'] = 'settings.tfesd';
        $route['tf1_tanksump'] = 'tf1.settings.tanksump';
        $route['tanks'] = 'tf1.settings.tanksump';
        $route['signs'] = 'settings.signs';

        $route['leak'] = 'settings.leak';

        $route['audit'] = 'settings.audit';

        $route['truck'] = 'settings.truck';

        $route['hose'] = 'settings.maintenance.hose';
        $route['vessel_filter'] = 'settings.maintenance.vessel_filter';

        $route['cathodic'] = 'settings.cathodic';
        $route['owsc'] = 'settings.owsc';
        $route['prevent'] = 'settings.prevent.task';

        return $route[$regulation_type];
    }

    public static function get_title($regulation_type)
    {

        $title['airline'] = 'Airline Management';
        $title['chamber'] = 'Valve Chamber';
        $title['drain'] = 'Low Point Drain';
        $title['esd'] = 'Hydrant Pit - ESD';
        $title['fire'] = 'Facility Fire Extinguisher';
        $title['fuel'] = 'Fuel Equipment';
        $title['gasbar'] = 'Gas Bar Task';
        $title['grading'] = 'Grading Result';
        $title['hazard'] = 'Facility Hazardous Material';
        $title['hpd'] = 'High Point Drain Checks';
        $title['hydrant'] = 'Hydrant Pit';
        $title['location'] = 'Primary Location';
        $title['monitor'] = 'Monitoring Well';
        $title['oil'] = 'Oil Water Seperator';
        $title['pit'] = 'Fuel Depot-Walk Around Task';
        $title['recycle'] = 'Recycle Area';

        $title['tf1_facility'] = 'Facility General Condition';

        $title['tf1_filter'] = 'Filter Separator';
        $title['tf1_sloptank'] = 'Slop Tank';
        $title['tf1_walk'] = 'Walk Around';

        $title['tf_dbb'] = 'Double Block and Bleed';
        $title['tf_pipline'] = 'Close Out Pipline';
        $title['tf_totalizer'] = 'Close Out Totalizer';

        $title['gasbarm'] = 'Gas Bar Task';
        $title['gasbarw'] = 'Gas Bar Task';

        $title['vessel'] = 'Vessel';
        $title['tfesd'] = 'Tank Farm Emergency Shut Down(ESD)';
        $title['tanks'] = 'Tanks';
        $title['signs'] = 'Signs & Placards';

        $title['leak'] = 'Leak Detection';

        $title['audit'] = 'Internal Audit';
        $title['truck'] = 'Fuel Depot - Truck Rack';

        $title['hose'] = 'Hose Change Out Certificate';
        $title['vessel_filter'] = 'Vessel Inspection, Filter Change Certificate';

        $title['cathodic'] = 'Cathodic Protection';
        $title['owsc'] = 'Oil Water Separator Cleaning';
        $title['prevent'] = 'Preventative Maintenance';

        return $title[$regulation_type];
    }

    public static function get_regulations($regulation_type, $sub_type = '')
    {
        if (!$regulation = DB::table('regulations')->where('type', $regulation_type)
            ->select('regulations')
            ->first()) {
            $obj = new \stdClass();
            $obj->regulations = '';
            $regulation = $obj;
        }

        if ($regulation_type == 'vessel') {
            $regulations = json_decode($regulation->regulations);
            if ($regulations == null || count($regulations) < 7) {
                $regulation->regulations = '';
            } else {
                switch ($sub_type) {
                    case 'water_defense':
                        $regulation->regulations = $regulations[0];
                        break;
                    case 'vessel_filter':
                        $regulation->regulations = $regulations[1];
                        break;
                    case 'bonding_cable':
                        $regulation->regulations = $regulations[2];
                        break;
                    case 'differential_pressure':
                        $regulation->regulations = $regulations[3];
                        break;
                    case 'filter_membrane':
                        $regulation->regulations = $regulations[4];
                        break;
                    case 'deadman_control':
                        $regulation->regulations = $regulations[5];
                        break;
                    case 'hoses_pumps_screens':
                        $regulation->regulations = $regulations[6];
                        break;
                }
            }

        }

        if ($regulation_type == 'fuel') {
            $regulations = json_decode($regulation->regulations);
            switch ($sub_type) {
                case 'hydrant_filter_sump':
                    $regulation->regulations = $regulations[0]??'';
                    break;
                case 'tanker_filter_sump':
                    $regulation->regulations = $regulations[1]??'';
                    break;
                case 'eye_wash_inspection':
                    $regulation->regulations = $regulations[2]??'';
                    break;
                case 'visi_jar_cleaning':
                    $regulation->regulations = $regulations[3]??'';
                    break;
                case 'filter_membrane_test':
                    $regulation->regulations = $regulations[4]??'';
                    break;
                case 'fuel_equipment_weekly':
                    $regulation->regulations = $regulations[5]??'';
                    break;
                case 'fuel_equipment_monthly':
                    $regulation->regulations = $regulations[6]??'';
                    break;
                case 'fuel_equipment_quarterly':
                    $regulation->regulations = $regulations[7]??'';
                    break;
                case 'fuel_equipment_daily':
                    $regulation->regulations = $regulations[8]??'';
                    break;
            }
        }

        if ($regulation_type == 'tanks') {
            $regulations = json_decode($regulation->regulations);
            if ($regulations == null || count($regulations) < 2) {
                $regulation->regulations = '';
            } else {
                switch ($sub_type) {
                    case 'tank_sump_results':
                        $regulation->regulations = $regulations[0];
                        break;
                    case 'tank_level_alarm_test':
                        $regulation->regulations = $regulations[1];
                        break;
                }
            }
        }

        return $regulation;
    }

    public static function get_table($cat)
    {

        $title['airline'] = 'airline_water_test_record';
        $title['chamber'] = 'valve_chambers';
        $title['drain'] = 'low_point_drain_checks';
        $title['esd'] = 'esd';
        $title['cart'] = 'hydrant_cart_filter_sump';
        $title['tanker'] = 'tanker_filter_sump';
        $title['visi'] = 'visi_jar_cleaning';
        $title['fire'] = 'fire_extinguisher';
        $title['fuel'] = 'fuel_equipment';
        $title['gasbar'] = 'gasbar';
        $title['gasbarm'] = 'gasbar_m';
        $title['gasbarw'] = 'gasbar_w';
        $title['grading'] = 'grading_result';
        $title['hazard'] = 'hazard_material';
        $title['hpd'] = 'hpd';
        $title['hydrant'] = 'hydrant_pit_checks';
        $title['location'] = 'primary_location';
        $title['monitor'] = 'monitor_well';
        $title['oil'] = 'oil_water_separator';
        $title['pit'] = 'pit_area';
        $title['recycle'] = 'recycle_area';
        $title['power'] = 'power_wash';
        $title['eye'] = 'eye_wash_inspection';
        $title['tf1_facility'] = 'tf1_facility_general_condition';
        $title['tf1_tanksump'] = 'tf1_tank_sump';
        $title['tf1_sloptank'] = 'tf1_sloptank';
        $title['tf1_filter'] = 'tf1_filter_separator';
        $title['tf1_walk'] = 'tf_walk_around';
        $title['tf_dbb'] = 'tf_dbb';
        $title['tf_pipline'] = 'tf_pipline';
        $title['tf_totalizer'] = 'tf_totalizer';

        $title['signs'] = 'settings_signs_placards';
        $title['truck'] = 'settings_truck';

        $title['cathodic'] = 'settings_cathodic';
        $title['owsc'] = 'settings_owsc';
        $title['prevent'] = 'settings_prevent_task';

        $res = '';
        if ($cat && array_key_exists($cat, $title)) $res = $title[$cat];
        return $res;
    }

    public static function regulation($type, $sub_type = '')
    {
        if ($regulation = DB::table('regulations')->where('type', $type)->first()) {

            if ($type == 'vessel') {
                $regulations = json_decode($regulation->regulations);
                if ($regulations == null || count($regulations) < 7) {
                    $regulation->regulations = '';
                } else {
                    switch ($sub_type) {
                        case 'water_defense':
                            $regulation->regulations = $regulations[0];
                            break;
                        case 'vessel_filter':
                            $regulation->regulations = $regulations[1];
                            break;
                        case 'bonding_cable':
                            $regulation->regulations = $regulations[2];
                            break;
                        case 'differential_pressure':
                            $regulation->regulations = $regulations[3];
                            break;
                        case 'filter_membrane':
                            $regulation->regulations = $regulations[4];
                            break;
                        case 'deadman_control':
                            $regulation->regulations = $regulations[5];
                            break;
                        case 'hoses_pumps_screens':
                            $regulation->regulations = $regulations[6];
                            break;
                    }
                }

            } else if ($type == 'fuel') {

                $regulations = json_decode($regulation->regulations);
                switch ($sub_type) {
                    case 'hydrant_filter_sump':
                        $regulation->regulations = $regulations[0]??'';
                        break;
                    case 'tanker_filter_sump':
                        $regulation->regulations = $regulations[1]??'';
                        break;
                    case 'eye_wash_inspection':
                        $regulation->regulations = $regulations[2]??'';
                        break;
                    case 'visi_jar_cleaning':
                        $regulation->regulations = $regulations[3]??'';
                        break;
                    case 'filter_membrane_test':
                        $regulation->regulations = $regulations[4]??'';
                        break;
                    case 'fuel_equipment_weekly':
                        $regulation->regulations = $regulations[5]??'';
                        break;
                    case 'fuel_equipment_monthly':
                        $regulation->regulations = $regulations[6]??'';
                        break;
                    case 'fuel_equipment_quarterly':
                        $regulation->regulations = $regulations[7]??'';
                        break;
                    case 'fuel_equipment_daily':
                        $regulation->regulations = $regulations[8]??'';
                        break;
                }
            }else if ($type == 'tanks') {

                $regulations = json_decode($regulation->regulations);
                if ($regulations == null || count($regulations) < 2) {
                    $regulation->regulations = '';
                } else {
                    switch ($sub_type) {
                        case 'tank_sump_results':
                            $regulation->regulations = $regulations[0];
                            break;
                        case 'tank_level_alarm_test':
                            $regulation->regulations = $regulations[1];
                            break;
                    }
                }
            }

            $regulation = strip_tags($regulation->regulations, '<p><h2><h3><h4>');

        } else
            $regulation = null;

        return $regulation;
    }

    public static function name($str, $flag = false)
    {
        $str1 = strtolower(str_replace(' ', '', $str));
        $str2 = Session::get('p_loc_name');
        $str2 = strtolower(str_replace(' ', '', $str2));
        if($flag)
            return $str1 === $str2;
        else
            return str_contains($str2, $str1);
    }

    public static function reason($str)
    {
        if($str == 'a') return 'a) Request by airport inspector';
        if($str == 'b') return 'b) Request by airline representative';
        if($str == 'c') return 'c) Doubtful nozzle sample';
        if($str == 'd') return 'd) High pressure differential';
        if($str == 'e') return 'e) Low pressure differential';
        if($str == 'f') return 'f) High membrane filtration test result';
        if($str == 'g') return 'g) Evidence of suspended water';
        if($str == 'h') return 'h) Annual inspection';
        if($str == 'i') return 'i) Other';
        return '';
    }

    public static function Insight($in){
        return $in == 'insight';
    }

    public static function GetValue($id){
        $condition_value = DB::table('grading_result')->where('grading_type','condition')->where('id',$id)
            ->select('id','result','value','grading_type')
            ->value('value');
        return $condition_value;
    }

    public static function unit_type($type = '')
    {
        if(!$type){
            return DB::raw('(CASE
                WHEN unit_type = 1 THEN "Hydrant Cart"
                WHEN unit_type = 2 THEN "Tankers"
                WHEN unit_type = 3 THEN "Stationary Hydrant Cart"
                WHEN unit_type = 4 THEN "Service Equipment"
                WHEN unit_type = 5 THEN "Other"
                WHEN unit_type = 6 THEN "Hydrant Trucks"
                WHEN unit_type = 7 THEN "Narrow-body Cart"
                WHEN unit_type = 8 THEN "Tanker 10K"
                WHEN unit_type = 9 THEN "Tanker 5K"
                WHEN unit_type = 10 THEN "Wide-body Cart"
                WHEN unit_type = 11 THEN "Catering"
                ELSE ""
                END) AS unit_type');
        }
        else{
            if($type == '1') return 'Hydrant Cart';
            if($type == '2') return 'Tankers';
            if($type == '3') return 'Stationary Hydrant Cart';
            if($type == '4') return 'Service Equipment';
            if($type == '5') return 'Other';
            if($type == '6') return 'Hydrant Trucks';
            if($type == '7') return 'Narrow-body Cart';
            if($type == '8') return 'Tanker 10K';
            if($type == '9') return 'Tanker 5K';
            if($type == '10') return 'Wide-body Cart';
            if($type == '11') return 'Catering';
            return '';
        }
    }

    public static function unit_types(): array
    {
        return [
            '1'=>'Hydrant Cart',
            '6'=>'Hydrant Trucks',
            '7'=>'Narrow-body Cart',
            '3'=>'Stationary Hydrant Cart',
            '4'=>'Service Equipment',
            '2'=>'Tankers',
            '8'=>'Tanker 10K',
            '9'=>'Tanker 5K',
            '10'=>'Wide-body Cart',
            '11'=>'Catering',
            '5'=>'Other',
        ];
    }

    public static function equip_types(): array
    {
        return [
            '9'=>'Bus',
            '4'=>'Pickup Truck - 2 door',
            '5'=>'Pickup Truck - 4 door',
            '1'=>'Sedan',
            '3'=>'SUV',
            '6'=>'Tanker',
            '7'=>'Trailer',
            '8'=>'Truck',
            '2'=>'Van',
        ];
    }

    public static function equip_type($type)
    {
        if($type == '1') return 'Sedan';
        if($type == '2') return 'Van';
        if($type == '3') return 'SUV';
        if($type == '4') return 'Pickup Truck - 2 door';
        if($type == '5') return 'Pickup Truck - 4 door';
        if($type == '6') return 'Tanker';
        if($type == '7') return 'Trailer';
        if($type == '8') return 'Truck';
        if($type == '9') return 'Bus';
        return '';
    }

    public static function inspect_type($type = '')
    {
        if(!$type){
            return DB::raw('(CASE
                WHEN inspect_type = 1 THEN "Before Shift Start"
                WHEN inspect_type = 2 THEN "Before Shift End"
                WHEN inspect_type = 3 THEN "Other"
                ELSE ""
                END) AS inspect_type');
        }
        else{
            if($type == '1') return 'Before Shift Start';
            if($type == '2') return 'Before Shift End';
            if($type == '3') return 'Other';
            return '';
        }
    }

    public static function inspect_types(): array
    {
        return [
            '1'=>'Before Shift Start',
            '2'=>'Before Shift End',
            '3'=>'Other',
        ];
    }

    public static function main_name($type): string
    {
        if($type == '0') return 'Fuel Equipment - Weekly Inspection';
        if($type == '1') return 'Fuel Equipment - Monthly Inspection';
        if($type == '2') return 'Fuel Equipment - Quarterly Inspection';
        if($type == '3') return 'Hose Inspection - Annual Inspection';
        if($type == '4') return 'Preventative Maintenance - Monthly Inspection';
        if($type == '5') return 'Vessel Inspection, Filter Change - Annual Inspection';
        return '';
    }

    public static function form_item($type): string
    {
        return self::form_items()[$type];
    }

    public static function form_items(): array
    {
        return [
            '0'=>'Date and Time',
            '1'=>'Number Field',
            '2'=>'Text Field',
            '3'=>'Text Area',
            '4'=>'Multiple Choice',
            '5'=>'Image Uploader',
            '6'=>'Condition',
        ];
    }

    public static function po_status($status): \stdClass
    {
        $ret = "AWAITING APPROVAL";
        $color = "warning";
        switch ($status){
            case 0:
                $ret = "AWAITING APPROVAL";
                $color = "warning";
                break;
            case 1:
                $ret = "EDITED";
                $color = "warning";
                break;
            case 2:
                $ret = "AWAITING DELIVERY";
                $color = "info";
                break;
            case 3:
                $ret = "PARTIALLY RECEIVED";
                $color = "success";
                break;
            case 4:
                $ret = "FULLY RECEIVED";
                $color = "success";
                break;
            case 5:
                $ret = "PARTIALLY PAID";
                $color = "secondary";
                break;
            case 6:
                $ret = "PAID";
                $color = "secondary";
                break;
            case 7:
                $ret = "CANCELLED";
                $color = "danger";
                break;
            case 8:
                $ret = "CANCELLED AFTER APPROVAL";
                $color = "danger";
                break;
            case 11:
                $ret = "EDITED";
                $color = "warning";
                break;
            default:
                break;
        }
        $obj = new \stdClass();
        $obj->status = $ret;
        $obj->color = $color;
        return $obj;
    }


    public static function get_drno($id){
        $pre = 'DR';
        $zero = '0000000';
        $diff = strlen($zero)-strlen($id);
        if(strlen($zero)-strlen($id) > 0){
            while ($diff > 0){
                $pre .= '0';
                $diff = $diff - 1;
            }
        }
        return $pre.$id;
    }
}
