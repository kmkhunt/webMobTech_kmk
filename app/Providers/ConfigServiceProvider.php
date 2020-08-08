<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       // echo "heres"; die;
        $arr_constant['site_url']                   =   url('/')."/";
        $arr_constant['admin_site_url']             =   url('admin');
        $arr_constant['root_path']                  =   base_path();
        $arr_constant['public_root_path']           =   public_path();
        $arr_constant['public_url']                 =   url('public');
        $arr_constant['application_root_path']      =   app_path();
        $arr_constant['asset_url']                  =   url('/');
     // $arr_constant['asset_url']                  =   url('/');

        $arr_constant['upload_root_path']           =   public_path('uploads/');
        $arr_constant['upload_url']                 =   url('public/uploads')."/";
        
        
        $arr_constant['image_size']                 =   10;
        $arr_constant['image_mimes']                =   "jpg,jpeg,png,gif";
        $arr_constant['upload_url']                 =   url('uploads')."/";
        
        $arr_constant['front_js_url']               =   $arr_constant['asset_url']."/js";
        $arr_constant['front_css_url']              =   $arr_constant['asset_url']."/css";
        $arr_constant['front_image_url']            =   $arr_constant['asset_url']."/images";
        $arr_constant['front_font_url']             =   $arr_constant['asset_url']."/fonts";


        $arr_constant['post_image_root']=$arr_constant['upload_root_path'].'post_images/';
        $arr_constant['post_image_url']=$arr_constant['upload_url'].'post_images/';
        $arr_constant['post_image_root_100X100'] =$arr_constant['post_image_root'].'100X100/thumb_';
        $arr_constant['post_image_url_100X100']=$arr_constant['post_image_url'].'100X100/thumb_';

        config($arr_constant);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //echo "heres2"; die;
        //
    }
}
