<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
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

    public function files_upload(Request $request){
        try{
            $file = null;
            if($file_temp = $request->file('file')){
                $destinationPath = public_path().'/uploads/settings/files';
                $file =  Str::random(10).'.'.$file_temp->getClientOriginalExtension();
                $file_temp->move($destinationPath, $file);
            }
        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return response()->json(['name'=> $file]);
    }

    public function get_download(Request $request)
    {
        try{
            $filename = $request->get('file');
            $file = public_path(). "/uploads/settings/files/".$filename;
            return Response::download($file, $filename);
        }catch (\Exception $e){
            return null;
        }
    }
}
