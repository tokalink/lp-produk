<?php

namespace App\Http\Controllers;

use App\Helpers\Whatsapp;
use App\Models\Device;
use App\Models\Message;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Rap2hpoutre\FastExcel\FastExcel;

class AdminMessagesController extends \crocodicstudio\crudbooster\controllers\CBController
{

	public function cbInit()
	{

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "id";
		$this->limit = "20";
		$this->orderby = "id,desc";
		$this->global_privilege = false;
		$this->button_table_action = true;
		$this->button_bulk_action = true;
		$this->button_action_style = "button_icon";
		$this->button_add = true;
		$this->button_edit = true;
		$this->button_delete = true;
		$this->button_detail = true;
		$this->button_show = true;
		$this->button_filter = true;
		$this->button_import = false;
		$this->button_export = false;
		$this->table = "messages";
		# END CONFIGURATION DO NOT REMOVE THIS LINE
		// menampilkan data terkirim, pending, gagal, semua data $this->countStatus
		

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label" => "User Id", "name" => "user_id", "join" => "cms_users,name"];
		$this->col[] = ["label" => "Device Id", "name" => "device_id", "join" => "devices,name"];
		$this->col[] = ["label" => "Phone", "name" => "phone"];
		$this->col[] = ["label" => "Message", "name" => "message"];
		$this->col[] = ["label" => "File", "name" => "file"];
		// status 0 draft, 1 pending, 2 sent, 3 failed
		$this->col[] = ["label" => "Status", "name" => "status", "callback" => function($row) {
			if ($row->status == 0) {
				return '<span class="label label-default">Draft</span>';
			} elseif ($row->status == 1) {
				return '<span class="label label-warning">Pending</span>';
			} elseif ($row->status == 2) {
				return '<span class="label label-success">Sent</span>';
			} elseif ($row->status == 3) {
				return '<span class="label label-danger">Failed</span>';
			}
		}];
		$this->col[] = ["label" => "Type", "name" => "type"];
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ['label' => 'Device Id', 'name' => 'device_id', 'type' => 'select2', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10', 'datatable' => 'devices,phone'];
		$this->form[] = ['label' => 'Phone', 'name' => 'phone', 'type' => 'text', 'validation' => 'required|numeric', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the number only'];
		$this->form[] = ['label' => 'Message', 'name' => 'message', 'type' => 'textarea', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
		$this->form[] = ['label' => 'File', 'name' => 'file', 'type' => 'upload', 'validation' => '', 'width' => 'col-sm-10'];
		# END FORM DO NOT REMOVE THIS LINE

		# OLD START FORM
		//$this->form = [];
		//$this->form[] = ["label"=>"User Id","name"=>"user_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"user,id"];
		//$this->form[] = ["label"=>"Device Id","name"=>"device_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"device,id"];
		//$this->form[] = ["label"=>"Phone","name"=>"phone","type"=>"number","required"=>TRUE,"validation"=>"required|numeric","placeholder"=>"You can only enter the number only"];
		//$this->form[] = ["label"=>"Message","name"=>"message","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"File","name"=>"file","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
		//$this->form[] = ["label"=>"Type","name"=>"type","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		//$this->form[] = ["label"=>"Send At","name"=>"send_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
		//$this->form[] = ["label"=>"Msgid","name"=>"msgid","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		# OLD END FORM

		/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
		$this->sub_module = array();


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
		$this->addaction = array();


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
		$this->button_selected = array();


		/* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
		$this->alert        = array();



		/* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
		$this->index_button = array();
		// button open modal upload file
		$this->index_button[] = ['label' => 'Upload File', 'url' => 'javascript:void(0)', 'icon' => 'fa fa-upload', 'color' => 'primary'];
		// button ubah status
		$this->index_button[] = ['label' => 'Proses Kirim', 'url' => url('admin/messages/send-draft'), 'icon' => 'fa fa-check', 'color' => 'success'];


		/* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
		$this->table_row_color = array();


		/*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
		$this->index_statistic = array();



		/*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
		$this->script_js = "
		// buka modal upload file upload-file
		$('#upload-file').click(function() {
			$('#uploadFileModal').modal('show');
		});
		";


		/*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
		function countStatus($status) {						
			if($status=='all'){
				$count = DB::table('messages')->count();
			}else{
				$count = DB::table('messages')->where('status','=', $status)->count();
			}
			return $count;
		}
		$devices = DB::table('devices')->get();
		$html_devices = '';
		foreach ($devices as $key => $value) {
			$html_devices .= "<option value='" . $value->id . "'>" . $value->phone .' - '. $value->name. "</option>";
		}
		$this->pre_index_html = "						
			<div class='row'>
				
				<div class='col-md-3'>
					<div class='small-box bg-yellow'>
						<div class='inner'>
							<h3>" . countStatus(1) . "</h3>
							<p>Draft & Pending</p>
						</div>
						<div class='icon'>
							<i class='fa fa-clock-o'></i>
						</div>
						<a href='" . url('admin/messages?status=1') . "' class='small-box-footer'>Lihat Data <i class='fa fa-arrow-circle-right'></i></a>
					</div>
				</div>
				<div class='col-md-3'>
					<div class='small-box bg-green'>
						<div class='inner'>
							<h3>" . countStatus(2) . "</h3>
							<p>Sent</p>
						</div>
						<div class='icon'>
							<i class='fa fa-check'></i>
						</div>
						<a href='" . url('admin/messages?status=2') . "' class='small-box-footer'>Lihat Data <i class='fa fa-arrow-circle-right'></i></a>
					</div>
				</div>
				<div class='col-md-3'>
					<div class='small-box bg-red'>
						<div class='inner'>
							<h3>" . countStatus(3) . "</h3>
							<p>Failed</p>
						</div>
						<div class='icon'>
							<i class='fa fa-times'></i>
						</div>
						<a href='" . url('admin/messages?status=3') . "' class='small-box-footer'>Lihat Data <i class='fa fa-arrow-circle-right'></i></a>
					</div>
				</div>
				<div class='col-md-3'>
					<div class='small-box bg-aqua'>
						<div class='inner'>
							<h3>" . countStatus('all') . "</h3>
							<p>All</p>
						</div>
						<div class='icon'>
							<i class='fa fa-list'></i>
						</div>
						<a href='" . url('admin/messages') . "' class='small-box-footer'>Lihat Data <i class='fa fa-arrow-circle-right'></i></a>
					</div>
				</div>
			</div>

			
			<div class='modal fade' id='uploadFileModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title' id='myModalLabel'>Upload Data</h4>
						</div>
						<div class='modal-body'>
							<form action='" . url('/messages/import') . "' method='post' enctype='multipart/form-data'>
								<input type='hidden' name='_token' value='" . csrf_token() . "'>								
								<div class='form-group'>
									<label for='device'>Device</label>
									<select name='device_id' id='device_id' class='form-control'>										
										".$html_devices."
									</select>
								</div>
								<div class='form-group'>
									<label for='file'>File</label>
									<input type='file' name='file' id='file' class='form-control'>
								</div>								
								<div class='form-group'>
									<label for='message'>Message</label>
									<textarea name='message' id='message' class='form-control' rows='5'></textarea>
								</div>
								<div class='form-group'>
									<button type='submit' class='btn btn-primary'>Upload</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			";



		/*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
		$this->post_index_html = null;



		/*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
		$this->load_js = array();



		/*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
		$this->style_css = NULL;



		/*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
		$this->load_css = array();
	}


	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	public function actionButtonSelected($id_selected, $button_name)
	{
		//Your code here

	}


	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	public function hook_query_index(&$query)
	{
		//Your code here
		// jika ada filter status
		if (request()->has('status')) {
			$query->where('messages.status', request()->get('status'));
		}
	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */
	public function hook_row_index($column_index, &$column_value)
	{
		//Your code here
	}

	/*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	public function hook_before_add(&$postdata)
	{
		//Your code here
		// add user_id
		$postdata['user_id'] = CRUDBooster::myId();
	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	public function hook_after_add($id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	public function hook_before_edit(&$postdata, $id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_edit($id)
	{
		//Your code here 

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_before_delete($id)
	{
		//Your code here

	}

	/* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	public function hook_after_delete($id)
	{
		//Your code here

	}



	//By the way, you can still create your own method in here... :) 
	// Buat fungsi upload dari file excel
	public function getImport()
	{
		// ambil data dari file excel dengan library fastexcel
		$collection = (new FastExcel)->import(request()->file('file'));
		// looping data
		foreach ($collection as $row) {
			// insert data ke database
			DB::table('messages')->insert([
				'user_id' => CRUDBooster::myId(),
				'device_id' => request()->get('device_id') ?? Device::first()->id,
				'message' => Whatsapp::ReplaceArray( $row, request()->get('message') ),
				'phone' => $row['phone'],
				'status' => 0
			]);
		}
		// redirect ke halaman index
		CRUDBooster::redirect(CRUDBooster::mainpath(), 'Data berhasil diupload', 'success');
	}

	// update draft ke 1
	public function sendDraft()
	{
		// ambil data dari database
		$messages = Message::where('status', 0)->update(['status' => 1]);
		// redirect ke halaman index
		CRUDBooster::redirect(CRUDBooster::mainpath(), 'Data berhasil diupdate', 'success');
	}

}
