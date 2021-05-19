<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Balance_user_model extends CI_Model {

    function get_all($start = 0, $length, $search = '', $order = [], $from, $to) {
        $this->where_like($search);
        if ($order) {
            $order['column'] = $this->get_alias_key($order['column']);
            $this->db->order_by($order['column'], $order['dir']);
        }
        $this->db->select('b.*, u.name')
                    ->join('users u', 'u.id = b.user_id', 'left')
                    ->limit($length, $start);
        return $this->db->get('user_balances b');
    }

    function count_all($search = '') {
        $this->where_like($search);
        return $this->db->count_all_results('user_balances b');
    }

    function get_alias_key($key) {
        switch ($key) {
            case 0: $key = 'b.created_at';
                break;
            case 1: $key = 'u.name';
                break;
            case 2: $key = 'b.amount';
                break;
            case 3: $key = 'b.status';
                break;
        }
        return $key;
    }

    function where_like($search = '') {
        $columns = ['b.created_at', 'u.name', 'b.amount', 'b.status'];
        if ($search) {
            foreach ($columns as $column) {
                $this->db->or_like('IFNULL(' . $column . ',"")', strtolower($search));
            }
        }
    }

}