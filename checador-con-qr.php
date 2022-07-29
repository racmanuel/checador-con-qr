<?php
/*
Plugin Name: Checador con QR
Plugin URI: 
Description: 
Version: 
Author: 
Author URI: 
License: 
License URI: 
*/

use chillerlan\QRCode\{QRCode, QROptions};

include_once 'vendor/autoload.php';

function new_modify_user_table( $column ) {
    $column['qr'] = 'QR';
    $column['xyz'] = 'XYZ';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    $qrcode = new QRCode();
    switch ($column_name) {
        case 'qr' :
            $name_user = get_user_meta($user_id, 'first_name', true);
            $last_name_user = get_user_meta($user_id, 'last_name', true);
            return '<img src="'.$qrcode->render($last_name_user).'" alt="QR Code" />';
        case 'xyz' :
            return '';
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

add_action( 'wp_enqueue_scripts', 'check_scripts' );
/**
 * Loads Scripts
 *
 * @return void
 */
function check_scripts() {
    wp_register_script(
        'check-scan',
        plugins_url( 'js/index.js', __FILE__ ),
        array( 'jquery' ),
        '1.0',
        true
    );
    wp_localize_script(
        'check-scan', 
        'ajax_object', 
        array(
            'ajax_url' => admin_url('admin-ajax.php')
    ));
    //Works with: 'https://rawcdn.githack.com/tobiasmuehl/instascan/4224451c49a701c04de7d0de5ef356dc1f701a93/bin/instascan.min.js';
    wp_register_script(
        'instascan',
        plugins_url( 'js/instascan.min.js', __FILE__ ),
        array( 'jquery' ),
        '1.0',
        true
    );
    wp_enqueue_style(
        'instascan-css', 
        plugins_url('css/styles.css', __FILE__),
        array(), 
        '1.0', 
        'all'
    );
    wp_enqueue_style('instascan-css');
}

add_shortcode('instascan', 'wp_instascan');

function wp_instascan(){
    wp_enqueue_script( 'instascan' );
    wp_enqueue_script( 'check-scan' );
    ?>
    <video id="preview"></video>
<?php
}

function insert_in_db()
{
    // Nuestro código de manipulación de los datos

    $content = $_POST['content'];
    
    wp_die();
}

add_action('wp_ajax_nopriv_insert_in_db', 'insert_in_db'); // Para usuarios no logueados
add_action('wp_ajax_insert_in_db', 'insert_in_db'); // Para usuarios logueados
