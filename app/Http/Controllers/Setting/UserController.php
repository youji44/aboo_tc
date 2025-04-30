<?php namespace App\Http\Controllers\Setting;

use App\Export\ExportUser;
use App\Http\Controllers\WsController;
use App\Models\User;
use App\Models\UserLocations;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Maatwebsite\Excel\Facades\Excel;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Reminder;
use URL;
use Validator;
use View;

class UserController extends WsController
{
    public function index(Request $request)
    {
        if(Sentinel::check()) return Redirect::route('dashboard');
        try {
            DB::connection()->getPdo();
        }catch(\Exception $e){
            Log::info($e->getMessage());
            return View('user.login')->with('error','Could not connect to the database');
        }
        return View('user.login');
    }

    /**
     * Microsoft API integration
     */
    public function login_microsoft(Request $request)
    {
        return Socialite::driver('microsoft')->redirect();
    }
    public function login_callback(Request $request)
    {
        $user_microsoft = Socialite::driver('microsoft')->user();
        try {

            if($user = DB::table('users as u')
                ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->select('u.*','r.slug')
                ->where('u.email',$user_microsoft->getEmail())->first())
            {
//                if($user->slug != 'superadmin' && !$user_access = DB::table('user_locations')->where('user_id',$user->id)->where('qc',0)->first()){
//                    return Redirect::route('login')->withInput()->with('info', 'User cannot access on this Portal');
//                }
            }else{
                return Redirect::route('login')->with('error', 'Current user does not exist in this Portal!');
            }
            $sentinelUser = Sentinel::findById($user->id);
            if ($sentinelUser) {
                Sentinel::login($sentinelUser);
            }else{
                // If user does not exist, you may want to create a new user
                $newUser = User::create([
                    'name' => $user_microsoft->getName(),
                    'email' => $user_microsoft->getEmail(),
                    // Add any other necessary fields
                    'password' => Hash::make('admin'), // Placeholder password
                ]);
                $newUser->roles()->attach(DB::table('roles')->where('slug','staff')->value('id'));
                // Authenticate the newly created user
                Sentinel::login($newUser);
            }

            $user_locations = DB::table('user_locations')->where('user_id',Sentinel::getUser()->id)->first();
            $ids = array();
            if($user_locations != null){
                $ids = json_decode($user_locations->location_ids);
            }

            if(Sentinel::inRole('superadmin')){
                Session::put('p_loc',DB::table('primary_location')->orderby('id')->first()->id);
                Session::put('p_loc_name',DB::table('primary_location')->orderby('id')->first()->location);
                Session::put('p_loc_color',DB::table('primary_location')->orderby('id')->first()->location_color);
            }else{
                $id =count($ids)>0?$ids[0]:1;
                Session::put('p_loc',$id);
                Session::put('p_loc_name',DB::table('primary_location')->where('id',$id)->first()->location);
                Session::put('p_loc_color',DB::table('primary_location')->where('id',$id)->first()->location_color);
            }

            Session::put('geo_lat', $request->get('geo_latitude'));
            Session::put('geo_lng', $request->get('geo_longitude'));

            if(Sentinel::inRole('operator')){
                return Redirect::route('daily.fuel')->with('success', 'Welcome to Sign in IR Portal');
            }
            // Redirect to the Dashboard page
            return Redirect::route('dashboard')->with('success', 'Welcome to Sign in IR Portal');

//            // Try to log the User in
//            if (Sentinel::authenticate([$user->username, $user->password]))
//            {
//
//            }
        } catch (NotActivatedException $e) {
            return Redirect::route('login')->with('error', 'User can not login');

        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();

        } catch (\Exception $e){
            Log::info($e->getMessage());
        }
        return back()->withInput()->withErrors('error','User can not login');
    }

    ///////////////////End Microsoft API integration////////////////////////////////


