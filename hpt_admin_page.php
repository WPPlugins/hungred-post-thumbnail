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
require_once 'hpt_function.php';

global $wpdb;
$error = "";
$table = $wpdb->prefix."hungred_post_thumbnail_options";
// check whether it is a submission or not before proceeding to update.
if($_POST['hpt_width']>0)
{

//retrieve all the options data to compare against the new submitted data
$query = "SELECT * FROM `".$table."` WHERE 1 AND `hpt_id` = '1' limit 1";
$row = $wpdb->get_row($query,ARRAY_A);

//check whether there is a new update for default image
	if(isset($_FILES['hpt_image']) && $_FILES['hpt_image']['tmp_name'] != "")
	{
		$file_image = $_FILES['hpt_image'];
		$allowedExtensions = array("png", "jpg", "jpeg", "gif");
		if($file_image['error'] == UPLOAD_ERR_OK) {
			if(in_array(end(explode(".", strtolower($file_image['name']))), $allowedExtensions)) {
				$uploadfile = dirname(__FILE__)."/images/"."hpt-options-".$file_image['name'];
				$oripath = str_replace("hpt-options-", "hpt-options-backup-", $row['hpt_image']);
				if(file_exists($oripath) != false)
						unlink($oripath);
				if(file_exists($row['hpt_image']) != false)
						unlink($row['hpt_image']);
						
				$oripath = str_replace("hpt-options-", "hpt-options-backup-", $uploadfile);
				if($_POST['hpt_keep'] == "Y")
				{
					copy($file_image['tmp_name'], $oripath);
				}

				
				if (move_uploaded_file($file_image['tmp_name'], $uploadfile)) {
					$_POST['hpt_image_url'] = get_bloginfo('url').'/wp-content/plugins/hungred-post-thumbnail/images/'."hpt-options-".$file_image['name'];
					$_POST['hpt_image'] = $uploadfile;
					
					if(file_exists($_POST['hpt_image']) != false)
					smart_resize_image($_POST['hpt_image'], $_POST['hpt_width'], $_POST['hpt_height']);
				}
				else
					$error .=  "<p><div class='label'>fail uploading default image</div></p>";
			}
			else
			{
				$error .=  "<p><div class='label'>Invalid extension for uploading default image. Please Upload Gif, Png or Jpg Images Files Only.</div></p>";
			}
		}
		else
		{
			$error .=  "<p><div class='label'>Error uploading default image!</div></p>";
		}
	}
//check whether there is a new update for random image
	if(isset($_FILES['hpt_random_image']) && $_FILES['hpt_random_image']['tmp_name'] != "")
	{
		$file_image = $_FILES['hpt_random_image'];
		$allowedExtensions = array("png", "jpg", "jpeg", "gif");
		if($file_image['error'] == UPLOAD_ERR_OK) {
			if(in_array(end(explode(".", strtolower($file_image['name']))), $allowedExtensions)) {
				$uploadfile = dirname(__FILE__)."/images/random/".$file_image['name'];
				$oripath = str_replace("random", "original/random", $uploadfile);
				if($_POST['hpt_keep'] == "Y")
				{
					copy($file_image['tmp_name'], $oripath);
				}
				if (move_uploaded_file($file_image['tmp_name'], $uploadfile)) {
					$_POST['hpt_image'] = $uploadfile;
					smart_resize_image($_POST['hpt_image'], $_POST['hpt_width'], $_POST['hpt_height']);
				}
				else
					$error .=  "<p><div class='label'>fail uploading to random image folder</div></p>";
			}
			else
			{
				$error .=  "<p><div class='label'>Invalid extension for uploading random image. Please Upload Gif, Png or Jpg Images Files Only.</div></p>";
			}
		}
		else
		{
			$error .=  "<p><div class='label'>Error uploading random image!</div></p>";
		}
	}
//check whether there is a new update for random normal image
	if(isset($_FILES['hpt_random_normal_image']) && $_FILES['hpt_random_normal_image']['tmp_name'] != "")
	{
		$file_image = $_FILES['hpt_random_normal_image'];
		$allowedExtensions = array("png", "jpg", "jpeg", "gif");
		if($file_image['error'] == UPLOAD_ERR_OK) {
			if(in_array(end(explode(".", strtolower($file_image['name']))), $allowedExtensions)) {
				$uploadfile = dirname(__FILE__)."/images/random/"."hpt_".$file_image['name'];
				$oripath = str_replace("random", "original/random", $uploadfile);
				if($_POST['hpt_keep'] == "Y")
				{
					copy($file_image['tmp_name'], $oripath);
				}
				if (move_uploaded_file($file_image['tmp_name'], $uploadfile)) {
					$_POST['hpt_image'] = $uploadfile;
					smart_resize_image($_POST['hpt_image'], $_POST['hpt_width'], $_POST['hpt_height']);
				}
				else
					$error .=  "<p><div class='label'>fail uploading to random image folder</div></p>";
			}
			else
			{
				$error .=  "<p><div class='label'>Invalid extension for uploading random image. Please Upload Gif, Png or Jpg Images Files Only.</div></p>";
			}
		}
		else
		{
			$error .=  "<p><div class='label'>Error uploading random image!</div></p>";
		}
	}
//check whether there is a new update for loading image
	if(isset($_FILES['hpt_loading']) && $_FILES['hpt_loading']['tmp_name'] != "")
	{
		$file_loading = $_FILES['hpt_loading'];
		$allowedExtensions = array("gif");
		if($file_loading['error'] == UPLOAD_ERR_OK) {
			if(in_array(end(explode(".", strtolower($file_loading['name']))), $allowedExtensions)) {
				$uploadfile = dirname(__FILE__)."/images/hpt-options-loading.gif";
				if (move_uploaded_file($file_loading['tmp_name'], $uploadfile)) {
					$_POST['hpt_loading_url'] = get_bloginfo('url').'/wp-content/plugins/hungred-post-thumbnail/images/hpt-options-loading.gif';
				}
				else
					$error .=  "<p><div class='label'>fail uploading loading image</div></p>";
			}
			else
			{
				$error .=  "<p><div class='label'>Invalid extension for uploading loading image. Please Upload Gif Files Only.</div></p>";
			}
		}
		else
		{
			$error .=  "<p><div class='label'>Error uploading loading image!</div></p>";
		}
	}
//if upload doesn't have new images we must write the old path to be updated
	if($_POST['hpt_image'] == "")
	$_POST['hpt_image'] = $row['hpt_image'];
	if($_POST['hpt_image_url'] == "")
	$_POST['hpt_image_url'] = $row['hpt_image_url'];
	if($_POST['hpt_loading_url'] == "")
	$_POST['hpt_loading_url'] = $row['hpt_loading_url'];
	
//check whether there is a change on width and height before proceeding to resize
	if($row['hpt_width'] != $_POST['hpt_width'] || $row['hpt_height'] != $_POST['hpt_height'])
	{
//check whether the options from user is a yes to resize all images to avoid redundent resizing
		if($_POST['hpt_resize'] == "Y")
		{
			smart_resize_image($_POST['hpt_image'], $_POST['hpt_width'], $_POST['hpt_height']);
			resize_n_image(dirname(__FILE__)."/images/live/", $_POST['hpt_width'], $_POST['hpt_height']);
			resize_n_image(dirname(__FILE__)."/images/draft/", $_POST['hpt_width'], $_POST['hpt_height']);
			resize_n_image(dirname(__FILE__)."/images/random/", $_POST['hpt_width'], $_POST['hpt_height']);
		}
	}

//update the database with Replace instead of insert to avoid duplication data in the table
	$query = "REPLACE INTO $table(hpt_id, hpt_width, hpt_height, hpt_space, hpt_image, hpt_image_url, hpt_loc,hpt_exist,hpt_loading_url,hpt_space_color,hpt_space_bcolor,hpt_gap,hpt_resize,hpt_default_exist,hpt_link,hpt_rss,hpt_default_display,hpt_keep) 
	VALUES('1', '".$_POST['hpt_width']."', '".$_POST['hpt_height']."', '".$_POST['hpt_space']."','".$_POST['hpt_image']."','".$_POST['hpt_image_url']."', '".$_POST['hpt_location']."', '".$_POST['hpt_exist']."', '".$_POST['hpt_loading_url']."', '".$_POST['hpt_space_color']."', '".$_POST['hpt_space_bcolor']."', '".$_POST['hpt_gap']."', '".$_POST['hpt_resize']."', '".$_POST['hpt_default_exist']."', '".$_POST['hpt_link']."', '".$_POST['hpt_rss']."', '".$_POST['hpt_default_display']."', '".$_POST['hpt_keep']."')";
	$wpdb->query($query);

}
//retrieve new data
$query = "SELECT * FROM `".$table."` WHERE 1 AND `hpt_id` = '1' limit 1";
$row = $wpdb->get_row($query,ARRAY_A);


