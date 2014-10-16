<?php
/*
Plugin Name: TheLink_Widget
Plugin URI: http://github.com/ritstudentgovernment/TheLink_Widget
Description: Displays "The Link" badge in a specified location.
Version: 0.3
Author: Colum McGaley
Author URI: http://volf.co
License: GPLv3
*/

/*
    TheLink_Widget.
    Copyright (C) 2014  Colum McGaley <c.mcgaley@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

    class FCCL_Widget extends WP_Widget {

        private $debug = false;
        private $link_url = "https://thelink.rit.edu/";

        function FCCL_Widget(){
            parent::WP_Widget(false, $name = __('Link Widget', 'FCCL_Widget') );
        }

        function form($instance) {
            echo "<p>Unless given an exemption, all sites are required to display this widget prominently on their site.</p>";

            // Get the values, if they exist
            if ($instance) {
                $link_name = esc_attr($instance['link_name']);
            } else {
                $link_name = '';
            }
            echo "<p><label for='".$this->get_field_id('link_name')."'>Link URL</label><input id='".$this->get_field_id('link_name')."' name='".$this->get_field_name('link_name')."' type='text' value='$link_name' /></p>";
        }

        function update($new_instance, $old_instance){
            $instance = $old_instance;
            $instance['link_name'] = strip_tags($new_instance['link_name']);

            return $instance;
        }

        function widget($args, $instance) {

            $site_meta = get_current_site();
            $site_name = $site_meta->site_name;

            extract( $args );
            $text = $instance['link_name'];

            // If the link is not defined, then try and pull it from the URL
            if (empty($text)) {
                $site_url_str = end(explode("/", get_site_url()));
                if (empty($site_url_str)) {
                    if ($this->debug == true) {
                        echo "<p>Warning. No URL defined. Is this being run under the root site?</p>";
                    } else {
                        echo "<!-- Warning. No URL defined. Is this being run under the root site? -->";
                    }
                    $link_url = $this->link_url; // If there is defined site, just link to the link
                }
            } else {
                $site_url_str = $text;
                $link_url = $this->link_url . "organization/" . $site_url_str;
            }


            $img_url = plugins_url( 'assets/thelink.png', __FILE__ );
            $img_tag = "<a href='$link_url'><img style='max-width: 100%;' src='$img_url' alt='Visit $site_name on the Link!' /></a>";

            echo "<div style='text-align: center'>$img_tag</div>";
        }
    }
    add_action('widgets_init', create_function('', 'return register_widget("FCCL_Widget");'));

?>
