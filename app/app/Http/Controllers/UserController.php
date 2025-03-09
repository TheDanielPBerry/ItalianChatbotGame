<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	public function login()
	{
		return view('login');
	}

	public function postLogin(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required:email',
			'password' => ['required'],
		]);
		if($validator->fails()) {
			return redirect('login')->withInput()->withErrors($validator, 'login');
		}


		$validated = $validator->validate();
		if(Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
			return redirect('game');
		}

		return redirect('login')->withInput()->withErrors('Could not find a user with those credentials');
	}

	public function logout()
	{
		if(Auth::check()) {
			Auth::logout();
		}
		return redirect('/');
	}

	public function register()
	{
		return view('register');
	}

	public function postRegister(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'fullname' => 'required',
			'email' => 'required:email',
			'password' => [
				'required',
				'confirmed',
				Password::min(8)->numbers()->letters()->symbols(), 'confirmed'],
		]);


		if($validator->fails()) {
			return redirect('register')->withInput()->withErrors($validator, 'register');
		}

		$validated = $validator->validate();

		$user = User::where('email', $validated['email'])->first();
		if(!empty($user)) {
			return redirect('register')->withInput()->withErrors(['User with email already exists'], 'register');
		}

		$user = new User();
		$user->name = $validated['fullname'];
		$user->email = $validated['email'];
		$user->password = Hash::make($validated['password']);
		$user->save();



		return redirect('game');
	}
}
