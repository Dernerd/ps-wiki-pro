<?php



class Wiki_Admin_Page_Settings {

	function __construct() {

		$this->maybe_save_settings();

		add_action('admin_menu', array(&$this, 'admin_menu'));

	}



	/**

	 * Adds the admin menus

	 *

	 * @see		http://codex.wordpress.org/Adding_Administration_Menus

	 */

	function admin_menu() {

		$page = add_submenu_page('edit.php?post_type=incsub_wiki', __('Wiki-Einstellungen', 'wiki'), __('Wiki-Einstellungen', 'wiki'), 'manage_options', 'incsub_wiki', array(&$this, 'display_settings'));

	}



	function display_settings() {

		global $wiki;



		if ( ! current_user_can('manage_options') )

			wp_die(__('Du hast keine Berechtigung, auf diese Seite zuzugreifen', 'wiki'));	//If accessed properly, this message doesn't appear.



		if ( isset($_GET['incsub_wiki_settings_saved']) && $_GET['incsub_wiki_settings_saved'] == 1 )

			echo '<div class="updated fade"><p>'.__('Einstellungen gespeichert.', 'wiki').'</p></div>';

		?>

		<div class="wrap">

			<h2><?php _e('Wiki-Einstellungen', 'wiki'); ?></h2>

			<form method="post" action="edit.php?post_type=incsub_wiki&amp;page=incsub_wiki">

			<?php wp_nonce_field('wiki_save_settings', 'wiki_settings_nonce'); ?>

			<table class="form-table">

				<tr valign="top">

					<th><label for="incsub_wiki-slug"><?php _e('Wiki Slug', 'wiki'); ?></label> </th>

					<td> /<input type="text" size="20" id="incsub_wiki-slug" name="wiki[slug]" value="<?php echo $wiki->get_setting('slug'); ?>" /></td>

				</tr>



				<?php

				if ( class_exists('Wiki_Premium') ) {

					Wiki_Premium::get_instance()->admin_page_settings();

				} ?>

			</table>



			<?php

			if ( ! class_exists('Wiki_Premium') ) : ?>

			<h3><?php _e('<a target="_blank" href="https://n3rds.work/piestingtal-source-project/ps-wiki-plugin/">Upgrade jetzt</a> um neue Features zu erhalten!', 'wiki'); ?></h3>

			<ul>

				<li><?php _e('Gib die Anzahl der Breadcrumbs an, die dem Titel hinzugefügt werden sollen', 'wiki'); ?></li>

				<li><?php _e('Gib einen benutzerdefinierten Namen für Wikis an', 'wiki'); ?></li>

				<li><?php _e('Sub-Wikis hinzufügen', 'wiki'); ?></li>

				<li><?php _e('Gib an wie Sub-Wikis bestellt werden sollen', 'wiki'); ?></li>

				<li><?php _e('Ermögliche anderen Benutzern als dem Administrator, Wikis zu bearbeiten', 'wiki'); ?></li>

			</ul>

			<?php

			endif; ?>



			<p class="submit">

			<input type="submit" class="button-primary" name="submit_settings" value="<?php _e('Änderungen speichern', 'wiki') ?>" />

			</p>

		</form>

		<?php

	}



	function maybe_save_settings() {

		global $wiki;



		if ( isset($_POST['wiki_settings_nonce']) ) {

			check_admin_referer('wiki_save_settings', 'wiki_settings_nonce');



			$new_slug = untrailingslashit($_POST['wiki']['slug']);



			if ( $wiki->get_setting('slug') != $new_slug )

				update_option('wiki_flush_rewrites', 1);



			$wiki->settings['slug'] = $new_slug;

			$wiki->settings = apply_filters('wiki_save_settings', $wiki->settings, $_POST['wiki']);

			update_option('wiki_settings', $wiki->settings);



			if ( !function_exists('get_editable_roles') )

				require_once ABSPATH . 'wp-admin/includes/user.php';



			$roles = get_editable_roles();



			foreach ( $roles as $role_key => $role ) {

				$role_obj = get_role($role_key);

				if ( isset($_POST['edit_wiki_privileges'][$role_key]) )

					$role_obj->add_cap('edit_wiki_privileges');

				else

					$role_obj->remove_cap('edit_wiki_privileges');

			}



			wp_redirect('edit.php?post_type=incsub_wiki&page=incsub_wiki&incsub_wiki_settings_saved=1');

			exit;

		}

	}

}



new Wiki_Admin_Page_Settings();