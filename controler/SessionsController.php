<?php

class SessionsController extends Controller {

	public function __construct()
	{
		
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::check()){
			return Redirect::to('admin/main');
		}
		else{
			return View::make('admin.sessions.login');
		}
	}

	public function login(){
		$rules = array(
		'username'	=>	'required|min:3|max:32',
		'password'	=>	'required|min:3|max:32'
		);
		$validator=Validator::make(Input::all(),$rules);
		if($validator->passes()){
			$credentials = array(
			            'username' => Input::get('username'),
			            'password' => Input::get('password')
			        );

			if (Auth::attempt($credentials)) {
				$id = Auth::id();
				$user=User::set_authnication($id);
			           return Redirect::to('/admin/main')
			           ->with('flash_notice', 'You are successfully logged in.');
			}
			else{
				Session::flash('error_login', 'Username and Password do not match');
				return Redirect::to('/admin');
			}
		}
		else{
			 return Redirect::to('/admin')
			 ->withErrors($validator)
			 ->withInput();
		}
	}
	public function logout()
	{
		Session::flush();
		return Redirect::to('/admin');
	}
	public function forget(){
		return View::make('admin.sessions.forget');
	}

	public function recover_password() 
	{
  		$validator = Validator::make(Input::all(), 
    				array(
        				'email' => 'required|email'
       				 )
    			);
  		if($validator->fails()) {
   			 return 'The Email you have entered is invalid !';
 		} else {
      				$user = User::where('email', '=', Input::get('email'));
      				if($user->count() == 1) {
        					$user = $user->first();
        					//generate code
        					$code                 = str_random(60);
        					$password             = str_random(10);
        					$user->code           = $code;
        					$user->password_temp  = Hash::make($password);
        					if($user->save()) {
	          					Mail::send('sessions.email', array(
	           					'link' => URL::to('admin/sessions/getRecover').'/'.$code,
		            				'username' => $user->username,
		            				'password' => $password),
		          					function($message) use ($user) {
		          						$message->to($user->email, $user->username)->subject('Your new password has been reset.');
		          					});
		          				return "Your password reset mail had been sent.";
	       					}
     				}
     				else{
     					return "Can't found your email in Our system ! ";
     				}


  		}
  		
	}
	// end recover password
	public function getRecover($code) 
	{
 		$user = User::where('code', '=', $code)
          			        ->where('password_temp', '!=', '');

  		if($user->count()) {
    			$user = $user->first();

    			$user->password       = $user->password_temp;
    			$user->password_temp  = '';
    			$user->code           = '';

    			if ($user->save()) {
        				return Redirect::to('admin')
       					 ->with('message', 'Your account has been recover and you can sign in with new password');
    			}
  		}
  		return Redirect::to('admin')
  			->with('message', 'Could not recover your account');
  	}

}
