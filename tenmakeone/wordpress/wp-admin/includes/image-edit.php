<?php
/**
 * WordPress Image Editor
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Loads the WP image-editing interface.
 *
 * @param int         $post_id Post ID.
 * @param bool|object $msg     Optional. Message to display for image editor updates or errors.
 *                             Default false.
 */
function wp_image_editor($post_id, $msg = false) {
	$nonce = wp_create_nonce("image_editor-$post_id");
	$meta = wp_get_attachment_metadata($post_id);
	$thumb = image_get_intermediate_size($post_id, 'thumbnail');
	$sub_sizes = isset($meta['sizes']) && is_array($meta['sizes']);
	$note = '';

	if ( isset( $meta['width'], $meta['height'] ) )
		$big = max( $meta['width'], $meta['height'] );
	else
		die( __('Image data does not exist. Please re-upload the image.') );

	$sizer = $big > 400 ? 400 / $big : 1;

	$backup_sizes = get_post_meta( $post_id, '_wp_attachment_backup_sizes', true );
	$can_restore = false;
	if ( ! empty( $backup_sizes ) && isset( $backup_sizes['full-orig'], $meta['file'] ) )
		$can_restore = $backup_sizes['full-orig']['file'] != basename( $meta['file'] );

	if ( $msg ) {
		if ( isset($msg->error) )
			$note = "<div class='error'><p>$msg->error</p></div>";
		elseif ( isset($msg->msg) )
			$note = "<div class='updated'><p>$msg->msg</p></div>";
	}

	?>
	<div class="imgedit-wrap">
	<div id="imgedit-panel-<?php echo $post_id; ?>">

	<div class="imgedit-settings">
	<div class="imgedit-group">
	<div class="imgedit-group-top">
		<h3><?php _e( 'Scale Image' ); ?> <a href="#" class="dashicons dashicons-editor-help imgedit-help-toggle" onclick="imageEdit.toggleHelp(this);return false;"></a></h3>
		<div class="imgedit-help">
		<p><?php _e('You can proportionally scale the original image. For best results, scaling should be done before you crop, flip, or rotate. Images can only be scaled down, not up.'); ?></p>
		</div>
		<?php if ( isset( $meta['width'], $meta['height'] ) ): ?>
		<p><?php printf( __('Original dimensions %s'), $meta['width'] . ' &times; ' . $meta['height'] ); ?></p>
		<?php endif ?>
		<div class="imgedit-submit">
		<span class="nowrap"><input type="text" id="imgedit-scale-width-<?php echo $post_id; ?>" onkeyup="imageEdit.scaleChanged(<?php echo $post_id; ?>, 1)" onblur="imageEdit.scaleChanged(<?php echo $post_id; ?>, 1)" style="width:4em;" value="<?php echo isset( $meta['width'] ) ? $meta['width'] : 0; ?>" /> &times; <input type="text" id="imgedit-scale-height-<?php echo $post_id; ?>" onkeyup="imageEdit.scaleChanged(<?php echo $post_id; ?>, 0)" onblur="imageEdit.scaleChanged(<?php echo $post_id; ?>, 0)" style="width:4em;" value="<?php echo isset( $meta['height'] ) ? $meta['height'] : 0; ?>" />
		<span class="imgedit-scale-warn" id="imgedit-scale-warn-<?php echo $post_id; ?>">!</span></span>
		<input type="button" onclick="imageEdit.action(<?php echo "$post_id, '$nonce'"; ?>, 'scale')" class="button button-primary" value="<?php esc_attr_e( 'Scale' ); ?>" />
		</div>
	</div>
	</div>

<?php if ( $can_restore ) { ?>

	<div class="imgedit-group">
	<div class="imgedit-group-top">
		<h3><a onclick="imageEdit.toggleHelp(this);return false;" href="#"><?php _e('Restore Original Image'); ?> <span class="dashicons dashicons-arrow-down imgedit-help-toggle"></span></a></h3>
		<div class="imgedit-help">
		<p><?php _e('Discard any changes and restore the original image.');

		if ( !defined('IMAGE_EDIT_OVERWRITE') || !IMAGE_EDIT_OVERWRITE )
			echo ' '.__('Previously edited copies of the image will not be deleted.');

		?></p>
		<div class="imgedit-submit">
		<input type="button" onclick="imageEdit.action(<?php echo "$post_id, '$nonce'"; ?>, 'restore')" class="button button-primary" value="<?php esc_attr_e( 'Restore image' ); ?>" <?php echo $can_restore; ?> />
		</div>
		</div>
	</div>
	</div>

<?php } ?>

	<div class="imgedit-group">
	<div class="imgedit-group-top">
		<h3><?php _e('Image Crop'); ?> <a href="#" class="dashicons dashicons-editor-help imgedit-help-toggle" onclick="imageEdit.toggleHelp(this);return false;"></a></h3>

		<div class="imgedit-help">
		<p><?php _e('To crop the image, click on it and drag to make your selection.'); ?></p>

		<p><strong><?php _e('Crop Aspect Ratio'); ?></strong><br />
		<?php _e('The aspect ratio is the relationship between the width and height. You can preserve the aspect ratio by holding down the shift key while resizing your selection. Use the input box to specify the aspect ratio, e.g. 1:1 (square), 4:3, 16:9, etc.'); ?></p>

		<p><strong><?php _e('Crop Selection'); ?></strong><br />
		<?php _e('Once you have made your selection, you can adjust it by entering the size in pixels. The minimum selection size is the thumbnail size as set in the Media settings.'); ?></p>
		</div>
	</div>

	<p>
		<?php _e('Aspect ratio:'); ?>
		<span  class="nowrap">
		<input type="text" id="imgedit-crop-width-<?php echo $post_id; ?>" onkeyup="imageEdit.setRatioSelection(<?php echo $post_id; ?>, 0, this)" style="width:3em;" />
		:
		<input type="text" id="imgedit-crop-height-<?php echo $post_id; ?>" onkeyup="imageEdit.setRatioSelection(<?php echo $post_id; ?>, 1, this)" style="width:3em;" />
		</span>
	</p>

	<p id="imgedit-crop-sel-<?php echo $post_id; ?>">
		<?php _e('Selection:'); ?>
		<span  class="nowrap">
		<input type="text" id="imgedit-sel-width-<?php echo $post_id; ?>" onkeyup="imageEdit.setNumSelection(<?php echo $post_id; ?>)" style="width:4em;" />
		&times;
		<input type="text" id="imgedit-sel-height-<?php echo $post_id; ?>" onkeyup="imageEdit.setNumSelection(<?php echo $post_id; ?>)" style="width:4em;" />
		</span>
	</p>
	</div>

	<?php if ( $thumb && $sub_sizes ) {
		$thumb_img = wp_constrain_dimensions( $thumb['width'], $thumb['height'], 160, 120 );
	?>

	<div class="imgedit-group imgedit-applyto">
	<div class="imgedit-group-top">
		<h3><?php _e('Thumbnail Settings'); ?> <a href="#" class="dashicons dashicons-editor-help imgedit-help-toggle" onclick="imageEdit.toggleHelp(this);return false;"></a></h3>
		<p class="imgedit-help"><?php _e('You can edit the image while preserving the thumbnail. For example, you may wish to have a square thumbnail that displays just a section of the image.'); ?></p>
	</div>

	<p>
		<img src="<?php echo $thumb['url']; ?>" width="<?php echo $thumb_img[0]; ?>" height="<?php echo $thumb_img[1]; ?>" class="imgedit-size-preview" alt="" draggable="false" />
		<br /><?php _e('Current thumbnail'); ?>
	</p>

	<p id="imgedit-save-target-<?php echo $post_id; ?>">
		<strong><?php _e('Apply changes to:'); ?></strong><br />

		<label class="imgedit-label">
		<input type="radio" name="imgedit-target-<?php echo $post_id; ?>" value="all" checked="checked" />
		<?php _e('All image sizes'); ?></label>

		<label class="imgedit-label">
		<input type="radio" name="imgedit-target-<?php echo $post_id; ?>" value="thumbnail" />
		<?php _e('Thumbnail'); ?></label>

		<label class="imgedit-label">
		<input type="radio" name="imgedit-target-<?php echo $post_id; ?>" value="nothumb" />
		<?php _e('All sizes except thumbnail'); ?></label>
	</p>
	</div>

	<?php } ?>

	</div>

	<div class="imgedit-panel-content">
		<?php echo $note; ?>
		<div class="imgedit-menu">
			<div onclick="imageEdit.crop(<?php echo "$post_id, '$nonce'"; ?>, this)" class="imgedit-crop disabled" title="<?php esc_attr_e( 'Crop' ); ?>"></div><?php

		// On some setups GD library does not provide imagerotate() - Ticket #11536
		if ( wp_image_editor_supports( array( 'mime_type' => get_post_mime_type( $post_id ), 'methods' => array( 'rotate' ) ) ) ) { ?>
			<div class="imgedit-rleft"  onclick="imageEdit.rotate( 90, <?php echo "$post_id, '$nonce'"; ?>, this)" title="<?php esc_attr_e( 'Rotate counter-clockwise' ); ?>"></div>
			<div class="imgedit-rright" onclick="imageEdit.rotate(-90, <?php echo "$post_id, '$nonce'"; ?>, this)" title="<?php esc_attr_e( 'Rotate clockwise' ); ?>"></div>
	<?php } else {
			$note_no_rotate = esc_attr__('Image rotation is not supported by your web host.');
	?>
		    <div class="imgedit-rleft disabled"  title="<?php echo $note_no_rotate; ?>"></div>
		    <div class="imgedit-rright disabled" title="<?php echo $note_no_rotate; ?>"></div>
	<?php } ?>

			<div onclick="imageEdit.flip(1, <?php echo "$post_id, '$nonce'"; ?>, this)" class="imgedit-flipv" title="<?php esc_attr_e( 'Flip vertically' ); ?>"></div>
			<div onclick="imageEdit.flip(2, <?php echo "$post_id, '$nonce'"; ?>, this)" class="imgedit-fliph" title="<?php esc_attr_e( 'Flip horizontally' ); ?>"></div>

			<div id="image-undo-<?php echo $post_id; ?>" onclick="imageEdit.undo(<?php echo "$post_id, '$nonce'"; ?>, this)" class="imgedit-undo disabled" title="<?php esc_attr_e( 'Undo' ); ?>"></div>
			<div id="image-redo-<?php echo $post_id; ?>" onclick="imageEdit.redo(<?php echo "$post_id, '$nonce'"; ?>, this)" class="imgedit-redo disabled" title="<?php esc_attr_e( 'Redo' ); ?>"></div>
			<br class="clear" />
		</div>

		<input type="hidden" id="imgedit-sizer-<?php echo $post_id; ?>" value="<?php echo $sizer; ?>" />
		<input type="hidden" id="imgedit-history-<?php echo $post_id; ?>" value="" />
		<input type="hidden" id="imgedit-undone-<?php echo $post_id; ?>" value="0" />
		<input type="hidden" id="imgedit-selection-<?php echo $post_id; ?>" value="" />
		<input type="hidden" id="imgedit-x-<?php echo $post_id; ?>" value="<?php echo isset( $meta['width'] ) ? $meta['width'] : 0; ?>" />
		<input type="hidden" id="imgedit-y-<?php echo $post_id; ?>" value="<?php echo isset( $meta['height'] ) ? $meta['height'] : 0; ?>" />

		<div id="imgedit-crop-<?php echo $post_id; ?>" class="imgedit-crop-wrap">
		<img id="image-preview-<?php echo $post_id; ?>" onload="imageEdit.imgLoaded('<?php echo $post_id; ?>')" src="<?php echo admin_url( 