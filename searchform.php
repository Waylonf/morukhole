<?php
/**
 * The template for displaying search forms in Underscores.me
 *
 * @package     WordPress
 * @subpackage  {THEME-NAME}
 * @since       {THEME-NAME} {THEME-VERSION}
 */
?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<label for="s" class="assistive-text"><?php _e( 'Search', 'TEXTDOMAIN' ); ?></label>
	<div class="input-group">
		<input type="text" class="field form-control" name="s" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'TEXTDOMAIN' ); ?>" />
		<span class="input-group-btn">
			<input type="submit" class="submit btn btn-primary" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'TEXTDOMAIN' ); ?>" />
		</span>
	</div>
</form>