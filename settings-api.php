<?php 
/**
* Plugin Name: Settings API
* Description: A New Admin Menu Page using Setting API.
* Version: 1.0
* Author: Sandeep Kamra
**/
class MyPlugin_AdminPage {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_new_menu_in_wordpress_admin'));
        add_action('admin_init', array($this, 'display_options') );
    }
    
    function add_new_menu_in_wordpress_admin() {
        add_menu_page('Practice', 'Practices', 'manage_options', 'practice', array($this, 'show_form'), 'dashicons-hourglass', 2);
    }
    
    function show_form(){
        ?>
        <div class="wrap">
            <h2>Practice Page</h2>
            <form method="post" action="options.php">
                <?php settings_fields('header_section'); ?>
                <?php do_settings_sections('practice'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    function display_options() {
        add_settings_section("header_section", "Header Options", array($this, "display_header_options_content"), "practice");
        
        add_settings_field('user_name', "Author Name", array($this, "display_username_form_element"), "practice", "header_section");	
        add_settings_field('marital_status', "Married?", array($this, "display_marital_status_form_element"), "practice", "header_section");	        
        add_settings_field('skills', "Skills", array($this, "display_skills_form_element"), "practice", "header_section");	        
        add_settings_field('gender', "Gender", array($this, "display_gender"), "practice", "header_section");
        add_settings_field('header_logo', "Logo Url", array($this, "display_logo_form_element"), "practice", "header_section");							
        add_settings_field('attachment_id', "Upload an Image", array($this, "images"), "practice", "header_section");
        add_settings_field('multiple_attachment_id', "Upload an Image", array($this, "multiple_images"), "practice", "header_section");
        
        register_setting('header_section', "user_name");
        register_setting('header_section', "marital_status");
        register_setting('header_section', "gender");
        register_setting('header_section', "header_logo");
        register_setting('header_section', "skills");
        register_setting('header_section', "attachment_id");
        register_setting('header_section', "multiple_attachment_id");
    }
    function display_header_options_content() {
        echo "Header of the theme";
    }
    
    function display_username_form_element() {
        ?>
        <input type="text" name="user_name" id="user_name" value="<?php echo get_option('user_name'); ?>" />
        <?php
    }
    function display_marital_status_form_element() {							
        $status = get_option('marital_status');
        $checked = '';
        if($status != '') {
            $checked = "checked=checked";
        }
        ?>
        <input type="checkbox" name="marital_status" id="marital_status" value="1" <?php echo $checked; ?> />
        <?php
    }
    function display_gender() {
        $gender_type = get_option('gender');
        ?>
        Male: <input type="radio" name="gender" value="male" <?php echo ($gender_type == 'male') ? $checked = "checked=checked" : ''; ?> />
        Female: <input type="radio" name="gender" value="female" <?php echo ($gender_type == 'female') ? $checked = "checked=checked" : ''; ?> />
        <?php
    }
    function display_logo_form_element() {
        ?>							
        <?php $value = get_option('header_logo'); ?>
        <select name="header_logo" id="header_logo">
            <option value="abc" <?php if($value == 'abc') {?> selected="selected" <?php } ?>>ABC</option>
            <option value="xyz" <?php if($value == 'xyz') {?> selected="selected" <?php } ?>>XYZ</option>
            <option value="mno" <?php if($value == 'mno') {?> selected="selected" <?php } ?>>MNO</option>
        </select>
        <?php
    }  
    function display_skills_form_element() {
        $skills = get_option('skills');
        $jquery = isset($skills['jquery']) ? $checked = "checked=checked" : '';
        $php = isset($skills['php']) ? $checked = "checked=checked" : '';
        $angular = isset($skills['angular']) ? $checked = "checked=checked" : '';
        ?>
        Jquery <input type="checkbox" name="skills[jquery]" value="jquery" <?php echo $jquery; ?> />
        PHP <input type="checkbox" name="skills[php]" value="php" <?php echo $php; ?> />
        Angular <input type="checkbox" name="skills[angular]" value="angular" <?php echo $angular; ?> />
        <?php      
    }
    function images() {
        /* https://webomnizz.com/custom-image-upload-in-wordpress/#:~:text=Adding%20Custom%20Image%20Upload,()%20function%20on%20wp_enqueue_scripts%20hook.&text=You%20can%20check%20the%20more,similar%20to%20the%20below%20image. */
        $image_id = get_option('attachment_id');
        if( $image = wp_get_attachment_image_src( $image_id ) ) {
            ?>
            <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
            <input type="hidden" name="attachment_id" class="wp_attachment_id" value="<?php echo $image_id; ?>" /> </br>
            <img src="<?php echo $image[0]; ?>" class="image" style="margin-top:10px;"/>
            <?php
        } else {           
            ?> 
            <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
            <input type="hidden" name="attachment_id" class="wp_attachment_id" value="" /> </br>
            <img src="" class="image" style="display:none;margin-top:10px;"/>
            <?php
        }        
    }
    function multiple_images() {
        $image_id = get_option('multiple_attachment_id');   
        if($image_id) {
            ?>
            <div class="item-wrap">
                <div class="item-inner-wrap">
                    <?php
                    foreach($image_id as $key => $id) {
                        if($image = wp_get_attachment_image_src($id)) {
                            ?>
                            <input type="hidden" name="multiple_attachment_id[]" class="wp_multiple_attachment_id" value="<?php echo $id; ?>" /> </br>
                            <img src="<?php echo $image[0]; ?>" width="50" height="50" class="multiple_image" style="margin-top:10px;" />
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <input type="button" value="Upload Image" class="button-primary" id="multiple_upload_image" />
            <?php
        }
        else {
            ?>
            <div class="item-wrap">
                <div class="item-inner-wrap"></div>
                <input type="button" value="Upload Image" class="button-primary" id="multiple_upload_image" />
            </div>
            <?php
        }
    }
}
if(is_admin()) {
    $myplugin_adminpage = new MyPlugin_AdminPage();
}

add_action( 'admin_enqueue_scripts', 'misha_include_js' );
function misha_include_js() {
    if(!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }    
    wp_enqueue_script( 'myuploadscript', plugin_dir_url( __FILE__ ) . '/customscript.js', array( 'jquery' ) );
}
?>