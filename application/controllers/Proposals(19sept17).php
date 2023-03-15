<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proposals extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->model("user_model");
		$this->load->model("services_model");
		$this->load->model("proposals_model");
	}

	public function index($active_status) {
		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && ($this->user->info->premium_time != -1 && $this->user->info->premium_time < time())) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));

			redirect(site_url("funds/plans"));
		}

		if($active_status == 0 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("all" => 1)));
		} else if($active_status == 1 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("draft" => 1)));
		} else if($active_status == 2 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("sent" => 1)));

			$this->template->loadData("activeLink", array("proposal" => array("sent_pending_review" => 1)));
		} else if($active_status == 3 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("sent" => 1)));

			$this->template->loadData("activeLink", array("proposal" => array("sent_pending_approval" => 1)));
		} else if($active_status == 4 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("received" => 1)));

			$this->template->loadData("activeLink", array("proposal" => array("received_pending_review" => 1)));
		} else if($active_status == 5 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("received" => 1)));

			$this->template->loadData("activeLink", array("proposal" => array("received_pending_approval" => 1)));
		} else if($active_status == 6 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("accepted" => 1)));
		} else if($active_status == 7 && $active_status != 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("declined" => 1)));
		} else if($active_status == 'templates') {
			$this->template->loadData("activeLink", array("proposal" => array("templates" => 1)));
		}

		$proposals = $this->proposals_model->get_user_all_proposals($this->user->info->ID);

		$proposal_count = $this->proposals_model->get_user_proposal_count($this->user->info->ID);

		//echo "<pre>"; print_r($proposal_count); die;

		$templates = array();
		$templates = $this->proposals_model->get_user_templates($this->user->info->ID);

		if(!empty($templates))
			$templates = $templates;

		$this->template->loadData("all_proposal_cnt", count($proposals));

		$this->template->loadData("proposal_status_cnt", $proposal_count);

		$this->template->loadContent("proposal/index.php", array("proposals" => $proposals, "active_status" => $active_status, 'templates' => $templates));
	}

	public function get_templates() {
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_to_proposal_templates.created_at", "desc");

		// Set page ordering options that can be used
        $this->datatables->ordering(
            array(
                0 => array(
                    "user_to_proposal_templates.title" => 0
                ),
                1 => array(
                    "user_to_proposal_templates.status" => 0
                ),
                2 => array(
                    "user_to_proposal_templates.created_at" => 0
                )
            )
        );

		$this->datatables->set_total_rows($this->proposals_model->get_user_template_total($this->user->info->ID));

		$templates = $this->proposals_model->get_user_templates($this->user->info->ID, $this->datatables);

		foreach($templates as $template) {
			$options = '<a target="_blank" href="'.site_url("proposals/view/".$template->template_id."/2").'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a> <a target="_blank" href="'.site_url("proposals/editor/" . $template->template_id).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("proposals/delete_template/".$template->template_id).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';

			if($template->status == 0) {
				$status = "<label class='label label-danger'>In Active</label>";
			} else {
				$status = "<label class='label label-success'>Active</label>";
			}

			$this->datatables->data[] = array(
											$template->title,
											$status,
											$template->created_at,
											$options
										);
		}

		echo json_encode($this->datatables->process());
	}

	public function get_proposals($status=0) {
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_to_proposals.created_at", "asc");

		// Set page ordering options that can be used
        $this->datatables->ordering(
            array(
                0 => array(
                    "user_to_proposals.content" => 0
                ),
                1 => array(
                    "user_to_proposals.price" => 0
                ),
                2 => array(
                    "user_to_proposals.expiration_date" => 0
                ),
                3 => array(
                    "user_to_proposals.status" => 0
                ),
                4 => array(
                    "user_to_proposals.payment_status" => 0
                ),
                5 => array(
                    "user_to_proposals.proposal_type" => 0
                )
            )
        );

		$this->datatables->set_total_rows($this->proposals_model->get_user_proposal_total($this->user->info->ID));

		$proposals = $this->proposals_model->get_user_proposals($this->user->info->ID, $status, $this->datatables);

        //echo ""; print_r($proposals); die;

		foreach($proposals as $proposal) {
			if($proposal->status == 1) {
				$options = '<a target="_blank" href="'.site_url("proposals/view/".$proposal->user_to_proposal_id."/".$proposal->proposal_type).'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a> <a target="_blank" href="'.site_url("proposals/editor/" . $proposal->user_to_proposal_id).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("proposals/delete_proposal/".$proposal->user_to_proposal_id).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
			} else {
				$options = '<a target="_blank" href="'.site_url("proposals/view/".$proposal->user_to_proposal_id."/".$proposal->proposal_type).'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a> <a href="'.site_url("proposals/delete_proposal/" . $proposal->user_to_proposal_id).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
			}

			if($proposal->payment_status == 0)
				$payment_status = 'Not Paid';
			else
				$payment_status = 'Paid';

			if($proposal->proposal_type == 0)
				$proposal_type = 'Not Editable';
			else
				$proposal_type = 'Editable';

			if($proposal->status == 1)
				$status = 'Draft';
			else if($proposal->status == 2)
				$status = 'Sent Pending Review';
			else if($proposal->status == 3)
				$status = 'Sent Pending Approval';
			else if($proposal->status == 4)
				$status = 'Received Pending Review';
			else if($proposal->status == 5)
				$status = 'Received Pending Approval';
			else if($proposal->status == 6)
				$status = 'Accepted';
			else if($proposal->status == 7)
				$status = 'Declined';

			if($proposal->price != '')
				$price = '$'.$proposal->price;
			else
				$price = '--';

			$this->datatables->data[] = array(
											$proposal->title,
											$price,
											//date($this->settings->info->date_format, $proposal->expiration_date),
											$proposal->expiration_date,
											$status,
											$payment_status,
											$proposal_type,
											$options
										);
		}

		echo json_encode($this->datatables->process());
	}

	public function edit($template_id = 0) {
		/*if(!$this->common->has_permissions(array("admin", "project_admin",
            "task_manage", "task_worker"),
            $this->user))
        {
            $this->template->error(lang("error_71"));
        }*/

		//$this->template->loadData("activeLink", array("proposal" => array("general" => 1)));

		if($template_id != '' && $template_id != 0) {
			$template_data = $this->proposals_model->get_template_by_id($template_id);

			//echo "<pre>"; print_r($template_data); die;

			$params = array(
							'user_id' => $this->user->info->ID,
							'template_id' => $template_id,
							'title' => 'Untitled',
							'content' => $template_data->content,
							'status' => 1
						);

		} else {
			$params = array(
							'user_id' => $this->user->info->ID,
							'template_id' => 0,
							'title' => 'Untitled',
							'content' => '<section><div class="row"><div class="col-md-12" data-type="container-content"><section data-type="component-text"><div class="inline-content"><blockquote class="generalinstructions"><em>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro labore architecto fuga tempore omnis aliquid, rerum numquam deleniti ipsam earum velit aliquam deserunt, molestiae officiis mollitia accusantium suscipit fugiat esse magnam eaque cumque, iste corrupti magni?</em><br></blockquote></div></section></div></div></section>',
							'status' => 1
						);
		}

		//print_r($params);

		$insert_id = $this->proposals_model->create_proposal($params);

		redirect(site_url("proposals/editor/".$insert_id));
	}

	public function editor($proposal_id) {
		$this->template->set_layout('layout/themes/content_editor_layout.php');

		$this->template->loadData("activeLink", array("proposal" => array("general" => 1)));

		$this->template->loadData("proposal_id", $proposal_id);

		$template = $this->proposals_model->get_user_proposal_by_id($proposal_id);

		//echo "<pre>"; print_r($template); die;

		if(empty($template))
			$template = $this->proposals_model->get_template_by_id($proposal_id);

		$this->template->loadData("template_id", $template->template_id);

		$this->template->loadContent("proposal/edit_template.php", array("template" => $template));
	}

	public function get_editor_side_bar() {
		$user_id = $this->user->info->ID;

		$user_proposal_data = $this->proposals_model->get_user_proposal($user_id);

		//echo "<pre>"; print_r($user_proposal_data); die;

		if(!empty($user_proposal_data)) {
			$proposal_id = $user_proposal_data->user_to_proposal_id;

			$services = $this->services_model->get_services_custom($this->user->info->ID);

			//echo "<pre>"; print_r($services);

			$services_list = array();

			foreach($services as $key => $service) {
				$proposal_service = $this->services_model->get_proposal_service($user_id, $service['id']);

				if(!empty($proposal_service)) {
					//$services_list[$key]['id'] = $proposal_service->proposal_service_id;
					$services_list[$key]['id'] = $proposal_service->service_id;
					$services_list[$key]['user_id'] = $proposal_service->user_id;
					$services_list[$key]['name'] = $proposal_service->name;
					$services_list[$key]['occurrence'] = $proposal_service->recurring;
					$services_list[$key]['interval'] = $proposal_service->interval;
					$services_list[$key]['interval_label'] = $proposal_service->interval_label;
					$services_list[$key]['description'] = $proposal_service->description;

					$services_tasks = $this->services_model->get_main_task_service($service['id']);

					$proposal_service_tasks = $this->services_model->get_proposal_service_tasks($service['id'], $proposal_service->user_to_proposal_id);

					//$services_list[$key]['tasks'] = $proposal_service_tasks;

					//echo "<pre>"; print_r($proposal_service_tasks);

					foreach($services_tasks as $key2 => $task2) {
						if(isset($proposal_service_tasks[$key2]['service_id'])) {
							//echo 'in if';

							$services_list[$key]['tasks'][$key2]['proposal_service_task_id'] = $proposal_service_tasks[$key2]['proposal_service_task_id'];
							$services_list[$key]['tasks'][$key2]['ID'] = $proposal_service_tasks[$key2]['proposal_service_task_id'];
							//$services_list[$key]['tasks'][$key2]['ID'] = $proposal_service_tasks[$key2]['project_task_template_id'];
							$services_list[$key]['tasks'][$key2]['service_id'] = $proposal_service_tasks[$key2]['proposal_service_id'];
							$services_list[$key]['tasks'][$key2]['project_task_template_id'] = $proposal_service_tasks[$key2]['project_task_template_id'];
							$services_list[$key]['tasks'][$key2]['is_subtask'] = $proposal_service_tasks[$key2]['is_subtask'];
							$services_list[$key]['tasks'][$key2]['parent_id'] = $proposal_service_tasks[$key2]['parent_id'];
							$services_list[$key]['tasks'][$key2]['name'] = $proposal_service_tasks[$key2]['name'];
							$services_list[$key]['tasks'][$key2]['description'] = $proposal_service_tasks[$key2]['description'];
							$services_list[$key]['tasks'][$key2]['price'] = $proposal_service_tasks[$key2]['price'];
							$services_list[$key]['tasks'][$key2]['billing_role'] = $proposal_service_tasks[$key2]['billing_role'];
							$services_list[$key]['tasks'][$key2]['recurring'] = $proposal_service_tasks[$key2]['recurring'];
							$services_list[$key]['tasks'][$key2]['due_count'] = $proposal_service_tasks[$key2]['due_count'];
							$services_list[$key]['tasks'][$key2]['hours'] = $proposal_service_tasks[$key2]['hours'];
							$services_list[$key]['tasks'][$key2]['filename'] = $proposal_service_tasks[$key2]['filename'];
							$services_list[$key]['tasks'][$key2]['filetype'] = $proposal_service_tasks[$key2]['filetype'];
							$services_list[$key]['tasks'][$key2]['encrypt'] = $proposal_service_tasks[$key2]['encrypt'];
							$services_list[$key]['tasks'][$key2]['start_date'] = $proposal_service_tasks[$key2]['start_date'];
							$services_list[$key]['tasks'][$key2]['due_date'] = $proposal_service_tasks[$key2]['due_date'];
							$services_list[$key]['tasks'][$key2]['status'] = $proposal_service_tasks[$key2]['status'];
							$services_list[$key]['tasks'][$key2]['userid'] = $proposal_service_tasks[$key2]['userid'];
							$services_list[$key]['tasks'][$key2]['complete'] = $proposal_service_tasks[$key2]['complete'];
							$services_list[$key]['tasks'][$key2]['complete_sync'] = $proposal_service_tasks[$key2]['complete_sync'];
							$services_list[$key]['tasks'][$key2]['archived'] = $proposal_service_tasks[$key2]['archived'];
						} else {
							//echo 'in else';

							$services_list[$key]['tasks'][$key2]['ID'] = $task2['ID'];
							$services_list[$key]['tasks'][$key2]['service_id'] = $task2['service_id'];
							$services_list[$key]['tasks'][$key2]['is_subtask'] = $task2['is_subtask'];
							$services_list[$key]['tasks'][$key2]['parent_id'] = $task2['parent_id'];
							$services_list[$key]['tasks'][$key2]['name'] = $task2['name'];
							$services_list[$key]['tasks'][$key2]['description'] = $task2['description'];
							$services_list[$key]['tasks'][$key2]['price'] = $task2['price'];
							$services_list[$key]['tasks'][$key2]['billing_role'] = $task2['billing_role'];
							$services_list[$key]['tasks'][$key2]['recurring'] = $task2['recurring'];
							$services_list[$key]['tasks'][$key2]['due_count'] = $task2['due_count'];
							$services_list[$key]['tasks'][$key2]['hours'] = $task2['hours'];
							$services_list[$key]['tasks'][$key2]['filename'] = $task2['filename'];
							$services_list[$key]['tasks'][$key2]['filetype'] = $task2['filetype'];
							$services_list[$key]['tasks'][$key2]['encrypt'] = $task2['encrypt'];
							$services_list[$key]['tasks'][$key2]['start_date'] = $task2['start_date'];
							$services_list[$key]['tasks'][$key2]['due_date'] = $task2['due_date'];
							$services_list[$key]['tasks'][$key2]['status'] = $task2['status'];
							$services_list[$key]['tasks'][$key2]['userid'] = $task2['userid'];
							$services_list[$key]['tasks'][$key2]['complete'] = $task2['complete'];
							$services_list[$key]['tasks'][$key2]['complete_sync'] = $task2['complete_sync'];
							$services_list[$key]['tasks'][$key2]['archived'] = $task2['archived'];
						}
					}
				} else {
					$services_list[$key]['id'] = $service['id'];
					$services_list[$key]['user_id'] = $service['user_id'];
					$services_list[$key]['name'] = $service['name'];
					$services_list[$key]['occurrence'] = $service['recurring'];
					$services_list[$key]['interval'] = $service['interval'];
					$services_list[$key]['interval_label'] = $service['interval_label'];
					$services_list[$key]['description'] = $service['description'];

					$services_list[$key]['tasks'] = $this->services_model->get_main_task_service($service['id']);
				}
			}

			//echo "<pre>"; print_r($services_list); die;
		}

		$this->template->loadAjax("proposal/editor_side_bar.php", array("services" => $services_list, 'logged_user' => $this->user->info, "proposal_id" => $proposal_id), 1);
	}

	public function update_proposal_content() {
		$title = $this->input->post('title');
		$proposal_id = $this->input->post('proposal_id');
		$content = $this->input->post('content');
		$status = $this->input->post('status');

		if($title == '')
			$title = 'Untitled';

		$this->proposals_model->update_proposal($proposal_id, array('title' => $title, 'content' => $content, 'status' => $status));

		echo 0;
	}

	public function view($proposal_id, $proposal_type) {
		$this->template->set_layout('layout/themes/proposal_view_layout.php');

		$proposal = $this->proposals_model->get_user_proposal_by_id($proposal_id);

		//echo "<pre>"; print_r($proposal); die;

		if(empty($proposal))
			$proposal = $this->proposals_model->get_template_by_id($proposal_id);

		$this->template->loadContent("proposal/view_proposal.php", array("proposal" => $proposal, "proposal_id" => $proposal_id, "proposal_type" => $proposal_type));
	}

	public function save_proposal_as_template() {
		$title = $this->input->post('title');
		$template_id = $this->input->post('template_id');
		$content = $this->input->post('content');
		$status = $this->input->post('status');

		if($title == '')
			$title = 'Untitled';

		if($template_id != 0) {
			$proposal = $this->proposals_model->get_template_by_id($template_id);

			$data = array(
						'user_id' => $this->user->info->ID,
						'image' => $proposal->image,
						'title' => $title,
						'description' => $proposal->description,
						'content' => $content,
						'status' => $status
					);
		} else {
			$data = array(
						'user_id' => $this->user->info->ID,
						'image' => 'blank.png',
						'title' => $title,
						'description' => 'Blank',
						'content' => $content,
						'status' => $status
					);
		}

		$this->proposals_model->save_proposal_as_template($data);

		echo 0;
	}

	public function send_proposal() {
		$proposal_id = $this->input->post('proposal_id');
		$proposal_type = $this->input->post('proposal_type');
		$emails = $this->input->post('emails');
		$title = $this->input->post('title');
		$discount = $this->input->post('discount');
		$proposal_validity = $this->input->post('proposal_validity');

		$expiration_date = date('Y-m-d', strtotime($proposal_validity));

		if($title == '')
			$title = 'Untitled';

		$this->proposals_model->update_proposal($proposal_id, array('title' => $title, 'sender_email' => $emails, 'discount' => $discount, 'expiration_date' => $expiration_date, 'status' => 2));

		/*$explode_emails = explode(',', $emails);

		foreach($explode_emails as $email) {
			//Load email library
			$this->load->library('email');

			$from_email = "admin@titan.com";
			$to_email = $email;

			$subject = $this->user->info->username.' has send you a proposal';

			$url = base_url()."proposals/view/$proposal_id/$proposal_type";

			$body = "<html><body><table><tr><td>Hello,</td></tr><tr><td>Please click on below link to view proposal</td></tr><tr><td>$url</td></tr></table></body></html>";

			$this->email->from($from_email);
			$this->email->to($to_email);

			$this->email->subject($subject);
			$this->email->message($body);

			if($this->email->send())
				//$this->session->set_flashdata("email_sent", "Proposal has been sent successfully.");
				echo 0;
			else
				//$this->session->set_flashdata("email_sent", "Error in sending Proposal!");
				echo 1;
		}*/

		echo 0;
	}

	public function delete_proposal($proposal_id) {
		/*if(!$this->common->has_permissions(array("admin", "project_admin",
            "task_manage", "task_worker"),
            $this->user))
        {
            $this->template->error(lang("error_71"));
        }*/

		/*if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}*/

		$proposal_id = intval($proposal_id);

		$proposal = $this->proposals_model->get_user_proposal_by_id($proposal_id);

		if(empty($proposal)) {
			$this->template->error(lang("error_166"));
		}

		$this->proposals_model->delete_proposal($proposal_id);

		$this->session->set_flashdata("globalmsg", lang("success_82"));

		redirect(site_url("proposals/index/0"));
	}

	public function delete_template($template_id) {
		/*if(!$this->common->has_permissions(array("admin", "project_admin",
            "task_manage", "task_worker"),
            $this->user))
        {
            $this->template->error(lang("error_71"));
        }*/

		/*if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}*/

		$template_id = intval($template_id);

		$template = $this->proposals_model->get_template_by_id($template_id);

		if(empty($template)) {
			$this->template->error(lang("error_166"));
		}

		$this->proposals_model->delete_template($template_id);

		$this->session->set_flashdata("globalmsg", lang("success_82"));

		redirect(site_url("proposals/index/templates"));
	}

}

?>