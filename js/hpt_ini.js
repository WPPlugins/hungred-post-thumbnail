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
jQuery(document).ready(function() {
	jQuery("#hpt_main").wrap('<form id="hpt_form" name="form" action="../wp-content/plugins/hungred-post-thumbnail/hpt_upload.php" method="POST" enctype="multipart/form-data" ></form>');

	if(document.getElementById("hpt_upload") != null && document.getElementById("hpt_upload") != "undefined")
	document.getElementById("hpt_upload").disabled = false;
	
});
/*
Name: hpt_redirect
Usage: use to redirect the main upload to iFrame
Parameter: 	NONE
Description: basic checking and display manipulation occurs
*/
function hpt_redirect()
{
	var hpt_uploadImg = document.getElementById('hpt_files');
	if(isAllowedFileExtension(hpt_uploadImg, "Please Upload Gif, Png or Jpg Images Files Only."))
	{
		document.getElementById('hpt_form').target = 'hpt_iframe'; //'hpt_iframe' is the name of the iframe
		document.getElementById('hpt_form').submit();
		jQuery("#hpt_image_container").css("display", "none");
		jQuery("#hpt_iframe").contents().find("body").html("");
		jQuery("#hpt_loading").css("display", "block");
		document.getElementById("hpt_upload").disabled = true;
		hpt_check_complete();
	}
}
/*
Name: hpt_check_complete
Usage: use to check whether an upload has completed
Parameter: 	NONE
Description: recursive checking until an image has uploaded completely and display the new image accordingly.
*/
var hpt_check_complete = function()
{
	if(jQuery("#hpt_iframe").contents().find("body").html() == "")
	{
	setTimeout ( hpt_check_complete, 2000 );
	}
	else
	{
		var imgObj = jQuery("#hpt_iframe").contents().find("body").find("img");
		if(imgObj.attr("src") != "" && imgObj.attr("src") != "undefined"&& imgObj.attr("src") != null)
		{
			var newImg = new Image();
			newImg.src = imgObj.attr("src");
			var height = newImg.height;
			var width = newImg.width;
			document.getElementById("hpt_upload").disabled = false;
			jQuery("#hpt_image").empty().append(jQuery("#hpt_iframe").contents().find("body").html());
			jQuery("#hpt_image").css({"height":height, "width": width});
			jQuery("#hpt_loading").css("display", "none");
			alert("Upload completed");
			jQuery("#hpt_image_container").css("display", "block");
			
		}
		else
		{
			jQuery("#hpt_loading").css("display", "none");
			jQuery("#hpt_image_container").css("display", "block");
			document.getElementById("hpt_upload").disabled = false;
			alert("Error: "+ jQuery("#hpt_iframe").contents().find("body").html());
		}
	}
}
/*
Name: isAllowedFileExtension
Usage: use to validate upload box
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
