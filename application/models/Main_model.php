<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

    function gets($table, $conditions = array(), $order = NULL, $group = NULL) {
        if ($order) {
            $this->db->order_by($order);
        }
        if ($group) {
            $this->db->group_by($group);
        }
        if (count($conditions) > 0) {
            $query = $this->db->get_where($table, $conditions);
        } else {
            $query = $this->db->get($table);
        }
        return $query;
    }

    function get($table, $conditions = array()) {
        $query = $this->db->get_where($table, $conditions);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function insert($table, $data = array()) {
        $query = $this->db->insert($table, $data);
        return ($query) ? $this->db->insert_id() : false;
    }

    function update($table, $data = array(), $condition = array()) {
        $this->db->where($condition);
        $this->db->update($table, $data);
        return($this->db->affected_rows() > 0) ? true : false;
    }

    function delete($table, $condition = array()) {
        $this->db->where($condition);
        $this->db->delete($table);
        return($this->db->affected_rows() > 0) ? true : false;
    }

    function count($table, $condition = array()) {
        if ($condition) {
            $this->db->where($condition);
        }
        return $this->db->count_all_results($table);
    }

    function truncate($table) {
        $query = $this->db->truncate($table);
        return($query) ? true : false;
    }

}