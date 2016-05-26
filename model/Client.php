<?php 

	class Client extends Eloquent {
		/**
		 * The database table used by the model.
		 *
		 * @var string
		 */
	    protected $table = 'client';


	    public static $rules = array(
			            		'join_date'	 =>	'required',
			            		'country'    	 =>	'required',
			            		'contact'  	 =>	'required',
			            		'salutation'  	 =>	'required',
			            		'fax'		 =>	'min:6',
			            		'office'		 =>  	'min:6' ,
			            		'name' 		 =>  	'',
			            		'address' 	 =>  	'required',
			            		'mobile' 	  =>   	'',
			            		'email' 		  =>    'unique:client|email',
			            		'website' 	  =>    ''
					);

	}

 ?>