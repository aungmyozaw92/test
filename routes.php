<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/	

	Route::get('/','SessionsController@index');
	Route::get('/admin','SessionsController@index');

	//login logout forget
	Route::post('admin/sessions/login','SessionsController@login');
	Route::get('admin/sessions/logout','SessionsController@logout');
	Route::get('admin/sessions/forget','SessionsController@forget');
	Route::get('admin/sessions/getRecover/{code}','SessionsController@getRecover');
	Route::post('admin/sessions/recover_password','SessionsController@recover_password');

	//admin valid auth route
	Route::group(array('before' => 'auth'), function(){

	//for error pages
	Route::get('admin/error','ErrorController@index');
	Route::get('admin/error/show/{page}','ErrorController@show');

	//for user mgmt	
	Route::get('admin/general/delete_stamp_sign/{name}','GeneralController@delete_stamp_sign');
	Route::post('admin/general/update_cpf_setting','GeneralController@update_cpf_setting');
	Route::controller('admin/general','GeneralController');
	Route::get('admin/main','MainController@index');
	Route::get('admin/main/back_up','MainController@back_up');
	Route::get('admin/main/restore_now/{file_name}','MainController@restore_now');

	Route::get('admin/main/get_download/{filename}', function($filename)
	{
	    // Check if file exists in app/storage/file folder
	    $file_path = storage_path().'/backup/'.$filename;
	    if (file_exists($file_path))
	    {
	        // Send Download
	        return Response::download($file_path, $filename, ['content-type' => 'application/x-sql']);
	    }
	    else
	    {
	        // Error
	        exit('Requested file does not exist on our server!');
	    }
	})->where('filename', '[A-Za-z0-9\-\_\.]+');


	Route::get('admin/main/back_up_now','MainController@back_up_now');
	Route::resource('admin/user','UserController');
	Route::resource('admin/module','ModuleController');
	Route::resource('admin/role','RoleController');
	Route::post('admin/role/get_role/{id}','RoleController@get_role');
	Route::resource('admin/permission','PermissionController');
	Route::get('admin/permission/delete/{id}', 'PermissionController@delete');
	Route::post('admin/permission/add_sub_permission','PermissionController@add_sub_permission');
	Route::post('admin/permission/getPermissionByRole/{id}/true','PermissionController@getPermissionByRole');

	//for staff mgmt
	Route::get('admin/staff/change_status/{id}/{status}','StaffController@change_status');
	Route::get('admin/staff/get_staffs/{id}','StaffController@get_staffs');
	Route::get('admin/staff/remove_attribute/{id}/{attr}','StaffController@remove_attribute');
	Route::post('admin/staff/update_staff_info','StaffController@update_staff_info');
	Route::post('admin/staff/add_new_attribute','StaffController@add_new_attribute');
	Route::post('admin/staff/upload/{id}/{file}','StaffController@upload');
	Route::get('admin/staff/payroll','StaffController@payroll');
	Route::get('admin/staff/pay/{id}/{month}','StaffController@pay');
	Route::post('admin/staff/save_payment/{id}','StaffController@save_payment');
	Route::resource('admin/staff','StaffController');

	//for salary payment mgmt
	Route::get('admin/salary_payment/salary_record','Salary_paymentController@salary_record');
	Route::get('admin/salary_payment/salary_list/{date}','Salary_paymentController@salary_list');
	Route::resource('admin/salary_payment','Salary_paymentController');

	//for cpf mgmt
	Route::get('admin/cpf/create/{month}','CpfController@create');
	Route::resource('admin/cpf','CpfController');
	
	//for client mgmt
	Route::get('admin/client/get_client_json/{id}','ClientController@get_client_json');
	Route::resource('admin/client','ClientController');

	//for supplier mgmt
	Route::get('admin/supplier/get_supplier_json/{id}','SupplierController@get_supplier_json');
	Route::resource('admin/supplier','SupplierController');

	//for service mgmt
	Route::resource('admin/service','ServiceController');
	Route::post('admin/service/get_service_json/{id}','ServiceController@get_service_json');

	//for category mgmt
	Route::post('admin/category/search_category_amount_avg','CategoryController@search_category_amount_avg');
	Route::resource('admin/category','CategoryController');
	Route::get('admin/subcategory/get_change_subcategory/{id}/{unique_id}','SubcategoryController@get_change_subcategory');
	Route::get('admin/subcategory/get_subcategory/{id}','SubcategoryController@get_subcategory');
	Route::resource('admin/subcategory','SubcategoryController');

	//for product mgmt
	Route::put('admin/product/upload_images/{id}','ProductController@upload_images');
	Route::post('admin/product/delete_upload_images/{id}','ProductController@delete_upload_images');
	Route::get('admin/product/upload_images/{id}','ProductController@upload_images');
	Route::get('admin/product/product_image/{id}','ProductController@product_image');
	Route::post('admin/product/get_product_json/{id}','ProductController@get_product_json');
	Route::get('admin/product/inventory','ProductController@inventory');
	Route::get('admin/product/destroy_product_name/{name}','ProductController@destroy_product_name');
	Route::resource('admin/product','ProductController');

	//for quotation mgmt
	Route::get('admin/quotation/remove_cart/{id}','QuotationController@remove_cart');
	Route::get('admin/quotation/empty_cart','QuotationController@empty_cart');
	Route::get('admin/quotation/empty_cart_error','QuotationController@empty_cart_error');
	Route::get('admin/quotation/cancel/{id}','QuotationController@cancel');
	Route::get('admin/quotation/restore/{id}','QuotationController@restore');
	Route::post('admin/quotation/change_due/{id}','QuotationController@change_due');
	Route::get('admin/quotation/send_quotation/{id}','QuotationController@send_quotation');
	Route::get('admin/quotation/change_complete/{id}','QuotationController@change_complete');
	Route::get('admin/quotation/quotation_print/{id}','QuotationController@quotation_print');
	Route::resource('admin/quotation','QuotationController');
	Route::post('admin/quotation/add_to_cart','QuotationController@add_to_cart');

	//for productquotation mgmt
	Route::get('admin/productquotation/remove_cart/{id}','ProductQuotationController@remove_cart');
	Route::get('admin/productquotation/empty_cart','ProductQuotationController@empty_cart');
	Route::get('admin/productquotation/empty_cart_error','ProductQuotationController@empty_cart_error');
	Route::get('admin/productquotation/cancel/{id}','ProductQuotationController@cancel');
	Route::get('admin/productquotation/restore/{id}','ProductQuotationController@restore');
	Route::post('admin/productquotation/change_due/{id}','ProductQuotationController@change_due');
	Route::get('admin/productquotation/send_quotation/{id}','ProductQuotationController@send_quotation');
	Route::get('admin/productquotation/change_complete/{id}','ProductQuotationController@change_complete');
	Route::get('admin/productquotation/quotation_print/{id}','ProductQuotationController@quotation_print');
	Route::resource('admin/productquotation','ProductQuotationController');
	Route::post('admin/productquotation/add_to_cart','ProductQuotationController@add_to_cart');

	//for order mgmt
	Route::get('admin/order/remove_cart/{id}','OrderController@remove_cart');
	Route::get('admin/order/empty_cart','OrderController@empty_cart');
	Route::get('admin/order/empty_cart_error','OrderController@empty_cart_error');
	Route::get('admin/order/cancel/{id}','OrderController@cancel');
	Route::get('admin/order/restore/{id}','OrderController@restore');
	Route::post('admin/order/change_due/{id}','OrderController@change_due');
	Route::get('admin/order/send_order/{id}','OrderController@send_order');
	Route::get('admin/order/change_complete/{id}','OrderController@change_complete');
	Route::get('admin/order/order_print/{id}','OrderController@order_print');
	Route::resource('admin/order','OrderController');
	Route::post('admin/order/add_to_cart','OrderController@add_to_cart');

	//for productorder mgmt
	Route::get('admin/productorder/remove_cart/{id}','ProductOrderController@remove_cart');
	Route::get('admin/productorder/empty_cart','ProductOrderController@empty_cart');
	Route::get('admin/productorder/empty_cart_error','ProductOrderController@empty_cart_error');
	Route::get('admin/productorder/cancel/{id}','ProductOrderController@cancel');
	Route::get('admin/productorder/restore/{id}','ProductOrderController@restore');
	Route::post('admin/productorder/change_due/{id}','ProductOrderController@change_due');
	Route::get('admin/productorder/send_order/{id}','ProductOrderController@send_order');
	Route::get('admin/productorder/change_complete/{id}','ProductOrderController@change_complete');
	Route::get('admin/productorder/order_print/{id}','ProductOrderController@order_print');
	Route::resource('admin/productorder','ProductOrderController');
	Route::post('admin/productorder/add_to_cart','ProductOrderController@add_to_cart');

	//for invoice mgmt
	Route::get('admin/invoice/remove_cart/{id}','InvoiceController@remove_cart');
	Route::get('admin/invoice/empty_cart','InvoiceController@empty_cart');
	Route::get('admin/invoice/empty_cart_error','InvoiceController@empty_cart_error');
	Route::get('admin/invoice/cancel/{id}','InvoiceController@cancel');
	Route::get('admin/invoice/restore/{id}','InvoiceController@restore');
	Route::post('admin/invoice/change_due/{id}','InvoiceController@change_due');
	Route::get('admin/invoice/send_invoice/{id}','InvoiceController@send_invoice');
	Route::get('admin/invoice/change_complete/{id}','InvoiceController@change_complete');
	Route::get('admin/invoice/invoice_print/{id}','InvoiceController@invoice_print');
	Route::get('admin/invoice/get_invoice_json/{id}','InvoiceController@get_invoice_json');
	Route::resource('admin/invoice','InvoiceController');
	Route::post('admin/invoice/add_to_cart','InvoiceController@add_to_cart');

	//for productinvoice/ mgmt
	Route::get('admin/productinvoice/remove_cart/{id}','ProductInvoiceController@remove_cart');
	Route::get('admin/productinvoice/empty_cart','ProductInvoiceController@empty_cart');
	Route::get('admin/productinvoice/empty_cart_error','ProductInvoiceController@empty_cart_error');
	Route::get('admin/productinvoice/cancel/{id}','ProductInvoiceController@cancel');
	Route::get('admin/productinvoice/restore/{id}','ProductInvoiceController@restore');
	Route::post('admin/productinvoice/change_due/{id}','ProductInvoiceController@change_due');
	Route::get('admin/productinvoice/send_invoice/{id}','ProductInvoiceController@send_invoice');
	Route::get('admin/productinvoice/change_complete/{id}','ProductInvoiceController@change_complete');
	Route::get('admin/productinvoice/get_invoice_json/{id}','ProductInvoiceController@get_invoice_json');
	Route::get('admin/productinvoice/invoice_print/{id}','ProductInvoiceController@invoice_print');
	Route::resource('admin/productinvoice','ProductInvoiceController');
	Route::post('admin/productinvoice/add_to_cart','ProductInvoiceController@add_to_cart');

	//for receipt mgmt
	Route::post('admin/receipt/change_due/{id}','ReceiptController@change_due');
	Route::get('admin/receipt/send_receipt/{id}','ReceiptController@send_receipt');
	Route::get('admin/receipt/confirm/{id}','ReceiptController@confirm');
	Route::get('admin/receipt/receipt_print/{id}','ReceiptController@receipt_print');
	Route::get('admin/receipt/send_now/{id}','ReceiptController@send_now');
	Route::resource('admin/receipt','ReceiptController');
	Route::get('admin/receipt/create/{id}','ReceiptController@create');

	//for productreceipt/ mgmt
	Route::post('admin/productreceipt/change_due/{id}','ProductReceiptController@change_due');
	Route::get('admin/productreceipt/send_receipt/{id}','ProductReceiptController@send_receipt');
	Route::get('admin/productreceipt/confirm/{id}','ProductReceiptController@confirm');
	Route::get('admin/productreceipt/receipt_print/{id}','ProductReceiptController@receipt_print');
	Route::get('admin/productreceipt/send_now/{id}','ProductReceiptController@send_now');
	Route::resource('admin/productreceipt','ProductReceiptController');
	Route::get('admin/productreceipt/create/{id}','ProductReceiptController@create');

	//for product payment mgmt
	Route::resource('admin/payment','PaymentController');
	
	//for overhead expense mgmt
	Route::resource('admin/expense','ExpenseController');

	//for profit and loss mgmt
	Route::controller('admin/profitandloss','ProfitandlossController');

	});