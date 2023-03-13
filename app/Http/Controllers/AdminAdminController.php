<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Models\Kontak;
	use App\Models\Chat;
	use App\Models\Device;
	use Carbon\Carbon;
	use App\Helpers\Whatsapp;


	class AdminAdminController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "kontaks";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"User Id","name"=>"user_id","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Device Id","name"=>"device_id","join"=>"devices,phone"];
			$this->col[] = ["label"=>"Phone","name"=>"phone"];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label" => "Status", "name" => "status", "callback_php" => '($row->status == 1) ? "Aktif" : "Tidak Aktif"'];

			$this->col[] = ["label" => "New Chat", "name" => "new_chat", "callback_php" => '($row->new_chat > 0) ? "<span class=\"badge badge-danger\">".$row->new_chat."</span>" : "<span class=\"badge badge-success\">".$row->new_chat."</span>"'];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
		$this->form = [];
		$this->form[] = ["label"=>"Device Id","name"=>"device_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"device,id"];
		$this->form[] = ["label"=>"Phone","name"=>"phone","type"=>"number","required"=>TRUE,"validation"=>"required|numeric","placeholder"=>"Anda hanya dapat memasukkan angka saja"];
		$this->form[] = ["label"=>"Name","name"=>"name","type"=>"text","required"=>TRUE,"validation"=>"required|string|min:3|max:70","placeholder"=>"Anda hanya dapat memasukkan huruf saja"];
		$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		$this->form[] = ["label"=>"Last Seen","name"=>"last_seen","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		$this->form[] = ["label"=>"Profile Pic","name"=>"profile_pic","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		$this->form[] = ["label"=>"Keterangan","name"=>"keterangan","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		$this->form[] = ["label"=>"New Chat","name"=>"new_chat","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
		$this->form[] = ["label"=>"User Id","name"=>"user_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"user,id"];

			# END FORM DO NOT REMOVE THIS LINE     

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
	        $this->script_js = NULL;


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
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
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
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :) 

		public function getDetail($id)
	{
		$kontak	= Kontak::where('id', $id)->first();
		$_chat	= Chat::where('to_phone', $kontak->phone)->first();
		$chat	= Chat::where('to_phone', $kontak->phone)->get();
		return view('chats.chat-record', compact('_chat', 'chat', 'kontak'));
	}

	public function sendReply(Request $request)
	{
		$device	= Device::where('id', 1)->first();
		if ($request->ajax()) {
			$phone		= $request->phone;
			$message	= $request->message;
			$chat 		= Whatsapp::send($phone, $message, null, $device->id);
			$chat		= json_decode($chat);
			if ($chat->data->status) {
				$_chat				= new Chat();
				$_chat->user_id		= $device->user_id;
				$_chat->device_id	= $device->id;
				$_chat->from_phone	= $device->phone;
				$_chat->to_phone	= $phone;
				$_chat->status		= 0;
				$_chat->type		= 'text';
				$_chat->msgid		= $chat->data->messageid;
				$_chat->chat_type	= 'out';
				$_chat->message		= $request->message;
				$_chat->send_at		= Carbon::now();
				$_chat->created_at	= Carbon::now();
				$_chat->updated_at	= Carbon::now();
				$_chat->save();
			}

			$data_chat	= Chat::where('to_phone', $request->phone)->get();
			return json_decode($data_chat);
		}
	}

	}