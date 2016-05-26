<?php 

	class Common extends Eloquent {
		


	/**
     * Fetch single field-data from DB
     *
     * @access public
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public static function query_single_data($table, $target_field, $source_data, $desired_data)
    {

    	$row = DB::table($table)->where($target_field,'=',$source_data)->first();

        return $row->$desired_data;
    }

     /**
     * Checkout duplicate data
     *
     * @access public
     * @param string
     * @param string
     * @param string
     * @return bool
     */
    public static function query_single_duplicate_data($table, $target_field, $data)
    {
        $count = DB::table($table)->where($target_field,'=',$data)->count();
        if ($count > 0)
            return true;

        else
            return false;
    }

      public static function query_multiple_rows_by_single_source($table, $target_field, $source_data){
        
        return DB::table($table)->where($target_field, $source_data)->get();
        
    }

    public static function get_yearly_income_array($month)
    {
            $start_date = date('Y-').$month.'-01 00:00:00';
            $end_date = date('Y-m-',strtotime($start_date)).date('t',strtotime($start_date)).' 23:59:59';

            $from = strtotime($start_date);
            $to = strtotime($end_date);
            $charge=0;

             $receipt_records =DB::table('receipts')->where('status',1)->get();
                foreach ($receipt_records as $row ){

                        if($from <= $row->date && $row->date <= $to){
                                $invoice_record = Invoice::where('unique_id',$row->unique_id)->get();
                                $total = 0;$discount = 0;$is_gst = 0;

                                    foreach ($invoice_record as $invoice) {                                        
                                         $total += $invoice->amount;
                                         $discount = $invoice->discount;
                                         $is_gst = $invoice->is_gst;
                                    }   
                                    $charge += $total;
                                    if($discount){
                                        $charge -= $discount;
                                    }
                                    if($is_gst){
                                        $charge -= $charge * 0.07;
                                    }
                        }                        
                }

            $receipt_records =DB::table('product_receipts')->where('status',1)->get();
                foreach ($receipt_records as $row ){

                        if($from <= $row->date && $row->date <= $to){
                                $invoice_record = ProductInvoice::where('unique_id',$row->unique_id)->get();
                                $total = 0;$discount = 0;$is_gst = 0;

                                    foreach ($invoice_record as $invoice) {                                        
                                         $total += $invoice->amount;
                                         $discount = $invoice->discount;
                                         $is_gst = $invoice->is_gst;
                                    }   
                                    $charge += $total;
                                    if($discount){
                                        $charge -= $discount;
                                    }
                                     if($is_gst){
                                        $charge -= $charge * 0.07;
                                    }
                        }                        
                }

        return intval($charge);
    }
    
    public static function get_yearly_expense_array($month)
    {
            $from = $start_date = strtotime('01-'.$month.'-'.date('Y').' 00:00:00');
            $to = $end_date = strtotime(date('t',$start_date).'-'.date('m-Y',$start_date).' 23:59:59');
            $charge=0;
            
            $service_records = DB::table('expenses')->where('paymentdate','>=',$start_date)->where('paymentdate','<=',$end_date)->get();
            
            foreach($service_records as $service) {
                $charge += $service->amount;
            }
            
            $service_records = DB::table('product_payments')->where('paymentdate','>=',$start_date)->where('paymentdate','<=',$end_date)->get();
            $gst_amount = 0;
            foreach($service_records as $service) {
                $charge += $service->qty * $service->unit_price;
                if($service->is_gst){
                    $gst_amount += $service->qty * $service->unit_price * 0.07;
                }
            }

            $charge  -= $gst_amount;
            $charge  += Profitandloss::get_paid_cpf($from,$to);

             $salary = DB::table('salary_payments')->get();
                foreach ($salary as $row) {
                        if($from <= $row->payment_date && $row->payment_date <= $to){
                            $charge  += $row->amount;
                        }
                }

        return intval($charge);
    }

    public static function get_new_quotation_no()
    {
            $quotations = DB::table('quotations')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($quotations as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }
            $inc = sprintf('%0'.$general->inc_digit.'d',++$count);

            $quotation_no = $general->quotation_no;
            $quotation_no = str_replace('{dd}', date('d'), $quotation_no);
            $quotation_no = str_replace('{mm}', date('m'), $quotation_no);
            $quotation_no = str_replace('{yy}', date('y'), $quotation_no);
            $quotation_no = str_replace('{inc}', $inc, $quotation_no);
            Session::put('in_number',$inc);
            return $quotation_no;
    }
    public static function get_new_product_quotation_no()
    {
            $quotations = DB::table('product_quotations')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($quotations as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }
            $inc = sprintf('%0'.$general->inc_digit.'d',++$count);

            $quotation_no = $general->pquotation_no;
            $quotation_no = str_replace('{dd}', date('d'), $quotation_no);
            $quotation_no = str_replace('{mm}', date('m'), $quotation_no);
            $quotation_no = str_replace('{yy}', date('y'), $quotation_no);
            $quotation_no = str_replace('{inc}', $inc, $quotation_no);
            Session::put('in_number',$inc);
            return $quotation_no;
    }
    public static function get_new_order_no($unique_id=NULL)
    {
            $orders = DB::table('orders')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($orders as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }
            if($unique_id){
                     $inc = DB::table('quotations')->where('unique_id',$unique_id)->first()->in_number;
            }else{
                 $inc = sprintf('%0'.$general->inc_digit.'d',++$count);
            }

            $order_no = $general->order_no;
            $order_no = str_replace('{dd}', date('d'), $order_no);
            $order_no = str_replace('{mm}', date('m'), $order_no);
            $order_no = str_replace('{yy}', date('y'), $order_no);
            $order_no = str_replace('{inc}', $inc, $order_no);
            return $order_no;
    }
     public static function get_new_product_order_no($unique_id)
    {
            $orders = DB::table('product_orders')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($orders as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }

            if($unique_id){
                     $inc = DB::table('product_quotations')->where('unique_id',$unique_id)->first()->in_number;
            }else{
                 $inc = sprintf('%0'.$general->inc_digit.'d',++$count);
            }

            $order_no = $general->porder_no;
            $order_no = str_replace('{dd}', date('d'), $order_no);
            $order_no = str_replace('{mm}', date('m'), $order_no);
            $order_no = str_replace('{yy}', date('y'), $order_no);
            $order_no = str_replace('{inc}', $inc, $order_no);
            return $order_no;
    }

    public static function get_new_invoice_no($unique_id)
    {
            $invoices = DB::table('invoices')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($invoices as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }
            
             if($unique_id){
                     $inc = DB::table('quotations')->where('unique_id',$unique_id)->first()->in_number;
            }else{
                 $inc = sprintf('%0'.$general->inc_digit.'d',++$count);
            }

            $invoice_no = $general->invoice_no;
            $invoice_no = str_replace('{dd}', date('d'), $invoice_no);
            $invoice_no = str_replace('{mm}', date('m'), $invoice_no);
            $invoice_no = str_replace('{yy}', date('y'), $invoice_no);
            $invoice_no = str_replace('{inc}', $inc, $invoice_no);
            return $invoice_no;
    }
     public static function get_new_product_invoice_no($unique_id)
    {
            $invoices = DB::table('product_invoices')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($invoices as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }
            
            if($unique_id){
                     $inc = DB::table('product_quotations')->where('unique_id',$unique_id)->first()->in_number;
            }else{
                 $inc = sprintf('%0'.$general->inc_digit.'d',++$count);
            }

            $invoice_no = $general->pinvoice_no;
            $invoice_no = str_replace('{dd}', date('d'), $invoice_no);
            $invoice_no = str_replace('{mm}', date('m'), $invoice_no);
            $invoice_no = str_replace('{yy}', date('y'), $invoice_no);
            $invoice_no = str_replace('{inc}', $inc, $invoice_no);
            return $invoice_no;
    }

    public static function get_last_subreceipt($unique_id)
    {
        return DB::table('sub_receipts')->where('unique_id',$unique_id)->orderBy('id','desc')->first();
    }

    public static function get_last_product_subreceipt($unique_id)
    {
        return DB::table('product_sub_receipts')->where('unique_id',$unique_id)->orderBy('id','desc')->first();
    }

    public static function get_new_receipt_no($unique_id)
    {
            $receipts = DB::table('receipts')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($receipts as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }
            
             if($unique_id){
                     $inc = DB::table('quotations')->where('unique_id',$unique_id)->first()->in_number;
            }else{
                 $inc = sprintf('%0'.$general->inc_digit.'d',++$count);
            }

            $receipt_no = $general->receipt_no;
            $receipt_no = str_replace('{dd}', date('d'), $receipt_no);
            $receipt_no = str_replace('{mm}', date('m'), $receipt_no);
            $receipt_no = str_replace('{yy}', date('y'), $receipt_no);
            $receipt_no = str_replace('{inc}', $inc, $receipt_no);
            return $receipt_no;
    }
     public static function get_new_product_receipt_no($unique_id)
    {
            $receipts = DB::table('product_receipts')->groupBy('unique_id')->get();
            $count = 0;
            $general = General::find(1);

            foreach($receipts as $row){
                
                if($general->is_yearly_increase){
                    if(date('y') == date('y',$row->date)) $count++; 
                }
                else{
                    if(date('y') == date('y',$row->date) && date('m') == date('m',$row->date)) $count++; 
                }
                
            }
            
            if($unique_id){
                     $inc = DB::table('product_quotations')->where('unique_id',$unique_id)->first()->in_number;
            }else{
                 $inc = sprintf('%0'.$general->inc_digit.'d',++$count);
            }

            $receipt_no = $general->preceipt_no;
            $receipt_no = str_replace('{dd}', date('d'), $receipt_no);
            $receipt_no = str_replace('{mm}', date('m'), $receipt_no);
            $receipt_no = str_replace('{yy}', date('y'), $receipt_no);
            $receipt_no = str_replace('{inc}', $inc, $receipt_no);
            return $receipt_no;
    }

}

?>