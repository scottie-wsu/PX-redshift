<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;

use Illuminate\Database\Eloquent\Model;

class MyAccountController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function show()
	{
		$user = User::find(auth()->id());
		return view('myaccount', compact('user'));
	}

	public function update(Request $request){
		User::where('id', $request->id)->update([
			'name' => $request->name,
			'email' => $request->email,
			'institution' => $request->institution,
		]);
		$user1 = new User;
		$user1->name = $request->name;
		$user1->email = $request->email;
		$user1->institution = $request->institution;

		return view('myaccount', compact('user1'));

	}
}
