<?php

class Proposals_Model extends CI_Model 
{
	public function get_public_templates()
	{
		return $this->db
			->where('user_id', 0)
			->where('status', 1)
			->get("user_to_proposal_templates")->result_array();
	}

	public function get_user_templates($user_id, $datatable = '')
	{
		if($datatable != '')
			return $this->db
					->where('user_id', $user_id)
					->where('status', 1)
					->limit($datatable->length, $datatable->start)
					->get("user_to_proposal_templates")->result();
		else
			return $this->db
					->where('user_id', $user_id)
					->where('status', 1)
					->get("user_to_proposal_templates")->result_array();
	}

	public function get_user_all_proposals($user_id) {
		return $this->db
			->where('user_id', $user_id)
			->get("user_to_proposals")->result();
	}

	public function get_user_proposal_count($user_id) {
		$query = $this->db->query("SELECT
								   SUM(CASE status
										  WHEN 1 THEN 1
										  ELSE 0
									   END) AS draft_count
								   , SUM(CASE status
											WHEN 2 THEN 1
											ELSE 0
										 END) AS sent_pending_review_count
								   , SUM(CASE status
											WHEN 3 THEN 1
											ELSE 0
										 END) AS sent_pending_approval_count
									, SUM(CASE status
											WHEN 4 THEN 1
											ELSE 0
										 END) AS received_pending_review_count
									, SUM(CASE status
											WHEN 5 THEN 1
											ELSE 0
										 END) AS received_pending_approval_count
									, SUM(CASE status
											WHEN 6 THEN 1
											ELSE 0
										 END) AS accepted_count
									, SUM(CASE status
											WHEN 7 THEN 1
											ELSE 0
										 END) AS declined_count
								FROM user_to_proposals
								WHERE user_id = $user_id");

		return $query->result();
	}

	public function get_user_proposals($user_id, $status, $datatable) {
		if($status == 0)
			return $this->db
					->where('user_id', $user_id)
                    ->order_by('updated_at', "desc")
					->limit($datatable->length, $datatable->start)
					->get("user_to_proposals")->result();
		else
			return $this->db
					->where('user_id', $user_id)
					->where('status', $status)
                    ->order_by('updated_at', "desc")
					->limit($datatable->length, $datatable->start)
					->get("user_to_proposals")->result();
	}

	public function get_user_proposal_total($user_id, $status=0) {
		if($status == 0)
            $s = $this->db->select("COUNT(*) as num")->where("user_id", $user_id)->get("user_to_proposals");
        else
            $s = $this->db->select("COUNT(*) as num")->where("user_id", $user_id)->where("status", $status)
			->get("user_to_proposals");

		$r = $s->row();

		if(isset($r->num)) return $r->num;

		return 0;
	}

	public function get_user_template_total($user_id) {
		$s = $this->db
			->select("COUNT(*) as num")
			->where("user_id", $user_id)
			->get("user_to_proposal_templates");

		$r = $s->row();

		if(isset($r->num)) return $r->num;

		return 0;
	}
	
	public function get_template_by_id($template_id)
	{
		return $this->db
			->where('template_id', $template_id)
			->get("user_to_proposal_templates")->row();
	}

	public function get_user_proposal_by_id($proposal_id)
	{
		return $this->db
			->where('user_to_proposal_id', $proposal_id)
			->get("user_to_proposals")->row();
	}

	public function get_user_proposal($user_id) {
		return $this->db
			->where('user_id', $user_id)
			->where('status', 1)
			->order_by('user_to_proposal_id', "desc")->limit(1)
			->get("user_to_proposals")->row();
	}

	public function create_proposal($params) {
		//print_r($params); die;

		$this->db->insert("user_to_proposals", $params);
		$insert_id = $this->db->insert_id();

		return  $insert_id;
	}

	public function update_proposal($proposal_id, $data) {
		$this->db->where("user_to_proposal_id", $proposal_id)->update("user_to_proposals", $data);

		return  0;
	}

	public function save_proposal_as_template($data) {
		$this->db->insert("user_to_proposal_templates", $data);
		$insert_id = $this->db->insert_id();

		return  $insert_id;
	}

	public function delete_proposal($proposal_id) {
		$this->db->where("user_to_proposal_id", $proposal_id)->delete("user_to_proposals");
	}
	
	public function delete_template($template_id) {
		$this->db->where("template_id", $template_id)->delete("user_to_proposal_templates");
	}

    public function update_proposal_service_update_status($proposal_service_id) {
        $this->db->where("proposal_service_id", $proposal_service_id)->update("proposal_services", array('update_status' => 1));
    }
    
    public function updateproposalServicesStatus($proposal_service_id, $proposal_id , $status=0) {
      $insert_id =  $this->db->where("proposal_service_id", $proposal_service_id)->update("proposal_services", array('update_status' => $status));
      return $insert_id;
    }

    public function delete_services($service_id, $proposal_id) {
       $query = $this->db->query("  
         DELETE proposal_services,proposal_service_tasks
                FROM proposal_services
                LEFT JOIN proposal_service_tasks ON proposal_service_tasks.proposal_service_id=proposal_services.proposal_service_id
                WHERE proposal_services.service_id = $service_id AND proposal_services.user_to_proposal_id = $proposal_id");

		return $query->result();
	}
    
    public function get_proposal_services($where){
		return $this->db
			->where($where)
			->get("proposal_services")->result_array();
	}
    
    public function get_original_service($service_id) {
        return $this->db->where('id', $service_id)->get("services")->row();
    }
    
    public function get_original_service_tasks($service_id) {
        return $this->db->where('service_id', $service_id)->get("project_task_template")->result_array();
    }
    
    public function get_service_data($service_id)
    {
        return $this->db
            ->where("proposal_services.proposal_service_id", $service_id)
            ->select("proposal_services.proposal_service_id as ID, proposal_services.name, proposal_services.price, proposal_services.description, proposal_services.notes, proposal_services.recurring, proposal_services.interval, proposal_services.interval_label, proposal_services.set_price, proposal_services.hours, proposal_services.filename, proposal_services.filetype, proposal_services.encrypt")
            //->join("users", "users.ID = proposal_services.user_id", "left outer")
            ->get("proposal_services");
    }
 
    public function get_task_service($service_id)
	{
		return $this->db
			->where('proposal_service_id', $service_id)
            ->select("proposal_service_tasks.proposal_service_task_id as ID, proposal_service_tasks.is_subtask, proposal_service_tasks.parent_id, proposal_service_tasks.name, proposal_service_tasks.description, proposal_service_tasks.price, proposal_service_tasks.billing_role, proposal_service_tasks.recurring, proposal_service_tasks.due_count, proposal_service_tasks.hours, proposal_service_tasks.filename, proposal_service_tasks.filetype, proposal_service_tasks.encrypt, proposal_service_tasks.start_date, proposal_service_tasks.due_date, proposal_service_tasks.status, proposal_service_tasks.userid, proposal_service_tasks.complete, proposal_service_tasks.complete_sync, proposal_service_tasks.archived")
			->get("proposal_service_tasks")->result_array();
	}

    public function get_service($where)
	{
		return $this->db->where($where)->get("services")->row();
	}

    public function get_proposals($where)
	{
		return $this->db
			->where($where)
			->get("user_to_proposals")->row();
	}

    public function update_proposal_service($where, $data) {
        $this->db->where($where)->update("proposal_services", $data);
    }
}

?>