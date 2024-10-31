<?php
/*
Plugin Name: PrintWhatYouLike
Plugin URI: http://www.printwhatyoulike.com/print_button
Description: Places the PrintWhatYouLike button on blog posts and pages to generate a printer friendly version of the page.
Version: 1.6
Author: Cassie Schmitz
Author URI: http://www.printwhatyoulike.com
*/

/*  Copyright 2009  Cassie Schmitz, Jonathan Koomjian  (email : admin@printwhatyoulike.com)

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

// Define hostname
define("HOST", "http://www.printwhatyoulike.com/");
// Define option names
define("BUTTON_TYPE_OPTION", "pwyl_button_type");
define("BUTTON_ID_OPTION", "pwyl_button_id");
define("POST_ONLY_OPTION", "pwyl_posts_only");
define("BUTTON_POSITION_OPTION", "pwyl_button_position");

// Inserts the print button into the post
function pwyl_insert_button($content)
{
	$button_type = get_option(BUTTON_TYPE_OPTION);
	$button_id = get_option(BUTTON_ID_OPTION);
	$button_position = get_option(BUTTON_POSITION_OPTION);
  $posts_only = get_option(POST_ONLY_OPTION);

	// Do not add the print button
	// if (1) The user has chosen to only include the button on posts/pages
	// and (2) The current page is not a post/page
	if ($posts_only && !(is_single() || is_page()))
	{
		return $content;
	}
	else
	{
		// Set up code for selected button type
		switch($button_type)
		{
			case "print_button_icon2.png":
			case "print_button_icon_small2.png":
			case "print_button_icon.png":
			case "print_button_icon_small.png":
				$button = "<img src=\"" . HOST . "button/$button_type\" alt=\"Print\" border=\"0\" />";
			break;

			case "big_logo_text":
				$button = '<img src="'. HOST . 'button/printer_icon2.png" border="0" alt="Print" />&nbsp;<span style="color: #719a11; font-size: 20px">Print</span>';
				break;

			case "small_logo_text":
				$button = '<img src="'. HOST . 'button/printer_icon_small2.png" border="0" alt="Print" />&nbsp;<span style="color: #719a11; font-size: 15px">Print</span>';
				break;

			case "big_icon_text":
				$button = '<img src="'. HOST . 'button/printer_icon.png" border="0" alt="Print" />&nbsp;<span style="color: #719a11; font-size: 20px">Print</span>';
				break;

			case "small_icon_text":
				$button = '<img src="'. HOST . 'button/printer_icon_small.png" border="0" alt="Print" />&nbsp;<span style="color: #719a11; font-size: 15px">Print</span>';
				break;

			default:
				$button = '<span style="text-decoration: none; color: #719a11;">Print</span>';
				break;
		}

		// Insert print button
		$button_code = "<a id=\"pwyl_print_button\" href=\"" . HOST . "\" onclick=\"javascript:(function(){window._pwyl_home='" . HOST . "';window._pwyl_print_button=document.createElement('script');window._pwyl_print_button.setAttribute('type','text/javascript');window._pwyl_print_button.setAttribute('src',window._pwyl_home+'js/print_button/$button_id');window._pwyl_print_button.setAttribute('pwyl','true');document.getElementsByTagName('head')[0].appendChild(window._pwyl_print_button);document.body.style.cursor='progress';document.getElementById('pwyl_print_button').style.cursor='progress';})();return false;\" title=\"Print this page\" style=\"text-decoration: none;\">$button</a>";

		if ($button_position == "top")
		{
			return $button_code . $content;
		}
		else if ($button_position == "bottom")
		{
			return $content . $button_code;
		}
		else {
			return $button_code . $content . $button_code;
		}
	}
}

// Set up WordPress action hook to insert button
add_action('the_content', 'pwyl_insert_button', 98);

// Options Page
add_action('admin_menu', 'pwyl_menu');

function pwyl_menu()
{
  add_options_page('PrintWhatYouLike Options', 'PrintWhatYouLike', 8, __FILE__, 'pwyl_options');
}

function pwyl_options()
{
	// Get existing option values
	$button_type = get_option(BUTTON_TYPE_OPTION);
	$button_id = get_option(BUTTON_ID_OPTION);
	$button_position = get_option(BUTTON_POSITION_OPTION);
	$posts_only = get_option(POST_ONLY_OPTION);
	?>
	<div class="wrap">
		<h2>PrintWhatYouLike</h2>
		<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<h3>Select your button</h3>
		<table cellspacing="10" cellpadding="10">
			<tr valign="top">
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="print_button_icon2.png" <?php echo(($button_type == 'print_button_icon2.png') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/print_button_icon2.png" border="0" alt="big button with printwhatyoulike logo" /></td>
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="print_button_icon_small2.png" <?php echo(($button_type == 'print_button_icon_small2.png') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/print_button_icon_small2.png" border="0" alt="small button with printwhatyoulike logo" /></td>
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="print_button_icon.png" <?php echo(($button_type == 'print_button_icon.png') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/print_button_icon.png" border="0" alt="big button with print icon" /></td>
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="print_button_icon_small.png" <?php echo(($button_type == 'print_button_icon_small.png') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/print_button_icon_small.png" border="0" alt="small button with print icon" /></td>
			</tr>
			<tr valign="top">
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="big_logo_text" <?php echo(($button_type == 'big_logo_text') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/printer_icon2.png" border="0" alt="big logo with text" />&nbsp;<span style="text-decoration: none; color: #719a11; font-size: 20px">Print</span></td>
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="small_logo_text" <?php echo(($button_type == 'small_logo_text') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/printer_icon_small2.png" border="0" alt="small logo with text" />&nbsp;<span style="text-decoration: none; color: #719a11; font-size: 15px">Print</span></td>
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="big_icon_text" <?php echo(($button_type == 'big_icon_text') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/printer_icon.png" border="0" alt="small print icon with text" />&nbsp;<span style="text-decoration: none; color: #719a11; font-size: 20px">Print</span></td>
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="small_icon_text" <?php echo(($button_type == 'small_icon_text') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><img src="<?php echo(HOST) ?>button/printer_icon_small.png" border="0" alt="small print icon with text" />&nbsp;<span style="text-decoration: none; color: #719a11; font-size: 15px">Print</span></td>
			</tr>
			<tr valign="top">
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_TYPE_OPTION) ?>" value="text_only" <?php echo(($button_type == 'text_only') ? 'checked="checked"' : '') ?>/></td>
				<td valign="middle"><span style="text-decoration: none; color: #719a11;">Print</span></td>
			</tr>
		</table>
		<h3>Display preferences</h3>
		<table cellspacing="10" cellpadding="10">
			<tr valign="top">
				<td valign="middle"><input type="checkbox" name="<?php echo(POST_ONLY_OPTION) ?>" value="1" <?php echo(($posts_only == 1) ? 'checked="checked"' : '') ?>/></td>
				<td>Only display button on individual posts and pages (not on front page)</td>
			</tr>
		</table>
		<h3>Print Button Placement</h3>
		<table cellspacing="10" cellpadding="10">
			<tr valign="top">
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_POSITION_OPTION) ?>" value="bottom" <?php echo(($button_position == "bottom" || $buttom_position == "") ? 'checked="checked"' : '') ?>/></td>
				<td>Bottom of post</td>
			</tr>
			<tr valign="top">
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_POSITION_OPTION) ?>" value="top" <?php echo(($button_position == "top") ? 'checked="checked"' : '') ?>/></td>
				<td>Top of post</td>
			</tr>
			<tr valign="top">
				<td valign="middle"><input type="radio" name="<?php echo(BUTTON_POSITION_OPTION) ?>" value="top_bottom" <?php echo(($button_position == "top_bottom") ? 'checked="checked"' : '') ?>/></td>
				<td>Top AND bottom of post</td>
			</tr>
		</table>
		<h3><a href="javascript:pwylShowAdvancedOptions()">Advanced:</a> <small>Customize how your blog is formatted for printing</small></h3>
		<span id="pwyl_advanced_options" style="display:none;">
		<p>By default, the PrintWhatYouLike button will automatically format your blog for printing.<br/>
			If you want to customize how your blog is formatted for printing, you can create a print layout for your blog.</p>
		<h4>Print Layout ID</h4>
		<i>(copy the ID from the Print Layout page)</i>
		<table cellspacing="10" cellpadding="10">
			<tr valign="top">
				<td valign="middle"></td>
				<td>
					<input type="text" name="<?php echo(BUTTON_ID_OPTION) ?>" value="<?php echo($button_id) ?>" size="3" />
					<?php	if ($button_id)
							{ ?>
								<a href="<?php echo(HOST) ?>print_button/new/?id=<?php echo($button_id) ?>&wordpress=1" target="_blank">Edit Your Layout <img src="<?php echo(HOST) ?>site/img/external.png" alt="external link"></a>
					<?php	}
							else
							{ ?>
								<a href="<?php echo(HOST) ?>print_button/new?wordpress=1" target="_blank">Create Your Layout <img src="<?php echo(HOST) ?>site/img/external.png" alt="external link"></a>
					<?php	} ?>
				</td>
			</tr>
		</table>
		</span>
		<script type="text/javascript">
			function pwylShowAdvancedOptions()
			{
				document.getElementById('pwyl_advanced_options').style.display = "";
			}
		</script>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="<?php echo(BUTTON_TYPE_OPTION) ?>,<?php echo(BUTTON_POSITION_OPTION) ?>,<?php echo(BUTTON_ID_OPTION) ?>,<?php echo(POST_ONLY_OPTION) ?>" />
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
  </form>
	</div>
<?php
}

?>
