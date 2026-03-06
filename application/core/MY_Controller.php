<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    // Fungsi inilah yang dicari oleh sistem
    protected function log_activity($type, $description) {
        $this->load->database();
        $user_id = $this->session->userdata('admin_id'); 

        $data = [
            'user_id'       => $user_id,
            'activity_type' => $type,
            'description'   => $description,
            'created_at'    => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('activity_logs', $data);
    }
}