<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\DataTables\UserDataTable;
use Auth, Config, Exception, Validator;

class UsersController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }


    /**
     * Show the form for updating a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpdate($userId)
    {
        try {
            $user = User::where('id',$userId)->with('role')->firstOrFail();
            $roles = Role::orderBy('name')->get();
        } catch (Exception $ex) {
            return redirect('/users')
                ->withInput();
        }
        return view('users.update', compact('user','roles'));
    }

    /**
     * update resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postUpdate($userId,Request $request)
    {
        $userTypes = array_pluck(Role::orderBy('name')->get(['id'])->toArray(),'id');

        $input = $request->all();

        $validator = Validator::make($input, [
            'name'     => 'max:255',
            'password' => 'min:8|confirmed|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/',
            'role_id'     => 'in:' . implode(',', $userTypes),
        ])->setAttributeNames([
            'name'     => 'Name',
            'password' => 'Password',
            'role_id'     => 'Role',
        ]);

        $validator->after(function ($validator) use ($request,$userId) {
            if ($userId == Auth::user()->id && $request->has('role_id')) {
                $validator->errors()->add('role_id', 'You cannot update role code of logged in user!');
            }
        });

        if ($validator->fails()) {
            return redirect('/users/update/'.$userId)
                ->withInput()
                ->withErrors($validator);
        }

        $data = [];
        if($request->has('password')){
             $data['password'] = bcrypt($input['password']);
        }
        if($request->has('role_id')){
             $data['role_id'] = $input['role_id'];
        }
        if($request->has('name')){
             $data['name'] = $input['name'];
        }

        $user = User::find($userId)->update($data);

        if ($user) {
            return redirect('/users');
        }

        return redirect('/users/update/'.$userId)
            ->withInput()
            ->withErrors($validator);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userTypes = array_pluck(Role::orderBy('name')->get(['id'])->toArray(),'id');

        $input = $request->all();

        $validator = Validator::make($input, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/',
            'role_id'     => 'required|in:' . implode(',', $userTypes),
        ])->setAttributeNames([
            'name'     => 'Name',
            'email'    => 'E-Mail Address',
            'password' => 'Password',
            'role_id'     => 'Role',
        ]);

        if ($validator->fails()) {
            return redirect('/users/add')
                ->withInput()
                ->withErrors($validator);
        }

        $user = User::create([
            'name'     => $input['name'],
            'email'    => strtolower($input['email']),
            'password' => bcrypt($input['password']),
            'role_id'     => $input['role_id'],
        ]);

        if ($user) {
            return redirect('/users');
        }

        return redirect('/users/add')
            ->withInput()
            ->withErrors($validator);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // validator rules
        $validator = Validator::make($request->only('id'), [
            'id' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('id') == Auth::user()->id) {
                $validator->errors()->add('id', 'You cannot delete logged user!');
            }
        });

        if ($validator->fails()) {
            return redirect('/users')
                ->withInput()
                ->withErrors($validator);
        }

        try {
            User::findOrFail($request->input('id'))->delete();
            return redirect('/users');
        } catch (Exception $ex) {
            return redirect('/users')
                ->withInput()
                ->withErrors($validator);
        }
    }

}
