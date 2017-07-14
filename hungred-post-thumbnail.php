<?php
/*
Plugin Name: Hungred Post Thumbnail
Plugin URI: http://hungred.com/2009/07/14/useful-information/wordpress-plugin-hungred-post-thumbnail/
Description: Please consider donate to this plugin development. For more information,please visit the plugin page.
Author: Clay lua
Version: 1.5.1
Author URI: http://hungred.com
*/

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


/*
Structure of the plugin
*/

require_once 'hpt_function.php';

/*
Name: add_hpt_to_admin_panel_actions
Usage: use to add an options on the Setting section of Wordpress
Parameter: 	NONE
Description: this method depend on hpt_admin for the interface to be produce when the option is created
			 on the Setting section of Wordpress
*/
function add_hpt_to_admin_panel_actions() {
    $plugin_page = add_options_page("Hungred Post Thumbnail", "Hungred Post Thumbnail", 10, "Hungred Post Thumbnail", "hpt_admin");  
	add_action( 'admin_head-'. $plugin_page, 'hpt_admin_header' );

}
/*
Name: hpt_admin_header
Usage: stop hpt admin page from caching
Parameter: 	NONE
Description: this method is to stop hpt admin page from caching so that the preview is shown.
*/
function hpt_admin_header()
{
nocache_headers();
}
/*
Name: hpt_admin
Usage: provide the GUI of the admin page
Parameter: 	NONE
Description: this method depend on hpt_admin_page.php to display all the relevant information on our admin page
*/
function hpt_admin(){
	include('hpt_admin_page.php');  
}
add_action('admin_menu', 'add_hpt_to_admin_panel_actions');
/*
Name: hpt_post_option
Usage: add a container into Wordpress post section
Parameter: 	NONE
Description: this method adds a container to Wordpress post section for user to upload images for their thumbnail.
			 This method depends on hpt_post_display for GUI.
*/
function hpt_post_option()
{
	add_meta_box( "hpt_box", "Hungred Post Thumbnail Options", "hpt_post_display", 'post', "normal", "high" );	
}
/*
Name: hpt_post_option
Usage: include the file and print them out to the container provided by Wordpress on the post section
Parameter: 	NONE
Description: This method depends on hpt_post_page.php for the code.
*/
function hpt_post_display()
{
	include(dirname(__FILE__).'/hpt_post_page.php');
}
/*
Name: hpt_loadcss
Usage: load the relevant CSS external files into Wordpress post section
Parameter: 	NONE
Description: uses wp_enqueue_style for safe printing of CSS style sheets
*/
function hpt_loadcss()
{
	wp_enqueue_style('hpt_ini', get_settings('siteurl').'/wp-content/plugins/hungred-post-thumbnail/css/hpt_ini.css');
}
/*
Name: hpt_loadjs
Usage: load the relevant JavaScript external files into Wordpress post section
Parameter: 	NONE
Description: uses wp_enqueue_script for safe printing of JavaScript
*/
function hpt_loadjs()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('hpt_ini', get_settings('siteurl').'/wp-content/plugins/hungred-post-thumbnail/js/hpt_ini.js');
}
add_action('admin_print_scripts', 'hpt_loadjs');
add_action('admin_print_styles', 'hpt_loadcss');
add_action('admin_menu', 'hpt_post_option');
function hpt_id()
{
	echo "<!-- This site is power up by Hungred Post Thumbnail -->";
}
add_action('wp_head', 'hpt_id');
/*
Name: hpt_install
Usage: upload all the table required by this plugin upon activation for the first time
Parameter: 	NONE
Description: the structure of our Wordpress plugin
*/
function hpt_install()
{
	$hpt_image = dirname(__FILE__).'/images/default.png';
	$hpt_image_url = get_bloginfo('url').'/wp-content/plugins/hungred-post-thumbnail/images/default.png';
	$hpt_loading_url = get_bloginfo('url').'/wp-content/plugins/hungred-post-thumbnail/images/hpt-options-loading.gif';
    global $wpdb;
    $table = $wpdb->prefix."hungred_post_thumbnail_options";
    $structure = "CREATE TABLE IF NOT EXISTS `".$table."` (
		hpt_id DOUBLE NOT NULL DEFAULT 1,
        hpt_width Double NOT NULL DEFAULT 250,
        hpt_height Double NOT NULL DEFAULT 250,
        hpt_space Double NOT NULL DEFAULT 1,
		hpt_space_color varchar(30) NOT NULL DEFAULT '#FFFFFF',
		hpt_space_bcolor varchar(30) NOT NULL DEFAULT '#CCCCCC',
		hpt_gap Double NOT NULL DEFAULT 5,
        hpt_image longtext NOT NULL ,
		hpt_image_url longtext NOT NULL ,
		hpt_loading_url longtext NOT NULL ,
		hpt_loc varchar(5) NOT NULL DEFAULT 'LEFT',
		hpt_exist varchar(1) NOT NULL DEFAULT 'B',
		hpt_default_exist varchar(1) NOT NULL DEFAULT 'Y',
		hpt_link varchar(1) NOT NULL DEFAULT 'Y',
		hpt_rss varchar(1) NOT NULL DEFAULT 'Y',
		hpt_default_display varchar(1) NOT NULL DEFAULT 'R',
		hpt_keep varchar(1) NOT NULL DEFAULT 'Y',
		hpt_resize varchar(1) NOT NULL DEFAULT 'Y',
		UNIQUE KEY id (hpt_id)
    );";
    $wpdb->query($structure);
    // Populate table
    $wpdb->query("INSERT INTO $table(hpt_image, hpt_image_url, hpt_loading_url)
        VALUES('".$hpt_image."', '".$hpt_image_url."', '".$hpt_loading_url."')");
		
	$table = $wpdb->prefix."hungred_post_thumbnail";
    $structure = "CREATE TABLE IF NOT EXISTS `".$table."` (
        hpt_post varchar(255) NOT NULL,
        hpt_name longtext NOT NULL,
		hpt_url longtext NOT NULL,
		hpt_loc longtext NOT NULL,
		UNIQUE KEY id (hpt_post)
    );";
    $wpdb->query($structure);
	
	$table = $wpdb->prefix."hungred_post_thumbnail_draft";
    $structure = "CREATE TABLE IF NOT EXISTS `".$table."` (
        hpt_post varchar(255) NOT NULL,
        hpt_name longtext NOT NULL,
		hpt_url longtext NOT NULL,
		hpt_loc longtext NOT NULL,
		UNIQUE KEY id (hpt_post)
    );";
    $wpdb->query($structure);
}
if ( function_exists('register_activation_hook') )
	register_activation_hook('hungred-post-thumbnail/hungred-post-thumbnail.php', 'hpt_install');
	
