<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends CI_Model {

    function get_all($start = 0, $length, $search = '', $order = [], $from, $to) {
        $this->where_like($search);
        if ($order) {
            $order['column'] = $this->get_alias_key($order['column']);
            $this->db->order_by($order['column'], $order['dir']);
        }
        $this->db->select('oi.*, u.name as user_name, s.store_name as store_name')
                    ->join('orders o', 'o.id = oi.order_id', 'left')
                    ->join('users u', 'u.id = o.user_id', 'left')
                    ->join('sellers s', 's.id = oi.seller_id', 'left')
                    ->where('oi.status', 2)
                    ->where('DATE(oi.created_at) BETWEEN \'' . $from . '\' AND \'' . $to . '\'')
                    ->limit($length, $start);
        return $this->db->get('order_invoices oi');
    }

    function count_all($search = '') {
        $this->where_like($search);
        return $this->db->join('orders o', 'o.id = oi.order_id', 'left')
                        ->join('users u', 'u.id = o.user_id', 'left')
                        ->join('sellers s', 's.id = oi.seller_id', 'left')
                        ->where('oi.status', 2)
                        ->count_all_results('order_invoices oi');
    }

    function get_alias_key($key) {
        switch ($key) {
            case 0: $key = 'oi.created_at';
                break;
            case 1: $key = 'oi.code';
                break;
            case 2: $key = 'u.name';
                break;
            case 3: $key = 's.store_name';
                break;
            case 4: $key = 'oi.subtotal';
                break;
            case 5: $key = 'oi.shipping_cost';
                break;
            case 6: $key = 'oi.total';
                break;
        }
        return $key;
    }

    function where_like($search = '') {
        $columns = ['oi.created_at', 'oi.code', 'u.name', 's.store_name', 'oi.subtotal', 'oi.shipping_cost', 'oi.total'];
        if ($search) {
            foreach ($columns as $column) {
                $this->db->or_like('IFNULL(' . $column . ',"")', $search);
            }
        }
    }

}