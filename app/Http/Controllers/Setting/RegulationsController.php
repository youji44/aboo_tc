<?php namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Utils;
use App\Http\Controllers\WsController;
use App\Models\Regulations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RegulationsController extends WsController
{
    /**
     * index, add, save, delete, update
     */
    public function add($type)
    {
        try {
            if(!$regulation = DB::table('regulations')->where('type',$type)->select('id','type','regulations')->first()){
                $obj = new \stdClass();
                $obj->id = '';
                if($type == 'vessel'){
                    $obj->water_defense = '';
                    $obj->vessel_filter = '';
                    $obj->bonding_cable = '';
                    $obj->differential_pressure = '';
                    $obj->filter_membrane = '';
                    $obj->deadman_control = '';
                    $obj->hoses_pumps_screens = '';
                    $obj->regulations = json_encode(array());

                }else if($type == 'fuel') {
                    $obj->hydrant_filter_sump = '';
                    $obj->tanker_filter_sump = '';
                    $obj->eye_wash_inspection = '';
                    $obj->visi_jar_cleaning = '';
                    $obj->filter_membrane_test = '';
                    $obj->fuel_equipment_weekly = '';
                    $obj->fuel_equipment_monthly = '';
                    $obj->fuel_equipment_quarterly = '';
                    $obj->fuel_equipment_daily = '';

                    $obj->regulations = json_encode(array());
                }else if($type == 'tanks') {
                    $obj->tank_sump_results = '';
                    $obj->tank_level_alarm_test = '';
                    $obj->regulations = json_encode(array());
                }
                else{
                    $obj->regulations = '';
                }

                $obj->type = $type;
                $regulation = $obj;
            }

            if($type == 'vessel'){

                $regulations = json_decode($regulation->regulations);

                $regulation->water_defense = $regulations[0]?? '';
                $regulation->vessel_filter = $regulations[1]?? '';
                $regulation->bonding_cable = $regulations[2]?? '';
                $regulation->differential_pressure = $regulations[3]?? '';
                $regulation->filter_membrane = $regulations[4]?? '';
                $regulation->deadman_control = $regulations[5]?? '';
                $regulation->hoses_pumps_screens = $regulations[6]?? '';

                return view('settings.regulations.vessel',compact('regulation','type'));
            }

            if($type == 'fuel'){
                $regulations = json_decode($regulation->regulations);

                $regulation->hydrant_filter_sump = $regulations[0]?? '';
                $regulation->tanker_filter_sump = $regulations[1]?? '';
                $regulation->eye_wash_inspection = $regulations[2]?? '';
                $regulation->visi_jar_cleaning = $regulations[3]?? '';
                $regulation->filter_membrane_test = $regulations[4]?? '';
                $regulation->fuel_equipment_weekly = $regulations[5]?? '';
                $regulation->fuel_equipment_monthly = $regulations[6]?? '';
                $regulation->fuel_equipment_quarterly = $regulations[7]?? '';
                $regulation->fuel_equipment_daily = $regulations[8] ?? '';

                return view('settings.regulations.fuel',compact('regulation','type'));
            }

            if($type == 'tanks'){
                $regulations = json_decode($regulation->regulations);
                if($regulations == null || count($regulations) < 2){
                    $regulation->tank_sump_results = '';
                    $regulation->tank_level_alarm_test = '';
                }else{
                    $regulation->tank_sump_results = $regulations[0];
                    $regulation->tank_level_alarm_test = $regulations[1];
                }
                return view('settings.regulations.tanks',compact('regulation','type'));
            }

            return view('settings.regulations.add',compact('regulation','type'));

        }catch(\Exception $e){
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(\Sentinel::check()) {
            $user_id = \Sentinel::getUser()->id;
            $user_name = \Sentinel::getUser()->name;
        }

        $id = $request->get('id');
        $type = $request->get('type');
        if($type == 'vessel'){
            $regulations1 = $request->get('regulations1');
            $regulations2 = $request->get('regulations2');
            $regulations3 = $request->get('regulations3');
            $regulations4 = $request->get('regulations4');
            $regulations5 = $request->get('regulations5');
            $regulations6 = $request->get('regulations6');
            $regulations7 = $request->get('regulations7');

            $regulations = json_encode([$regulations1,$regulations2,$regulations3,$regulations4,$regulations5,$regulations6,$regulations7]);

        }else if($type == 'fuel'){

            $regulations1 = $request->get('regulations1');
            $regulations2 = $request->get('regulations2');
            $regulations3 = $request->get('regulations3');
            $regulations4 = $request->get('regulations4');
            $regulations5 = $request->get('regulations5');
            $regulations6 = $request->get('regulations6');
            $regulations7 = $request->get('regulations7');
            $regulations8 = $request->get('regulations8');
            $regulations9 = $request->get('regulations9');

            $regulations = json_encode([$regulations1,$regulations2,$regulations3,$regulations4,$regulations5,
                $regulations6,$regulations7,$regulations8,$regulations9]);

        }else if($type == 'tanks'){

            $regulations1 = $request->get('regulations1');
            $regulations2 = $request->get('regulations2');
            $regulations = json_encode([$regulations1,$regulations2]);

        } else{
            $regulations = $request->get('regulations');
        }

        $route = Utils::get_indexRoute($type);
        try {
            DB::beginTransaction();

            if($id == ''){

                $db = new Regulations();
                $db->user_id = $user_id;
                $db->user_name = $user_name;
                $db->type = $type;
                $db->regulations = $regulations;
                $db->save();

            }else{
                DB::table('regulations')->where('id',$id)->update([
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'regulations' => $regulations,
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);
            }
            DB::commit();
            return Redirect::route($route)->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route($route)->with('error', "Failed Adding");
        }
    }
}