/*
Name: hpt_uninstall
Usage: delete hpt table
Parameter: 	NONE
Description: the structure of our Wordpress plugin
*/
function hpt_uninstall()
{
	global $wpdb;
	$table = $wpdb->prefix."hungred_post_thumbnail_draft";
	$structure = "DROP TABLE `".$table."`";
	$wpdb->query($structure);
	
	$table = $wpdb->prefix."hungred_post_thumbnail";
	$structure = "DROP TABLE `".$table."`";
	$wpdb->query($structure);
	
	$table = $wpdb->prefix."hungred_post_thumbnail_options";
	$structure = "DROP TABLE `".$table."`";
	$wpdb->query($structure);
}
if ( function_exists('register_uninstall_hook') )
    register_uninstall_hook(__FILE__, 'hpt_uninstall');
	
/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/repos/v/function.copyr.php
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function hpt_copyr($source, $dest)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        hpt_copyr("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
}
/**
* Delete a file, or a folder and its contents
*
* @author Aidan Lister <aidan@php.net>
* @version 1.0.2
* @param string $dirname Directory to delete
* @return bool Returns TRUE on success, FALSE on failure
*/
function hpt_rmdirr($dirname)
{
	// Sanity check
	if (!file_exists($dirname)) {
	return false;
	}

	// Simple delete for a file
	if (is_file($dirname)) {
	return unlink($dirname);
	}

	// Loop through the folder
	$dir = dir($dirname);
	while (false !== $entry = $dir->read()) {
	// Skip pointers
	if ($entry == '.' || $entry == '..') {
	continue;
	}

	// Recurse
	hpt_rmdirr($dirname."/".$entry);
	}

	// Clean up
	$dir->close();
	return rmdir($dirname);
}
function hpt_backup()
{
	$to = dirname(__FILE__)."/../hpt_images_backup/";
	$from = dirname(__FILE__)."/images/";
	hpt_copyr($from, $to);
	
}
function hpt_recover()
{
	$from = dirname(__FILE__)."/../hpt_images_backup/";
	$to = dirname(__FILE__)."/images/";
	hpt_copyr($from, $to);
	if (is_dir($from)) {
		hpt_rmdirr($from);
	}
}

