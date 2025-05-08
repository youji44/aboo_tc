<?php namespace App\Http\Controllers\Setting;

use App\Http\Controllers\WsController;
use App\Models\PrimaryLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingController extends WsController
{
    public function index(Request $request)
    {
        return View('settings.index');
    }

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
}
