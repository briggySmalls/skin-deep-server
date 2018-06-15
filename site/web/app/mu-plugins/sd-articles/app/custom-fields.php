<?php

namespace App;

if( function_exists('acf_add_local_field_group') ) {
    acf_add_local_field_group(array (
        'key' => 'group_5b21a61fddb39',
        'title' => 'Featured',
        'fields' => array (
            array (
                'key' => 'field_5b21a633286bf',
                'label' => 'Featured Video',
                'name' => 'sd_featured_video',
                'type' => 'oembed',
                'value' => NULL,
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'width' => '',
                'height' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
}
else {
    add_action( 'admin_notices', function () {
      ?>
      <div class="update-nag error">
          <p><?php _e( 'Advanced Custom Fields not installed. Skin Deep Articles will not work', 'my_plugin_textdomain' ); ?></p>
      </div>
      <?php
    });
}
