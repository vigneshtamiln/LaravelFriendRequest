<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFriend;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    protected function _validation_rules($request, $id)
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => @$request->password != null ? ['string', 'min:8', 'confirmed'] : '',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb

        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['users'] = $user = User::where('id', '!=',auth()->user()->id)->get();
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['users']  = User::all();
        $data['method']  = 'POST';
        $data['route']  = route('users.store');
        return view('users.partials.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), 
        $this->_validation_rules($request, Null));
        if ($validation->fails())
        {
            return redirect('users/index')
                        ->withErrors($validation)
                        ->withInput();
        }
        $model = new User();
        $this->_save($model, $id);
        return redirect()->route('users.index')
        ->with('success','User created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    public function addFriend(Request $request)
    {
        $model = UserFriend::where('friend_id', $request->friend_id)->where('user_id',$request->user_id)->first();
        $userFriendModel = $model != null ? $model : new UserFriend;
        $data = $request->all();
        if(@$model != null && $data['status'] == 0){
            $userFriendModel->delete();
        }else{
            $userFriendModel->fill($data);
            $userFriendModel->save();
        }
        $status = $userFriendModel->status;
        $message = $status == 1 ? 'Accept' : ($status == 2 ? 'Declined': ($status == 0 ? 'Sent' :'Delete'));
        return response()->json('Request '.$message);
    }

    public function getMyFriendList(Request $request)
    {
        $data['followers']  = User::find($request->id)->followers ?: [];
        $data['followings'] = User::find($request->id)->followings ?: [];
        $data['users']      =  $data['followers']->merge($data['followings']);
        return view('users.index', $data);

        // return view('users.partials.myfriendlist', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $user = User::find($id);
        $data['model']  =[
            'user' => $user
        ];
        $data['id']     = $id;
        $data['genders'] = $user::$genders;
        return view('users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), 
        $this->_validation_rules($request, $id));

        if ($validation->fails())
        {
            return redirect('users/'.$id.'/edit')
                        ->withErrors($validation)
                        ->withInput();
        }
        $model = User::find($id);
      
        $this->_save($model, $id);
        return redirect()->route('users.index')
        ->with('success','User Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = User::find($id);
        $model->delete();

        return redirect()->route('users.index')
        ->with('success','User Deleted successfully');
    }

    protected function _save($model){
        $data = request()->except('_token');
        $password = request()->password ? $data['password'] : $model['password'];
        $data['password'] = Hash::make($password);
        if(request()->file()){
            $image = request()->file('image');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/images'), $new_name);
            $data['image'] = $new_name;
        }
        $model->fill($data);
        $model->save();
    }
}
