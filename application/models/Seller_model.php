<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller_model extends CI_Model {
    protected $table = 'sellers';

    function get_all($start = 0, $length, $search = '', $order = []) {
        $this->_where_like($search);
        if ($order) {
            $order['column'] = $this->_get_alias_key($order['column']);
            $this->db->order_by($order['column'], $order['dir']);
        }
        $this->db->limit($length, $start);
        return $this->db->get($this->table);
    }

    private function _get_alias_key($key) {
        switch ($key) {
            case 0: $key = 'store_name';
                break;
            case 1: $key = 'phone';
                break;
            case 2: $key = 'address';
                break;
            case 3: $key = 'status';
                break;
        }
        return $key;
    }

    function count_all($search = '') {
        $this->_where_like($search);
        return $this->db->count_all_results($this->table);
    }

    private function _where_like($search = '') {
        $columns = ['name', 'store_name', 'email', 'phone', 'address'];
        if ($search) {
            foreach($columns as $column) {
                $this->db->or_like('IFNULL('.$column.', "")', $search);
            }
        }
    }
}