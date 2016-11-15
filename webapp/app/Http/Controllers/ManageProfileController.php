<?php

namespace App\Http\Controllers;

use App;
use App\Models\User;
use Google2FA;
use Illuminate\Http\Request;
use Validator;

class ManageProfileController extends Controller
{

    public function getManageProfile(Request $request)
    {
        $user = $request->user();

        return view('users.manage_profile', compact('user'));
    }

    public function postManageProfile(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name'     => 'max:30',
            'password' => 'min:8|confirmed|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/',
        ])->setAttributeNames([
            'name'     => 'Name',
            'password' => 'Password',
        ]);

        if ($validator->fails()) {
            return redirect('/manage_profile')
                ->withInput()
                ->withErrors($validator);
        } else {

            $data = [];
            if ($request->has('password')) {
                $data['password'] = bcrypt($input['password']);
            }

            if ($request->has('name')) {
                $data['name'] = $input['name'];
            }

            $user = $request->user()->update($data);

            if ($user) {
                return redirect()->back()->with('status', trans('messages.user_profile_update_success'));
            }
        }
    }

}
