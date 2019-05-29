<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wp_My_Bookmarks
 * @subpackage Wp_My_Bookmarks/admin/partials
 */
?>
<?php
    if ( !defined( 'ABSPATH' ) ) exit;

    if ( isset($_REQUEST['_wpnonce']) && 
         wp_verify_nonce($_REQUEST['_wpnonce'], $_REQUEST['action'].'-post_'.$_REQUEST['post_id']) ) {
            $user_id = get_current_user_id();
            $bookmarks_array = json_decode( get_user_meta($user_id, 'lm_my_bookmarks', true) , true); 

            if ($_REQUEST['action'] == 'boo_trash') {
                $bookmarks_array[$_REQUEST['post_id']]['is_in_trash'] = true;
            } elseif ($_REQUEST['action'] == 'boo_delete') {
                unset($bookmarks_array[$_REQUEST['post_id']]);
            }
            update_user_meta($user_id, 'lm_my_bookmarks', json_encode($bookmarks_array, JSON_UNESCAPED_UNICODE));

    }

/**
 * function that generates permalink for delete bookmark in the backend
 */
function get_delete_bookmark_link($id = null, $force_delete = false) {
	if ( empty( $id ) ) { 
		return;
	}

	if ($_REQUEST['status'] == 'boo_trash')  {
		$force_delete = true;  
	}

	$action = ( $force_delete ) ? 'boo_delete' : 'boo_trash';

	$delete_link = add_query_arg( 'action', $action, 
		wp_nonce_url( 
			admin_url('admin.php?page=my_bookmarks&post_id='.$id ), "$action-post_$id" ) 
		);

	return $delete_link;
}    
?>
<div class="wrap">
<h1><?php _e('My Bookmarks', 'wp-my-bookmarks');?></h1>
<?php
    $user_id = get_current_user_id();
    $bookmarks_array = json_decode( get_user_meta($user_id, 'lm_my_bookmarks', true) , true);
    if (empty($bookmarks_array)) {
        _e('There is no bookmarks yet. You can add them from the archives at the bottom of the each post', 'wp-my-bookmarks');
    } else {
        ?>
        <ul class="subsubsub">
	        <li class="all"><a href="admin.php?page=my_bookmarks" class="" aria-current="page"><?php _e('All Bookmarks', 'wp-my-bookmarks'); ?></a> |</li>
	        <li class="trash"><a href="admin.php?page=my_bookmarks&status=boo_trash"><?php _e('Trash'); ?> </a></li>
        </ul>
            <table class="wp-list-table lm_bookmarks_table widefat fixed posts"><thead>
            <td class="check-column">
                <label class="screen-reader-text" for="cb-select-all-1"><?php _e( 'Select All' ); ?></label><input id="cb-select-all-1" type="checkbox" />
            </td>
            <td class="thumb_td"><?php _e('Thumbnail'); ?></td>
            <td class="title column-title has-row-actions column-primary page-title"><?php  _e('Title'); ?></td>
            </thead><tbody>
        <?php       
            foreach($bookmarks_array as $boo) {
                if ($_REQUEST['status'] == 'boo_trash' && $boo['is_in_trash']== false) continue;
                if ($_REQUEST['status'] !== 'boo_trash' && $boo['is_in_trash']== true) continue;
        ?>         
            <tr>
                <td>
                    <input id="cb-select-'<?php echo $boo['ID']; ?>'" type="checkbox" name="post[]" value="<?php echo $boo['ID']; ?>" /></td>
                <td class="thumb_td">
                    <div class="lm_thumb"><?php echo html_entity_decode($boo['thumbnail']); ?></div>
                </td>    
                <td>
                    <h3><a href="<?php echo $boo['permalink']; ?>"><?php echo $boo['title']; ?></a></h3><div class="lm_short_desc"><?php echo html_entity_decode($boo['excerpt']); ?></div> 
                    <div class="row-actions">
        <?php 
            echo sprintf(
                '<span class="trash"><a href="%s" class="submitdelete" aria-label="%s">%s</a></span>',
                get_delete_bookmark_link( $boo['ID'] ),
                esc_attr( sprintf( __( 'Move &#8220;%s&#8221; to the Trash' ), $boo['title'] ) ),
                _x( 'Trash', 'verb' )
              );
        ?>      
        </div></td></tr>
        <?php
            } ?>
        </tbody></table>
<?php        
    }
?>

</div>