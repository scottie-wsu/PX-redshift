<?php

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use Prologue\Alerts\Facades\Alert;
use Backpack\CRUD\app\Http\Requests\AccountInfoRequest;


use Illuminate\Database\Eloquent\Model;

class MyAccountController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	/**
	* Get the guard to be used for account manipulation.
	*
	* @return \Illuminate\Contracts\Auth\StatefulGuard
	*/
	protected function guard()
	{
		return backpack_auth();
	}

	public function show()
	{
		$user = User::find(auth()->id());
		return view('myaccount', compact('user'));
	}

	public function update(Request $request){
		//todo - do server side checks on inputs here
		User::where('id', auth()->id())->update([
			'name' => $request->name,
			'email' => $request->email,
			'institution' => $request->institution,
		]);
		//$user1 = new User;
		//$user1->name = $request->name;
		//$user1->email = $request->email;
		//$user1->institution = $request->institution;


		Alert::success(trans('backpack::base.account_updated'))->flash();
		$alerts = 'yes';
		//return view('myaccount', compact('alerts'));
		return redirect()->route('MyAccount', ['alert' => 1]);
		//return view('myaccount', compact('user1'));

	}


	public function postAccountInfoForm(AccountInfoRequest $request)
	{
		$result = $this->guard()->user()->update($request->except(['_token']));

		if ($result) {
			Alert::success(trans('backpack::base.account_updated'))->flash();
		} else {
			Alert::error(trans('backpack::base.error_saving'))->flash();
		}

		return redirect()->back()->withSuccess('success');
	}

	public function postChangePasswordForm(ChangePasswordRequest $request)
	{
		$user2 = $this->guard()->user();
		$user2->password = Hash::make($request->new_password);

		if ($user2->save()) {
			Alert::success(trans('backpack::base.account_updated'))->flash();
		} else {
			Alert::error(trans('backpack::base.error_saving'))->flash();
		}

		return redirect()->back();

		//return back()->with('alerts', Alert::all());
		//return view('myaccount', compact('user2'));

	}
}
