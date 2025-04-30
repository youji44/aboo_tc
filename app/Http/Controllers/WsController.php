<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class WsController extends Controller {

    public $isAdmin;
    public $user_id;
    public function _construct(){
        $this->isAdmin = false;
        $this->user_id = Sentinel::getUser()->id;
        if( Sentinel::inRole('admin') || Sentinel::inRole('superadmin'))$this->isAdmin = true;
    }

    public function update_session(Request $request)
    {
        Session::put('geo_lat', $request->get('geo_latitude'));
        Session::put('geo_lng', $request->get('geo_longitude'));
        return response()->json($request->all());
    }

    public function convert_desc($desc){
        $str_desc = '';
        if(is_array($desc = json_decode($desc))){
            foreach ($desc as $item){
                foreach ($item as $key=>$value){
                    if (strtolower(trim(str_replace(' ','',$value))) == 'satisfied') $value = '<span class="text-success">'.$value.'</span>';
                    if (strtolower(trim(str_replace(' ','',$value))) == 'notsatisfied' || strtolower(trim(str_replace(' ','',$value))) == 'unsatisfied') $value = '<span class="text-danger">'.$value.'</span>';
                    if (strtolower(trim(str_replace(' ','',$value))) == 'other') $value = '<span class="text-secondary">'.$value.'</span>';
                    if (strtolower(trim(str_replace(' ','',$value))) == 'notapplicable') $value = '<span class="text-secondary">'.$value.'</span>';
                    else $value = '<span class="text-secondary">'.$value.'</span>';
                    $str_desc .= $key.' - '.$value.'<br>';
                }
            }
        }
        return $str_desc?$str_desc:'-';
    }

    public function images_upload(Request $request){
        try{
            $images = null;
            if($file_temp = $request->file('file')){
                $destinationPath = public_path() . '/uploads';
                $extension = $file_temp->getClientOriginalExtension() ?: 'png';
                $images =  Str::random(10).'.'.$extension;
                if($extension =='pdf'){
                    $file_temp->move($destinationPath, $images);
                }else{
                    $img = Image::make($file_temp->getRealPath());
                    // Check and correct image orientation
                    $img->orientate();
                    // Resize and save the image
                    if ($img->width() > 1024 || $img->height() > 1024) {
                        $img->resize(1024, 1024, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $img->save($destinationPath.'/'.$images);
                }
            }
        }catch (\Exception $e){
            Log::info($e->getMessage());
        }

        return response()->json(['name'=> $images]);
    }

    public function images_settings_upload(Request $request){
        try{
            $images = null;
            if($file_temp = $request->file('file')){
                $destinationPath = public_path() . '/uploads/settings/';
                $extension   = $file_temp->getClientOriginalExtension() ?: 'png';
                $images =  Str::random(10).'.'.$extension;
                $img = Image::make($file_temp->getRealPath());
                // Check and correct image orientation
                $img->orientate();
                // Resize and save the image
                if ($img->width() > 1024 || $img->height() > 1024) {
                    $img->resize(1024, 1024, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $img->save($destinationPath.'/'.$images);
            }
        }catch (\Exception $e){
            Log::info($e->getMessage());
        }

        return response()->json(['name'=> $images]);
    }
}
