<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proposals extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->model("user_model");
		$this->load->model("services_model");
		$this->load->model("proposals_model");
        $this->load->model("task_model");
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

	public function get_proposals($status=0) {
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_to_proposals.updated_at", "desc");

		// Set page ordering options that can be used
        if($status == 6) {
            $this->datatables->ordering(
                array(
                    0 => array(
                        "user_to_proposals.title" => 0
                    ),
                    1 => array(
                        "user_to_proposals.receiver_email" => 0
                    ),
                    2 => array(
                        "user_to_proposals.price" => 0
                    ),
                    3 => array(
                        "user_to_proposals.discount" => 0
                    ),
                    4 => array(
                        "discounted_price" => 0
                    ),
                    5 => array(
                        "user_to_proposals.payment_status" => 0
                    ),
                    6 => array(
                        "user_to_proposals.status" => 0
                    ),
                    7 => array(
                        "user_to_proposals.receiver_email" => 0
                    )
                )
            );
        } else {
            $this->datatables->ordering(
                array(
                    0 => array(
                        "user_to_proposals.title" => 0
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
                        "setup_service" => 0
                    ),
                )
            );
        }

		$this->datatables->set_total_rows($this->proposals_model->get_user_proposal_total($this->user->info->ID, $status));

		$proposals = $this->proposals_model->get_user_proposals($this->user->info->ID, $status, $this->datatables);

        //echo ""; print_r($proposals); die;

		foreach($proposals as $proposal) {
			if($proposal->status == 1) {
				$options = '<a target="_blank" href="'.site_url("proposals/view/".$proposal->user_to_proposal_id."/0").'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a> <a target="_blank" href="'.site_url("proposals/editor/" . $proposal->user_to_proposal_id).'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("proposals/delete_proposal/".$proposal->user_to_proposal_id).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
			} else {
				$options = '<a target="_blank" href="'.site_url("proposals/view/".$proposal->user_to_proposal_id."/2").'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a> <a href="'.site_url("proposals/delete_proposal/" . $proposal->user_to_proposal_id).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>';
			}

            if($proposal->payment_status == 0) {
				$payment_status = 'Not Paid';
			} else {
				$payment_status = 'Paid';
            }

			if($proposal->proposal_type == 0)
				$proposal_type = 'Not Editable';
			else
				$proposal_type = 'Editable';

			if($proposal->status == 1)
				$proposal_status = 'Draft';
			else if($proposal->status == 2)
				$proposal_status = 'Not Viewed';
			else if($proposal->status == 3)
				$proposal_status = 'Viewed';
			else if($proposal->status == 4)
				$proposal_status = 'Received Pending Review';
			else if($proposal->status == 5)
				$proposal_status = 'Received Pending Approval';
			else if($proposal->status == 6)
				$proposal_status = 'Accepted';
			else if($proposal->status == 7)
				$proposal_status = 'Declined';

			if($proposal->price != '') {
				$price = '$'.$proposal->price;
                $discounted_price = '$'.($proposal->price - (($proposal->price * $proposal->discount) / 100));
			} else {
                $discounted_price = '--';
				$price = '--';
            }

            if($proposal->discount != '') {
                $discount = $proposal->discount.'%';
			} else {
                $discount = '--';
            }

            if($proposal->expiration_date == '' || $proposal->expiration_date == '0000-00-00 00:00:00')
                $expiration_date = '--';
            else
                $expiration_date = date('d-m-Y', strtotime($proposal->expiration_date));

            if($status == 6) {
                if($proposal->payment_status == 0) {
                    $service_status = "<a href='".site_url("proposals/payment/".$proposal->user_to_proposal_id."/".$proposal->proposal_type) ."'>Setup</a>";
                } else {
                    $service_status = 'Done';
                }

                $this->datatables->data[] = array(
											$proposal->title,
                                            $proposal->receiver_email,
											$price,
                                            $discount,
                                            $discounted_price,
											$payment_status,
                                            $proposal_status,
											$service_status,
											$options
										);
            } else {
                $this->datatables->data[] = array(
											$proposal->title,
                                            $proposal->receiver_email,
											$price,
                                            $discount,
                                            $discounted_price,
											$expiration_date,
                                            $payment_status,
											$proposal_status,
											$options
										);
            }
		}

		echo json_encode($this->datatables->process());
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
							'content' => '<section><div class="row"><div class="col-md-12" data-type="container-content"><section data-type="component-text"><div class="inline-content"><blockquote class="generalinstructions"><em>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro labore architecto fuga tempore omnis aliquid, rerum numquam deleniti ipsam earum velit aliquam deserunt, molestiae officiis mollitia accusantium suscipit fugiat esse magnam eaque cumque, iste corrupti magni?</em><br></blockquote></div></section></div></div></section><section><div class="row"><div class="col-lg-12" data-type="container-content"><section data-type="component-text"><div class="inline-content"><h2>[[Service Details]]</h2><div id="service_details"></div></div></section></div></div><div class="row"><div class="col-lg-12" data-type="container-content"><section data-type="component-text"><div class="inline-content"><h2>[[Pricing Details]]</h2><div id="service_pricing_details"></div></div></section></div></div></section>',
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

		if(!empty($user_proposal_data)) {
			$proposal_id = $user_proposal_data->user_to_proposal_id;

			$services = $this->services_model->get_services_custom($user_id);

			$services_list = array();

			foreach($services as $key => $service) {
                $services_list[$key]['id'] = $service['id'];
                $services_list[$key]['user_id'] = $service['user_id'];
                $services_list[$key]['name'] = $service['name'];
                $services_list[$key]['occurrence'] = $service['recurring'];
                $services_list[$key]['interval'] = $service['interval'];
                $services_list[$key]['interval_label'] = $service['interval_label'];
                $services_list[$key]['description'] = $service['description'];

                $services_list[$key]['tasks'] = $this->services_model->get_main_task_service($service['id']);
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

        $price = $this->input->post('price');

		if($title == '')
			$title = 'Untitled';

		$this->proposals_model->update_proposal($proposal_id, array('title' => $title, 'content' => $content, 'status' => $status, 'price' => $price));

		echo 0;
	}

	public function view($proposal_id, $proposal_type) {
		$this->template->set_layout('layout/themes/proposal_view_layout.php');

		$proposal = $this->proposals_model->get_user_proposal_by_id($proposal_id);

        if(!empty($proposal) && $proposal_type == 0 && $proposal->status == 2)
            $this->proposals_model->update_proposal($proposal_id, array('status' => 3));

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
						'image' => 'no_image_available.png',
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

        $price = $this->input->post('price');

		$expiration_date = date('Y-m-d', strtotime($proposal_validity));

		if($title == '')
			$title = 'Untitled';

        //preg_match("/\[(.*)\]/", $emails , $matches);

        //$save_emails = $matches[1];

        //echo $save_emails;

		$this->proposals_model->update_proposal($proposal_id, array('title' => $title, 'price' => $price, 'receiver_email' => $emails, 'discount' => $discount, 'expiration_date' => $expiration_date, 'status' => 2));

        //Load email library
        $this->load->library('email');

        $from_email = "admin@titan.com";
        $to_email = $emails;

        $subject = $this->user->info->username.' has send you a proposal';

        $url = base_url()."proposals/view/$proposal_id/$proposal_type";

        $body = "<html><body><table><tr><td>Hello,</td></tr><tr><td>Please click on below link to view proposal</td></tr><tr><td><a target='_blank' href='$url'>Click here</a></td></tr></table></body></html>";

        $headers 	= "MIME-Version: 1.0" . "\r\n"; 
        $headers 	.= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers 	.= 'From: Titan <admin@titan.com>' . "\r\n";

        mail($to_email, $subject, $body, $headers);

        /*$explode_emails = explode(',', $save_emails);

		foreach($explode_emails as $email) {
			//Load email library
			$this->load->library('email');

			$from_email = "admin@titan.com";
			$to_email = str_replace('"', '', $email);

			$subject = $this->user->info->username.' has send you a proposal';

			$url = base_url()."proposals/view/$proposal_id/$proposal_type";

			$body = "<html><body><table><tr><td>Hello,</td></tr><tr><td>Please click on below link to view proposal</td></tr><tr><td><a target='_blank' href='$url'>Click here</a></td></tr></table></body></html>";

            $headers 	= "MIME-Version: 1.0" . "\r\n"; 
            $headers 	.= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
            $headers 	.= 'From: Titan <admin@titan.com>' . "\r\n";

			mail($to_email, $subject, $body, $headers);

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
    
     public function deleteService(){ 
        if($this->input->post('deleteService')){ 
            $service_id = $this->input->post('service_id');
            $proposal_id = $this->input->post('proposal_id');
            $proposal_service = $this->services_model->get_proposal_service($this->user->info->ID, $service_id);
            if(!empty($proposal_service)){
                $this->proposals_model->delete_services($service_id, $proposal_id);
            }
        }
    }

    public function checkUpdateStatus($user_to_proposal_id){ 
        if($this->input->post('deleteService')){ 
            $service_id = $this->input->post('service_id');
            $proposal_id = $this->input->post('proposal_id');
            $proposal_service = $this->services_model->get_proposal_service($this->user->info->ID, $service_id);
            if(!empty($proposal_service)){
                $this->proposals_model->delete_services($service_id, $proposal_id);
            }
        }
    }

    public function getproposalServices($proposal_id){ 
        $proposalServices = $this->proposals_model->get_proposal_services(array('user_to_proposal_id'=>$proposal_id));

        if(!empty($proposalServices))
            echo json_encode($proposalServices);
    }

    public function getproposalServicesStatus($proposal_id, $service_id){ 
        $proposalServices = $this->proposals_model->get_proposal_services(array('proposal_service_id'=> $service_id,'user_to_proposal_id'=>$proposal_id));

        if(!empty($proposalServices))
             echo $proposalServices[0]['update_status'];
    }
    
    public function updateproposalServicesStatus(){ 
        if($this->input->post('updateServicesStatus')){
             $proposal_id    =   $this->input->post('proposal_id');
             $service_id     =   $this->input->post('service_id');
             $res =    $this->proposals_model->updateproposalServicesStatus($service_id, $proposal_id, 0);
             if($res) {
                 echo 1;
             }
        }
    }

    public function addProposalServicesAndTasks($service_id, $proposal_id) {
        $service = $this->proposals_model->get_original_service($service_id);

        $proposal_service_id = $this->services_model->insert_proposal_service(array(
                                                        'service_id' => $service_id,
                                                        'user_to_proposal_id' => $proposal_id,
                                                        'name' => $service->name,
                                                        'description' => $service->description,
                                                        'notes' => $service->notes,
                                                        'price' => $service->price,
                                                        'hours' => $service->hours,
                                                        'set_price' => $service->set_price,
                                                        'recurring' => $service->recurring,
                                                        'interval' => $service->interval,
                                                        'interval_label' => $service->interval_label,
                                                        'filename' => $service->filename,
                                                        'filetype' => $service->filetype,
                                                        'encrypt' => $service->encrypt
                                                    ));

        $service_tasks_list = $this->proposals_model->get_original_service_tasks($service_id);

        foreach($service_tasks_list as $service_task) {
            if($service_task['is_subtask'] == 0) {
                $parent_task_id = $this->task_model->insert_proposal_service_task(array(
                                                                                    'proposal_service_id' => $proposal_service_id,
                                                                                    'project_task_template_id' => $service_task['ID'],
                                                                                    'is_subtask' => 0,
                                                                                    'parent_id' => 0,
                                                                                    'name' => $service_task['name'],
                                                                                    'description' => $service_task['description'],
                                                                                    'price' => $service_task['price'],
                                                                                    'billing_role' => $service_task['billing_role'],
                                                                                    'recurring' => $service_task['recurring'],
                                                                                    'due_count' => $service_task['due_count'],
                                                                                    'hours' => $service_task['hours'],
                                                                                    'filename' => $service_task['filename'],
                                                                                    'filetype' => $service_task['filetype'],
                                                                                    'encrypt' => $service_task['encrypt'],
                                                                                    'start_date' => $service_task['start_date'],
                                                                                    'due_date' => $service_task['due_date'],
                                                                                    'status' => $service_task['status'],
                                                                                    'userid' => $service_task['userid'],
                                                                                    'complete' => $service_task['complete'],
                                                                                    'complete_sync' => $service_task['complete_sync'],
                                                                                    'archived' => $service_task['archived']
                                                                                 ));
            } else {
                $task_id = $this->task_model->insert_proposal_service_task(array(
                                                                                    'proposal_service_id' => $proposal_service_id,
                                                                                    'project_task_template_id' => $service_task['ID'],
                                                                                    'is_subtask' => 1,
                                                                                    'parent_id' => $parent_task_id,
                                                                                    'name' => $service_task['name'],
                                                                                    'description' => $service_task['description'],
                                                                                    'price' => $service_task['price'],
                                                                                    'billing_role' => $service_task['billing_role'],
                                                                                    'recurring' => $service_task['recurring'],
                                                                                    'due_count' => $service_task['due_count'],
                                                                                    'hours' => $service_task['hours'],
                                                                                    'filename' => $service_task['filename'],
                                                                                    'filetype' => $service_task['filetype'],
                                                                                    'encrypt' => $service_task['encrypt'],
                                                                                    'start_date' => $service_task['start_date'],
                                                                                    'due_date' => $service_task['due_date'],
                                                                                    'status' => $service_task['status'],
                                                                                    'userid' => $service_task['userid'],
                                                                                    'complete' => $service_task['complete'],
                                                                                    'complete_sync' => $service_task['complete_sync'],
                                                                                    'archived' => $service_task['archived']
                                                                                 ));
            }
        }

        echo 0;
    }

    public function updateUserProposal(){ 
        if($this->input->post('updateUserProposal')){ 
            $proposal_id = $this->input->post('proposal_id');
            $status = $this->input->post('status');
            $res = $this->proposals_model->update_proposal($proposal_id, array('status' => $status));
            echo  $res ;
        }
    }
    
    //Redirect to Payment Page
    public function payment($user_to_proposal_id, $proposal_type){
       $data['proposals'] =  $this->proposals_model->get_proposals(array('user_to_proposal_id'=>$user_to_proposal_id, "user_id"=>$this->user->info->ID));
       if(!empty($data['proposals'])){
           $data['praposal_services'] =  $this->proposals_model->get_proposal_services(array('user_to_proposal_id'=>$user_to_proposal_id));
       }
       $this->template->loadContent("proposal/payment_view.php", $data);
    }
 
    //Mark As Paid
    public function mark_as_paidServices(){
        if($this->input->post('user_to_proposal_id')){
        	if (!empty($_POST['proposal_service_id']) && !empty($_POST['start_date'])) {  
				$proposal_service_id 			= $_POST['proposal_service_id'];
				$start_date 		            = $_POST['start_date'];

				for ($i = 0; $i < count($proposal_service_id); $i++) {
                    $where  =   array("proposal_service_id"=>$proposal_service_id[$i], "user_to_proposal_id"=>$this->input->post('user_to_proposal_id'));
                    $data   =   array("start_date"=>date("Y-m-d", strtotime($start_date[$i])));
					$this->proposals_model->update_proposal_service($proposal_service_id[$i], $data);	
				}
                $update   =  array('payment_comment'=>$this->input->post('payment_comment'),"payment_status"=>1);
                $updated  =  $this->proposals_model->update_proposal($this->input->post('user_to_proposal_id'), $update);
                if($updated==0){
                    redirect(site_url("proposals/index/6"));
                }
            }
        }
    }
    
    public function templates() {
		if(!$this->common->has_permissions(array("admin"), $this->user)) {
			$this->template->error(lang("error_2"));
		}

		$templates = array();
		$templates = $this->proposals_model->get_user_templates(0);

		//echo "<pre>"; print_r($templates); die;

		if(!empty($templates))
			$templates = $templates;

		$this->template->loadData("all_templates_cnt", count($templates));

		$this->template->loadContent("proposal/templates/index.php", array('templates' => $templates));
	}

	public function add() {
		if(!$this->common->has_permissions(array("admin"), $this->user)) {
			$this->template->error(lang("error_2"));
		}

		if($this->input->post()) {
			$title = $this->common->nohtml($this->input->post("title"));
			$content = $this->lib_filter->go($this->input->post("content"));

            $this->proposals_model->save_proposal_as_template(array("userid" => 0, 'image' => '', 'title' => $title, 'content' => $content));

			$this->session->set_flashdata("globalmsg", lang("success_81"));

			redirect(site_url("proposals/templates"));
		}

		$this->template->loadData("activeLink", array("templates" => array("general" => 1)));

		$this->template->loadContent("proposal/templates/add.php");
	}

	public function edit_template($template_id = 0) {
		$this->template->loadData("activeLink", array("templates" => array("general" => 1)));

		if($template_id != 0) {
			$template = $this->proposals_model->get_template_by_id($template_id);

			$template_id = $template->template_id;

			if($this->input->post()) {
				$title = $this->common->nohtml($this->input->post("title"));
				$content = $this->lib_filter->go($this->input->post("content"));

                $this->proposals_model->edit_template($template_id, array('image' => '', 'title' => $title, 'content' => $content));

				$this->session->set_flashdata("globalmsg", lang("success_81"));

				redirect(site_url("proposals/templates"));
			}
		}

		$this->template->loadContent("proposal/templates/edit.php", array('template_id' => $template_id, 'template' => $template));
	}

}

?>