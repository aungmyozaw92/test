<?php  

/*
* app/validators.php
*/

Validator::extend('check_expense_unique', function($attribute, $value)
{ 
		$other_expense = Input::get('other_expense');
		$records = Expense::get_expense_name_count($other_expense);

		if($records > 0) {

			return false;
		}else{

			return true;
		}

});

Validator::extend('check_valid_month', function($attribute, $value)
{ 
		if(Session::get($value)) {

			return true;
		}else{

			return false;
		}

});
Validator::extend('check_update_valid_month', function($attribute, $value)
{ 
		if(Session::get($value)) {
			$cpf_payments = DB::table('cpf_payments')->where('month',$value)->count();
			if($cpf_payments > 2){
				return false;
			}
			else return true;
		}else{

			return false;
		}

});

Validator::extend('check_receipt_amount', function($attribute, $value)
{ 
	$unique_id = Input::get('unique_id');
	$paid_amount = 0;
	$tot_amount = 0;

	if(Receipt::where('unique_id',$unique_id)->count()){
		            $paid_receipts = Common::query_multiple_rows_by_single_source('sub_receipts','unique_id',$unique_id); 
		            foreach ($paid_receipts as $paid) {
		                $paid_amount +=$paid->pay_amount;
		              }
	}

	$service =  Common::query_multiple_rows_by_single_source('invoices','unique_id',$unique_id);
	             foreach ($service as $ser) {
		          $tot_amount += $ser->amount;
		          $discount = $ser->discount;
		          $is_gst = $ser->is_gst;
		 }

		 $tot_amount -= $discount;
		if($is_gst){
		     $tot_amount += $tot_amount * 0.07;
		}

		$balance_amount = $value;

		$paid_total = $balance_amount+ $paid_amount;
		if($paid_total <= $tot_amount)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

});

Validator::extend('check_product_receipt_amount', function($attribute, $value)
{ 
	$unique_id = Input::get('unique_id');
	$paid_amount = 0;
	$tot_amount = 0;

	if(ProductReceipt::where('unique_id',$unique_id)->count()){
		            $paid_receipts = Common::query_multiple_rows_by_single_source('product_sub_receipts','unique_id',$unique_id); 
		            foreach ($paid_receipts as $paid) {
		                $paid_amount +=$paid->pay_amount;
		              }
	}

	$product =  Common::query_multiple_rows_by_single_source('product_invoices','unique_id',$unique_id);
	             foreach ($product as $pro) {
		          $tot_amount += $pro->amount;
		          $discount = $pro->discount;
		          $is_gst = $pro->is_gst;
		 }

		 $tot_amount -= $discount;
		if($is_gst){
		     $tot_amount += $tot_amount * 0.07;
		}

		$balance_amount = $value;

		$paid_total = $balance_amount+ $paid_amount;
		if($paid_total <= $tot_amount)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}

});

?>