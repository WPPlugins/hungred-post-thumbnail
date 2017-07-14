<?php
/*  Copyright 2009  Clay Lua  (email : clay@hungred.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $post;
global $wpdb;
$htp_post = $post->ID;
$hpt_flag = false;
//retrieve the draft image for display
$htp_table = $wpdb->prefix."hungred_post_thumbnail_draft";
$htp_query = "SELECT `hpt_url` FROM `".$htp_table."` WHERE `hpt_post` = '".$htp_post."' limit 1";
$htp_row = $wpdb->get_row($htp_query,ARRAY_A);
$htp_draft_img = "<img src='".$htp_row['hpt_url']."'/>";
//retrieve the live image for display
$htp_table = $wpdb->prefix."hungred_post_thumbnail";
$htp_query = "SELECT `hpt_url` FROM `".$htp_table."` WHERE `hpt_post` = '".$htp_post."'  limit 1";
$htp_row = $wpdb->get_row($htp_query,ARRAY_A);
$htp_live_img = "<img src='".$htp_row['hpt_url']."'/>";
//check for displaying different title
if($htp_row['hpt_url'] != "")
	$hpt_flag = true;
//retrieve the loading image for display
$htp_table = $wpdb->prefix."hungred_post_thumbnail_options";
$htp_query = "SELECT `hpt_loading_url` FROM `".$htp_table."` WHERE 1  limit 1";
$htp_row = $wpdb->get_row($htp_query,ARRAY_A);
$htp_load_img = "<img src='".$htp_row['hpt_loading_url']."'/>";


?>

<div id="hpt_main">
		<input name="hpt_files" id="hpt_files" size="27" type="file" />
		<input id="hpt_upload" type="button" name="action" value="Upload" disabled onclick="hpt_redirect()"/>
		<h3><?php  
		if($hpt_flag)
		_e("Uploaded thumbnail");
		else
		_e("No thumbnail uploaded for this post");
		?></h3>
		<iframe id='hpt_iframe' name='hpt_iframe'>
		</iframe>
		<div id="hpt_loading"><?php echo $htp_load_img;?></div>
		<div id="hpt_image_container">
			<p><?php _e("Draft Image");?></p>
			<div id="hpt_image"><?php  echo $htp_draft_img; ?></div>
			<p><?php _e("Live Image");?></p>
			<div id="hpt_image_live"><?php echo $htp_live_img; ?></div>
		</div>
		<input type="hidden" name="hpt_id" value="<?php echo $htp_post;?>" />
</div>
