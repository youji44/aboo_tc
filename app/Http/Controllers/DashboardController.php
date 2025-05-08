<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends WsController
{
    public function index(Request $request)
    {
        try {
            DB::beginTransaction();


            DB::commit();
            return View('dashboard');

        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

}
