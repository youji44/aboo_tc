<?php namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Utils;
use App\Http\Controllers\WsController;
use App\Models\AssignInspection;
use App\Models\FuelEquipment;
use App\Models\GradingResult;
use App\Models\Inspections;
use App\Models\Operators;
use App\Models\PrimaryLocation;
use App\Models\SettingsCathodic;
use App\Models\SettingsInspectPreset;
use App\Models\SettingsInspectTask;
use App\Models\SettingsOWSC;
use App\Models\SettingsRefuelled;
use App\Models\Vessel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingController extends WsController
{
    public function index(Request $request)
    {
        return View('settings.index_ir');
    }

    /**
     * index, add, save, delete, update
     */

    public function fuel_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $fuel = DB::table('fuel_equipment as fe')
                ->leftJoin('settings_fire_type as sf','sf.id','=','fe.fire_ext_id')
                ->leftJoin('primary_location as pl','pl.id','=','fe.plocation_id')
                ->where('fe.status','<',2)
                ->orderBy('fe.unit','asc')
                ->select('fe.*','pl.location','sf.fire_extinguisher_type')
                ->get();
            foreach ($fuel as $item){
                $item->unit_type = Utils::unit_type($item->unit_type);
                $item->equip_type = Utils::equip_type($item->equip_type);
            }
            DB::commit();
            return view('settings.fuel.index',compact('fuel'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return back()->with('error', "Loading Failed!");
        }
    }

    public function fuel_add(Request $request)
    {
        $primary_locations = DB::table('primary_location')->get();
        $fire_ext = DB::table('settings_fire_type')->select('id','fire_extinguisher_type')->where('status','<',2)->get();
        return View('settings.fuel.add',compact('fire_ext','primary_locations'));
    }

    public function fuel_edit($id)
    {
        try {
            DB::beginTransaction();
            $primary_locations = DB::table('primary_location')->get();
            $fire_ext = DB::table('settings_fire_type')->select('id','fire_extinguisher_type')->where('status','<',2)->get();
            $fuel = DB::table('fuel_equipment')->where('id',$id)->first();
            DB::commit();

            return view('settings.fuel.edit',compact('fuel','fire_ext','primary_locations'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function fuel_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $pid = $request->get('pid');
        $unit = $request->get('unit');
        $unit_type = $request->get('unit_type');

        $model_type = $request->get('model_type');
        $serial_number = $request->get('serial_number');
        $qty_installed = $request->get('qty_installed');
        $max_flow_rate = $request->get('max_flow_rate');
        $max_dp = $request->get('max_dp');
        $last_inspected= $request->get('last_inspected');
        $fire_ext_id= $request->get('fire_ext_id');

        $vin_number = $request->get('vin_number');
        $manu_year = $request->get('manu_year');
        $make_model = $request->get('make_model');
        $equip_type= $request->get('equip_type');
        $size= $request->get('equip_type');

        $hydrant_filter_sump = $request->get('hydrant_filter_sump')=='on'?1:0;
        $tanker_filter_sump = $request->get('tanker_filter_sump')=='on'?1:0;
        $eye_wash_inspection = $request->get('eye_wash_inspection')=='on'?1:0;
        $visi_jar_cleaning = $request->get('visi_jar_cleaning')=='on'?1:0;
        $filter_membrane_test = $request->get('filter_membrane_test')=='on'?1:0;
        $fuel_equipment_weekly = $request->get('fuel_equipment_weekly')=='on'?1:0;
        $fuel_equipment_monthly = $request->get('fuel_equipment_monthly')=='on'?1:0;
        $fuel_equipment_quarterly = $request->get('fuel_equipment_quarterly')=='on'?1:0;

        try {
            DB::beginTransaction();

            $db = new FuelEquipment();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->plocation_id = $pid;
            $db->unit = $unit;
            $db->unit_type = $unit_type;
            $db->model_type = $model_type;
            $db->serial_number = $serial_number;
            $db->qty_installed = $qty_installed;
            $db->max_flow_rate = $max_flow_rate;
            $db->max_dp = $max_dp;
            $db->last_inspected = $last_inspected;
            $db->fire_ext_id = $fire_ext_id;
            $db->size = $size;
            $db->vin_number = $vin_number;
            $db->manu_year = $manu_year;
            $db->make_model = $make_model;
            $db->equip_type = $equip_type;

            $db->hydrant_filter_sump = $hydrant_filter_sump;
            $db->tanker_filter_sump = $tanker_filter_sump;
            $db->eye_wash_inspection = $eye_wash_inspection;
            $db->visi_jar_cleaning = $visi_jar_cleaning;
            $db->filter_membrane_test = $filter_membrane_test;
            $db->fuel_equipment_weekly = $fuel_equipment_weekly;
            $db->fuel_equipment_monthly = $fuel_equipment_monthly;
            $db->fuel_equipment_quarterly = $fuel_equipment_quarterly;

            /**
             * File uploads code
             * Begin
             */
            $images = null;
            if(count($request->get('images',[])) > 0) $images = json_encode($request->get('images',[]));
            /**
             * End
             */
            $db->images = $images;

            $db->save();

            DB::commit();
            return Redirect::route('settings.fuel')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.fuel')->with('error', "Failed Adding");
        }
    }

    public function fuel_delete(Request $request)
    {
        $id = $request->get('id');

        if(DB::table('fuel_equipment')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('settings.fuel')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.fuel')->with('error', 'Failed Deleting!');

    }

    public function fuel_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $pid = $request->get('pid');
        $unit = $request->get('unit');
        $unit_type = $request->get('unit_type');

        $model_type = $request->get('model_type');
        $serial_number = $request->get('serial_number');
        $qty_installed = $request->get('qty_installed');
        $max_flow_rate = $request->get('max_flow_rate');
        $max_dp = $request->get('max_dp');
        $last_inspected= $request->get('last_inspected');
        $fire_ext_id= $request->get('fire_ext_id');
        $size= $request->get('size');

        $vin_number = $request->get('vin_number');
        $manu_year = $request->get('manu_year');
        $make_model = $request->get('make_model');
        $equip_type= $request->get('equip_type');

        $hydrant_filter_sump = $request->get('hydrant_filter_sump')=='on'?1:0;
        $tanker_filter_sump = $request->get('tanker_filter_sump')=='on'?1:0;
        $eye_wash_inspection = $request->get('eye_wash_inspection')=='on'?1:0;
        $visi_jar_cleaning = $request->get('visi_jar_cleaning')=='on'?1:0;
        $filter_membrane_test = $request->get('filter_membrane_test')=='on'?1:0;
        $fuel_equipment_weekly = $request->get('fuel_equipment_weekly')=='on'?1:0;
        $fuel_equipment_monthly = $request->get('fuel_equipment_monthly')=='on'?1:0;
        $fuel_equipment_quarterly = $request->get('fuel_equipment_quarterly')=='on'?1:0;

        $old_images = $request->get('old_images');
        try {
            DB::beginTransaction();

            /**
             * File uploads code
             * Begin
             */
            $images = null;
            if(count($request->get('images',[])) > 0){
                $images = $request->get('images',[]);
                if(count($images) > 4){
                    return Redirect::route('settings.fuel.edit',$id)->withInput($request->Input())->with('warning', "The images for uploading should be less than 4");
                }
                $images = json_encode($images);
            }
            /**
             * End
             */

            DB::table('fuel_equipment')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'plocation_id' => $pid,
                'unit' => $unit,
                'unit_type' => $unit_type,

                'model_type' => $model_type,
                'serial_number' => $serial_number,
                'qty_installed' => $qty_installed,
                'max_flow_rate' => $max_flow_rate,
                'max_dp' => $max_dp,
                'last_inspected' => $last_inspected,
                'fire_ext_id' => $fire_ext_id,

                'size' => $size,
                'vin_number' => $vin_number,
                'manu_year' => $manu_year,
                'make_model' => $make_model,
                'equip_type' => $equip_type,
                'images' => $images,

                'hydrant_filter_sump' => $hydrant_filter_sump,
                'tanker_filter_sump' => $tanker_filter_sump,
                'eye_wash_inspection' => $eye_wash_inspection,
                'visi_jar_cleaning' => $visi_jar_cleaning,
                'filter_membrane_test' => $filter_membrane_test,
                'fuel_equipment_weekly' => $fuel_equipment_weekly,
                'fuel_equipment_monthly' => $fuel_equipment_monthly,
                'fuel_equipment_quarterly' => $fuel_equipment_quarterly,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.fuel')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.fuel')->with('error', "Failed Updating");
        }
    }
    ////////////////////////////////////////////////

    /**
     * index, add, save, delete, update
     */

    public function location_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $location = DB::table('primary_location')->get();
            DB::commit();
            return view('settings.location.index',compact('location'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function location_add(Request $request)
    {
        return View('settings.location.add');
    }

    public function location_edit($id)
    {
        try {
            DB::beginTransaction();
            $location = DB::table('primary_location')->where('id',$id)->first();
            DB::commit();

            return view('settings.location.edit',compact('location'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function location_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $location = $request->get('location');
        $location_latitude = $request->get('location_latitude');
        $location_longitude = $request->get('location_longitude');
        $location_color = $request->get('location_color');
        $location_address = $request->get('location_address');

        try {
            DB::beginTransaction();

            $db = new PrimaryLocation();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->location = $location;
            $db->location_latitude = $location_latitude;
            $db->location_longitude = $location_longitude;
            $db->location_color = $location_color;
            $db->location_address = $location_address;

            $db->save();

            DB::commit();
            return Redirect::route('settings.location')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.location')->with('error', "Failed Adding");
        }
    }

    public function location_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('primary_location')->where('id',$id)->delete())
            return Redirect::route('settings.location')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.location')->with('error', 'Failed Deleting!');

    }

    public function location_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $location = $request->get('location');
        $location_latitude = $request->get('location_latitude');
        $location_longitude = $request->get('location_longitude');
        $location_color = $request->get('location_color');
        $location_address = $request->get('location_address');

        try {
            DB::beginTransaction();

            DB::table('primary_location')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'location' => $location,
                'location_color' => $location_color,
                'location_address' => $location_address,
                'location_latitude' => $location_latitude,
                'location_longitude' => $location_longitude,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.location')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.location')->with('error', "Failed Updating");
        }
    }

    ////////////////////////////////////////////////

    /**
     * index, add, save, delete, update
     */

    public function grading_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $grading = DB::table('grading_result')->orderBy('value','asc')->get();
            DB::commit();
            return view('settings.grading.index',compact('grading'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function grading_add(Request $request)
    {
        $colors = DB::table('colors')->get();
        return View('settings.grading.add',compact('colors'));
    }

    public function grading_edit($id)
    {
        try {
            DB::beginTransaction();
            $grading = DB::table('grading_result')->where('id',$id)->first();
            $colors = DB::table('colors')->get();
            DB::commit();

            return view('settings.grading.edit',compact('grading','colors'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function grading_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $grade = $request->get('grade');
        $result = $request->get('result');
        $grading_type = $request->get('grading_type');
        $color = $request->get('color');
        $is_comments = $request->get('is_comments')=='on'?1:0;

        $next_value = $grading_type.'_1';
        if($grading_result = DB::table('grading_result')
            ->where('grading_type',$grading_type)
            ->where('status','<',2)->orderBy('value','desc')->first()){
            if($grading_result->value && is_array(explode('_', $grading_result->value)))
                $next_value = $grading_type.'_'.(explode('_',$grading_result->value)[1]+1);
        }


        try {
            DB::beginTransaction();

            $db = new GradingResult();
            $db->user_id = $user_id;
            $db->user_name = $user_name;

            $db->grade = $grade;
            $db->result = $result;
            $db->grading_type = $grading_type;
            $db->color = $color;
            $db->status = $is_comments;
            $db->value = $next_value;

            $db->save();

            DB::commit();
            return Redirect::route('settings.grading')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.grading')->with('error', "Failed Adding");
        }
    }

    public function grading_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('grading_result')->where('id',$id)->delete())
            return Redirect::route('settings.grading')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.grading')->with('error', 'Failed Deleting!');

    }

    public function grading_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $grade = $request->get('grade');
        $result = $request->get('result');
        $grading_type = $request->get('grading_type');
        $color = $request->get('color');
        $is_comments = $request->get('is_comments')=='on'?1:0;

        $now_grading = DB::table('grading_result')->where('id',$id)->first();
        $next_value = $grading_type.'_1';
        if($now_grading->value == null || $now_grading->value == ''){
            if($grading_result = DB::table('grading_result')
                ->where('grading_type',$grading_type)
                ->where('status','<',2)->orderBy('value','desc')->first()){
                if($grading_result->value != null && $grading_result->value != ''){
                    $next_value = $grading_type.'_'.(explode('_',$grading_result->value)[1]+1);
                }
            }
        }else{
            $next_value = $now_grading->value;
            if($now_grading->grading_type !=  $grading_type){
                $next_value = $grading_type.'_'.(explode('_',$now_grading->value)[1]+1);
            }
        }


        try {
            DB::beginTransaction();

            DB::table('grading_result')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'grade' => $grade,
                'result' => $result,
                'grading_type' => $grading_type,
                'color' => $color,
                'value' => $request->get('value'),
                'status' => $is_comments,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.grading')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.grading')->with('error', "Failed Updating");
        }
    }
    ////////////////////////////////////////////////
    ///
    ///
    /**
     * index, add, save, delete, update
     */

    public function vessel_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $vessel = DB::table('vessel as v')
                ->leftjoin('primary_location as pl','pl.id','=','v.plocation_id')
                ->select('v.*','pl.location as pl_location')
                ->orderBy('v.vessel','asc')
                ->get();
            DB::commit();
            return view('settings.vessel.index',compact('vessel'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function vessel_add(Request $request)
    {
        $locations = DB::table('primary_location')->get();
        return View('settings.vessel.add',compact('locations'));
    }

    public function vessel_edit($id)
    {
        try {
            DB::beginTransaction();
            $locations = DB::table('primary_location')->get();
            $vessel = DB::table('vessel')->where('id',$id)->first();
            DB::commit();

            return view('settings.vessel.edit',compact('vessel','locations'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function vessel_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $vessel = $request->get('vessel');
        $plocation_id = $request->get('plocation_id');
        $location = $request->get('location');
        $location_code = $request->get('location_code');
        $location_latitude = $request->get('location_latitude');
        $location_longitude = $request->get('location_longitude');
        $vessel_rate = $request->get('vessel_rate');
        $filter_type = $request->get('filter_type');
        $filter_serial = $request->get('filter_serial');
        $qty = $request->get('qty');
        $last_inspected = $request->get('last_inspected');
        $water_defense = $request->get('water_defense')=='on'?1:0;
        $vessel_filter = $request->get('vessel_filter')=='on'?1:0;
        $bonding_cable = $request->get('bonding_cable')=='on'?1:0;
        $differential_pressure = $request->get('differential_pressure')=='on'?1:0;
        $filter_membrane = $request->get('filter_membrane')=='on'?1:0;
        $deadman_control = $request->get('deadman_control')=='on'?1:0;
        $hoses_pumps_screens = $request->get('hoses_pumps_screens')=='on'?1:0;
        $bol = $request->get('bol')=='on'?1:0;
        $bol_pipeline = $request->get('bol_pipeline')=='on'?1:0;
        $eye_wash_inspection = $request->get('eye_wash_inspection')=='on'?1:0;
        $bulk_sump = $request->get('bulk_sump')=='on'?1:0;

        try {
            DB::beginTransaction();

            $db = new Vessel();
            $db->user_id = $user_id;
            $db->user_name = $user_name;

            $db->vessel = $vessel;
            $db->plocation_id = $plocation_id;
            $db->location_name = $location;
            $db->location_code = $location_code;
            $db->location_latitude = $location_latitude;
            $db->location_longitude = $location_longitude;

            $db->vessel_rate = $vessel_rate;
            $db->filter_type = $filter_type;
            $db->filter_serial = $filter_serial;
            $db->qty = $qty;
            $db->last_inspected = $last_inspected;

            $db->water_defense = $water_defense;
            $db->vessel_filter = $vessel_filter;
            $db->bonding_cable = $bonding_cable;
            $db->differential_pressure = $differential_pressure;
            $db->filter_membrane = $filter_membrane;
            $db->deadman_control = $deadman_control;
            $db->hoses_pumps_screens = $hoses_pumps_screens;
            $db->bol = $bol;
            $db->bol_pipeline = $bol_pipeline;
            $db->eye_wash_inspection = $eye_wash_inspection;
            $db->bulk_sump = $bulk_sump;
            /**
             * File uploads code
             * Begin
             */
            $images = null;
            if(count($request->get('images',[])) > 0) $images = json_encode($request->get('images',[]));
            /**
             * End
             */
            $db->images = $images;

            $db->save();

            DB::commit();
            return Redirect::route('settings.vessel')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.vessel')->with('error', "Failed Adding");
        }
    }

    public function vessel_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('vessel')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('settings.vessel')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.vessel')->with('error', 'Failed Deleting!');

    }

    public function vessel_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $vessel = $request->get('vessel');
        $plocation_id = $request->get('plocation_id');
        $location = $request->get('location');
        $location_code = $request->get('location_code');
        $location_latitude = $request->get('location_latitude');
        $location_longitude = $request->get('location_longitude');
        $last_inspected = $request->get('last_inspected');
        $vessel_rate = $request->get('vessel_rate');
        $filter_type = $request->get('filter_type');
        $filter_serial = $request->get('filter_serial');
        $qty = $request->get('qty');

        $water_defense = $request->get('water_defense')=='on'?1:0;
        $vessel_filter = $request->get('vessel_filter')=='on'?1:0;
        $bonding_cable = $request->get('bonding_cable')=='on'?1:0;
        $differential_pressure = $request->get('differential_pressure')=='on'?1:0;
        $filter_membrane = $request->get('filter_membrane')=='on'?1:0;
        $deadman_control = $request->get('deadman_control')=='on'?1:0;
        $hoses_pumps_screens = $request->get('hoses_pumps_screens')=='on'?1:0;
        $bol = $request->get('bol')=='on'?1:0;
        $bol_pipeline = $request->get('bol_pipeline')=='on'?1:0;
        $eye_wash_inspection = $request->get('eye_wash_inspection')=='on'?1:0;
        $bulk_sump = $request->get('bulk_sump')=='on'?1:0;
        try {
            DB::beginTransaction();

            /**
             * File uploads code
             * Begin
             */
            $images = null;
            if(count($request->get('images',[])) > 0){
                $images = $request->get('images',[]);
                if(count($images) > 4){
                    return Redirect::route('settings.vessel.edit',$id)->withInput($request->Input())->with('warning', "The images for uploading should be less than 4");
                }
                $images = json_encode($images);
            }
            /**
             * End
             */

            DB::table('vessel')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,

                'vessel' => $vessel,
                'plocation_id' => $plocation_id,
                'location_name' => $location,
                'location_code' => $location_code,
                'location_latitude' => $location_latitude,
                'location_longitude' => $location_longitude,

                'last_inspected' => $last_inspected,
                'vessel_rate' => $vessel_rate,
                'filter_type' => $filter_type,
                'filter_serial' => $filter_serial,
                'qty' => $qty,
                'images' => $images,

                'water_defense' => $water_defense,
                'vessel_filter' => $vessel_filter,
                'bonding_cable' => $bonding_cable,
                'differential_pressure' => $differential_pressure,
                'filter_membrane' => $filter_membrane,
                'deadman_control' => $deadman_control,
                'hoses_pumps_screens' => $hoses_pumps_screens,
                'bol' => $bol,
                'bol_pipeline' => $bol_pipeline,
                'eye_wash_inspection' => $eye_wash_inspection,
                'bulk_sump' => $bulk_sump,

                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.vessel')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.vessel')->with('error', "Failed Updating");
        }
    }
    ////////////////////////////////////////////////

    /**
     * index, add, save, delete, update
     */

    public function assign_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $loc_name = '';
            if (Utils::name('intoplane')) $loc_name = 'intoplane';
            if (Utils::name('tankfarm1')) $loc_name = 'tankfarm1';
            if (Utils::name('tankfarm2')) $loc_name = 'tankfarm2';

            $assign = DB::table('assign_inspection as a')
                ->leftJoin('inspections as i','i.id','=','a.inspections')
                ->leftJoin('users as u','u.id','=','a.staff')
                ->leftJoin('primary_location as pl','pl.id','=','a.plocation_id')
                ->select('a.id','a.date','a.time','a.month','a.status','a.created_at',
                    'i.name as i_name','u.name as u_name', 'i.p_name','pl.location as pl_location','a.plocation_id')
                ->where('a.plocation_id',Session::get('p_loc'))
                ->where(function ($query) use ($loc_name) {
                    $query->where('i.location', '=', $loc_name)
                        ->OrWhere('i.location2', '=', $loc_name)
                        ->OrWhere('i.location3', '=', $loc_name);
                    return $query;
                })
                ->where(function($q){
                    $q->where('a.status',0)
                        ->orwhere('a.status',3);
                })
                ->get();


            $all_assign = DB::table('assign_inspection as a')
                ->leftJoin('inspections as i','i.id','=','a.inspections')
                ->leftJoin('users as u','u.id','=','a.staff')
                ->leftJoin('primary_location as pl','pl.id','=','a.plocation_id')
                ->select('a.id','a.date','a.time','a.month','a.status','a.created_at',
                    'i.name as i_name','u.name as u_name', 'i.p_name','pl.location as pl_location')
                ->where('a.status',0)
                ->where('a.plocation_id',Session::get('p_loc'))
                ->get();

            $approve = true;
            if (count($all_assign) > 0)
                $approve = false;

            DB::commit();
            return view('assign.index',compact('assign','approve'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return back()->with('error', "Loading Failed!");
        }
    }

    public function assign_check(Request $request){

        try {
            $user_id = '';
            $user_name = '';
            if(Sentinel::check()) {
                $user_id = Sentinel::getUser()->id;
                $user_name = Sentinel::getUser()->name;
            }
            DB::beginTransaction();
            $id = $request->get('id');
            if($id==''){
                DB::table('assign_inspection')->where('status',3)
                    ->where('plocation_id',Session::get('p_loc'))
                    ->update(['status' => 1,'ck_uid'=>$user_id,'ck_name'=>$user_name,'checked_at'=>Date('Y-m-d H:i:s')]);

            }elseif($id=='assign'){

                DB::table('assign_inspection')->where('status',0)
                    ->where('plocation_id',Session::get('p_loc'))
                    ->update(['status' => 3]);
            }else{
                DB::table('assign_inspection')->where('id',$id)->update(['status' => 1,'ck_uid'=>$user_id,'ck_name'=>$user_name,'checked_at'=>Date('Y-m-d H:i:s')]);
            }
            DB::commit();
            return Redirect::route('settings.assign')->with('success','Checked successfully');
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function assign_add(Request $request)
    {
        try {

            $loc_name = '';
            if (Utils::name('intoplane')) $loc_name = 'intoplane';
            if (Utils::name('tankfarm1')) $loc_name = 'tankfarm1';
            if (Utils::name('tankfarm2')) $loc_name = 'tankfarm2';

            $inspections = DB::table('inspections')
                ->where(function ($query) use ($loc_name) {
                    $query->where('location', '=', $loc_name)
                        ->OrWhere('location2', '=', $loc_name)
                        ->OrWhere('location3', '=', $loc_name);
                    return $query;
                })
                ->where('period', '!=', 'daily')
                ->orderBy('period','ASC')
                ->get();

            $users = DB::table('users as u')
                ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->join('activations as a', 'a.user_id', '=', 'u.id')
                ->join('user_locations as ul','ul.user_id','=','u.id')
                ->select('u.*', 'r.slug', 'r.id as role_id','ul.location_ids')
                ->where('r.slug', 'staff')
                ->where('a.completed', 1)
                ->get();
            $loc_users = array();
            foreach ($users as $user){
                if (in_array(Session::get('p_loc'), json_decode($user->location_ids))){
                    array_push($loc_users, $user);
                }
            }

            $users = $loc_users;

            $date = $request->get('date', date('Y-m-d'));

            $assign_data = DB::table('assign_inspection as a')
                ->leftJoin('inspections as i', 'i.id', '=', 'a.inspections')
                ->whereYear('a.month', '>=', date('Y', strtotime($date)))
                ->whereMonth('a.month', '>=', date('m', strtotime($date)))
                ->where('a.plocation_id', Session::get('p_loc'))
                ->where('a.status', '!=', 2)
                ->where('a.status', '!=', 1)
                ->select('a.id', 'a.inspections')->get();

            $c_ins = array();
            $used = array();
            foreach ($inspections as $o) {
                array_push($c_ins, $o);
                foreach ($assign_data as $c) {
                    if ($c->inspections == $o->id) array_push($used, $o);
                }
            }
            foreach ($used as $u) {
                if (($key = array_search($u, $c_ins)) !== FALSE) {
                    unset($c_ins[$key]);
                }
            }
            $c_ins = array_values($c_ins);

            if (count($c_ins) < 1)
                return back()->with('warning', "Sorry, You cannot add any more new inspection for today. if you think this is an error, please contact administrator");

            if (count($users) < 1)
                return back()->with('warning', "Sorry, Current primary location has no staffs. if you think this is an error, please contact administrator");


            $locations = DB::table('primary_location')->get();
            $inspections = $c_ins;
            return View('assign.add', compact('inspections', 'users','locations'));
        }catch (\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Loading Failed!");
        }
    }

    public function assign_edit(Request $request,$id)
    {
        try {

            if(!$assign = DB::table('assign_inspection')->where('id',$id)->first())
                return back()->with('error', "Loading Failed!");

            $loc_name = '';
            if (Utils::name('intoplane')) $loc_name = 'intoplane';
            if (Utils::name('tankfarm1')) $loc_name = 'tankfarm1';
            if (Utils::name('tankfarm2')) $loc_name = 'tankfarm2';

            $inspections = DB::table('inspections')
                ->where(function ($query) use ($loc_name) {
                    $query->where('location', '=', $loc_name)
                        ->OrWhere('location2', '=', $loc_name)
                        ->OrWhere('location3', '=', $loc_name);
                    return $query;
                })
                ->where('period', '!=', 'daily')
                ->orderBy('period','ASC')
                ->get();

            $users = DB::table('users as u')
                ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->join('activations as a', 'a.user_id', '=', 'u.id')
                ->join('user_locations as ul','ul.user_id','=','u.id')
                ->select('u.*', 'r.slug', 'r.id as role_id','ul.location_ids')
                ->where('r.slug', 'staff')
                ->where('a.completed', 1)
                ->get();

            $loc_users = array();
            foreach ($users as $user){
                if (in_array(Session::get('p_loc'), json_decode($user->location_ids))){
                    array_push($loc_users, $user);
                }
            }

            $users = $loc_users;


            $date = $request->get('date',date('Y-m-d'));

            $assign_data = DB::table('assign_inspection as a')
                ->leftJoin('inspections as i','i.id','=','a.inspections')
                ->whereYear('a.month','>=',date('Y', strtotime($date)))
                ->whereMonth('a.month','>=',date('m',strtotime($date)))
                ->where('a.plocation_id', Session::get('p_loc'))
                ->where('a.status','<',2)
                ->select('a.id','a.inspections')->get();

            $c_ins = array();
            $used = array();
            foreach ($inspections as $o){
                array_push($c_ins,$o);
                foreach ($assign_data as $c){
                    if($c->inspections == $o->id && $id != $c->id) array_push($used,$o);
                }
            }
            foreach ($used as $u){
                if(($key = array_search($u, $c_ins)) !== FALSE) {
                    unset($c_ins[$key]);
                }
            }
            $c_ins = array_values($c_ins);

            if(count($c_ins) < 1)
                return back()->with('warning', "Sorry you cannot add any more new reports for today. if you think this is an error, please contact administrator");

            if (count($users) < 1)
                return back()->with('warning', "Sorry, Current primary location has no staffs. if you think this is an error, please contact administrator");

            $locations = DB::table('primary_location')->get();
            $inspections = $c_ins;

            return view('assign.edit',compact('assign','inspections','users','locations'));

        }catch(\Exception $e){
            Log::info($e->getMessage());

            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function assign_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $date = $request->get('date');
        $time = $request->get('time');
        $plocation_id = Session::get('p_loc');//$request->get('plocation_id');
        $inspection = $request->get('inspection');
        $staff = $request->get('user_id');
        $month = $request->get('month');

        try {
            DB::beginTransaction();

            $db = new AssignInspection();
            $db->user_id = $user_id;
            $db->user_name = $user_name;

            $db->date = $date;
            $db->time = $time;
            $db->plocation_id = $plocation_id;
            $db->inspections = $inspection;
            $db->staff = $staff;
            $db->month = date('Y-m-d',strtotime($month));

            $db->save();

            DB::commit();
            return Redirect::route('settings.assign')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.assign')->with('error', "Failed Adding");
        }
    }

    public function assign_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('assign_inspection')->where('id',$id)->delete())
            return Redirect::route('settings.assign')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.assign')->with('error', 'Failed Deleting!');

    }

    public function assign_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $date = $request->get('date');
        $time = $request->get('time');
        $inspection = $request->get('inspection');
        $staff = $request->get('user_id');
        $month = $request->get('month');
        $plocation_id = Session::get('p_loc');//$request->get('plocation_id');
        try {
            DB::beginTransaction();

            DB::table('assign_inspection')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'date' => $date,
                'time' => $time,
                'plocation_id' => $plocation_id,
                'inspections' => $inspection,
                'month' => date('Y-m-d',strtotime($month)),
                'staff' => $staff,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.assign')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.assign')->with('error', "Failed Updating");
        }
    }


    /**
     *
     */

    /**
     * index, add, save, delete, update
     */

    public function inspect_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $inspections = DB::table('inspections')->get();
            DB::commit();
            return view('settings.inspect.index',compact('inspections'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function inspect_add(Request $request)
    {
        return View('settings.inspect.add');
    }

    public function inspect_edit($id)
    {
        try {
            DB::beginTransaction();
            $inspection = DB::table('inspections')->where('id',$id)->first();
            DB::commit();

            return view('settings.inspect.edit',compact('inspection'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function inspect_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $name = $request->get('name');
        $period = $request->get('period');
        $p_name = '';
        if($period == 'daily') $p_name = 'Daily Inspection';
        if($period == 'monthly') $p_name = 'Monthly Inspection';
        if($period == 'weekly') $p_name = 'Weekly Inspection';
        if($period == 'quarterly') $p_name = 'Quarterly Inspection';
        if($period == 'annual') $p_name = 'Annual Inspection';

        $intoplane = $request->get('intoplane')=='on'?'intoplane':'';
        $tankfarm1 = $request->get('tankfarm1')=='on'?'tankfarm1':'';
        $tankfarm2 = $request->get('tankfarm2')=='on'?'tankfarm2':'';

        try {
            DB::beginTransaction();

            $db = new Inspections();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->name = $name;
            $db->period = $period;
            $db->p_name = $p_name;
            $db->location = $intoplane;
            $db->location2 = $tankfarm1;
            $db->location3 = $tankfarm2;

            $db->save();

            DB::commit();
            return Redirect::route('settings.inspect')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.inspect')->with('error', "Failed Adding");
        }
    }

    public function inspect_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('inspections')->where('id',$id)->delete())
            return Redirect::route('settings.inspect')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.inspect')->with('error', 'Failed Deleting!');

    }

    public function inspect_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $name = $request->get('name');
        $period = $request->get('period');
        $p_name = '';
        if($period == 'daily') $p_name = 'Daily Inspection';
        if($period == 'monthly') $p_name = 'Monthly Inspection';
        if($period == 'weekly') $p_name = 'Weekly Inspection';
        if($period == 'quarterly') $p_name = 'Quarterly Inspection';
        if($period == 'annual') $p_name = 'Annual Inspection';

        $intoplane = $request->get('intoplane')=='on'?'intoplane':'';
        $tankfarm1 = $request->get('tankfarm1')=='on'?'tankfarm1':'';
        $tankfarm2 = $request->get('tankfarm2')=='on'?'tankfarm2':'';

        try {
            DB::beginTransaction();

            DB::table('inspections')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'name' => $name,
                'period' => $period,
                'p_name' => $p_name,
                'location' => $intoplane,
                'location2' => $tankfarm1,
                'location3' => $tankfarm2,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.inspect')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.inspect')->with('error', "Failed Updating");
        }
    }
    ////////////////////////////////////////////////
    /**
     * index, add, save, delete, update
     */

    public function operator_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $operator = DB::table('operators')
                ->where('plocation_id',Session::get('p_loc'))
                ->where('status','<',2)->orderBy('operator','ASC')->get();
            DB::commit();
            return view('settings.operator.index',compact('operator'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function operator_add(Request $request)
    {
        return View('settings.operator.add');
    }

    public function operator_edit($id)
    {
        try {
            DB::beginTransaction();
            $operator = DB::table('operators')->where('id',$id)->first();
            DB::commit();

            return view('settings.operator.edit',compact('operator'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function operator_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }
        $operator = $request->get('operator');
        try {
            DB::beginTransaction();

            $db = new Operators();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->plocation_id = Session::get('p_loc');
            $db->operator = $operator;
            $db->save();

            DB::commit();
            return Redirect::route('settings.operator')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.operator')->with('error', "Failed Adding");
        }
    }

    public function operator_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('operators')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('settings.operator')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.operator')->with('error', 'Failed Deleting!');

    }

    public function operator_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $operator = $request->get('operator');

        try {
            DB::beginTransaction();

            DB::table('operators')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'operator' => $operator,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.operator')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.operator')->with('error', "Failed Updating");
        }
    }


    ////////////////////////////////////////////////
    /**
     * index, add, save, delete, update
     */

    public function inspect_task_index($mode)
    {
        try {
            DB::beginTransaction();
            $inspect_task = DB::table('settings_inspect_list')
                ->where('plocation_id',Session::get('p_loc'))
                ->where('status','<',2)
//                ->orderBy('task','ASC')
                ->get();

            $fuel = DB::table('fuel_equipment')
                ->where('status','<',2)
                ->where('plocation_id',Session::get('p_loc'))
                ->select('id','unit','unit_type')
                ->orderBy('unit','ASC')
                ->get();
            foreach ($fuel as $item){
                $item->unit_type = Utils::unit_type($item->unit_type);
            }

            $refuelled = DB::table('settings_refuelled')
                ->where('plocation_id',Session::get('p_loc'))
                ->where('status','<',2)->orderBy('refuelled','ASC')->get();

            DB::commit();
            return view('settings.inspect_task.index',compact('inspect_task','fuel','mode','refuelled'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return back()->with('error', "Loading Failed!");
        }
    }

    public function inspect_task_add(Request $request)
    {
        return View('settings.inspect_task.add');
    }

    public function inspect_task_edit($id)
    {
        try {
            DB::beginTransaction();
            $inspect_task = DB::table('settings_inspect_list')->where('id',$id)->first();
            DB::commit();

            return view('settings.inspect_task.edit',compact('inspect_task'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function inspect_task_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }
        $inspect_task = $request->get('inspect_task');
        $description = $request->get('description');

        try {
            DB::beginTransaction();

            $db = new SettingsInspectTask();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->plocation_id = Session::get('p_loc');

            $db->task = $inspect_task;
            $db->description = $description;
            $db->update_detail = 'Last updated by '.$user_name.' on '.date('Y-m-d').' at '.date('H:i');

            /**
             * File uploads code
             * Begin
             */
            $images = null;
            if($file_temp = $request->file('images')){
                $destinationPath = public_path() . '/uploads/settings';
                $extension   = $file_temp->getClientOriginalExtension() ?: 'png';
                $images =  Str::random(10).'.'.$extension;
                $file_temp->move($destinationPath, $images);
            }
            /**
             * End
             */
            $db->images = $images;

            $db->save();

            DB::commit();
            return Redirect::route('settings.inspect_task','d')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.inspect_task','d')->with('error', "Failed Adding");
        }
    }

    public function inspect_task_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('settings_inspect_list')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('settings.inspect_task','d')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.inspect_task','d')->with('error', 'Failed Deleting!');

    }

    public function inspect_task_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $inspect_task = $request->get('inspect_task');
        $description = $request->get('description');

        $old_image = $request->get('old_images');
        try {
            DB::beginTransaction();

            /**
             * File uploads code
             * Begin
             */
            $images = $old_image;
            if($file_temp = $request->file('images')){
                $destinationPath = public_path() . '/uploads/settings';
                $extension   = $file_temp->getClientOriginalExtension() ?: 'png';
                $images =  Str::random(10).'.'.$extension;
                $file_temp->move($destinationPath, $images);
            }
            /**
             * End
             */

            DB::table('settings_inspect_list')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'task' => $inspect_task,
                'description' => $description,
                'images' => $images,
                'update_detail' => 'Last updated by '.$user_name.' on '.date('Y-m-d').' at '.date('H:i'),
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.inspect_task','d')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.inspect_task','d')->with('error', "Failed Updating");
        }
    }

    public function inspect_preset_edit($id)
    {
        try {

            $grading_condition = DB::table('grading_result')
                ->where('grading_type','condition')
                ->select('id','grade','result','color')->get();

            $inspect_list = DB::table('settings_inspect_list')
                ->where('status','<',2)
                ->where('plocation_id',Session::get('p_loc'))
                ->select('*')
                ->get();

            $inspect_preset = array();
            foreach ($inspect_list as $item){
                $obj = new \stdClass();
                $obj->sl_id = $item->id;
                $obj->task = $item->task;
                $obj->description = $item->description;
                $obj->enable = 1;
                $obj->update_detail = NULL;

                if($preset = DB::table('settings_inspect_preset as sp')
                    ->leftJoin('settings_inspect_list as sl','sl.id','=','sp.inspect_task')
                    ->leftJoin('grading_result as gr','gr.id','=','sp.condition')
                    ->where('sp.unit',$id)
                    ->where('sp.inspect_task',$item->id)
                    ->select('sl.id as sl_id','sl.task','sp.enable','sp.condition','sp.update_detail')
                    ->first()){
                    $obj->enable = $preset->enable;
                    $obj->update_detail = $preset->update_detail;
                }

                array_push($inspect_preset, $obj);
            }

            $unit = DB::table('fuel_equipment')->where('id',$id)->value('unit');
            return view('settings.inspect_preset.edit',compact('inspect_preset','grading_condition','id','unit'));

        }catch(\Exception $e){
            Log::info($e->getMessage());
            return back()->with('error', "Loading Failed!");
        }
    }

    public function inspect_preset_update(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $id = $request->get('id');

        try {
            DB::beginTransaction();

            $inspect_list = DB::table('settings_inspect_list')->where('status','<',2)->where('plocation_id', Session::get('p_loc'))->get();
            foreach ($inspect_list as $item){
                if($preset = DB::table('settings_inspect_preset')
                    ->where('unit',$id)
                    ->where('inspect_task',$item->id)->first())
                {
                    DB::table('settings_inspect_preset')->where('unit',$id)
                        ->where('inspect_task',$item->id)->update
                        ([
                            'enable'=>$request->get('enable_'.$item->id)=='on'?1:0,
                            'update_detail'=> 'Last updated by '.$user_name.' on '.date('Y-m-d').' at '.date('H:i')
                        ]);
                }else{
                    $db = new SettingsInspectPreset();
                    $db->unit = $id;
                    $db->inspect_task = $item->id;
                    $db->enable = $request->get('enable_'.$item->id)=='on'?1:0;
                    $db->update_detail = 'Last updated by '.$user_name.' on '.date('Y-m-d').' at '.date('H:i');
                    $db->save();
                }
            }

            DB::commit();
            return Redirect::route('settings.inspect_task','s')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.inspect_task','s')->with('error', "Failed Adding");
        }
    }

    ////////////////////////////////////////////////

    public function refuelled_add(Request $request)
    {
        return View('settings.refuelled.add');
    }

    public function refuelled_edit($id)
    {
        try {
            DB::beginTransaction();
            $refuelled = DB::table('settings_refuelled')->where('id',$id)->first();
            DB::commit();

            return view('settings.refuelled.edit',compact('refuelled'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function refuelled_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }
        $refuelled = $request->get('refuelled');
        $icao = $request->get('icao');
        try {
            DB::beginTransaction();

            $db = new SettingsRefuelled();
            $db->user_id = $user_id;
            $db->user_name = $user_name;
            $db->plocation_id = Session::get('p_loc');
            $db->refuelled = $refuelled;
            $db->icao = $icao;
            $db->save();

            DB::commit();
            return Redirect::route('settings.inspect_task','r')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.inspect_task','r')->with('error', "Failed Adding");
        }
    }

    public function refuelled_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('settings_refuelled')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('settings.inspect_task','r')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.inspect_task','r')->with('error', 'Failed Deleting!');

    }

    public function refuelled_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $refuelled = $request->get('refuelled');
        $icao = $request->get('icao');
        try {
            DB::beginTransaction();

            DB::table('settings_refuelled')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'refuelled' => $refuelled,
                'icao' => $icao,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.inspect_task','r')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.inspect_task','r')->with('error', "Failed Updating");
        }
    }

    ////////////////////////////////////////////////
    /**
     * index, add, save, delete, update
     */

    public function cathodic_index(Request $request)
    {
        try {
            DB::beginTransaction();
            $cathodic = DB::table('settings_cathodic as v')
                ->leftjoin('primary_location as pl','pl.id','=','v.plocation_id')
                ->select('v.*','pl.location')
                ->get();
            DB::commit();
            return view('settings.cathodic.index',compact('cathodic'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    public function cathodic_add(Request $request)
    {
        $locations = DB::table('primary_location')->get();
        return View('settings.cathodic.add',compact('locations'));
    }

    public function cathodic_edit($id)
    {
        try {
            DB::beginTransaction();
            $locations = DB::table('primary_location')->get();
            $cathodic = DB::table('settings_cathodic')->where('id',$id)->first();
            DB::commit();

            return view('settings.cathodic.edit',compact('cathodic','locations'));
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }
    /**
     *
     */
    public function cathodic_save(Request $request)
    {
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $plocation_id = $request->get('plocation_id');
        $location = $request->get('location_name');
        $location_code = $request->get('location_code');
        $location_latitude = $request->get('location_latitude');
        $location_longitude = $request->get('location_longitude');
        $volts = $request->get('volts')=='on'?1:0;
        $amps = $request->get('amps')=='on'?1:0;
        $volt_resister = $request->get('volt_resister')=='on'?1:0;

        try {
            DB::beginTransaction();

            $db = new SettingsCathodic();
            $db->user_id = $user_id;
            $db->user_name = $user_name;

            $db->plocation_id = $plocation_id;
            $db->location_name = $location;
            $db->location_code = $location_code;
            $db->location_latitude = $location_latitude;
            $db->location_longitude = $location_longitude;
            $db->volts = $volts;
            $db->amps = $amps;
            $db->volt_resister = $volt_resister;

            $attach_image = null;
            if($file_temp = $request->file('attach_image')){
                $destinationPath = public_path() . '/uploads/settings/cathodic';
                $attach_image =  Str::random(10).'.'.$file_temp->getClientOriginalExtension();
                $file_temp->move($destinationPath, $attach_image);
            }

            $db->images = $attach_image;
            $db->save();

            DB::commit();
            return Redirect::route('settings.cathodic')->with('success', "Successful Added!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.cathodic')->with('error', "Failed Adding");
        }
    }

    public function cathodic_delete(Request $request)
    {
        $id = $request->get('id');
        if(DB::table('settings_cathodic')->where('id',$id)->update(['status'=>2]))
            return Redirect::route('settings.cathodic')->with('success', 'Successful Deleted!');
        else
            return Redirect::route('settings.cathodic')->with('error', 'Failed Deleting!');

    }

    public function cathodic_update(Request $request)
    {
        $id = $request->get('id');
        $user_id = '';
        $user_name = '';
        if(Sentinel::check()) {
            $user_id = Sentinel::getUser()->id;
            $user_name = Sentinel::getUser()->name;
        }

        $plocation_id = $request->get('plocation_id');
        $location = $request->get('location_name');
        $location_code = $request->get('location_code');
        $location_latitude = $request->get('location_latitude');
        $location_longitude = $request->get('location_longitude');
        $volts = $request->get('volts')=='on'?1:0;
        $amps = $request->get('amps')=='on'?1:0;
        $volt_resister = $request->get('volt_resister')=='on'?1:0;
        $old_image = $request->get('old_images');

        $image = $old_image;
        if($file_temp = $request->file('attach_image')){
            $destinationPath = public_path() . '/uploads/settings/cathodic';
            $image =  Str::random(10).'.'.$file_temp->getClientOriginalExtension();
            $file_temp->move($destinationPath, $image);
        }

        try {
            DB::beginTransaction();
            DB::table('settings_cathodic')->where('id',$id)->update([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'plocation_id' => $plocation_id,
                'location_name' => $location,
                'location_code' => $location_code,
                'location_latitude' => $location_latitude,
                'location_longitude' => $location_longitude,
                'volts' => $volts,
                'amps' => $amps,
                'volt_resister' => $volt_resister,
                'images' => $image,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return Redirect::route('settings.cathodic')->with('success', "Successful Updated!");

        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return Redirect::route('settings.cathodic')->with('error', "Failed Updating");
        }
    }

}
