<?php

class ClientController extends BaseController {

	public function __construct()
	{
		parent::__construct();	
		define('MODULE',"client");				
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(!User::hasPermTo(MODULE,'view'))return Redirect::to('admin/error/show/403');
		$client=Client::all();
		$this->view('admin.client.index',compact('client'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(!User::hasPermTo(MODULE,'create'))return Redirect::to('admin/error/show/403');
		$this->view('admin.client.add');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator=Validator::make(Input::all(),Client::$rules);
		if($validator->passes()){
			$client=new Client;
			$client->join_date	= strtotime(Input::get('join_date'));
			$client->country	= Input::get('country');
			$client->contact	= Input::get('contact');
			$client->salutation	= Input::get('salutation');
			$client->fax		= Input::get('fax');
			$client->office		= Input::get('office');
			$client->name		= Input::get('name');
			$client->address	= Input::get('address');
			$client->mobile	    = Input::get('mobile');
			$client->email		= Input::get('email');
			$client->website	= Input::get('website');
			$client->save();
			Session::flash('message', 'Client has been added.');
			return Redirect::to('admin/client');
		}
		else{
			return $this->redirecterrorBack($validator);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(!User::hasPermTo(MODULE,'view'))return Redirect::to('admin/error/show/403');
		$client=Client::findOrFail($id);
		$this->view('admin.client.detail', compact('client'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(!User::hasPermTo(MODULE,'edit'))return Redirect::to('admin/error/show/403');
		$client = Client::find($id);
		$this->view('admin.client.edit',compact('client'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules =  Client::$rules; 
		$rules['email'] = 'unique:members,email,'.$id;
		
		$validator=Validator::make(Input::all(),$rules);
		if($validator->passes()){
			$client 			= Client::find($id);
			$client->join_date	= strtotime(Input::get('join_date'));
			$client->country	= Input::get('country');
			$client->contact	= Input::get('contact');
			$client->salutation	= Input::get('salutation');
			$client->fax		= Input::get('fax');
			$client->office		= Input::get('office');
			$client->name		= Input::get('name');
			$client->address	= Input::get('address');
			$client->mobile	= Input::get('mobile');
			$client->email		= Input::get('email');
			$client->website	= Input::get('website');
			$client->save();
			Session::flash('message', 'Client has been updated');
			return Redirect::to('admin/client');
		}
		else{
			return $this->redirecterrorBack($validator);
		}
	}

	public function get_client_json($id)
	{
		if(!User::hasPermTo(MODULE,'view'))return Redirect::to('admin/error/show/403');
		$client = Client::find($id);
		return Response::json($client);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(!User::hasPermTo(MODULE,'delete'))return Redirect::to('admin/error/show/403');
		Client::destroy($id);
		Session::flash('message', 'You have successfully deleted client list');
		return Redirect::route('admin.client.index');
	}


}
