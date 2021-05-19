<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends CI_Model {

    protected $table = 'categories';

    function get_all($start = 0, $length, $search = '', $order = array()) {
        $this->where_like($search);
        if ($order) {
            $order['column'] = $this->get_alias_key($order['column']);
            $this->db->order_by($order['column'], $order['dir']);
        }
        $this->db->select('*')->limit($length, $start);
        return $this->db->get($this->table);
    }

    function get_alias_key($key) {
        switch ($key) {
            case 0: $key = 'name';
                break;
            case 1: $key = 'status';
        }
        return $key;
    }

    function count_all($search = '') {
        $this->where_like($search);
        return $this->db->count_all_results($this->table);
    }

    function where_like($search = '') {
        $columns = array('name');
        if ($search) {
            foreach ($columns as $column) {
                $this->db->or_like('IFNULL(' . $column . ',"")', $search);
            }
        }
    }

}