?>
<div class="hpt_wrap">
	<?php    echo "<h2>" . __( 'Hungred Thumbnail Post Options' ) . "</h2>"; ?>
	
	<form name="hpt_form" id="hpt_form" class="hpt_admin" onsubmit="return validate()" enctype="multipart/form-data" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="hpt_hidden" value="Y">
		<?php    echo "<h4>" . __( 'Hungred Thumbnail Post Settings' ) . "</h4>"; ?>
		<p><div class='label'><?php _e("Thumbnail Width: " ); ?></div><input type="text" id="hpt_width" name="hpt_width" value="<?php echo $row['hpt_width']; ?>" size="20"></p>
		<p><div class='label'><?php _e("Thumbnail Height: " ); ?></div><input type="text" id="hpt_height" name="hpt_height" value="<?php echo $row['hpt_height']; ?>" size="20"></p>
		<p><div class='label'><?php _e("Thumbnail Space: " ); ?></div><input type="text" id="hpt_space" name="hpt_space" value="<?php echo $row['hpt_space']; ?>" size="20"></p>
		<p><div class='label'><?php _e("Thumbnail Space Color: " ); ?></div><input type="text" id="hpt_space_color" name="hpt_space_color" value="<?php echo $row['hpt_space_color']; ?>" size="20"></p>
		<p><div class='label'><?php _e("Thumbnail Space Border Color: " ); ?></div><input type="text" id="hpt_space_bcolor" name="hpt_space_bcolor" value="<?php echo $row['hpt_space_bcolor']; ?>" size="20"></p>
		<p><div class='label'><?php _e("Thumbnail Gap: " ); ?></div><input type="text" id="hpt_gap" name="hpt_gap" value="<?php echo $row['hpt_gap']; ?>" size="20"></p>
		<p><div class='label'><?php _e("Thumbnail Location: " ); ?>
		</div><SELECT name="hpt_location">
		<?php 
		
		if($row['hpt_loc'] == "LEFT"){ ?>
		<option selected value="LEFT">LEFT</option>
		<option value="RIGHT">RIGHT</option>
		<option value="TOP">TOP</option>
		<option value="RAND">RANDOM</option>
		<?php }else if($row['hpt_loc'] == "RIGHT"){?>
		<option value="LEFT">LEFT</option>
		<option selected value="RIGHT">RIGHT</option>
		<option value="TOP">TOP</option>
		<option value="RAND">RANDOM</option>
		<?php }else if($row['hpt_loc'] == "TOP"){?>
		<option value="LEFT">LEFT</option>
		<option value="RIGHT">RIGHT</option>
		<option selected value="TOP">TOP</option>
		<option value="RAND">RANDOM</option>
		<?php }else if($row['hpt_loc'] == "RAND"){?>
		<option value="LEFT">LEFT</option>
		<option value="RIGHT">RIGHT</option>
		<option value="TOP">TOP</option>
		<option selected value="RAND">RANDOM</option>
		<?php }?>
		</SELECT>
		
		</p>
		
		<p><div class='label'><?php _e("Thumbnail Must Exist: " ); ?>
		</div><SELECT name="hpt_exist">
		<?php 
		if($row['hpt_exist'] == "E"){ ?>
		<option selected value="E">Excerpt Only</option>
		<option value="M">More Tag Only</option>
		<option value="B">Both</option>
		<option value="D">Disable</option>
		<?php }else if($row['hpt_exist'] == "M"){?>
		<option value="E">Excerpt Only</option>
		<option selected value="M">More Tag Only</option>
		<option value="B">Both</option>
		<option value="D">Disable</option>
		<?php }else if($row['hpt_exist'] == "B"){?>
		<option value="E">Excerpt Only</option>
		<option value="M">More Tag Only</option>
		<option selected value="B">Both</option>
		<option value="D">Disable</option>
		<?php }else if($row['hpt_exist'] == "D"){?>
		<option value="E">Excerpt Only</option>
		<option value="M">More Tag Only</option>
		<option value="B">Both</option>
		<option selected value="D">Disable</option>
		<?php }?>
		</SELECT>
		</p>
		
		<p><div class='label'><?php _e("Thumbnail Default Exist: " ); ?>
		</div><SELECT name="hpt_default_exist">
		<?php 
		if($row['hpt_default_exist'] == "Y"){ ?>
		<option selected value="Y">YES</option>
		<option value="N">NO</option>
		<?php }else if($row['hpt_default_exist'] == "N"){?>
		<option value="Y">YES</option>
		<option selected value="N">NO</option>
		<?php }?>
		</SELECT>
		</p>
		
		<p><div class='label'><?php _e("Thumbnail Default Display: " ); ?>
		</div><SELECT name="hpt_default_display">
		<?php 
		if($row['hpt_default_display'] == "S"){ ?>
		<option selected value="S">Single</option>
		<option value="R">Random</option>
		<option value="T">Smart</option>
		<option value="V">Advance</option>
		<?php }else if($row['hpt_default_display'] == "R"){?>
		<option value="S">Single</option>
		<option selected value="R">Random</option>
		<option value="T">Smart</option>
		<option value="V">Advance</option>
		<?php }else if($row['hpt_default_display'] == "T"){?>
		<option value="S">Single</option>
		<option value="R">Random</option>
		<option selected value="T">Smart</option>
		<option value="V">Advance</option>
		<?php }else if($row['hpt_default_display'] == "V"){?>
		<option value="S">Single</option>
		<option value="R">Random</option>
		<option value="T">Smart</option>
		<option selected value="V">Advance</option>
		<?php }?>
		</SELECT>
		</p>

		<p><div class='label'><?php _e("Thumbnail Link To Post: " ); ?>
		</div><SELECT id="hpt_link" name="hpt_link">
		<?php 
		if($row['hpt_link'] == "Y"){ ?>
		<option selected value="Y">YES</option>
		<option value="N">NO</option>
		<?php }else{?>
		<option value="Y">YES</option>
		<option selected value="N">NO</option>
		<?php }?>
		</SELECT>
		</p>
		
		<p><div class='label'><?php _e("Thumbnail On RSS: " ); ?>
		</div><SELECT id="hpt_rss" name="hpt_rss">
		<?php 
		if($row['hpt_rss'] == "Y"){ ?>
		<option selected value="Y">YES</option>
		<option value="N">NO</option>
		<?php }else if($row['hpt_rss'] == "N"){?>
		<option value="Y">YES</option>
		<option selected value="N">NO</option>
		<?php }?>
		</SELECT>
		</p>
		
		<p><div class='label'><?php _e("Thumbnail Resize All: " ); ?>
		</div><SELECT id="hpt_resize" name="hpt_resize">
		<?php 
		if($row['hpt_resize'] == "Y"){ ?>
		<option selected value="Y">YES</option>
		<option value="N">NO</option>
		<?php }else{?>
		<option value="Y">YES</option>
		<option selected value="N">NO</option>
		<?php }?>
		</SELECT>
		</p>
		
		<p><div class='label'><?php _e("Thumbnail Keep Original: " ); ?>
		</div><SELECT id="hpt_keep" name="hpt_keep">
		<?php 
		if($row['hpt_keep'] == "Y"){ ?>
		<option selected value="Y">YES</option>
		<option value="N">NO</option>
		<?php }else{?>
		<option value="Y">YES</option>
		<option selected value="N">NO</option>
		<?php }?>
		</SELECT>
		</p>
		<hr />
		
		<?php    echo "<h4>" . __( 'Hungred Thumbnail Upload Section' ) . "</h4>"; ?>
		<p><div class='label'><?php _e("Thumbnail Default Image: " ); ?></div><input type="file" id="hpt_images" name="hpt_image" size="20"></p>
		<p><div class='label'><?php _e("Thumbnail Loading Image: " ); ?></div><input type="file" id="hpt_loadings" name="hpt_loading" size="20"><img src="<?php echo $row['hpt_loading_url'];?>"/></p>
		<p><div class='label'><?php _e("Thumbnail Random Upload: " ); ?></div><input type="file" id="hpt_random_image" name="hpt_random_image" size="20">(This upload is for smart and random method, please name your files with keywords by using '-' to seperate each keyword in order to use smart method properly)</p>
		<p><div class='label'><?php _e("Thumbnail Normal Upload: " ); ?></div><input type="file" id="hpt_random_normal_image" name="hpt_random_normal_image" size="20"> (This upload will append a hpt_ to marked as normal image if smart method is used)</p>
		
		
		<hr />
		<?php    echo "<h4>" . __( 'Hungred Thumbnail File Management Section' ) . "</h4>"; ?>
		<p class="file_management">
		</div><input type="button" id="button_management" value="<?php _e('File Management') ?>" onclick="JavaScript: window.open('<?php echo get_bloginfo('url');?>/wp-content/plugins/hungred-post-thumbnail/File Management/hpt_file_management.php')"/>
		</p>
		
		<?php if($error != ""){?>
		<hr />
		<?php    echo "<h4>" . __( 'Hungred Thumbnail Error Section' ) . "</h4>"; ?>
		<p><div class='label'>
		<h2><?php _e("Error Message: " ); ?></h2>
		</div>
		<div class="hpt_red">
		<?php echo $error; ?>
		</div>
		</p>
		<?php }?>
		
		<hr />
		<h2><?php _e("WARNING" ); ?></h2>
		<p class="hpt_red">
		If the original image was not saved due to the options 'Thumbnail keep original' turned to off, your image will be resize using the current image size which might affect your resized resolution.
		</p>
		<p class="submit">
		</div><input type="submit" id="submit" value="<?php _e('Update Options' ) ?>" />
		</p>
		<div id="hpt_preview">
			<p><h2><?php _e("Default Image Preview: " ); ?></h2></p>
			<img style="border: <?php echo $row['hpt_space_bcolor'];?> solid <?php echo $row['hpt_space'];?>px;background:<?php echo $row['hpt_space_color'];?>;padding:<?php echo $row['hpt_gap'];?>px;" src="<?php echo $row['hpt_image_url'];?>"/></p>
		</div>
		<hr />
		<h2><?php _e("Support" ); ?></h2>
		<p>
		Please visit <a href="http://hungred.com/2009/07/14/useful-information/wordpress-plugin-hungred-post-thumbnail/">hungred.com</a> for any support enquiry or email <a href='clay@hungred.com'>clay@hungred.com</a>. You can also show your appreciation by saying 'Thanks' on the <a href='http://hungred.com/2009/07/14/useful-information/wordpress-plugin-hungred-post-thumbnail/'>plugin page</a> or visits our sponsors on <a href="http://hungred.com/2009/07/14/useful-information/wordpress-plugin-hungred-post-thumbnail/">hungred.com</a> to help us keep up with the maintanance. If you like this plugin, you can buy me a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=i_ah_yong%40hotmail%2ecom&lc=MY&item_name=Coffee&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted">coffee</a>! You can also support this development with the donation button. Thanks!
		<p>
