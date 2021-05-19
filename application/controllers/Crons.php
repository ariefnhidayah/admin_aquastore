<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crons extends CI_Controller {

    public function delete_image() {
        $this->load->library('api');

        $get = $this->api->get($this->api->url_media . 'media');
        if ($get['status'] == 'success') {
            $images = $get['data'];
            $image_null = [];
            foreach ($images as $image) {
                $product_image = $this->main->get('product_images', ['image' => $image['image']]);
                $products = $this->main->get('products', ['thumbnail' => $image['image']]);
                $seller_balances = $this->main->get('seller_balances', ['confirm_image' => $image['image']]);
                $user_balances = $this->main->get('user_balances', ['confirm_image' => $image['image']]);

                if (!$product_image && !$products && !$seller_balances && !$user_balances) {
                    array_push($image_null, $image['image']);
                }
            }

            if (count($image_null) > 0) {
                foreach ($image_null as $img) {
                    $this->api->post($this->api->url_media . 'media/delete', ['image' => $img]);
                }
            }
        }
    }

}