    public function loginAdmin(Request $request){

        try {
            DB::connection()->getPdo();
        }catch(\Exception $e){
            return View('user.login')->with('error','Could not connect to the database');
        }

        try {
            $rules = array(
                'username'     => 'required',
                'password'  => 'required|between:3,32'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return Redirect::route('login')->with('info','Please input correct values');
            }

            if($user = DB::table('users as u')
                ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->select('u.*','r.slug')
                ->where('u.username',$request->get('username'))->first())
            {
//                if($user->slug != 'superadmin' && !$user_access = DB::table('user_locations')->where('user_id',$user->id)->where('qc',0)->first()){
//                    return Redirect::route('login')->withInput()->with('info', 'User cannot access on this Portal');
//                }
            }else{
                return Redirect::route('login')->with('error', 'Incorrect Username or password!');
            }

            // Try to log the User in
            if (Sentinel::authenticate($request->only(['username','password'])))
            {
                $user_locations = DB::table('user_locations')->where('user_id',Sentinel::getUser()->id)->first();
                $ids = array();
                if($user_locations != null){
                    $ids = json_decode($user_locations->location_ids);
                }

                if(Sentinel::inRole('superadmin')){
                    Session::put('p_loc',DB::table('primary_location')->orderby('id')->first()->id);
                    Session::put('p_loc_name',DB::table('primary_location')->orderby('id')->first()->location);
                    Session::put('p_loc_color',DB::table('primary_location')->orderby('id')->first()->location_color);
                }else{
                    $id =count($ids)>0?$ids[0]:1;
                    Session::put('p_loc',$id);
                    Session::put('p_loc_name',DB::table('primary_location')->where('id',$id)->first()->location);
                    Session::put('p_loc_color',DB::table('primary_location')->where('id',$id)->first()->location_color);
                }

                Session::put('geo_lat', $request->get('geo_latitude'));
                Session::put('geo_lng', $request->get('geo_longitude'));

                if(Sentinel::inRole('operator')){
                    return Redirect::route('daily.fuel')->with('success', 'Welcome');
                }
                // Redirect to the Dashboard page
                return Redirect::route('dashboard')->with('success', 'Welcome to Signing in IR Portal');
            }
            return Redirect::route('login')->with('error', 'Incorrect Username or password!');

        } catch (NotActivatedException $e) {
            return Redirect::route('login')->with('error', 'User can not login');

        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();

        } catch (\Exception $e){
            Log::info($e->getMessage());
        }

        return back()->withInput()->withErrors('error','User can not login');
    }

    public function getLogout()
    {
        // Log the User out
        Sentinel::logout();
        Session()->flush();
        // Redirect to the first page
        return Redirect::route('login');
    }

