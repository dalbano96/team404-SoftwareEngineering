<?php

/**
	App\Controller\UserController.php
**/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\Http\Requests;
use App\User as User;
use App\Requirement as Requirement;
use App\Department as Department;
use App\User_Requirement as User_Requirement;

class UserController extends Controller
{
		public function __construct() {
			$this->middleware('auth');
		} 

		// Sends single user information to view userProfile
		/* public function showProfile() {
			if(Auth::check() && Auth::user()->id) {
				return view('userProfile')->with(['user' => $user]);
			}
		} */

		// Show all users
		public function index() {
			$users = User::all();
			return view('admin/index', array('users'=>$users));
		}

		// Show specific user
		public function show($id) {
			$user = User::find($id);
			return view('user/show', array(	'user'=>$user));
		}

		public function create() {

		}

		public function store() {

		}

		// Work in progress
		public function edit($id) {
			$user = User::find($id);
			return view('users/edit', array('user'=>$user));	
		}

		public function update($id) {

		}

		public function destroy($id) {

		}
}
