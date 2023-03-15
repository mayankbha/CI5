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
					->limit($datatable->length, $datatable->start)
					->get("user_to_proposals")->result();
		else
			return $this->db
					->where('user_id', $user_id)
					->where('status', $status)
					->limit($datatable->length, $datatable->start)
					->get("user_to_proposals")->result();
	}

	public function get_user_proposal_total($user_id) {
		$s = $this->db
			->select("COUNT(*) as num")
			->where("user_id", $user_id)
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

}

?>