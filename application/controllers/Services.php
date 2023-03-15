<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("invoices_model");
		$this->load->model("team_model");
		$this->load->model("projects_model");
		$this->load->model("services_model");
		$this->load->helper('site_helper');
        $this->load->model("proposals_model");
	}

    public function dd($data)
    {
		echo '<pre>';
		print_r($data);
		exit;
	}

	private function check_requirements() 
	{
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}

		$this->common->check_permissions(
			"Services", 
			array("admin", "project_admin", "service_manage"), // User Roles
			array(), // Team Roles
			0  
		);
	}

	public function index() 
	{
		$this->check_requirements();
		
		$this->template->loadData("activeLink", 
			array("services" => array("general" => 1)));

		$this->template->loadContent("services/index.php", array(
			)
		);
	}

	public function services_page() 
	{
		/*$this->check_requirements();
		
		$this->load->library("datatables");

		$this->datatables->set_default_order("service_forms.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"service_forms.title" => 0
				 ),
				 1 => array(
				 	"users.username" => 0
				 ),
				 2 => array(
				 	"service_forms.cost" => 0
				 )
			)
		);

		
		$this->datatables->set_total_rows(
			$this->services_model->get_services_total()
		);

		$services = $this->services_model->get_services($this->datatables);

		foreach($services->result() as $r) {
			
			if(!isset($r->username)) {
				$user = lang("ctn_990");
			} else {
				$user = $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp));
			}
			$this->datatables->data[] = array(
				$r->title,
				$user,
				number_format($r->cost, 2),
				'<a href="'.site_url("services/view_service/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_555").'</a> <a href="'.site_url("services/edit_service/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("services/delete_service/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'" onclick="return confirm(\''.lang("ctn_317").'\')"><span class="glyphicon glyphicon-trash"></span></a>'

				);
		}
		echo json_encode($this->datatables->process());*/
	}

	public function edit_service($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$service = $this->services_model->get_service($id);
		if($service->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}
		$service = $service->row();

		$this->template->loadData("activeLink", 
			array("services" => array("general" => 1)));

		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><script src="' . base_url() . 'scripts/custom/services.js">
			</script>'
		);

		$currencies = $this->invoices_model->get_currencies();

		$fields = $this->services_model->get_form_fields($id);


		$this->template->loadContent("services/edit.php", array(
			"service" => $service,
			"currencies" => $currencies,
			"fields" => $fields
			)
		);

	}

	public function edit_service_pro($id) 
	{
		$this->check_requirements();
		$id = intval($id);
		$service = $this->services_model->get_service($id);
		if($service->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}
		$service = $service->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$welcome = $this->lib_filter->go($this->input->post("welcome"));
		$username = $this->common->nohtml($this->input->post("username"));
		$cost = abs($this->input->post("cost"));
		$invoice = intval($this->input->post("invoice"));
		$currencyid = intval($this->input->post("currencyid"));


		$invoice_message = $this->lib_filter->go($this->input->post("invoice_message"));
		$require_login = intval($this->input->post("require_login"));

		$userid = 0;
		if(!empty($username)) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_156"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}

		if(empty($title)) {
			$this->template->error(lang("error_239"));
		}

		$currency = $this->invoices_model->get_currency($currencyid);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_126"));
		}

		// Check all fields
		$fields = array();
		$field_count = intval($this->input->post("field_count"));
		for($i=1;$i<=$field_count;$i++) {
			$ftitle = $this->common->nohtml($this->input->post("field_title_" . $i));
			$ftype = intval($this->input->post("field_type_" . $i));
			$frequired = intval($this->input->post("field_require_" . $i));
			$fdesc = $this->common->nohtml($this->input->post("field_desc_" . $i));
			$foptions = $this->common->nohtml($this->input->post("field_options_" . $i));
			$fid = intval($this->input->post("form_field_id_" . $i));
			$fcost = abs($this->input->post("field_cost_" . $i));

			if(!empty($ftitle)) {
				$fields[] = array(
					"title" => $ftitle,
					"type" => $ftype,
					"required" => $frequired,
					"desc" => $fdesc,
					"options" => $foptions,
					"fid" => $fid,
					"cost" => $fcost
				);
			}
		}

		$fields_r = $this->services_model->get_form_fields($id);
		foreach($fields_r->result() as $r) {
			// Check to see if FID is in our array.
			$flag = false;
			foreach($fields as $rr) {
				if($rr['fid'] == $r->ID) {
					$flag = true;
				}
			}

			if(!$flag) {
				// Delete field
				$this->services_model->delete_form_field($r->ID);
			}
		}

		$this->services_model->update_service($id, array(
			"title" => $title,
			"welcome" => $welcome,
			"userid" => $userid,
			"invoice" => $invoice,
			"cost" => $cost,
			"currencyid" => $currencyid,
			"require_login" => $require_login,
			"invoice_message" => $invoice_message
			)
		);

		foreach($fields as $r) {
			if($r['fid'] > 0) {
				$this->services_model->update_form_field($r['fid'], array(
					"title" => $r['title'],
					"type" => $r['type'],
					"required" => $r['required'],
					"description" => $r['desc'],
					"options" => $r['options'],
					"cost" => $r['cost']
					)
				);
			} else {
				$this->services_model->add_field(array(
					"formid" => $id,
					"title" => $r['title'],
					"type" => $r['type'],
					"required" => $r['required'],
					"description" => $r['desc'],
					"options" => $r['options'],
					"cost" => $r['cost']
					)
				);
			}
		}

		$this->session->set_flashdata("globalmsg", lang("success_118"));
		redirect(site_url("services"));


	}

	public function delete_service($id, $hash) 
	{
		$this->check_requirements();
		$id = intval($id);
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$service = $this->services_model->get_service($id);
		if($service->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}

		$this->services_model->delete_service($id);
		$this->session->set_flashdata("globalmsg", lang("success_119"));
		redirect(site_url("services"));
	}

	public function add() 
	{
		$this->check_requirements();
		
		$this->template->loadData("activeLink", 
			array("services" => array("general" => 1)));

		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><script src="' . base_url() . 'scripts/custom/services.js">
			</script>'
		);

		$currencies = $this->invoices_model->get_currencies();

		$this->template->loadContent("services/add.php", array(
			"currencies" => $currencies
			)
		);
	}

	public function add_pro() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$welcome = $this->lib_filter->go($this->input->post("welcome"));
		$username = $this->common->nohtml($this->input->post("username"));
		$cost = abs($this->input->post("cost"));
		$invoice = intval($this->input->post("invoice"));
		$currencyid = intval($this->input->post("currencyid"));

		$invoice_message = $this->lib_filter->go($this->input->post("invoice_message"));
		$require_login = intval($this->input->post("require_login"));

		$userid = 0;
		if(!empty($username)) {
			$user = $this->user_model->get_user_by_username($username);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_156"));
			}
			$user = $user->row();
			$userid = $user->ID;
		}

		if(empty($title)) {
			$this->template->error(lang("error_239"));
		}

		$currency = $this->invoices_model->get_currency($currencyid);
		if($currency->num_rows() == 0) {
			$this->template->error(lang("error_126"));
		}

		$fields = array();
		$field_count = intval($this->input->post("field_count"));
		for($i=1;$i<=$field_count;$i++) {
			$ftitle = $this->common->nohtml($this->input->post("field_title_" . $i));
			$ftype = intval($this->input->post("field_type_" . $i));
			$frequired = intval($this->input->post("field_require_" . $i));
			$fdesc = $this->common->nohtml($this->input->post("field_desc_" . $i));
			$foptions = $this->common->nohtml($this->input->post("field_options_" . $i));
			$fcost = abs($this->input->post("field_cost_" . $i));

			if(!empty($ftitle)) {
				$fields[] = array(
					"title" => $ftitle,
					"type" => $ftype,
					"required" => $frequired,
					"desc" => $fdesc,
					"options" => $foptions,
					"cost" => $fcost
				);
			}
		}

		$formid = $this->services_model->add_service(array(
			"title" => $title,
			"welcome" => $welcome,
			"userid" => $userid,
			"invoice" => $invoice,
			"cost" => $cost,
			"currencyid" => $currencyid,
			"require_login" => $require_login,
			"invoice_message" => $invoice_message
			)
		);

		foreach($fields as $r) {
			$this->services_model->add_field(array(
				"formid" => $formid,
				"title" => $r['title'],
				"type" => $r['type'],
				"required" => $r['required'],
				"description" => $r['desc'],
				"options" => $r['options'],
				"cost" => $r['cost']
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_120"));
		redirect(site_url("services"));
	}

	public function orders() 
	{
		$this->template->loadData("activeLink", 
			array("services" => array("orders" => 1)));
		$this->template->loadContent("services/orders.php", array(
			)
		);
	}

	public function orders_page() 
	{
		$this->check_requirements();
		
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_services.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"service_forms.title" => 0
				 ),
				 1 => array(
				 	"users.username" => 0
				 ),
				 2 => array(
				 	"user_services.email" => 0
				 ),
				 3 => array(
				 	"user_services.total_cost" => 0
				 ),
				 4 => array(
				 	"invoices.status" => 0
				 ),
				 5 => array(
				 	"user_services.timestamp" => 0
				 ),

			)
		);

		
		$this->datatables->set_total_rows(
			$this->services_model->get_orders_total()
		);

		$orders = $this->services_model->get_orders($this->datatables);

		foreach($orders->result() as $r) {
			
			if(!isset($r->username)) {
				$user = lang("ctn_819");
			} else {
				$user = $this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp));
			}

			if(!isset($r->status)) {
				$status = "No Invoice";
			} elseif($r->status == 1) {
				$status = "<label class='label label-danger'>".lang("ctn_595")."</label>";
			} elseif($r->status == 2) {
				$status = "<label class='label label-success'>".lang("ctn_596")."</label>";
			} elseif($r->status == 3) {
				$status = "<label class='label label-default'>".lang("ctn_597")."</label>";
			}
			$this->datatables->data[] = array(
				$r->title,
				$user,
				$r->email,
				number_format($r->total_cost, 2),
				$status,
				date($this->settings->info->date_format, $r->timestamp),
				'<a href="'.site_url("services/remind_order/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_1254").'">'.lang("ctn_1254").'</a> <a href="'.site_url("services/edit_order/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("services/delete_order/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'" onclick="return confirm(\''.lang("ctn_317").'\')"><span class="glyphicon glyphicon-trash"></span></a>'

				);
		}
		echo json_encode($this->datatables->process());
	}

	public function remind_order($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->erorr(lang("error_6"));
		}
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}
		$order = $order->row();

		$invoiceid = $order->invoiceid;
		$hash = $order->invoice_hash;
		$email = $order->email;
		$name = $order->name;

		// Send Email
		$this->load->model("home_model");
		if(!isset($_COOKIE['language'])) {
			// Get first language in list as default
			$lang = $this->config->item("language");
		} else {
			$lang = $this->common->nohtml($_COOKIE["language"]);
		}

		// Send Email
		$email_template = $this->home_model->get_email_template_hook("ordered_service", $lang);
		if($email_template->num_rows() == 0) {
			$this->template->error(lang("error_48"));
		}
		$email_template = $email_template->row();

		$email_template->message = $this->common->replace_keywords(array(
			"[NAME]" => $name,
			"[SITE_URL]" => site_url(),
			"[INVOICE_URL]" => site_url("invoices/view/" . $invoiceid . "/" . $hash),
			"[SITE_NAME]" =>  $this->settings->info->site_name
			),
		$email_template->message);

		$this->common->send_email($email_template->title,
			 $email_template->message, $email);

		$this->session->set_flashdata("globalmsg", lang("success_121"));
		redirect(site_url("services/orders"));
	}

	public function edit_order($id) 
	{
		$this->template->loadData("activeLink", 
			array("services" => array("orders" => 1)));
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}
		$order = $order->row();

		$fields = $this->services_model->get_order_fields($id, $order->formid);

		$currency = $this->invoices_model->get_currency($order->currencyid);

		$this->template->loadContent("services/edit_order.php", array(
			"order" => $order,
			"fields" => $fields,
			"currency" => $currency->row()
			)
		);
	}

	public function edit_order_pro($id) 
	{
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}
		$order = $order->row();

		$invoice_generate = intval($this->input->post("invoice_generate"));
		$invoice_delete = intval($this->input->post("invoice_delete"));
		$email_remind = intval($this->input->post("email_remind"));

		// Email
		$email = $this->common->nohtml($this->input->post("email"));
		$name = $this->common->nohtml($this->input->post("name"));
		if(empty($email)) {
			$this->template->error(lang("error_241"));
		}
		if(empty($name)) {
			$this->template->error(lang("error_242"));
		}
		$this->load->helper("email");
		if(!valid_emaiL($email)) {
			$this->template->error(lang("error_243"));
		}

		$total_cost = $order->cost;

		$fields = $this->services_model->get_form_fields($order->formid);

		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			$cost = 0;
			if($r->type == 1) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 2) {
				// HTML
				$answer = $this->lib_filter->go($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 3) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("field_checkbox_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}

				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);

			} elseif($r->type == 4) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['field_id_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_158") . $r->title);
					}

					if($flag) {
						$cost = $r->cost;
					}
					if($flag) {
						$total_cost += $cost;
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer,
							"cost" => $cost,
							"field_name" => $r->title
						);
					}
				}

			} elseif($r->type == 5) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_158") . $r->title);
				}
				if($flag) {
					$cost = $r->cost;
					$total_cost += $cost;
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer,
						"cost" => $cost,
						"field_name" => $r->title
					);
				}
			}
		}

		$invoiceid = $order->invoiceid;
		$hash = $order->invoice_hash;

		if($invoice_delete) {
			$this->invoices_model->delete_invoice($order->invoiceid);
			$invoiceid = 0;
		}

		if($invoice_generate) {
			// Get previous invoice for this service
			// To generate ID
			$previnvoice = $this->invoices_model->get_invoice_serviceid($order->formid);
			if($previnvoice->num_rows() == 0) {
				$invoice_tmp_id = "service_id_" . $order->formid . "_0001";
			} else {
				$previnvoice = $previnvoice->row();
				$invoice_tmp_id = $previnvoice->invoice_id;
				// Get last 4 digits
				if (preg_match('#(\d+)$#', $invoice_tmp_id, $matches)) {
					$num = intval($matches[1]);
					$pad = strlen($matches[1]);
					$num++;
					$num = str_pad($num, $pad, '0', STR_PAD_LEFT);
					$invoice_tmp_id = 
						substr($invoice_tmp_id, 0, strlen($invoice_tmp_id)-$pad);
					$invoice_tmp_id = $invoice_tmp_id . $num;
				} else {
					$invoice_tmp_id = $invoice_tmp_id . "_0001";
				}
			}

			$hash = sha1(rand(1,100000) . $order->title . time());

			// Get site settings for invoice address
			$settings = $this->invoices_model->get_invoice_settings();
			$settings = $settings->row();

			// Process
			$invoiceid = $this->invoices_model->add_invoice(array(
				"invoice_id" => $invoice_tmp_id,
				"title" => $order->title,
				"notes" => $order->invoice_message,
				"userid" => 1, // Person who made the occuring invoice
				"status" => 1,
				"clientid" => $order->userid,
				"projectid" => 0,
				"currencyid" => $order->currencyid,
				"timestamp" => time(),
				"due_date" => time(),
				"total" => $total_cost,
				"hash" => $hash,
				"paypal" => 0,
				"first_name" => $settings->first_name,
				"last_name" => $settings->last_name,
				"address_1" => $settings->address_1,
				"address_2" => $settings->address_2,
				"city" => $settings->city,
				"state" => $settings->state,
				"zipcode" => $settings->zipcode,
				"country" => $settings->country,
				"stripe" => 0,
				"checkout2" => 0,
				"time_date" => date("Y-m-d"),
				"serviceid" => $order->formid,
				"guest_name" => $name,
				"guest_email" => $email
				)
			);

			// Add invoice items
			$this->invoices_model->add_invoice_item(array(
				"invoiceid" => $invoiceid,
				"name" => $order->title,
				"quantity" => 1,
				"amount" => $order->cost
				)
			);

			foreach($answers as $a) {
				if($a['cost'] > 0) {
					$this->invoices_model->add_invoice_item(array(
						"invoiceid" => $invoiceid,
						"name" => $a['field_name'],
						"quantity" => 1,
						"amount" => $a['cost']
						)
					);
				}
			}
		}

		if($email_remind) {
			// Send Email
			$this->load->model("home_model");
			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("ordered_service", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $name,
				"[SITE_URL]" => site_url(),
				"[INVOICE_URL]" => site_url("invoices/view/" . $invoiceid . "/" . $hash),
				"[SITE_NAME]" =>  $this->settings->info->site_name
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $email);
		}

		// Delete old answers
		$this->services_model->delete_order_answers($id);

		// update entry
		$this->services_model->update_user_service($id, array(
			"email" => $email,
			"total_cost" => $total_cost,
			"invoiceid" => $invoiceid,
			"name" => $name
			)
		);

		foreach($answers as $a) {
			$this->services_model->add_user_service_answer(array(
				"serviceid" => $id,
				"fieldid" => $a['fieldid'],
				"answer" => $a['answer'],
				"cost" => $a['cost']
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_122"));
		redirect(site_url("services/orders"));

	}

	public function delete_order($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->tempalte->error(lang("error_6"));
		}
		$id = intval($id);
		$order = $this->services_model->get_order($id);
		if($order->num_rows() == 0) {
			$this->template->error(lang("error_240"));
		}

		$this->services_model->delete_order($id);
		$this->session->set_flashdata("globalmsg", lang("success_123"));
		redirect(site_url("services/orders"));
	}


	public function view_service($id) 
	{
		$this->template->loadData("activeLink", 
			array("quote" => array("forms" => 1)));
		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script>'
		);
		$id = intval($id);
		$form = $this->services_model->get_service($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_244"));
		}

		$form = $form->row();
		if($form->require_login) {
			if(!$this->user->loggedin) {
				$this->template->error(lang("error_245"));
			}
		}

		$currency = $this->invoices_model->get_currency($form->currencyid);

		$fields = $this->services_model->get_form_fields($form->ID);
		$this->template->loadAjax("services/view_full_service.php", array(
			"form" => $form,
			"fields" => $fields,
			"currency" => $currency->row()
			),1
		);
	}

	public function process_form($id, $guest=0) 
	{
		if($guest) {
			$this->template->error_hack = 1;
		}
		$id = intval($id);
		$form = $this->services_model->get_service($id);
		if($form->num_rows() == 0) {
			$this->template->error(lang("error_238"));
		}

		$form = $form->row();

		if($form->require_login) {
			if(!$this->user->loggedin) {
				$this->template->error(lang("error_245"));
			}
		}

		$fields = $this->services_model->get_form_fields($form->ID);

		$email = $this->common->nohtml($this->input->post("email"));
		$name = $this->common->nohtml($this->input->post("name"));

		if(empty($email)) {
			$this->template->error(lang("error_241"));
		}
		if(empty($name)) {
			$this->template->error(lang("error_242"));
		}

		$this->load->helper("email");

		if(!valid_emaiL($email)) {
			$this->template->error(lang("error_243"));
		}

		$total_cost = $form->cost;

		// Process fields
		$answers = array();
		foreach($fields->result() as $r) {
			$answer = "";
			$cost = 0;
			if($r->type == 1) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 2) {
				// HTML
				$answer = $this->lib_filter->go($this->input->post("field_id_" . $r->ID));

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}
				// Add
				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);
			} elseif($r->type == 3) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("field_checkbox_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && empty($answer)) {
					$this->template->error(lang("error_158") . $r->title);
				}

				if(!empty($answer)) {
					$cost = $r->cost;
				}

				$total_cost += $cost;
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"cost" => $cost,
					"field_name" => $r->title
				);

			} elseif($r->type == 4) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['field_id_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_158") . $r->title);
					}

					if($flag) {
						$cost = $r->cost;
					}
					if($flag) {
						$total_cost += $cost;
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer,
							"cost" => $cost,
							"field_name" => $r->title
						);
					}
				}

			} elseif($r->type == 5) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("field_id_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_158") . $r->title);
				}
				if($flag) {
					$cost = $r->cost;
					$total_cost += $cost;
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer,
						"cost" => $cost,
						"field_name" => $r->title
					);
				}
			}
		}

		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

		$invoiceid = 0;
		$hash = "";
		// Generate invoice time
		if($form->invoice) {

			// Get previous invoice for this service
			// To generate ID
			$previnvoice = $this->invoices_model->get_invoice_serviceid($id);
			if($previnvoice->num_rows() == 0) {
				$invoice_tmp_id = "service_id_" . $id . "_0001";
			} else {
				$previnvoice = $previnvoice->row();
				$invoice_tmp_id = $previnvoice->invoice_id;
				// Get last 4 digits
				if (preg_match('#(\d+)$#', $invoice_tmp_id, $matches)) {
					$num = intval($matches[1]);
					$pad = strlen($matches[1]);
					$num++;
					$num = str_pad($num, $pad, '0', STR_PAD_LEFT);
					$invoice_tmp_id = 
						substr($invoice_tmp_id, 0, strlen($invoice_tmp_id)-$pad);
					$invoice_tmp_id = $invoice_tmp_id . $num;
				} else {
					$invoice_tmp_id = $invoice_tmp_id . "_0001";
				}
			}

			$hash = sha1(rand(1,100000) . $form->title . time());

			// Get site settings for invoice address
			$settings = $this->invoices_model->get_invoice_settings();
			$settings = $settings->row();

			// Process
			$invoiceid = $this->invoices_model->add_invoice(array(
				"invoice_id" => $invoice_tmp_id,
				"title" => $form->title,
				"notes" => $form->invoice_message,
				"userid" => 1, // Person who made the occuring invoice
				"status" => 1,
				"clientid" => $userid,
				"projectid" => 0,
				"currencyid" => $form->currencyid,
				"timestamp" => time(),
				"due_date" => time(),
				"total" => $total_cost,
				"hash" => $hash,
				"paypal" => 0,
				"first_name" => $settings->first_name,
				"last_name" => $settings->last_name,
				"address_1" => $settings->address_1,
				"address_2" => $settings->address_2,
				"city" => $settings->city,
				"state" => $settings->state,
				"zipcode" => $settings->zipcode,
				"country" => $settings->country,
				"stripe" => 0,
				"checkout2" => 0,
				"time_date" => date("Y-m-d"),
				"serviceid" => $form->ID,
				"guest_name" => $name,
				"guest_email" => $email
				)
			);

			// Add invoice items
			$this->invoices_model->add_invoice_item(array(
				"invoiceid" => $invoiceid,
				"name" => $form->title,
				"quantity" => 1,
				"amount" => $form->cost
				)
			);

			foreach($answers as $a) {
				if($a['cost'] > 0) {
					$this->invoices_model->add_invoice_item(array(
						"invoiceid" => $invoiceid,
						"name" => $a['field_name'],
						"quantity" => 1,
						"amount" => $a['cost']
						)
					);
				}
			}

		}

		// Add new entry
		$serviceid = $this->services_model->add_user_service(array(
			"userid" => $userid,
			"timestamp" => time(),
			"formid" => $form->ID,
			"IP" => $_SERVER['REMOTE_ADDR'],
			"email" => $email,
			"total_cost" => $total_cost,
			"invoiceid" => $invoiceid,
			"name" => $name
			)
		);

		foreach($answers as $a) {
			$this->services_model->add_user_service_answer(array(
				"serviceid" => $serviceid,
				"fieldid" => $a['fieldid'],
				"answer" => $a['answer'],
				"cost" => $a['cost']
				)
			);
		}

		// Send Email
		$this->load->model("home_model");
		$email_template = $this->home_model->get_email_template(5);
		if($email_template->num_rows() == 0) {
			$this->template->error(lang("error_48"));
		}
		$email_template = $email_template->row();

		$email_template->message = $this->common->replace_keywords(array(
			"[NAME]" => $name,
			"[SITE_URL]" => site_url(),
			"[INVOICE_URL]" => site_url("invoices/view/" . $invoiceid . "/" . $hash),
			"[SITE_NAME]" =>  $this->settings->info->site_name
			),
		$email_template->message);

		$this->common->send_email($email_template->title,
			 $email_template->message, $email);

		if($form->userid > 0) {
			// Notification
			$this->user_model->increment_field($form->userid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $form->userid,
				"url" => "services/edit_order/" . $serviceid,
				"timestamp" => time(),
				"message" => lang("ctn_1262"),
				"status" => 0,
				"fromid" => 1,
				"email" => $form->assigned_email,
				"username" => $form->username,
				"email_notification" => $form->assigned_email_notification
				)
			);
		}
		$this->session->set_flashdata("globalmsg", lang("success_124"));
		if($invoiceid > 0) {
			redirect(site_url("invoices/view/" . $invoiceid . "/" . $hash));
		} else {
			if(!$guest) { 
				redirect(site_url("services"));
			} else {
				redirect(site_url("services/view_service/" . $id));
			}
		}
	}

	public function overview($proposal_id = 0){
//        $this->check_requirements();

        $this->template->loadData("activeLink",
            array("services" => array("general" => 1)));

		if($this->user->info->user_role == 1){
			$user_type = 0;
		}else{
			$user_type = $this->user->info->ID;
		}

		$services = $this->services_model->get_services_custom($user_type);

		foreach($services as $service){
			$tasks[$service['id']] = $this->services_model->get_main_task_service($service['id']);
		}

        $this->template->loadContent("services/index_custom.php", array(
				'services' => $services,
				'tasks' => $tasks,
				'logged_user' => $this->user->info,
				'proposal_id' => $proposal_id
            )
        );
	}
	public function add_review(){


		$services = [];
		foreach($_POST['service'] as $service_id){
			$services[$service_id]['name'] = $this->services_model->get_services_custom_name($service_id);
			$services[$service_id]['tasks'] = $this->services_model->get_task_service($service_id);
            $services[$service_id]['price'] = $this->services_model->get_task_price($service_id);
            $services[$service_id]['hours'] = $this->services_model->get_task_hours($service_id);
            $services[$service_id]['description'] = $this->services_model->get_task_description($service_id);
            $services[$service_id]['filename'] = $this->services_model->get_task_filename($service_id);
            $services[$service_id]['filetype'] = $this->services_model->get_task_filetype($service_id);
            $services[$service_id]['encrypt'] = $this->services_model->get_task_file_encrypt($service_id);
            $services[$service_id]['notes'] = $this->services_model->get_task_notes($service_id);
			$tasks[$service_id] = $this->services_model->get_task_service($service_id);
		}


		$this->template->loadContent("services/add_review.php", array(
				'services' => $services,
				'tasks' => $tasks
			)
		);
	}

	public function create(){
		$this->check_requirements();

		$this->template->loadData("activeLink",
			array("services" => array("general" => 1)));

		$this->template->loadExternal(
			'<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><script src="' . base_url() . 'scripts/custom/services.js">
			</script>'
		);

		$currencies = $this->invoices_model->get_currencies();
		$billing_roles = $this->team_model->get_billing_roles();

		$this->template->loadContent("services/create.php", array(
				"currencies" => $currencies,
				"billing_roles" => $billing_roles
			)
		);
	}

    public function update ($id, $proposal_id=0)
    {
        $this->check_requirements();

        if($proposal_id != 0) {
            $service = $this->proposals_model->get_service_data($id);

            if($service->num_rows() == 0) {
                $this->template->error(lang("error_238"));
            }

            $service = $service->row();
        } else {
            $service = $this->services_model->get_service_data($id);

            if($service->num_rows() == 0) {
                $this->template->error(lang("error_238"));
            }
            $service = $service->row();
        }

        $billing_roles = $this->team_model->get_billing_roles();

        $this->template->loadData("activeLink",
            array("services" => array("general" => 1)));

        $this->template->loadExternal(
            '<script src="//cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
			</script><script src="' . base_url() . 'scripts/custom/services.js">
			</script>'
        );

        if($proposal_id != 0) {
            $tasks[$id] = $this->proposals_model->get_task_service($id);
        } else {
            $services[$id]['name'] = $this->services_model->get_services_custom_name($id);
            $services[$id]['tasks'] = $this->services_model->get_task_service($id);
            $services[$id]['price'] = $this->services_model->get_task_price($id);
            $services[$id]['hours'] = $this->services_model->get_task_hours($id);
            $services[$id]['description'] = $this->services_model->get_task_description($id);
            $services[$id]['recurring'] = $this->services_model->get_task_recurring($id);
            $services[$id]['interval'] = $this->services_model->get_task_interval($id);
            $services[$id]['interval_label'] = $this->services_model->get_task_interval_label($id);
            $services[$id]['filename'] = $this->services_model->get_task_filename($id);
            $services[$id]['filetype'] = $this->services_model->get_task_filetype($id);
            $services[$id]['encrypt'] = $this->services_model->get_task_file_encrypt($id);
            $services[$id]['notes'] = $this->services_model->get_task_notes($id);
            //$services[$id]['category_id'] = $this->services_model->get_task_category($id);
            $tasks[$id] = $this->services_model->get_task_service($id);
        }

		//$this->dd($services);
		//$this->dd($tasks[$id]);

		if($this->user->info->user_role == 1){
			$user_type = 0;
		}else{
			$user_type = $this->user->info->ID;
		}
		//$currencies = $this->invoices_model->get_currencies();
		//$categories = $this->services_model->get_services_categories($user_type);

        $this->template->loadContent("services/update.php", array(
        		"service" => $service,
        		"tasks" => $tasks,
                "billing_roles" => $billing_roles,
				"proposal_id" => $proposal_id
				)
        );
	}

	public function create_pro(){
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		} else {
			$userid = 0;
		}

        $filename = "";
        $encrypt = "";
        $filetype = "";

        if(isset($_FILES)){
			if (isset($_FILES['service_file']) && !empty($_FILES['service_file']['name'])) {
                $filename = $_FILES['service_file'] ['name'];
                $filetype = $_FILES['service_file'] ['type'];
                $encrypt = rand(1,100).time();
				$abs_path = FCPATH.'uploads/services/'.$encrypt ;
                if(move_uploaded_file($_FILES['service_file'] ['tmp_name'], $abs_path)){

                }
			}

            if (isset($_FILES['task_file']) && !empty($_FILES['task_file'])) {
				foreach ($_FILES['task_file'] as $tk => $task_files) {
					foreach ($task_files as $tkindex => $task_file) {
                        $filename_task = $_FILES['task_file']['name'][$tkindex];
                        $filetype_task = $_FILES['task_file']['type'][$tkindex];
						$encrypt_task = rand(1,100).time();
                        $abs_path = FCPATH.'uploads/tasks/'.$encrypt_task;
                        if(move_uploaded_file($_FILES['task_file']['tmp_name'][$tkindex], $abs_path)){
                            $_FILES['task_file']['encrypt'][$tkindex] = $encrypt_task;
                        }
					}

				}
            }


			if (isset($_FILES['subtask']) && isset($_FILES['subtask']['name'])) {
				foreach ($_FILES['subtask']['name'] as $stk => $sub_task_files) {
					if (isset($sub_task_files['subtask_file']) && !empty($sub_task_files['subtask_file'])) {
                        foreach ($sub_task_files['subtask_file'] as $tkindex => $task_file) {
                            $filename_subtask = $task_file;
                            $filetype_subtask = $_FILES['subtask']['type'][$stk]['subtask_file'][$tkindex];
                            $encrypt_subtask = rand(1,100).time();
                            $abs_path = FCPATH.'uploads/tasks/'.$encrypt_subtask;
                            if(move_uploaded_file($_FILES['subtask']['tmp_name'][$stk]['subtask_file'][$tkindex], $abs_path)){
                                $_FILES['subtask']['encrypt'][$stk]['subtask_file'][$tkindex] = $encrypt_subtask;
                            }
                        }
					}
				}
			}

        }
        $price_hour = $this->calculate_price_and_hours($_POST);

		$service_id = $this->services_model->insert_service(array(
			'user_id' => $userid,
			'name' => $_POST['service_name'],
			'description' => $_POST['service_description'],
            'recurring' => (isset($_POST['recurring'][0]) ? $_POST['recurring'][0] : 0),
			'interval' => $_POST['interval'],
			'interval_label' => $_POST['interval_label'],
			'notes' => $_POST['service_notes'],
			'price' => $_POST['set_price_value'],
            'set_price' => (isset($_POST['set_price'][0]) ? $_POST['set_price'][0] : 0),
			'hours' => $price_hour['hours'],
			'filename' => $filename,
			'filetype' => $filetype,
			'encrypt' => $encrypt
		));



		$_SESSION['add_service'][$service_id] = $_POST;
		$_SESSION['task_files'][$service_id] = $_FILES['task_file'];
		$_SESSION['subtask_files'][$service_id] = $_FILES['subtask'];
		redirect(site_url("tasks/insert_service_task?service_id=".$service_id));

	}

    public function edit_pro ($service_id, $proposal_id=0)
    {
        if($this->user->loggedin) {
            $userid = $this->user->info->ID;
        } else {
            $userid = 0;
        }

        if(isset($_FILES)){
            if (isset($_FILES['service_file']) && !empty($_FILES['service_file'])) {
                $filename = $_FILES['service_file'] ['name'];
                $filetype = $_FILES['service_file'] ['type'];
                $encrypt = rand(1,100).time();
                $abs_path = FCPATH.'uploads/services/'.$encrypt;
                if(move_uploaded_file($_FILES['service_file'] ['tmp_name'], $abs_path)){
                    if($proposal_id != 0) {
                        $this->services_model->edit_proposal_service($service_id, array(
                            'filename' => $filename,
                            'filetype' => $filetype,
                            'encrypt' => $encrypt
                        ));
                    } else {
                        $this->services_model->edit_service($service_id, array(
                            'filename' => $filename,
                            'filetype' => $filetype,
                            'encrypt' => $encrypt
                        ));
                    }
                }
            }

            if (isset($_FILES['task_file']) && !empty($_FILES['task_file'])) {
                foreach ($_FILES['task_file'] as $tk => $task_files) {
                    foreach ($task_files as $tkindex => $task_file) {
                        $encrypt_task = rand(1,100).time();
                        $abs_path = FCPATH.'uploads/tasks/'.$encrypt_task;
                        if(move_uploaded_file($_FILES['task_file']['tmp_name'][$tkindex], $abs_path)){
                            $_FILES['task_file']['encrypt'][$tkindex] = $encrypt_task;
                        }
                    }
                }
                
                $_SESSION['task_files'][$service_id] = $_FILES['task_file'];
            }

            if (isset($_FILES['new_task_file']) && !empty($_FILES['new_task_file'])) {
                foreach ($_FILES['new_task_file'] as $tk => $task_files) {
                    foreach ($task_files as $tkindex => $task_file) {
                        $encrypt_task = rand(1,100).time();
                        $abs_path = FCPATH.'uploads/tasks/'.$encrypt_task;
                        if(move_uploaded_file($_FILES['new_task_file']['tmp_name'][$tkindex], $abs_path)){
                            $_FILES['new_task_file']['encrypt'][$tkindex] = $encrypt_task;
                        }
                    }

                }
                
                $_SESSION['new_task_files'][$service_id] = $_FILES['new_task_file'];
            }


            if (isset($_FILES['subtask']) && isset($_FILES['subtask']['name'])) {
                foreach ($_FILES['subtask']['name'] as $stk => $sub_task_files) {
                    if (isset($sub_task_files['subtask_file']) && !empty($sub_task_files['subtask_file'])) {
						$encrypt_subtask = rand(1,100).time();
						$abs_path = FCPATH.'uploads/tasks/'.$encrypt_subtask;
						if(move_uploaded_file($_FILES['subtask']['tmp_name'][$stk]['subtask_file'], $abs_path)){
							$_FILES['subtask']['encrypt'][$stk]['subtask_file'] = $encrypt_subtask;
						}
                    }
                }
                
                $_SESSION['subtask_files'][$service_id] = $_FILES['subtask'];
            }

        } else {
            $filename = "";
            $encrypt = "";
            $filetype = "";
        }

        $price_hour = $this->calculate($_POST);

        //$this->dd($_FILES);

		if($proposal_id != 0) {
			$this->services_model->edit_proposal_service($service_id, array(
                            'name' => $_POST['service_name'],
                            'description' => $_POST['service_description'],
                            'notes' => $_POST['service_notes'],
                            'price' => ((isset($_POST['set_price'][0]) && $_POST['set_price'][0] == 1) ? $price_hour['price'] : $_POST['set_price_value']),
                            'hours' => $price_hour['hours'],
                            'set_price' => $_POST['set_price'],
                            'recurring' => (isset($_POST['recurring'][0]) ? $_POST['recurring'][0] : 0),
                            'interval' => $_POST['interval'],
                            'interval_label' => $_POST['interval_label'],
                            'filename' => $filename,
                            'filetype' => $filetype,
                            'encrypt' => $encrypt
                        ));
		} else {
			$this->services_model->edit_service($service_id, array(
				'name' => $_POST['service_name'],
				'description' => $_POST['service_description'],
				'notes' => $_POST['service_notes'],
				'price' => ((isset($_POST['set_price'][0]) && $_POST['set_price'][0] == 1) ? $price_hour['price'] : $_POST['set_price_value']),
				'hours' => $price_hour['hours'],
				'set_price' => $_POST['set_price'],
				'recurring' => (isset($_POST['recurring'][0]) ? $_POST['recurring'][0] : 0),
				'interval' => $_POST['interval'],
				'interval_label' => $_POST['interval_label']
			));
		}

        $_SESSION['edit_service'][$service_id] = $_POST;
        
        redirect(site_url("tasks/edit_service_task/".$service_id."/$proposal_id"));
	}

    public function calculate_price_and_hours ($post)
    {
		$prices = 0;
		$hours = 0;

		foreach ($post['name'] as $index => $value) {
			if (!empty(trim($value))) {
				if (isset($post['subtask'][$index])) {
					foreach ($post['subtask'][$index]['hours'] as $hour) {

						if (!empty($hour)) {
                            $hours += $hour;
						} else {
							if (is_numeric($post['hours'][$index])) {
                                $hours += $post['hours'][$index];
							}
						}
					}
                    foreach ($post['subtask'][$index]['price'] as $price) {
                        if (!empty($price)) {
                            $prices += $price;
                        } else {
                            if (is_numeric($post['price'][$index])) {
                                $prices += $post['price'][$index];
                            }
						}
                    }
				}
			}
		}


        $data = array("price" => $prices, "hours" => $hours);

        return $data;
	}

    public function calculate ($post)
    {
        $prices = 0;
        $hours = 0;

        foreach ($post['name'] as $index => $value) {
            if (!empty(trim($value))) {
                $prices += $post['price'][$index];
            }
        }

        foreach ($post['name'] as $index => $value) {
            if (!empty(trim($value))) {
                $hours += $post['hours'][$index];
            }
        }

        $data = array("price" => $prices, "hours" => $hours);

        return $data;
    }

	public function manage_subtask(){
		$this->check_requirements();
		$this->template->loadAjax('services/manage_subtask.php', []);

	}

}

?>