    public function profile(Request $request){

        try {
            $user_id = '';
            if(\Sentinel::check()) {
                $user_id = \Sentinel::getUser()->id;
            }
            DB::beginTransaction();
            $user = DB::table('users as u')
                ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->join('activations as a', 'a.user_id', '=', 'u.id')
                ->select('u.*','r.slug','r.id as role_id')
                ->where('u.id',$user_id)
                ->first();

            DB::commit();

            return view('user.profile',compact('user'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Server Errors");
        }
    }

    /**
     */
    public function profile_update(Request $request){

        $rules = array(
            'name'       => 'required',
        );

        $user_id  = $request->get('uid');
        $name     = $request->get('name');
        $email     = $request->get('email');
        $oldpassword = $request->get('oldpassword');
        $password = $request->get('password');

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::route('user.profile',$user_id)->with('error','You must input Name!');
        }
        try {
            DB::beginTransaction();
            $user = DB::table('users')->where('id',$user_id)->first();
            if($oldpassword != ''){
                $rules = array(
                    'password'       => 'required|between:6,32',
                    'passwordconfirm'   => 'required|same:password',
                );

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return Redirect::route('user.profile',$user_id)->with('error','Password length must be over or confirm the password');
                }

                if(Hash::check($oldpassword, $user->password)){

                    DB::table('users')->where('id',$user_id)->update([
                        'name' => $name,
                        'email' => $email,
                        'password'      => Hash::make($password),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }else{
                    return Redirect::route('user.profile',$user_id)->with('error','Incorrect old password');
                }

            }else{

                DB::table('users')->where('id',$user_id)->update([
                    'name'  => $name,
                    'email'  => $email,
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);
            }

            DB::commit();
            return Redirect::route('user.profile')->with('success', "Updated a user information successfully");

        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::route('user.profile',$user_id)->with('error', "Failed updating!");
        }
    }

    /**
     * Location changing
     */
    public function set_plocation(Request $request){
        try {
            DB::beginTransaction();
            $id = $request->get('primary_location');
            Session::put('p_loc',$id);
            Session::put('insight','no');
            Session::put('p_loc_name',DB::table('primary_location')->where('id',$id)->first()->location);
            Session::put('p_loc_color',DB::table('primary_location')->where('id',$id)->first()->location_color);
            DB::commit();
            return Redirect::back();
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Loading Failed!");
        }
    }

    /////////////////////////////////////////

    /**
     * User management
     * list, create, store, update, profile, password reset, delete
     */

    public function user_list(Request $request){

        try {
            $users = DB::table('users as u')
                ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->join('activations as a', 'a.user_id', '=', 'u.id')
                ->leftjoin('user_locations as ul','ul.user_id','=','u.id')
                ->select('u.*','a.completed','r.slug as role_slug','r.name as role_name','ul.staff_position','ul.location_ids','ul.fm','ul.qc')
                ->where('a.completed',1)
                ->where('r.slug','!=','superadmin')
                ->get();
            DB::commit();

            $locations = DB::table('primary_location')->get();

            return View('user.index', compact('users','locations'));

        }catch(\Exception $e){
            return Redirect::route('settings')->with('error','Users loading failed');
        }
    }

    public function create(){

        try {
            DB::beginTransaction();

            $roles = DB::table('roles')->select('id','name')->where('slug','!=','superadmin')->get();
            $locations = DB::table('primary_location')->get();
            DB::commit();
            return view('user.create',compact('roles','locations'));
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', "Server Errors");
        }
    }

    /**
     * @param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */

    public function edit($id){

        try {
            DB::beginTransaction();
            $roles = DB::table('roles')->select('id','name')->where('slug','!=','superadmin')->get();
            $user = DB::table('users as u')
                ->join('role_users as ru', 'ru.user_id', '=', 'u.id')
                ->join('roles as r', 'r.id', '=', 'ru.role_id')
                ->join('activations as a', 'a.user_id', '=', 'u.id')
                ->select('u.*','r.slug','r.id as role_id')
                ->where('u.id',$id)
                ->first();

            $user->qc = 0;
            $user->fm = 0;

            $locations = DB::table('primary_location')->get();
            $staff_position = '';
            $location_ids = array();
            $user_loc = DB::table('user_locations')->where('user_id',$id)->first();

            if($user_loc != null){
                $staff_position = $user_loc->staff_position;
                $location_ids = json_decode($user_loc->location_ids);
                $user->qc = $user_loc->qc;
                $user->fm = $user_loc->fm;
            }
            DB::commit();

            return view('user.edit',compact('user','roles','locations','location_ids','staff_position'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::info($e->getMessage());
            return back()->with('error', "Server Errors");
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request){

        $rule1 = array(
            'username'      => 'required|unique:users',
        );

        $validator = Validator::make($request->all(), $rule1);
        if ($validator->fails()) {
            return Redirect::route('settings.user.add')->with('error','Username exist already.')->withInput($request->input());
        }

        $rule2 = array(
            'password'      => 'required|between:3,32',
            'passwordconfirm' => 'required|same:password',
            'name'          => 'required',
        );

        $validator = Validator::make($request->all(), $rule2);
        if ($validator->fails()) {
            return Redirect::route('settings.user.add')->with('error','Please input all fields correctly')->withInput($request->input());
        }

        try {
            DB::beginTransaction();

            $rid = $request->get('rid');
            //$role = DB::table('roles')->where('id',$request->get('rid'))->select('*')->first();

            $user = new User();
            $user->username     = $request->get('username');
            $user->name         = $request->get('name');
            $user->name         = $request->get('name');
            $user->password     = Hash::make($request->get('password'));
            $user->email        = $request->get('email');
            $user->last_login   = date('Y-m-d H:i:s');
            $user->save();

            $locations = DB::table('primary_location')->get();

            $ids = array();
            foreach ($locations as $item){
                if($request->get('location_'.$item->id) == 'on'){
                    array_push($ids,$item->id);
                }
            }

            $qc = $request->get('qc') == 'on'?0:1;
            $fm = $request->get('fm') == 'on'?0:1;

            $user_location = new UserLocations();
            $user_location->user_id = $user->id;
            $user_location->location_ids = json_encode($ids);
            $user_location->staff_position = $request->get('staff_position');
            $user_location->qc = $qc;
            $user_location->fm = $fm;
            $user_location->save();

            //role_user Table
            DB::table('role_users')->insert(['user_id'=>$user->id, 'role_id'=>$rid]);
            //Activations Table
            DB::table('activations')->insert(['user_id'=>$user->id, 'completed'=>1]);
            DB::commit();

            return Redirect::route('settings.user')->with('success', "Successful Added!");

        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return Redirect::route('settings.user.add')->with('error', "Failed Adding");
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request){

        $rules = array(
            'name'       => 'required',
        );

        $user_id  = $request->get('uid');
        $role_id  = $request->get('rid');
        $name     = $request->get('name');
        $email     = $request->get('email');
        $oldpassword = $request->get('oldpassword');
        $password = $request->get('password');
        $qc = $request->get('qc') == 'on'?0:1;
        $fm = $request->get('fm') == 'on'?0:1;

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::route('settings.user.edit',$user_id)->with('error','You must input Name!');
        }
        try {
            DB::beginTransaction();
            $user = DB::table('users')->where('id',$user_id)->first();
            if($oldpassword != ''){
                $rules = array(
                    'password'       => 'required|between:3,32',
                    'passwordconfirm'   => 'required|same:password',
                );

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return Redirect::route('settings.user.edit',$user_id)->with('error','Failed password changing');
                }

                if(Hash::check($oldpassword, $user->password)){

                    DB::table('users')->where('id',$user_id)->update([
                        'name' => $name,
                        'email' => $email,
                        'password'      => Hash::make($password),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                }else{
                    return Redirect::route('settings.user.edit',$user_id)->with('error','Incorrect old password');
                }

            }else{

                DB::table('users')->where('id',$user_id)->update([
                    'name'  => $name,
                    'email'  => $email,
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);
            }

            $locations = DB::table('primary_location')->get();
            DB::table('user_locations')->where('user_id',$user_id)->delete();

            $ids = array();
            foreach ($locations as $item){
                if($request->get('location_'.$item->id) == 'on'){
                    $ids[] = $item->id;
                }
            }

            if(count(DB::table('user_locations')->where('user_id')->get()) > 0){
                DB::table('user_locations')->where('user_id',$user_id)->update([
                    'location_ids' => json_encode($ids),
                    'staff_position'=>$request->get('staff_position'),
                    'qc'=>$qc,
                    'fm'=>$fm
                ]);
            }else{
                $user_location = new UserLocations();
                $user_location->user_id = $user->id;
                $user_location->location_ids = json_encode($ids);
                $user_location->staff_position = $request->get('staff_position');
                $user_location->qc = $qc;
                $user_location->fm = $fm;
                $user_location->save();
            }

            //role_user Table
            DB::table('role_users')->where('user_id',$user_id)->update([
                'role_id'=>$role_id
            ]);

            DB::commit();
            return Redirect::route('settings.user')->with('success', "Updated a user information successfully");

        }catch(\Exception $e){
            DB::rollBack();
            return Redirect::route('settings.user.edit',$user_id)->with('error', "Failed updating!");
        }
    }

    //deny admin
    public function delete(Request $request){
        $id = $request->get('uid');
        try {
            DB::beginTransaction();
            DB::table('activations')->where('user_id',$id)->update([
                'user_id' => 0
            ]);
            DB::commit();
            return  Redirect::route('settings.user')->with('success', 'Deleted a User Successfully');
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return  Redirect::route('settings.user')->with('error', 'Failed deleting');
        }

//        if(DB::table('users')->where('id',$id)->delete()){
//            //Role_users Table
//            DB::table('role_users')->where('user_id',$id)->delete();
//            //Activations Table
//            DB::table('activations')->where('user_id',$id)->delete();
//            return  Redirect::route('settings.user')->with('success', 'Deleted a User Successfully');
//        }
//        return  Redirect::route('settings.user')->with('error', 'Error!');
    }

    public function format(Request $request){
        $id = $request->get('uid');
        try {
            DB::beginTransaction();
            $user = DB::table('users')->where('id',$id)->first();
            DB::table('users')->where('id',$id)->update([
                'password'      => Hash::make('admin'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
            DB::commit();
            return  Redirect::route('settings.user')->with('success', $user->name.' reset password as "admin"');
        }catch(\Exception $e){
            Log::info($e->getMessage());
            DB::rollBack();
            return  Redirect::route('settings.user')->with('error', 'Failed reset password!');
        }
    }
}
