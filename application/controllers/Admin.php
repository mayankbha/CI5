<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("admin_model");
		$this->load->model("user_model");
		$this->load->model("home_model");

		if (!$this->user->loggedin) $this->template->error(lang("error_1"));
		if(!$this->common->has_permissions(array("admin", "admin_settings",
			"admin_members", "admin_payment"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
	}


	public function index() 
	{	
		$this->template->loadData("activeLink", 
			array("admin" => array("general" => 1)));
		$new_members = $this->user_model->get_new_members(5);
		$months = array();

		// Graph Data
		$current_month = date("n");
		$current_year = date("Y");

		// First month
		for($i=6;$i>=0;$i--) {
			// Get month in the past
			$new_month = $current_month - $i;
			// If month less than 1 we need to get last years months
			if($new_month < 1) {
				$new_month = 12 + $new_month;
				$new_year = $current_year - 1;
			} else {
				$new_year = $current_year;
			}
			// Get month name using mktime
			$timestamp = mktime(0,0,0,$new_month,1,$new_year);
			$count = $this->user_model
				->get_registered_users_date($new_month, $new_year);
			$months[] = array(
				"date" => date("F", $timestamp),
				"count" => $count
				);
		}


		$javascript = 'var data_graph = {
					    labels: [';
		    foreach($months as $d) {
		    	$javascript .= '"'.$d['date'].'",';
		    }
		    $javascript.='],
		    datasets: [
		        {
		            label: "'.lang("ctn_1108").'",
		            fillColor: "rgba(220,220,220,0.2)",
		            strokeColor: "rgba(220,220,220,1)",
		            pointColor: "rgba(220,220,220,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(220,220,220,1)",
		            data: [';
		            foreach($months as $d) {
				    	$javascript .= $d['count'].',';
				    }
		            $javascript.=']
		        }
		    ]
		};';


		$stats = $this->home_model->get_home_stats();
		if($stats->num_rows() == 0) {
			$this->template->error(lang("error_24"));
		} else {
			$stats = $stats->row();
			if($stats->timestamp < time() - $this->settings->info->cache_time) {
				$stats = $this->get_fresh_results($stats);
				// Update Row
				$this->home_model->update_home_stats($stats);
			}
		}


		$javascript .= ' var social_data = {
				labels:[
					"Google","Local","Facebook","Twitter"
				],
				datasets:[
				{
					data:['.$stats->google_members.','.($stats->total_members - ($stats->google_members +
		         $stats->facebook_members + $stats->twitter_members)).','.$stats->facebook_members.','.$stats->twitter_members.'],
					backgroundColor: [
		                "#F7464A",
		                "#39bc2c",
		                "#2956BF",
		                "#5BACD4"
		            ],
		            hoverBackgroundColor: [
		                "#FF5A5E",
		                "#5AD3D1",
		                "#FFC870",
		                "#7db864"
		            ]
		        }
				]
		};';


		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>
			<script type="text/javascript">'.$javascript.'</script>
			<script type="text/javascript" src="'
			.base_url().'scripts/custom/home.js" /></script>'
		);

		$online_count = $this->user_model->get_online_count();

		$this->template->loadContent("admin/index.php", array(
			"new_members" => $new_members,
			"stats" => $stats,
			"online_count" => $online_count
			)
		);
	}

	private function get_fresh_results($stats) 
	{
		$data = new STDclass;

		$data->google_members = $this->user_model->get_oauth_count("google");
		$data->facebook_members = $this->user_model->get_oauth_count("facebook");
		$data->twitter_members = $this->user_model->get_oauth_count("twitter");
		$data->total_members = $this->user_model->get_total_members_count();
		$data->new_members = $this->user_model->get_new_today_count();
		$data->active_today = $this->user_model->get_active_today_count();

		return $data;
	}

	public function chat_settings() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("chat" => 1)));

		$this->template->loadContent("admin/chat_settings.php", array(
			)
		);
	}

	public function chat_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}

		$enable_chat = intval($this->input->post("enable_chat"));
		$chat_update = intval($this->input->post("chat_update"));

		$this->admin_model->updateSettings(
			array(
			"enable_chat" => $enable_chat,
			"chat_update" => $chat_update
			)
		);
			
		$this->session->set_flashdata("globalmsg", lang("success_125"));
		redirect(site_url("admin/chat_settings"));
	}

	public function custom_fields() 
	{	
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("custom_fields" => 1)));
		$fields = $this->admin_model->get_custom_fields(array());
		$this->template->loadContent("admin/custom_fields.php", array(
			"fields" => $fields
			)
		);

	}

	public function add_custom_field_pro() 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$options = $this->common->nohtml($this->input->post("options"));
		$required = intval($this->input->post("required"));
		$edit = intval($this->input->post("edit"));
		$profile = intval($this->input->post("profile"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));
		$register = intval($this->input->post("register"));

		$leads = intval($this->input->post("leads"));

		if(empty($name)) {
			$this->template->error(lang("error_246"));
		}

		if($type < 0 || $type > 4) {
			$this->template->error(lang("error_247"));
		}

		// Add
		$this->admin_model->add_custom_field(array(
			"name" => $name,
			"type" => $type,
			"options" => $options,
			"required" => $required,
			"edit" => $edit,
			"profile" => $profile,
			"help_text" => $help_text,
			"register" => $register,
			"leads" => $leads
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_126"));
		redirect(site_url("admin/custom_fields"));
	}

	public function edit_custom_field($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("custom_fields" => 1)));
		$id = intval($id);
		$field = $this->admin_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_248"));
		}

		$field = $field->row();
		$this->template->loadContent("admin/edit_custom_field.php", array(
			"field" => $field
			)
		);
	}

	public function edit_custom_field_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$field = $this->admin_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_248"));
		}

		$field = $field->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$options = $this->common->nohtml($this->input->post("options"));
		$required = intval($this->input->post("required"));
		$edit = intval($this->input->post("edit"));
		$profile = intval($this->input->post("profile"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));
		$register = intval($this->input->post("register"));
		$leads = intval($this->input->post("leads"));

		if(empty($name)) {
			$this->template->error(lang("error_246"));
		}

		if($type < 0 || $type > 4) {
			$this->template->error(lang("error_247"));
		}

		// Add
		$this->admin_model->update_custom_field($id, array(
			"name" => $name,
			"type" => $type,
			"options" => $options,
			"required" => $required,
			"edit" => $edit,
			"profile" => $profile,
			"help_text" => $help_text,
			"register" => $register,
			"leads" => $leads
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_127"));
		redirect(site_url("admin/custom_fields"));
	}

	public function delete_custom_field($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin",
			"admin_members"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$field = $this->admin_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_248"));
		}

		$this->admin_model->delete_custom_field($id);
		$this->session->set_flashdata("globalmsg", lang("success_128"));
		redirect(site_url("admin/custom_fields"));
	}

	public function tools() 
	{
		if(!$this->common->has_permissions(array("admin"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}

		$this->template->loadData("activeLink", 
			array("admin" => array("tools" => 1)));
		$this->template->loadContent("admin/tools.php", array(
			)
		);
	}

	public function tool_email_debug() 
	{
		if(!$this->common->has_permissions(array("admin"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}

		$debug = "";
		if(isset($_POST['email'])) {
			$email = $this->common->nohtml($this->input->post("email"));

			$debug = $this->common->send_email("Debug Email Test", "<p>This is a test email used for debugging issues with email server</p>", $email,array(), 1);

		}

		$this->template->loadData("activeLink", 
			array("admin" => array("tools" => 1)));
		$this->template->loadContent("admin/tool_email_debug.php", array(
			"debug" => $debug
			)
		);
	}

	public function tool_noti_sync() 
	{
		if(!$this->common->has_permissions(array("admin"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$debug = "";

		if(isset($_POST['s'])) {
			// Get all users
			$users = $this->db->get("users");
			$debug = "Preparing to sync users ...<br />";
			foreach($users->result() as $r) {
				$notifications = $this->db
					->select("COUNT(*) as num")
					->where("userid", $r->ID)
					->where("status", 0)
					->get("user_notifications");
				$rr = $notifications->row();
				if(isset($rr->num)) {
					$notifications = $rr->num;
				} else {
					$notifications = 0;
				}

				$this->db->where("ID", $r->ID)->update("users", array("noti_count" => $notifications));
				$debug .= $r->username . " was synced...($notifications)<br />";
			}
			$debug .= "Users have been synced.";
		}

		$this->template->loadData("activeLink", 
			array("admin" => array("tools" => 1)));
		$this->template->loadContent("admin/tool_noti_sync.php", array(
			"debug" => $debug
			)
		);
	}

	public function date_settings() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("date" => 1)));

		$this->template->loadContent("admin/date_settings.php", array(
			)
		);
	}

	public function date_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$date_format = $this->common->nohtml($this->input->post("date_format"));
		$date_picker_format = $this->common->nohtml($this->input->post("date_picker_format"));
		$calendar_picker_format = $this->common->nohtml($this->input->post("calendar_picker_format"));


		$this->admin_model->updateSettings(
			array(
			"date_format" => $date_format,
			"date_picker_format" => $date_picker_format,
			"calendar_picker_format" => $calendar_picker_format
			)
		);
			
		$this->session->set_flashdata("globalmsg", lang("success_117"));
		redirect(site_url("admin/date_settings"));
	}

	public function tickets() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("tickets" => 1)));

		$this->template->loadContent("admin/tickets.php", array(
			)
		);
	}

	public function ticket_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$protocol = intval($this->input->post("protocol"));
		$protocol_path = $this->common->nohtml($this->input->post("protocol_path"));
		$protocol_email = $this->common->nohtml($this->input->post("protocol_email"));
		$protocol_pass = $this->common->nohtml($this->input->post("protocol_password"));
		$ticket_title = $this->common->nohtml($this->input->post("ticket_title"));
		$protocol_ssl = intval($this->input->post("protocol_ssl"));

		$imap_ticket_string = $this->common->nohtml($this->input->post("imap_ticket_string"));
		$imap_reply_string = $this->common->nohtml($this->input->post("imap_reply_string"));

		if(empty($ticket_title)) {
			$this->template->error(lang("error_68"));
		}

		$this->admin_model->updateSettings(
			array(
			"protocol" => $protocol,
			"protocol_path" => $protocol_path,
			"protocol_email" => $protocol_email,
			"protocol_password" => $protocol_pass,
			"ticket_title" => $ticket_title,
			"protocol_ssl" => $protocol_ssl,
			"imap_ticket_string" => $imap_ticket_string,
			"imap_reply_string" => $imap_reply_string
			)
		);
			
		$this->session->set_flashdata("globalmsg", lang("success_33"));
		redirect(site_url("admin/tickets"));
	}

	public function invoice() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->load->model("invoices_model");
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_invoice" => 1)));

		$invoice = $this->invoices_model->get_invoice_settings();
		$invoice = $invoice->row();

		$this->template->loadContent("admin/invoice.php", array(
			"invoice" => $invoice
			)
		);
	}

	public function invoice_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->load->model("invoices_model");
		$invoice = $this->invoices_model->get_invoice_settings();
		$invoice = $invoice->row();


		$enable_paypal = intval($this->input->post("enable_paypal"));
		$enable_stripe = intval($this->input->post("enable_stripe"));
		$enable_checkout2 = intval($this->input->post("enable_checkout2"));

		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => $this->settings->info->file_types,
		       "max_size" => 2000,
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21") 
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= $invoice->image;
		}

		$this->invoices_model->update_settings(array(
			"image" => $image,
			"enable_paypal" => $enable_paypal,
			"enable_stripe" => $enable_stripe,
			"enable_checkout2" => $enable_checkout2,
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_34"));
		redirect(site_url("admin/invoice"));
	}

	public function currencies() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_currency" => 1)));

		$currencies = $this->admin_model->get_currencies();

		$this->template->loadContent("admin/currencies.php", array(
			"currencies" => $currencies
			)
		);
	}

	public function add_currency_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$symbol = $this->common->nohtml($this->input->post("symbol"));
		$code = $this->common->nohtml($this->input->post("code"));

		if(empty($name)) {
			$this->template->error(lang("error_69"));
		}

		$this->admin_model->add_currency(array(
			"name" => $name,
			"symbol" => $symbol,
			"code" => $code
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_35"));
		redirect(site_url("admin/currencies"));
	}

	public function delete_currency($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$currency = $this->admin_model->get_currency($id);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_70"));
		}

		$this->admin_model->delete_currency($id);
		$this->session->set_flashdata("globalmsg", lang("success_36"));
		redirect(site_url("admin/currencies"));
	}

	public function edit_currency($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$currency = $this->admin_model->get_currency($id);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_70"));
		}
		$currency = $currency->row();

		$this->template->loadContent("admin/edit_currency.php", array(
			"currency" => $currency
			)
		);
	}

	public function edit_currency_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$currency = $this->admin_model->get_currency($id);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_70"));
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$symbol = $this->common->nohtml($this->input->post("symbol"));
		$code = $this->common->nohtml($this->input->post("code"));

		if(empty($name)) {
			$this->template->error(lang("error_69"));
		}

		$this->admin_model->update_currency($id, array(
			"name" => $name,
			"symbol" => $symbol,
			"code" => $code
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_37"));
		redirect(site_url("admin/currencies"));
	}

	public function calendar_settings() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("calendar_settings" => 1)));

		$this->template->loadContent("admin/calendar_settings.php", array(
			)
		);
	}

	public function calendar_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$type = intval($this->input->post("type"));
		$google_calendar_id = $this->common->nohtml(
				$this->input->post("google_calendar_id"));
		$google_client_id = $this->common->nohtml(
				$this->input->post("google_client_id"));
		$google_client_secret = $this->common->nohtml(
				$this->input->post("google_client_secret"));
		$calendar_timezone = $this->common->nohtml(
				$this->input->post("calendar_timezone"));
		$google_calendar_api_key = $this->common->nohtml(
				$this->input->post("google_calendar_api_key"));

		$this->admin_model->updateSettings(
			array(
				"calendar_type" => $type,
				"google_calendar_id" => $google_calendar_id,
				"calendar_timezone" => $calendar_timezone,
				"google_client_id" => $google_client_id,
				"google_client_secret" => $google_client_secret,
				"google_calendar_api_key" => $google_calendar_api_key
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/calendar_settings"));
	}

	public function premium_users($page=0) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("premium_users" => 1)));


		$page = intval($page);
		$users = $this->admin_model->get_premium_users($page);

		$this->load->library('pagination');
		$config['base_url'] = site_url("admin/premium_users");
		$config['total_rows'] = $this->admin_model
			->get_total_premium_users_count();
		$config['per_page'] = 20;
		$config['uri_segment'] = 2;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 

		$this->template->loadContent("admin/premium_users.php", array(
			"members" => $users
			)
		);
	}

	public function user_roles() 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$this->template->loadData("activeLink", 
			array("admin" => array("user_roles" => 1)));
		$roles = $this->admin_model->get_user_roles();

		$permissions = $this->get_default_permissions();

		$this->template->loadContent("admin/user_roles.php", array(
			"roles" => $roles,
			"permissions" => $permissions
			)
		);
	}

	public function add_user_role_pro() 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));

		$name = $this->common->nohtml($this->input->post("name"));
		if (empty($name)) $this->template->error(lang("error_64"));

		$permissions = $this->get_default_permissions();

		$user_roles = $this->input->post("user_roles");
		foreach($user_roles as $ur) {
			$ur = intval($ur);
			foreach($permissions as $key => $p) {
				if($p['id'] == $ur) {
					$permissions[$key]['selected'] = 1;
				}
			}
		}

		$data = array();
		foreach($permissions as $k=>$v) {
			$data[$k] = $v['selected'];
		}
		$data['name'] = $name;

		$this->admin_model->add_user_role(
			$data
		);

		$this->session->set_flashdata("globalmsg", lang("success_30"));
		redirect(site_url("admin/user_roles"));
	}

	public function edit_user_role($id) 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$id = intval($id);
		$role = $this->admin_model->get_user_role($id);
		if ($role->num_rows() == 0) $this->template->error(lang("error_65"));

		$role = $role->row();
		$this->template->loadData("activeLink", 
			array("admin" => array("user_roles" => 1)));

		$permissions = $this->get_default_permissions();
		foreach($permissions as $k=>$v) {
			if($role->{$k}) {
				$permissions[$k]['selected'] = 1;
			}
		}

		$this->template->loadContent("admin/edit_user_role.php", array(
			"role" => $role,
			"permissions" => $permissions
			)
		);
	}

	private function get_default_permissions() 
	{
		$urp = $this->admin_model->get_user_role_permissions();
		$permissions = array();
		foreach($urp->result() as $r) {
			$permissions[$r->hook] = array(
				"name" => lang($r->name),
				"desc" => lang($r->description),
				"id" => $r->ID,
				"class" => $r->classname,
				"selected" => 0
			);
		}
		return $permissions;
	}

	public function edit_user_role_pro($id) 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		$id = intval($id);
		$role = $this->admin_model->get_user_role($id);
		if ($role->num_rows() == 0) $this->template->error(lang("error_65"));

		$name = $this->common->nohtml($this->input->post("name"));
		if (empty($name)) $this->template->error(lang("error_64"));

		$permissions = $this->get_default_permissions();

		$user_roles = $this->input->post("user_roles");
		foreach($user_roles as $ur) {
			$ur = intval($ur);
			foreach($permissions as $key => $p) {
				if($p['id'] == $ur) {
					$permissions[$key]['selected'] = 1;
				}
			}
		}

		$data = array();
		foreach($permissions as $k=>$v) {
			$data[$k] = $v['selected'];
		}
		$data['name'] = $name;


		$this->admin_model->update_user_role($id, 
			$data
		);
		$this->session->set_flashdata("globalmsg", lang("success_31"));
		redirect(site_url("admin/user_roles"));
	}

	public function delete_user_role($id, $hash) 
	{
		if(!$this->user->info->admin) $this->template->error(lang("error_2"));
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_role($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_65"));

		$this->admin_model->delete_user_role($id);
		// Delete all user groups from member

		$this->session->set_flashdata("globalmsg", lang("success_32"));
		redirect(site_url("admin/user_roles"));
	}

	public function payment_logs($page = 0) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}

		$page = intval($page);
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_logs" => 1)));

		$logs = $this->admin_model->get_payment_logs($page);

		$this->load->library('pagination');
		$config['base_url'] = site_url("admin/payment_logs");
		$config['total_rows'] = $this->admin_model
			->get_total_payment_logs_count();
		$config['per_page'] = 20;
		$config['uri_segment'] = 2;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 

		$this->template->loadContent("admin/payment_logs.php", array(
			"logs" => $logs
			)
		);
	}

	public function payment_plans() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_plans" => 1)));
		$plans = $this->admin_model->get_payment_plans();

		$this->template->loadContent("admin/payment_plans.php", array(
			"plans" => $plans
			)
		);
	}

	public function add_payment_plan() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$cost = abs($this->input->post("cost"));
		$color = $this->common->nohtml($this->input->post("color"));
		$fontcolor = $this->common->nohtml($this->input->post("fontcolor"));
		$days = intval($this->input->post("days"));

		$this->admin_model->add_payment_plan(array(
			"name" => $name,
			"cost" => $cost,
			"hexcolor" => $color,
			"days" => $days,
			"description" => $desc,
			"fontcolor" => $fontcolor
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_25"));
		redirect(site_url("admin/payment_plans"));
	}

	public function edit_payment_plan($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_plans" => 1)));
		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$this->template->loadContent("admin/edit_payment_plan.php", array(
			"plan" => $plan->row()
			)
		);
	}

	public function edit_payment_plan_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->common->nohtml($this->input->post("description"));
		$cost = abs($this->input->post("cost"));
		$color = $this->common->nohtml($this->input->post("color"));
		$fontcolor = $this->common->nohtml($this->input->post("fontcolor"));
		$days = intval($this->input->post("days"));

		$this->admin_model->update_payment_plan($id, array(
			"name" => $name,
			"cost" => $cost,
			"hexcolor" => $color,
			"days" => $days,
			"description" => $desc,
			"fontcolor" => $fontcolor
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_26"));
		redirect(site_url("admin/payment_plans"));
	}

	public function delete_payment_plan($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$plan = $this->admin_model->get_payment_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));

		$this->admin_model->delete_payment_plan($id);
		$this->session->set_flashdata("globalmsg", lang("success_27"));
		redirect(site_url("admin/payment_plans"));
	}

	public function payment_settings() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("payment_settings" => 1)));
		$this->template->loadContent("admin/payment_settings.php", array(
			)
		);
	}

	public function payment_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_payment"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$paypal_email = $this->common->nohtml(
			$this->input->post("paypal_email"));
		$paypal_currency = $this->common->nohtml(
			$this->input->post("paypal_currency"));
		$payment_enabled = intval($this->input->post("payment_enabled"));
		$payment_symbol = $this->common->nohtml(
			$this->input->post("payment_symbol"));
		$global_premium = intval($this->input->post("global_premium"));

		// update
		$this->admin_model->updateSettings(
			array(
				"paypal_email" => $paypal_email,
				"paypal_currency" => $paypal_currency,
				"payment_enabled" => $payment_enabled,
				"payment_symbol" => $payment_symbol,
				"global_premium" => $global_premium
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_24"));
		redirect(site_url("admin/payment_settings"));

	}

	public function email_members() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_members" => 1)));
		$groups = $this->admin_model->get_user_groups();
		$this->template->loadContent("admin/email_members.php", array(
			"groups" => $groups
			)
		);
	}

	public function email_members_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$usernames = $this->common->nohtml($this->input->post("usernames"));
		$groupid = intval($this->input->post("groupid"));
		$title = $this->common->nohtml($this->input->post("title"));
		$message = $this->lib_filter->go($this->input->post("message"));

		if ($groupid == -1) {
			// All members
			$users = array();
			$usersc = $this->admin_model->get_all_users();
			foreach ($usersc->result() as $r) {
				$users[] = $r;
			}
		} else {
			$usernames = explode(",", $usernames);

			$users = array();
			foreach ($usernames as $username) {
				if (empty($username)) continue;
				$user = $this->user_model->get_user_by_username($username);
				if ($user->num_rows() == 0) {
					$this->template->error(lang("error_3") . $username);
				}
				$users[] = $user->row();
			}

			if ($groupid > 0) {
				$group = $this->admin_model->get_user_group($groupid);
				if ($group->num_rows() == 0) {
					$this->template->error(lang("error_4"));
				}

				$users_g = $this->admin_model->get_all_group_users($groupid);
				$cursers = $users;

				foreach ($users_g->result() as $r) {
					// Check for duplicates
					$skip = false;
					foreach ($cusers as $a) {
						if($a->userid == $r->userid) $skip = true;
					}
					if (!$skip) {
						$users[] = $r;
					}
				}
			}

		}

		foreach ($users as $r) {
			$this->common->send_email($title, $message, $r->email);
		}

		$this->session->set_flashdata("globalmsg", lang("success_1"));
		redirect(site_url("admin/email_members"));
	}

	public function user_groups() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("user_groups" => 1)));
		$groups = $this->admin_model->get_user_groups();
		$this->template->loadContent("admin/groups.php", array(
			"groups" => $groups
			)
		);
	}

	public function add_group_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$default = intval($this->input->post("default_group"));
		if (empty($name)) $this->template->error(lang("error_5"));


		$this->admin_model->add_group(
			array(
				"name" =>$name,
				"default" => $default,
				)
			);
		$this->session->set_flashdata("globalmsg", lang("success_2"));
		redirect(site_url("admin/user_groups"));
	}

	public function edit_group($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$this->template->loadData("activeLink", 
			array("admin" => array("user_groups" => 1)));

		$this->template->loadContent("admin/edit_group.php", array(
			"group" => $group->row()
			)
		);
	}

	public function edit_group_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$name = $this->common->nohtml($this->input->post("name"));
		$default = intval($this->input->post("default_group"));
		if (empty($name)) $this->template->error(lang("error_5"));

		$this->admin_model->update_group($id, 
			array(
				"name" =>$name,
				"default" => $default
				)
		);
		$this->session->set_flashdata("globalmsg", lang("success_3"));
		redirect(site_url("admin/user_groups"));
	}

	public function delete_group($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$this->admin_model->delete_group($id);
		// Delete all user groups from member
		$this->admin_model->delete_users_from_group($id); 

		$this->session->set_flashdata("globalmsg", lang("success_4"));
		redirect(site_url("admin/user_groups"));
	}

	public function view_group($id, $page=0) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("user_groups" => 1)));
		$id = intval($id);
		$page = intval($page);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$users = $this->admin_model->get_users_from_groups($id, $page);

		$this->load->library('pagination');
		$config['base_url'] = site_url("admin/view_group/" . $id);
		$config['total_rows'] = $this->admin_model
			->get_total_user_group_members_count($id);
		$config['per_page'] = 20;
		$config['uri_segment'] = 3;

		include (APPPATH . "/config/page_config.php");

		$this->pagination->initialize($config); 

		$this->template->loadContent("admin/view_group.php", array(
			"group" => $group->row(),
			"users" => $users,
			"total_members" => $config['total_rows']
			)
		);

	}

	public function add_user_to_group_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$usernames = $this->common->nohtml($this->input->post("usernames"));
		$usernames = explode(",", $usernames);

		$users = array();
		foreach ($usernames as $username) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) $this->template->error(lang("error_3") 
				. $username);
			$users[] = $user->row();
		}

		foreach ($users as $user) {
			// Check not already a member
			$userc = $this->admin_model->get_user_from_group($user->ID, $id);
			if ($userc->num_rows() == 0) {
				$this->admin_model->add_user_to_group($user->ID, $id);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_5"));
		redirect(site_url("admin/view_group/" . $id));
	}

	public function remove_user_from_group($userid, $id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$userid = intval($userid);
		$group = $this->admin_model->get_user_group($id);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$user = $this->admin_model->get_user_from_group($userid, $id);
		if ($user->num_rows() == 0) $this->template->error(lang("error_7"));

		$this->admin_model->delete_user_from_group($userid, $id);
		$this->session->set_flashdata("globalmsg", lang("success_6"));
		redirect(site_url("admin/view_group/" . $id));
	}

	public function email_templates() 
	{
		if(!$this->user->info->admin) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_templates" => 1)));

		$languages = $this->config->item("available_languages");

		$this->template->loadContent("admin/email_templates.php", array(
			"languages" => $languages
			)
		);
	}

	public function email_template_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("email_templates.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"email_templates.title" => 0
				 ),
				 1 => array(
				 	"email_templates.hook" => 0
				 ),
				 2 => array(
				 	"email_templates.language" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->admin_model
				->get_total_email_templates()
		);
		$templates = $this->admin_model->get_email_templates($this->datatables);

		foreach($templates->result() as $r) {

			$this->datatables->data[] = array(
				$r->title,
				$r->hook,
				$r->language,
				'<a href="'.site_url("admin/edit_email_template/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("admin/delete_email_template/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function add_email_template() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$template = $this->lib_filter->go($this->input->post("template"));
		$hook = $this->common->nohtml($this->input->post("hook"));
		$language = $this->common->nohtml($this->input->post("language"));

		$this->admin_model->add_email_template(array(
			"title" => $title,
			"message" => $template,
			"hook" => $hook,
			"language" => $language
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_129"));
		redirect(site_url("admin/email_templates"));
	}

	public function edit_email_template($id) 
	{
		if(!$this->user->info->admin) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_templates" => 1)));
		$id = intval($id);

		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$languages = $this->config->item("available_languages");

		$this->template->loadContent("admin/edit_email_template.php", array(
			"email_template" => $email_template->row(),
			"languages" => $languages
			)
		);
	}

	public function edit_email_template_pro($id) 
	{
		if(!$this->user->info->admin) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("email_templates" => 1)));
		$id = intval($id);
		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$template = $this->lib_filter->go($this->input->post("template"));
		$hook = $this->common->nohtml($this->input->post("hook"));
		$language = $this->common->nohtml($this->input->post("language"));

		$this->admin_model->update_email_template($id, array(
			"title" => $title,
			"message" => $template,
			"hook" => $hook,
			"language" => $language
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_7"));
		redirect(site_url("admin/email_templates"));
	}

	public function delete_email_template($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);

		$email_template = $this->admin_model->get_email_template($id);
		if ($email_template->num_rows() == 0) {
			$this->template->error(lang("error_8"));
		}

		$this->admin_model->delete_email_template($id);
		$this->session->set_flashdata("globalmsg", lang("success_130"));
		redirect(site_url("admin/email_templates"));
	}

	public function ipblock() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("ipblock" => 1)));

		$ipblock = $this->admin_model->get_ip_blocks();

		$this->template->loadContent("admin/ipblock.php", array(
			"ipblock" => $ipblock
			)
		);
	}

	public function add_ipblock() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$ip = $this->common->nohtml($this->input->post("ip"));
		$reason = $this->common->nohtml($this->input->post("reason"));

		if (empty($ip)) $this->template->error(lang("error_10"));

		$this->admin_model->add_ipblock($ip, $reason);
		$this->session->set_flashdata("globalmsg", lang("success_8"));
		redirect(site_url("admin/ipblock"));
	}

	public function delete_ipblock($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);
		$ipblock = $this->admin_model->get_ip_block($id);
		if ($ipblock->num_rows() == 0) $this->template->error(lang("error_11"));

		$this->admin_model->delete_ipblock($id);
		$this->session->set_flashdata("globalmsg", lang("success_9"));
		redirect(site_url("admin/ipblock"));
	}

	public function members() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("members" => 1)));

		$user_roles = $this->admin_model->get_user_roles();

		$this->template->loadContent("admin/members.php", array(
			"user_roles" => $user_roles
			)
		);
	}

	public function members_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"users.first_name" => 0
				 ),
				 2 => array(
				 	"users.last_name" => 0
				 ),
				 3 => array(
				 	"users.email" => 0
				 ),
				 4 => array(
				 	"user_roles.name" => 0
				 ),
				 5 => array(
				 	"users.joined" => 0
				 ),
				 6 => array(
				 	"users.oauth_provider" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->user_model
				->get_total_members_count()
		);
		$members = $this->user_model->get_members_admin($this->datatables);

		foreach($members->result() as $r) {
			if($r->oauth_provider == "google") {
				$provider = "Google";
			} elseif($r->oauth_provider == "twitter") {
				$provider = "Twitter";
			} elseif($r->oauth_provider == "facebook") {
				$provider = "Facebook";
			} else {
				$provider =  lang("ctn_196");
			}
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp, "first_name" => $r->first_name, "last_name" => $r->last_name)),
				$r->first_name,
				$r->last_name,
				$r->email,
				$this->common->get_user_role($r),
				date($this->settings->info->date_format, $r->joined),
				$provider,
				'<a href="'.site_url("admin/edit_member/" . $r->ID).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("admin/delete_member/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function edit_member($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("members" => 1)));
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$user_groups = $this->user_model->get_user_groups($id);
		$user_roles = $this->admin_model->get_user_roles();

		$fields = $this->user_model->get_custom_fields_answers(array(), $id);

		$this->template->loadContent("admin/edit_member.php", array(
			"member" => $member->row(),
			"user_groups" => $user_groups,
			"user_roles" => $user_roles,
			"fields" => $fields
			)
		);
	}

	public function edit_member_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();

		$fields = $this->user_model->get_custom_fields_answers(array(
			), $id);

		$this->load->model("register_model");
		$email = $this->input->post("email", true);
		$first_name = $this->common->nohtml(
			$this->input->post("first_name", true));
		$last_name = $this->common->nohtml(
			$this->input->post("last_name", true));
		$pass = $this->common->nohtml(
			$this->input->post("password", true));
		$username = $this->common->nohtml(
			$this->input->post("username", true));
		$user_role = intval($this->input->post("user_role"));
		$aboutme = $this->common->nohtml($this->input->post("aboutme"));
		$points = abs($this->input->post("credits"));
		$active = intval($this->input->post("active"));

		$address_1 = $this->common->nohtml($this->input->post("address_1"));
		$address_2 = $this->common->nohtml($this->input->post("address_2"));
		$city = $this->common->nohtml($this->input->post("city"));
		$state = $this->common->nohtml($this->input->post("state"));
		$zipcode = $this->common->nohtml($this->input->post("zipcode"));
		$country = $this->common->nohtml($this->input->post("country"));

		if (strlen($username) < 3) $this->template->error(lang("error_14"));

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$this->template->error(lang("error_15"));
		}

		if ($username != $member->username) {
			if (!$this->register_model->check_username_is_free($username)) {
				 $this->template->error(lang("error_16"));
			}
		}

		if (!empty($pass)) {
			if (strlen($pass) <= 5) {
				 $this->template->error(lang("error_17"));
			}
			$pass = $this->common->encrypt($pass);
		} else {
			$pass = $member->password;
		}

		$this->load->helper('email');
		$this->load->library('upload');

		if (empty($email)) {
				$this->template->error(lang("error_18"));
		}

		if (!valid_email($email)) {
			$this->template->error(lang("error_19"));
		}

		if ($email != $member->email) {
			if (!$this->register_model->checkEmailIsFree($email)) {
				 $this->template->error(lang("error_20"));
			}
		}

		if($user_role != $member->user_role) {
			if(!$this->user->info->admin) {
				$this->template->error(lang("error_66"));
			}
		}
		if($user_role > 0) {
			$role = $this->admin_model->get_user_role($user_role);
			if($role->num_rows() == 0) $this->template->error(lang("error_65"));
		}

		// Custom Fields
		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->name);
				}
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));
					
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_158") . $r->name);
					}
					if($flag) {
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_158") . $r->name);
				}
				if($flag) {
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			}
		}

		if ($_FILES['userfile']['size'] > 0) {
				$this->upload->initialize(array( 
			       "upload_path" => $this->settings->info->upload_path,
			       "overwrite" => FALSE,
			       "max_filename" => 300,
			       "encrypt_name" => TRUE,
			       "remove_spaces" => TRUE,
			       "allowed_types" => "gif|jpg|png|jpeg|",
			       "max_size" => 1000,
			       "xss_clean" => TRUE,
			    ));

			    if (!$this->upload->do_upload()) {
			    	$this->template->error(lang("error_21")
			    	.$this->upload->display_errors());
			    }

			    $data = $this->upload->data();

			    $image = $data['file_name'];
			} else {
				$image= $member->avatar;
			}

		$this->user_model->update_user($id, 
			array(
				"username" => $username,
				"email" => $email,
				"first_name" => $first_name,
				"last_name" => $last_name,
				"password" => $pass,
				"user_role" => $user_role,
				"avatar" => $image,
				"aboutme" => $aboutme,
				"points" => $points,
				"active" => $active,
				"address_1" => $address_1,
				"address_2" => $address_2,
				"city" => $city,
				"state" => $state,
				"zipcode" => $zipcode,
				"country" => $country
				)
		);

		// Update CF
		// Add Custom Fields data
		foreach($answers as $answer) {
			// Check if field exists
			$field = $this->user_model->get_user_cf($answer['fieldid'], $id);
			if($field->num_rows() == 0) {
				$this->user_model->add_custom_field(array(
					"userid" => $id,
					"fieldid" => $answer['fieldid'],
					"value" => $answer['answer']
					)
				);
			} else {
				$this->user_model->update_custom_field($answer['fieldid'], 
					$id, $answer['answer']);
			}
		}
		$this->session->set_flashdata("globalmsg", lang("success_10"));
		redirect(site_url("admin/members"));
	}

	public function member_user_groups($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("members" => 1)));
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();

		// Groups
		$user_groups = $this->user_model->get_user_groups($id);
		$groups = $this->admin_model->get_user_groups();


		$this->template->loadContent("admin/member_user_groups.php", array(
			"member" => $member,
			"user_groups" => $user_groups,
			"groups" => $groups
			)
		);
	}

	public function add_member_to_group_pro($id) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$id = intval($id);

		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$member = $member->row();

		$groupid = intval($this->input->post("groupid"));

		$group = $this->admin_model->get_user_group($groupid);
		if ($group->num_rows() == 0) $this->template->error(lang("error_4"));

		$userc = $this->admin_model->get_user_from_group($id, $groupid);
		if ($userc->num_rows() > 0) {
			$this->template->error(lang("error_249"));
		}

		$this->admin_model->add_user_to_group($member->ID, $groupid);
		

		$this->session->set_flashdata("globalmsg", lang("success_5"));
		redirect(site_url("admin/member_user_groups/" . $id));

	}

	public function add_member_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->load->model("register_model");
		$email = $this->input->post("email", true);
		$first_name = $this->common->nohtml(
			$this->input->post("first_name", true));
		$last_name = $this->common->nohtml(
			$this->input->post("last_name", true));
		$pass = $this->common->nohtml(
			$this->input->post("password", true));
		$pass2 = $this->common->nohtml(
			$this->input->post("password2", true));
		$captcha = $this->input->post("captcha", true);
		$username = $this->common->nohtml(
			$this->input->post("username", true));
		$user_role = intval($this->input->post("user_role"));

		if($user_role > 0) {
			$role = $this->admin_model->get_user_role($user_role);
			if($role->num_rows() == 0) $this->template->error(lang("error_65"));
			$role = $role->row();
			if($role->admin || $role->admin_members || $role->admin_settings 
				|| $role->admin_payment) {
				if(!$this->user->info->admin) {
					$this->template->error(lang("error_67"));
				}
			}
		}


		if (strlen($username) < 3) $this->template->error(lang("error_14"));

		if (!preg_match("/^[a-z0-9_]+$/i", $username)) {
			$this->template->error(lang("error_15"));
		}

		if (!$this->register_model->check_username_is_free($username)) {
			 $this->template->error(lang("error_16"));
		}

		if ($pass != $pass2) $this->template->error(lang("error_22"));

		if (strlen($pass) <= 5) {
			 $this->template->error(lang("error_17"));
		}

		$this->load->helper('email');

		if (empty($email)) {
				$this->template->error(lang("error_18"));
		}

		if (!valid_email($email)) {
			$this->template->error(lang("error_19"));
		}

		if (!$this->register_model->checkEmailIsFree($email)) {
			 $this->template->error(lang("error_20"));
		}

		$pass = $this->common->encrypt($pass);
		$this->register_model->add_user(array(
			"username" => $username,
			"email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"password" => $pass,
			"user_role" => $user_role,
			"IP" => $_SERVER['REMOTE_ADDR'],
			"joined" => time(),
			"joined_date" => date("n-Y")
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_11"));
		redirect(site_url("admin/members"));
	}

	public function delete_member($id, $hash) 
	{
		if(!$this->common->has_permissions(array("admin", "admin_members"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if ($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$member = $this->user_model->get_user_by_id($id);
		if ($member->num_rows() ==0 ) $this->template->error(lang("error_13"));

		$this->user_model->delete_user($id);
		$this->session->set_flashdata("globalmsg", lang("success_12"));
		redirect(site_url("admin/members"));
	}

	public function social_settings() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("social_settings" => 1)));
		$this->template->loadContent("admin/social_settings.php", array(
			)
		);
	}

	public function social_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$disable_social_login = 
			intval($this->input->post("disable_social_login"));
		$twitter_consumer_key = 
			$this->common->nohtml($this->input->post("twitter_consumer_key"));
		$twitter_consumer_secret = 
			$this->common->nohtml($this->input->post("twitter_consumer_secret"));
		$facebook_app_id = 
			$this->common->nohtml($this->input->post("facebook_app_id"));
		$facebook_app_secret = 
			$this->common->nohtml($this->input->post("facebook_app_secret"));
		$google_client_id = 
			$this->common->nohtml($this->input->post("google_client_id"));
		$google_client_secret = 
			$this->common->nohtml($this->input->post("google_client_secret"));

		$this->admin_model->updateSettings(
			array(
				"disable_social_login" => $disable_social_login,
				"twitter_consumer_key" => $twitter_consumer_key,
				"twitter_consumer_secret" => $twitter_consumer_secret,
				"facebook_app_id" => $facebook_app_id, 
				"facebook_app_secret"=> $facebook_app_secret,  
				"google_client_id" => $google_client_id,
				"google_client_secret" => $google_client_secret,
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/social_settings"));
	}

	public function section_settings() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$this->template->loadData("activeLink", 
			array("admin" => array("section" => 1)));
		$this->template->loadContent("admin/section.php", array(
			)
		);
	}

	public function section_settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$enable_calendar = intval($this->input->post("enable_calendar"));
		$enable_tasks = intval($this->input->post("enable_tasks"));
		$enable_files = intval($this->input->post("enable_files"));
		$enable_team = intval($this->input->post("enable_team"));
		$enable_time = intval($this->input->post("enable_time"));
		$enable_tickets = intval($this->input->post("enable_tickets"));
		$enable_finance = intval($this->input->post("enable_finance"));
		$enable_invoices = intval($this->input->post("enable_invoices"));
		$enable_notes = intval($this->input->post("enable_notes"));
		$enable_leads = intval($this->input->post("enable_leads"));
		$enable_services = intval($this->input->post("enable_services"));
		$enable_reports = intval($this->input->post("enable_reports"));

		$this->admin_model->updateSettings(array(
			"enable_calendar" => $enable_calendar,
			"enable_tasks" => $enable_tasks,
			"enable_files" => $enable_files,
			"enable_team" => $enable_team,
			"enable_time" => $enable_time,
			"enable_tickets" => $enable_tickets,
			"enable_finance" => $enable_finance,
			"enable_invoices" => $enable_invoices,
			"enable_notes" => $enable_notes,
			"enable_leads" => $enable_leads,
			"enable_reports" => $enable_reports,
			"enable_services" => $enable_services
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/section_settings"));
	}

	public function settings() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}

		$roles = $this->admin_model->get_user_roles();

		$layouts = $this->admin_model->get_layouts();

		$this->template->loadData("activeLink", 
			array("admin" => array("settings" => 1)));
		$this->template->loadContent("admin/settings.php", array(
			"roles" => $roles,
			"layouts" => $layouts
			)
		);
	}

	public function settings_pro() 
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings"),
		 $this->user)) {
			$this->template->error(lang("error_2"));
		}
		$site_name = $this->common->nohtml($this->input->post("site_name"));
		$site_desc = $this->common->nohtml($this->input->post("site_desc"));
		$site_email = $this->common->nohtml($this->input->post("site_email"));
		$upload_path = $this->common->nohtml($this->input->post("upload_path"));
		$file_types = $this->common
			->nohtml($this->input->post("file_types"));
		$file_size = intval($this->input->post("file_size"));
		$upload_path_rel = 
			$this->common->nohtml($this->input->post("upload_path_relative"));
		$register = intval($this->input->post("register"));
		$avatar_upload = intval($this->input->post("avatar_upload"));
		$disable_captcha = intval($this->input->post("disable_captcha"));
		$disable_ticket_upload = intval($this->input->post("disable_ticket_upload"));

		$login_protect = intval($this->input->post("login_protect"));
		$activate_account = intval($this->input->post("activate_account"));
		$fp_currency_symbol = $this->common->nohtml(
				$this->input->post("fp_currency_symbol"));
		$default_user_role = intval($this->input->post("default_user_role"));
		$client_user_role = intval($this->input->post("client_user_role"));
		$secure_login = intval($this->input->post("secure_login"));

		$google_recaptcha = intval($this->input->post("google_recaptcha"));
		$google_recaptcha_secret = $this->common->nohtml($this->input->post("google_recaptcha_secret"));
		$google_recaptcha_key = $this->common->nohtml($this->input->post("google_recaptcha_key"));

		$logo_option = intval($this->input->post("logo_option"));

		$cache_time = intval($this->input->post("cache_time"));

		$profile_comments = intval($this->input->post("profile_comments"));

		$layoutid = intval($this->input->post("layoutid"));
		$layout = $this->admin_model->get_layout($layoutid);
		if($layout->num_rows() == 0) {
			$this->template->error(lang("error_250"));
		}
		$layout = $layout->row();

		// Validate
		if (empty($site_name) || empty($site_email)) {
			$this->template->error(lang("error_23"));
		}
		$this->load->library("upload");

		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => $this->settings->info->file_types,
		       "max_size" => 2000,
		       "xss_clean" => TRUE
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21") 
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= $this->settings->info->site_logo;
		}

		$this->admin_model->updateSettings(
			array(
				"site_name" => $site_name,
				"site_desc" => $site_desc,
				"upload_path" => $upload_path,
				"upload_path_relative" => $upload_path_rel, 
				"site_logo"=> $image,  
				"site_email" => $site_email,
				"register" => $register,
				"avatar_upload" => $avatar_upload,
				"file_types" => $file_types,
				"disable_captcha" => $disable_captcha,
				"file_size" => $file_size,
				"disable_ticket_upload" => $disable_ticket_upload,
				"login_protect" => $login_protect,
				"activate_account" => $activate_account,
				"fp_currency_symbol" => $fp_currency_symbol,
				"default_user_role" => $default_user_role,
				"secure_login" => $secure_login,
				"google_recaptcha" => $google_recaptcha,
				"google_recaptcha_key" => $google_recaptcha_key,
				"google_recaptcha_secret" => $google_recaptcha_secret,
				"logo_option" => $logo_option,
				"layout" => $layout->layout_path,
				"cache_time" => $cache_time,
				"profile_comments" => $profile_comments,
				"client_user_role" => $client_user_role
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_13"));
		redirect(site_url("admin/settings"));
	}

}

?>