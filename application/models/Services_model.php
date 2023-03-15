<?php

class Services_Model extends CI_Model 
{

	public function add_service($data) 
	{
		$this->db->insert("service_forms", $data);
		return $this->db->insert_id();
	}

	public function add_field($data) 
	{
		$this->db->insert("service_form_fields", $data);
	}

	public function get_services_total() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("service_forms");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_services($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"service_forms.title",
			"users.username",
			)
		);

		return $this->db
			->select("service_forms.ID, service_forms.title, service_forms.cost,
				service_forms.invoice, service_forms.welcome, 
				service_forms.currencyid, service_forms.invoice_message,
				users.username, users.avatar, users.online_timestamp")
			->join("users", "users.ID = service_forms.userid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("service_forms");
	}

	public function get_service($id) 
	{
		return $this->db
			->where("service_forms.ID", $id)
			->select("service_forms.ID, service_forms.title, service_forms.cost,
				service_forms.invoice, service_forms.welcome, 
				service_forms.currencyid, service_forms.invoice_message,
				service_forms.require_login, service_forms.userid,
				users.username, users.avatar, users.online_timestamp, users.email 
				as assigned_email, 
				users.email_notification as assigned_email_notification")
			->join("users", "users.ID = service_forms.userid", "left outer")
			->get("service_forms");
	}

    public function get_service_data($id)
    {
        return $this->db
            ->where("services.ID", $id)
            ->select("services.ID, services.name, services.price, services.description, 
                services.notes,
                services.recurring, services.interval, services.interval_label, services.set_price, 
				services.hours, services.filename, services.filetype, services.encrypt,
				users.username, users.avatar, users.online_timestamp, users.email 
				as assigned_email, 
				users.email_notification as assigned_email_notification")
            ->join("users", "users.ID = services.user_id", "left outer")
            ->get("services");
    }

	public function delete_service($id) 
	{
		$this->db->where("ID", $id)->delete("service_forms");
	}

	public function update_service($id, $data) 
	{
		$this->db->where("ID", $id)->update("service_forms", $data);
	}

	public function get_form_fields($id) 
	{
		return $this->db->where("formid", $id)->get("service_form_fields");
	}

	public function delete_form_field($id) 
	{
		$this->db->where("ID", $id)->delete("service_form_fields");
	}

	public function update_form_field($id, $data) 
	{
		$this->db->where("ID", $id)->update("service_form_fields", $data);
	}

	public function add_user_service($data) 
	{
		$this->db->insert("user_services", $data);
		return $this->db->insert_id();
	}

	public function add_user_service_answer($data) 
	{
		$this->db->insert("user_service_fields", $data);
	}

	public function get_orders_total() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("user_services");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_orders($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"service_forms.title",
			"user_services.email",
			"users.username"
			)
		);

		return $this->db
			->select("service_forms.ID as serviceid, service_forms.title,
				users.username, users.avatar, users.online_timestamp,
				user_services.ID, user_services.total_cost, user_services.invoiceid, 
				user_services.email, user_services.timestamp, user_services.IP,
				user_services.name,
				invoices.status")
			->join("users", "users.ID = user_services.userid", "left outer")
			->join("service_forms", "service_forms.ID = user_services.formid")
			->join("invoices", "invoices.ID = user_services.invoiceid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("user_services");
	}

	public function get_order($id) 
	{
		return $this->db
			->where("user_services.ID", $id)
			->select("user_services.ID, user_services.email, user_services.timestamp,
				user_services.IP, user_services.userid, user_services.total_cost,
				user_services.invoiceid, user_services.name,
				service_forms.ID as formid, service_forms.title, service_forms.currencyid,
				service_forms.cost, service_forms.invoice_message,
				invoices.hash as invoice_hash")
			->join("users", "users.ID = user_services.userid", "left outer")
			->join("service_forms", "service_forms.ID = user_services.formid")
			->join("invoices", "invoices.ID = user_services.invoiceid", "left outer")
			->get("user_services");
	}

	public function delete_order($id) 
	{
		$this->db->where("ID", $id)->delete("user_services");
	}

	public function get_order_fields($serviceid, $formid) 
	{

		return $this->db
			->where("service_form_fields.formid", $formid)
			->select("service_form_fields.ID, service_form_fields.title,
				service_form_fields.description, service_form_fields.required,
				service_form_fields.type, service_form_fields.options, 
				service_form_fields.cost,
				user_service_fields.ID as usfid, user_service_fields.answer")
			->join("user_service_fields", "user_service_fields.fieldid = service_form_fields.ID AND user_service_fields.serviceid = " . $serviceid, "left outer")
			->get("service_form_fields");
		return $this->db->where("formid", $id)->get("service_form_fields");
	}

	public function delete_order_answers($id) 
	{
		$this->db->where("serviceid", $id)->delete("user_service_fields");
	}

	public function update_user_service($id, $data) 
	{
		$this->db->where("ID", $id)->update("user_services", $data);
	}

	public function get_services_custom($user_type = 0){
		if($user_type == 0){
			return $this->db
				->get("services")->result_array();
		}else{
			return $this->db
				->where('user_id', 0)
				->or_where('user_id', 1)
				->or_where('user_id', $user_type)
				->get("services")->result_array();
		}
	}
	
	public function get_task_service($id)
	{
		return $this->db
			->where('service_id', $id)
//			->where('parent_id', 0)
			->get("project_task_template")->result_array();
	}

    public function get_parent_task_service($id)
    {
        return $this->db
            ->where('service_id', $id)
			->where('parent_id', 0)
            ->get("project_task_template")->result_array();
    }

    public function get_task_price ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->price;
	}

    public function get_task_hours ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->hours;
    }

    public function get_task_description ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->description;
    }

    public function get_task_recurring ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->recurring;
    }

    public function get_task_interval ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->interval;
    }

    public function get_task_interval_label ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->interval_label;
    }

    public function get_task_filename ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->filename;
    }

    public function get_task_filetype ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->filetype;
    }

    public function get_task_file_encrypt($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->encrypt;
    }

    public function get_task_notes ($id)
    {
        return $this->db
            ->where('id', $id)
            ->get("services")->row()->notes;
    }

    public function get_main_task_service($id)
    {
        return $this->db
            ->where('service_id', $id)
			->where('parent_id', 0)
            ->get("project_task_template")->result_array();
    }

    public function get_subtask ($parentid)
    {
        return $this->db
            ->where('parent_id', $parentid)
            ->get("project_task_template")->result_array();
    }

	public function get_services_custom_name($id){
		return $this->db
			->where('id', $id)
			->get("services")->row()->name;
	}

	public function insert_service($params){
		$this->db->insert("services", $params);
		$insert_id = $this->db->insert_id($params);

		return $insert_id;
	}

    public function edit_service ($id, $data)
    {
        $this->db->where("ID", $id)->update("services", $data);
    }

	public function get_proposal_service($user_id, $service_id){
		$this->db->select('*');
		$this->db->from('proposal_services');
		$this->db->join('user_to_proposals', 'proposal_services.user_to_proposal_id=user_to_proposals.user_to_proposal_id');
		$this->db->where('user_to_proposals.user_id', $user_id);
		$this->db->where('proposal_services.service_id', $service_id);

		$query = $this->db->get();

		return $query->row();
	}

	public function get_proposal_service_tasks($service_id, $proposal_id){
		$this->db->select('proposal_services.proposal_service_id as service_proposal_service_id, proposal_services.service_id, proposal_services.user_to_proposal_id, proposal_services.name as service_name, proposal_services.description as service_description, proposal_services.recurring as service_recurring, proposal_services.interval, proposal_services.interval_label, proposal_services.notes, proposal_services.price as service_price, proposal_services.set_price, proposal_services.hours as service_hours, proposal_services.filename as service_filename, proposal_services.filetype as service_filetype, proposal_services.encrypt as service_encrypt, proposal_service_tasks.*');
		$this->db->from('proposal_service_tasks');
		$this->db->join('proposal_services', 'proposal_service_tasks.proposal_service_id=proposal_services.proposal_service_id');
		$this->db->where('proposal_services.service_id', $service_id);
		$this->db->where('proposal_services.user_to_proposal_id', $proposal_id);

		$query = $this->db->get();

		return $query->result_array();
	}

	public function insert_proposal_service($params){
		$this->db->insert("proposal_services", $params);
		$insert_id = $this->db->insert_id($params);

		return $insert_id;
	}
	
	public function edit_proposal_service ($id, $data)
    {
        $this->db->where("proposal_service_id", $id)->update("proposal_services", $data);
    }
	
	public function get_proposal_service_data($id)
    {
        return $this->db
            ->where("proposal_services.proposal_service_id", $id)
            ->get("proposal_services");
    }
	
	public function get_proposal_task_service($id)
	{
		return $this->db
			->where('proposal_service_id', $id)
			->get("proposal_service_tasks")->result_array();
	}
	
	public function check_proposal_service_task_exists($service_id, $task_id) {
		return $this->db
			->where('proposal_service_id', $service_id)
			->where('proposal_service_task_id', $task_id)
			->get("proposal_service_tasks")->row();
	}
	
	public function check_proposal_service_subtask_exists($proposal_service_id, $task_template_id) {
		$this->db->select('*');
		$this->db->from('proposal_service_tasks');
		$this->db->join('proposal_services', 'proposal_service_tasks.proposal_service_id=proposal_services.proposal_service_id');
        $this->db->where('proposal_service_tasks.proposal_service_id', $proposal_service_id);
		$this->db->where('proposal_service_tasks.project_task_template_id', $task_template_id);

		$query = $this->db->get();

		return $query->row();
	}
	
	public function get_proposal_service_task($proposal_id, $project_task_template_id){
		$this->db->select('*');
		$this->db->from('proposal_service_tasks');
		$this->db->join('proposal_services', 'proposal_service_tasks.proposal_service_id=proposal_services.proposal_service_id');
		$this->db->where('proposal_services.user_to_proposal_id', $proposal_id);
		$this->db->where('proposal_service_tasks.project_task_template_id', $project_task_template_id);

		$query = $this->db->get();

		return $query->row();
	}
}

?>