<a href='http://www.pledgie.com/campaigns/5792'><img alt='Click here to lend your support to: Support Hungred Post Thumbnail and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/5792.png?skin_name=chrome' border='0' /></a>
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="ppbutton" onclick="window.open('https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=i_ah_yong%40hotmail%2ecom&lc=MY&item_name=Support%20Hungred%20Post%20Thumbnail%20Development&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest');return false;">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</p>
		</p>
	</form>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo get_bloginfo('url')?>/wp-content/plugins/hungred-post-thumbnail/css/hpt_ini.css"></link>
<script type="text/javascript">
/*
Name: validate
Usage: use to validate the form upon user submission
Parameter: 	NONE
Description: use to validate all the basic inputs by the users
*/
function validate()
{
	var width = document.getElementById('hpt_width');
	var height = document.getElementById('hpt_height');
	var space = document.getElementById('hpt_space');
	var gap = document.getElementById('hpt_gap');
	var space_color = document.getElementById('hpt_space_color');
	var space_bcolor = document.getElementById('hpt_space_bcolor');
	var defaultImg = document.getElementById('hpt_images');
	var loadingImg = document.getElementById('hpt_loadings');
	var random = document.getElementById('hpt_random_image');
	var normalrandom = document.getElementById('hpt_random_normal_image');
	var resizeAll = document.getElementById('hpt_resize');
	if(isNumeric(width, "Invalid number found in width"))
		if(isNumeric(height, "Invalid number found in height"))
			if(isNumeric(space, "Invalid number found in space"))
				if(isNumeric(gap, "Invalid number found in gap"))
					if(isAllowedColor(space_color, "Invalid color found in space color"))
						if(isAllowedColor(space_bcolor, "Invalid color found in border color"))
							if(isAllowedFileExtension(defaultImg, "Please Upload Gif, Png or Jpg Images Files Only."))
								if(isAllowedFileExtension(normalrandom, "Please Upload Gif, Png or Jpg Images Files Only."))
									if(isAllowedFileExtension(random, "Please Upload Gif, Png or Jpg Images Files Only."))
										if(isAllowedLoadFileExtension(loadingImg, "Please Upload Gif Files Only."))
											if(isResizeAll(resizeAll, "You have selected resize all, Are you sure?"))
											{
												return true;
											}
						
					
	return false;
	
}
/*
Name: isNumeric
Usage: use to validate width, height, space and gap text box
Parameter: 	elem: the DOM object of each element
			helperMsg: the pop out box message
Description: This is a simple method to check whether a given text box string contains 
			 numbers and '.' symbols
*/
function isNumeric(elem, helperMsg){
	var numericExpression = /^[0-9.]+$/;
	if(elem.value.match(numericExpression)){
		return true;
	}else{
		alert(helperMsg);
		elem.focus();
		return false;
	}
}
/*
Name: isAllowedColor
Usage: use to validate space color and space border color text box
Parameter: 	elem: the DOM object of each element
			helperMsg: the pop out box message
Description: This is a simple method to check whether a given text box string contains 
			 all type of characters except symbols excluding '#'
*/
function isAllowedColor(elem, helperMsg){
	var alphaExp = /^[#0-9a-zA-Z]+$/;
	if(elem.value.toLowerCase().match(alphaExp)){
		return true;
	}else{
		alert(helperMsg);
		elem.focus();
		return false;
	}
}
/*
Name: isAllowedFileExtension
Usage: use to validate default upload box
Parameter: 	elem: the DOM object of each element
			helperMsg: the pop out box message
Description: This is a simple method to check whether a given upload file contains appropriate extension
*/
function isAllowedFileExtension(elem, helperMsg){
	var alphaExp = /.*\.(gif)|(jpeg)|(jpg)|(png)$/;
	if(elem.value != "")
	{
		if(elem.value.toLowerCase().match(alphaExp)){
			return true;
		}else{
			alert(helperMsg);
			elem.focus();
			return false;
		}
	}
	else
		return true;
	return false;
}
/*
Name: isAllowedLoadFileExtension
Usage: use to validate default loading box
Parameter: 	elem: the DOM object of each element
			helperMsg: the pop out box message
Description: This is a simple method to check whether a given upload file contains appropriate extension
*/
function isAllowedLoadFileExtension(elem, helperMsg){
	var alphaExp = /.*\.(gif)$/;
	if(elem.value != "")
	{
		if(elem.value.toLowerCase().match(alphaExp)){
			return true;
		}else{
			alert(helperMsg);
			elem.focus();
			return false;
		}
	}
	else
		return true;
	return false;
}
/*
Name: isResizeAll
Usage: use to validate resize all select box
Parameter: 	elem: the DOM object of each element
			helperMsg: the pop out box message
Description: This is a confirmation to the user when user select 'YES' to resize all images.
*/
function isResizeAll(elem, helperMsg){
	
	if(elem.options[elem.selectedIndex].value == "Y"){
		var r=confirm(helperMsg);
		if (r==true)
		  {
			return true;
		  }
		else
		  {
			elem.focus();
			return false;
		  }
	}else{
		return true;
	}
}
</script>
