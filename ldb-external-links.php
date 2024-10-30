<?php
/*
Plugin Name: LDB External Links
Description: LDB External Links turns all your links with target="_blank" into rel="external" and uses Javascript to open them in a new window or tab.
Author: Luc De Brouwer
Version: 1.1
Plugin URI: http://www.lucdebrouwer.nl/wordpress-plugin-ldb-external-links/
Author URI: http://www.lucdebrouwer.nl/
License: GPL
*/

// Pre-2.6 compatibility
if (!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
if ( ! defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

// has this plugin been installed in its own directory?
define('LDB_EL_URL', (basename(dirname(__FILE__)) != 'plugins') ? WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) : '');

function ldb_external_links_add_js()
{
?>
	<script type="text/javascript" src="<?php echo LDB_EL_URL; ?>/ldb-external-links.js"></script>
<?php
}

function ldb_rel_external($content)
{
	$options = get_option('ldb_external_links');
	if (!is_array($options))
	{
		$options = array(
			'ldb_external_links_rel' => 'external'
		);
	}
	$regexp = '/\<a[^\>]*(target="_([\w]*)")[^\>]*\>[^\<]*\<\/a>/smU';
	if( preg_match_all($regexp, $content, $matches) ){
		for ($m=0;$m<count($matches[0]);$m++) {
			if ($matches[2][$m] == 'blank') {
				$temp = str_replace($matches[1][$m], 'rel="' . $options['ldb_external_links_rel'] . '"', $matches[0][$m]);
				$content = str_replace($matches[0][$m], $temp, $content);
			} else if ($matches[2][$m] == 'self'){
				$temp = str_replace(' ' . $matches[1][$m], '', $matches[0][$m]);
				$content = str_replace($matches[0][$m], $temp, $content);
			}
		}
	}
	return $content;
}

function ldb_external_links_admin()
{
?>

	<div class="wrap metabox-holder">
		<h2>LDB External Links</h2>
<?php
	$options = get_option('ldb_external_links');
	if (!is_array($options))
	{
		$options = array(
			'ldb_external_links_rel' => 'external'
		);
	}
	if (array_key_exists('ldb_external_links_submit', $_POST))
	{
		$options['ldb_external_links_rel'] = strip_tags(stripslashes($_POST['ldb_external_links_rel']));
		update_option('ldb_external_links', $options);
		echo '<div id="message" class="updated fade"><p>Your settings have been updated.</p></div>';
	}
?>
		<div class="postbox" style="width: 60%; float: left;">
			<h3 class="hndle"><span>Settings</span></h3>
			<div class="inside">
				<form method="post">
					<table class="form-table" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td colspan="2">
								<p>Use the form below to adjust the settings of this plugin. It's pretty trivial for now but I hope to add more functions in the future.</p>
							</td>
						</tr>
						<tr>
							<th><label for="ldb_external_links_rel">Value of the rel attribute : </label></th>
							<td>
								<select id="ldb_external_links_rel" name="ldb_external_links_rel">
									<option value="external"<?php if ($options['ldb_external_links_rel'] == 'external') { echo ' selected="selected"'; } ?>>external</option>
									<option value="external nofollow"<?php if ($options['ldb_external_links_rel'] == 'external nofollow') { echo ' selected="selected"'; } ?>>external nofollow</option>
									<option value="nofollow external"<?php if ($options['ldb_external_links_rel'] == 'nofollow external') { echo ' selected="selected"'; } ?>>nowfollow external</option>
									<option value="nofollow"<?php if ($options['ldb_external_links_rel'] == 'nofollow') { echo ' selected="selected"'; } ?>>nofollow</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><small>This allows you to set the rel attribute which replaces target="_blank" to a value you desire.</small></td>
						</tr>
						<tr>
							<th><label for="ldb_external_links_submit">&nbsp;</label></th>
							<td><input class="button-primary" type="submit" name="ldb_external_links_submit" id="ldb_external_links_submit" value="Save settings" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<div class="postbox" style="width: 35%; float: right; border-color: green; border-width: 2px;">
			<h3 class="hndle" style="color: green; font-weight: bold; font-size: 105%;"><span>Please donate</span></h3>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="DDP34VG3Y8446">
			<img alt="" border="0" src="https://www.paypal.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
			<table class="form-table" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
						<p>Development of this plugin has cost quite some time, if you use it, please donate a token of your appreciation!</p>
						<p style="text-align: center;"><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></p>
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>
<?php
}
function ldb_external_links_add_page() {
	add_submenu_page('options-general.php', 'LDB External Links', 'LDB External Links', 8, basename(__FILE__), 'ldb_external_links_admin');
}

add_filter('the_content', 'ldb_rel_external');
add_action('wp_head', 'ldb_external_links_add_js');
if (is_admin()) 
{
	add_action('admin_menu', 'ldb_external_links_add_page');
}

?>