add_filter('upgrader_pre_install', 'hpt_backup', 10, 2);
add_filter('upgrader_post_install', 'hpt_recover', 10, 2);

/*
Function of the plugin
*/

/*
Name: hpt_post_delete
Usage: activated when user delete a post
Parameter: 	NONE
Description: clear out all data and images that the post contains
*/
function hpt_post_delete()
{
	global $post;
	global $wpdb;
	$deletedID = $post->ID;
	
	$table = $wpdb->prefix."hungred_post_thumbnail";
	$query = "SELECT `hpt_loc` FROM `".$table."` WHERE `hpt_post` = '".$deletedID."' limit 1";
	$row = $wpdb->get_row($query,ARRAY_A);
	$oripath = str_replace("live", "original/live", $row['hpt_loc']);
	if(file_exists($oripath) != false)
	unlink($oripath);
	if(file_exists($row['hpt_loc']) != false)
	unlink($row['hpt_loc']);
	$sqlquery = "DELETE FROM `".$table."`
				WHERE `hpt_post` = '".$deletedID."'";
	$wpdb->query($sqlquery);	
	
	
	$table = $wpdb->prefix."hungred_post_thumbnail_draft";
	$query = "SELECT `hpt_loc` FROM `".$table."` WHERE `hpt_post` = '".$deletedID."' limit 1";
	$row = $wpdb->get_row($query,ARRAY_A);
	if(file_exists($row['hpt_loc']) != false)
	unlink($row['hpt_loc']);
	$oripath = str_replace("draft", "original/draft", $row['hpt_loc']);
	if(file_exists($oripath) != false)
	unlink($oripath);
	$sqlquery = "DELETE FROM `".$table."`
				WHERE `hpt_post` = '".$deletedID."'";
	$wpdb->query($sqlquery);	
}
add_action('delete_post', 'hpt_post_delete');
/*
Name: hpt_post_published
Usage: activated when user publish a post
Parameter: 	NONE
Description: transfer all relevant data from draft table to live table to populate out to the public
*/
function hpt_post_published($post_ID)
{
	global $wpdb;
	$publishID = $post_ID;
	$table = $wpdb->prefix."hungred_post_thumbnail_draft";
	$query = "SELECT * FROM `".$table."` WHERE `hpt_post` = '".$publishID."' limit 1";

	$row = $wpdb->get_row($query,ARRAY_A);
	if(file_exists($row['hpt_loc']) != false)
	{
		$loc = str_replace("draft", "live", $row['hpt_loc']);
		$url = str_replace("draft", "live", $row['hpt_url']);
		copy($row['hpt_loc'], $loc);
		
		$ori_draft_loc = str_replace("draft", "original/draft", $row['hpt_loc']);
		$ori_live_loc = str_replace("draft", "live", $ori_draft_loc);
		copy($ori_draft_loc, $ori_live_loc);
		
		$table = $wpdb->prefix."hungred_post_thumbnail";
		$query = "SELECT `hpt_loc` FROM `".$table."` WHERE `hpt_post` = '".$publishID."' limit 1";
		$oldRow = $wpdb->get_row($query,ARRAY_A);
		if(basename($oldRow['hpt_loc']) != basename($row['hpt_loc']))
		{
			if(file_exists($oldRow['hpt_loc']) != false)
				unlink($oldRow['hpt_loc']);
				
			$oripath = str_replace("live", "original/live", $oldRow['hpt_loc']);
			if(file_exists($oripath) != false)
				unlink($oripath);
		}
		$query = "REPLACE INTO $table(hpt_post, hpt_name, hpt_url, hpt_loc) VALUES('".$row['hpt_post']."', '".$row['hpt_name']."', '".$url. "', '".$loc."')";

		$wpdb->query($query);
	}
	
}
add_action('publish_post', 'hpt_post_published');
/*
Name: hpt_attach_template
Usage: activated when excerpt is printed on the template
Parameter: 	$caller: indicate whether it is excerpt or more tag
		    $content: this is the content of the post
Description: modify the excerpt way of populating the data into a new format that is self-independent across the any template
*/
$hpt_usedImg = Array();
function hpt_attach_template($caller, $content)
{
	global $wpdb;  	
	global $post;
	global $hpt_usedImg;
	$current = $post->ID;
	$table = $wpdb->prefix."hungred_post_thumbnail";
	$query = "SELECT `hpt_url`, `hpt_name` FROM `".$table."` WHERE `hpt_post` = '".$current."' limit 1";
	$row = $wpdb->get_row($query,ARRAY_A);
	$table = $wpdb->prefix."hungred_post_thumbnail_options";
	$query = "SELECT * FROM `".$table."` WHERE `hpt_id` = '1' limit 1";
	$options = $wpdb->get_row($query,ARRAY_A);
	if($options['hpt_link'] == "Y")
	$permalink = get_permalink($current);
	$post_title = the_title('','', false);
	
?>
	
<?php 
	if(($row['hpt_url'] != "" || $options['hpt_default_exist'] == "Y") && ($options['hpt_exist'] == $caller || $options['hpt_exist'] == "B")){
	
		if($options['hpt_loc'] == "TOP")
		{
			echo "<div class='hpt_container' style='display:block;clear:both;'>";
			echo "<div class='hpt_element' style='background:".$options['hpt_space_color'].";padding:".$options['hpt_gap']."px;'>";
		}
		else
		{
			echo "<div class='hpt_container' style='width:100%;display:block;clear:both;height:".($options['hpt_height']+$options['hpt_space']*2+$options['hpt_gap']*2+20)."px;'>";
			if($options['hpt_loc'] == "RAND")
			{
				$dir = rand(0, 1)?$dir="LEFT":$dir="RIGHT";
				echo "<div class='hpt_element' style='float:".$dir.";border: ".$options['hpt_space_bcolor']." solid ".$options['hpt_space']."px;background:".$options['hpt_space_color'].";padding:".$options['hpt_gap']."px;margin-right:10px;'>";
			}
			else
			echo "<div class='hpt_element' style='float:".$options['hpt_loc'].";border: ".$options['hpt_space_bcolor']." solid ".$options['hpt_space']."px;background:".$options['hpt_space_color'].";padding:".$options['hpt_gap']."px;margin-right:10px;'>";
		}
		if($options['hpt_link'] == "Y")
		echo "<a href='".$permalink."'>";
		
		if($row['hpt_url'] != ""){
			$name = hpt_extract_file_name($row['hpt_url']);
			echo "<img style='border: ".$options['hpt_space_bcolor']." solid 1px;' title='".$post_title."' alt='".$name. " ". $post_title."' src='".$row['hpt_url']."'/>";
		}else if($options['hpt_default_exist'] == "Y"){
			$hpt_allImages = hpt_getAllFile(dirname(__FILE__).'/images/random/');
			$hpt_numOfImages = count($hpt_allImages);
			$hpt_allNormalImages = hpt_getAllNormalImage(dirname(__FILE__).'/images/random/');
			$hpt_total = count($hpt_allNormalImages);
			$i = 0;
			$hpt_lookup_image = Array();
			$advance_tmp_image = "";
			if($options['hpt_default_display'] == "S")
			{
				$name = hpt_extract_file_name($row['hpt_image_url']);
				echo "<img style='border: ".$options['hpt_space_bcolor']." solid 1px;' title='".$post_title."' alt='".$name. " ". $post_title."' src='".$options['hpt_image_url']."'/>";
			}
			else 
			{
				$highestScore = 0;
				while(true)
				{
					//ensure that we check all the image
					$fail = false;
					while(true)
					{
						$random = rand()%($hpt_numOfImages);
						$splittedPath = explode(getcwd(),$hpt_allImages[$random]);
						if(!in_array($splittedPath[1], $hpt_lookup_image))
						{
							$hpt_lookup_image[] = $splittedPath[1];
							break;
						}
						if(count($hpt_lookup_image) >= $hpt_numOfImages)
						{
							$fail = true;
							break;
						}
					}
					if($fail)
					{
						break;
					}
					//try to retrieve an image depend on the display type given on the setting
					if($splittedPath[1] != null)
					{
						if(!in_array($splittedPath[1], $hpt_usedImg) && $options['hpt_default_display'] == "R")
						{
							$url = get_bloginfo('url').$splittedPath[1];
							break;
						}
						else 
						{
							//get keywords from file name
							$fileName = explode(".", basename($splittedPath[1]));
							$fileName[0] = remove_numbers($fileName[0]);
							$keywords = explode("-", $fileName[0]);
							//get list of title words to compare against keywords
							$title = get_the_title($current);
							hpt_removeSymbols($title);
							$list = explode(" ", $title);
							$category = wp_get_post_categories( $current);
							$category_name = Array();
							foreach($category as $catID)
							{
								array_push($category_name, get_cat_name($catID));
							}
							//get the words on its category and create a good list
							$list = array_merge($list, $category_name);
							array_walk($list, "lower");
							array_walk($keywords, "lower");
							$found = false;
							//see whether there is a single match keyword in the list
							foreach($keywords as $t)
							{
								if(in_array($t, $list) && !in_array($splittedPath[1], $hpt_usedImg))
								{
									$found = true;
									if($options['hpt_default_display'] == "T")
									{
										$url = get_bloginfo('url').$splittedPath[1];
									}
									break;
								}
							}
							
							if($found)
							{
								if($options['hpt_default_display'] == "V")
								{
									$currentScore = 0;
									foreach($keywords as $t)
									{
										if(in_array($t, $list))
										{
											$currentScore++;
										}
									}
									if($currentScore > $highestScore)
									{
										$highestScore = $currentScore;
										$url = get_bloginfo('url').$splittedPath[1];
									}
								}
								else if($options['hpt_default_display'] == "T")
								{
									break;
								}
							}
							else if($options['hpt_default_display'] == "T")
							{
								if(!in_array($splittedPath[1], $hpt_usedImg) && !in_array(getcwd().$splittedPath[1], $hpt_allNormalImages))
									$url = get_bloginfo('url').$splittedPath[1];
								else if(!in_array($splittedPath[1], $hpt_usedImg))
									$url = get_bloginfo('url').$splittedPath[1];
							}
							else if($options['hpt_default_display'] == "V")
							{
								if(!in_array($splittedPath[1], $hpt_usedImg) && !in_array(getcwd().$splittedPath[1], $hpt_allNormalImages))
									$advance_tmp_image = get_bloginfo('url').$splittedPath[1];
								else if(!in_array($splittedPath[1], $hpt_usedImg))
									$advance_tmp_image = get_bloginfo('url').$splittedPath[1];
							}

						}
					}
					$i++;
					if($i > $hpt_numOfImages)
						break;
				}
				if($row['hpt_name'] == "")
				{
					$name = hpt_extract_file_name($url);
				}
				if($advance_tmp_image != "" && $url == "")
						$url = $advance_tmp_image;
				
				if($url == "")
					$url = $options['hpt_image_url'];
				else	
				{
					$splittedPath = explode(get_bloginfo('url'),$url);
					array_push($hpt_usedImg, $splittedPath[1]);
				}
				echo "<img style='border: ".$options['hpt_space_bcolor']." solid 1px;' title='".$post_title."' alt='".$name. " ". $post_title."' src='".$url."'/>";
				$url = "";

			}
		}	
		
		if($options['hpt_link'] == "Y")
		echo "</a>";
		
		echo "</div>";

		echo $content."</div>";
		return true;
	}
	else
	{
		echo $content;
		return false;
	}
	
}

/*
Name: hpt_attach_excerpt
Usage: event handler for excerpt
Parameter: 	$content: content of the excerpt
Description: 
*/
function hpt_attach_excerpt($content)
{
	$result = hpt_attach_template("E", $content);

}
/*
Name: hpt_attach_content
Usage: event handler for content
Parameter: 	$content: content of the content? 
Description: 
*/
function hpt_attach_content($content)
{
	$output = $content;
	$result = false;
	if(strpos($output, "more-link") != false)
	{
		$result = hpt_attach_template("M", $content);
	}
	else
	{
		return $content;
	}
}
 add_filter('the_excerpt', 'hpt_attach_excerpt');
 add_filter('the_content', 'hpt_attach_content');

global $wpdb;
$table = $wpdb->prefix."hungred_post_thumbnail_options";
$query = "SELECT `hpt_rss` FROM `".$table."` WHERE `hpt_id` = '1' limit 1";
$options = $wpdb->get_row($query,ARRAY_A);
if($options['hpt_rss'] == "Y")
add_filter('the_excerpt_rss', 'hpt_attach_excerpt');
?>