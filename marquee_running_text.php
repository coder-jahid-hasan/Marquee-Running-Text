<?php
/*
Plugin Name: Marquee Running Text
Plugin URI: https://mrt.zayaas.com/
Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: activate the Akismet plugin and then go to your Akismet Settings page to set up your API key.
Version: 1.0
Requires at least: 5.0
Requires PHP: 5.2
Author: Jahid Hasan
Author URI: https://author.zayaas.com/
License: GPLv2 or later
Text Domain: mrtext
*/

function mrtext_enqueue_scripts()
{
    wp_enqueue_style(
        "mrtext-main",
        plugin_dir_url(__FILE__) . "assets/css/mrtext-main.css"
    );
}
add_action("wp_enqueue_scripts", "mrtext_enqueue_scripts");

function mrtext_admin_enqueue_scripts()
{
    wp_enqueue_style(
        "mrtext-admin",
        plugin_dir_url(__FILE__) . "assets/css/mrtext-admin.css"
    );
}
add_action("admin_enqueue_scripts", "mrtext_admin_enqueue_scripts");

function mrtext_shortcode_function()
{
    $mrtext_font_direction = get_option("mrtext_font_direction");
    $mrtext_font_scroll_delay = get_option("mrtext_font_scroll_delay", "85");
    $mrtext_bg_color_option = get_option("mrtext_bg_color_option", "#000000");
    $mrtext_color_option = get_option("mrtext_color_option", "#ffffff");
    $mrtext_hover_color_option = get_option("mrtext_hover_color_option", "#ffffff");
    $mrtext_text_field_1 = get_option("mrtext_text_field_1");
    $mrtext_text_field_1_link = get_option("mrtext_text_field_1_link");
    $mrtext_text_field_2 = get_option("mrtext_text_field_2");
    $mrtext_text_field_2_link = get_option("mrtext_text_field_2_link");
    $mrtext_text_field_3 = get_option("mrtext_text_field_3");
    $mrtext_text_field_3_link = get_option("mrtext_text_field_3_link");
    $mrtext_text_field_4 = get_option("mrtext_text_field_4");
    $mrtext_text_field_4_link = get_option("mrtext_text_field_4_link");
    $mrtext_text_field_5 = get_option("mrtext_text_field_5");
    $mrtext_text_field_5_link = get_option("mrtext_text_field_5_link");
    $mrtext_font_size = get_option("mrtext_font_size", "16px");
    $mrtext_font_weight = get_option("mrtext_font_weight", "500");

    ob_start();

    echo '<style> 
.runtext-container {
    background:' .
        $mrtext_bg_color_option .
        ';
    border: 1px solid ' .
        $mrtext_bg_color_option .
        ';
    }
.runtext-container .holder a{ 
    color: ' .
        $mrtext_color_option .
        ';
    font-size: ' .
        $mrtext_font_size .
        ';
    font-weight: ' .
        $mrtext_font_weight .
        ';
}
.text-container a:before {
    background-color: ' .
        $mrtext_color_option .
        ';
}
.runtext-container .holder a:hover{
	color:' .
        $mrtext_hover_color_option .
        ';
}
.text-container a:hover::before {
    background-color: ' .
        $mrtext_hover_color_option .
        ';
}
</style>';
    ?>
<div class="runtext-container">
    <div class="main-runtext">
        <marquee direction="<?php echo $mrtext_font_direction; ?>" scrolldelay="<?php echo $mrtext_font_scroll_delay; ?>" onmouseover="this.stop();"
            onmouseout="this.start();">

            <div class="holder">
                <?php
                if (!empty($mrtext_text_field_1)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_html($mrtext_text_field_1_link) .
                        '" >' .
                        esc_html($mrtext_text_field_1) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_2)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_html($mrtext_text_field_2_link) .
                        '" >' .
                        esc_html($mrtext_text_field_2) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_3)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_html($mrtext_text_field_3_link) .
                        '" >' .
                        esc_html($mrtext_text_field_3) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_4)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_html($mrtext_text_field_4_link) .
                        '" >' .
                        esc_html($mrtext_text_field_4) .
                        '</a>
    </div>';
                }
                if (!empty($mrtext_text_field_5)) {
                    echo '<div class="text-container"><a class="fancybox" href="' .
                        esc_html($mrtext_text_field_5_link) .
                        '" >' .
                        esc_html($mrtext_text_field_5) .
                        '</a>
    </div>';
                }
                ?>
            </div>
        </marquee>
    </div>
</div>


<?php return ob_get_clean();
}
function mrtext_register_shortcode()
{
    add_shortcode("mrtext", "mrtext_shortcode_function");
}
add_action("init", "mrtext_register_shortcode");

function add_shortcode_header()
{
    $current_value = get_option("mrtext_radio", "show");
    if ($current_value == "show") {
        echo do_shortcode("[mrtext]");
    }
}
add_action("wp_head", "add_shortcode_header", 20);

/*
 * Plugin Option Page Function
 */
function mrtext_menu_item()
{
    add_menu_page(
        "Add Marquee",
        "Add Marquee",
        "manage_options",
        "mrtext-settings",
        "mrtext_settings_page",
        "dashicons-buddicons-groups",
        100
    );
    add_submenu_page(
        "mrtext-settings", // Parent menu slug
        "Marquee Style", // Page title
        "Marquee Style", // Menu title
        "manage_options", // Capability required to access the page
        "mrtext_style", // Menu slug
        "mrtext_style_page", // Callback function to render the submenu page
    );
}

add_action("admin_menu", "mrtext_menu_item");

/*
 * Plugin Callback
 */

function mrtext_settings_page()
{
    ?>
<div class="wrap">
    <h1><?php print esc_attr("Add Marquee"); ?></h1>
</div>
<!-- Tab -->
<h2 class="nav-tab-wrapper">
    <a href="?page=mrtext-settings" class="nav-tab nav-tab-active"><?php print esc_attr(
        "Add Marquee"
    ); ?></a>
    <a href="?page=mrtext_style" class="nav-tab"><?php print esc_attr(
        "Marquee Style"
    ); ?></a>
</h2>
<!-- Input Form -->
<div class="container">
    <div class="left-column">
        <form method="post" action="options.php">
            <?php wp_nonce_field("update-options"); ?>
            <h2><?php print esc_attr("Marquee 1"); ?></h2>
            <table class="form-table">
                <!--- Text Area Field 1 --->
                <tr>
                    <th scope="row"><label for="mrtext_text_field_1"><?php print esc_attr(
                "Text Area "
            ); ?></label></th>
                    <td><textarea id="mrtext_text_field_1" name="mrtext_text_field_1" rows="5" cols="50"><?php print esc_textarea(
                        get_option("mrtext_text_field_1", "Text 1")
                    ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mrtext_text_field_1_link" name="mrtext_text_field_1_link"><?php print esc_attr(
                        "Link"
                    ); ?></label></th>
                    <td><input type="text" id="mrtext_text_field_1_link" name="mrtext_text_field_1_link" value="<?php print esc_attr(
                        get_option("mrtext_text_field_1_link", "#")
                    ); ?>" class="color-picker">
                    </td>
                </tr>
            </table>
            <hr>
            <h2><?php print esc_attr("Marquee 2"); ?></h2>
            <table class="form-table">
                <!--- Text Area Field 2 --->
                <tr>
                    <th scope="row"><label for="mrtext_text_field_2"><?php print esc_attr(
                "Text Area "
            ); ?></label></th>
                    <td><textarea id="mrtext_text_field_2" name="mrtext_text_field_2" rows="5" cols="50"><?php print esc_textarea(
                        get_option("mrtext_text_field_2", "Text 2")
                    ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mrtext_text_field_2_link" name="mrtext_text_field_2_link"><?php print esc_attr(
                        "Link"
                    ); ?></label></th>
                    <td><input type="text" id="mrtext_text_field_2_link" name="mrtext_text_field_2_link" value="<?php print esc_attr(
                        get_option("mrtext_text_field_2_link", "#")
                    ); ?>" class="color-picker">
                    </td>
                </tr>
            </table>
            <hr>
            <h2><?php print esc_attr("Marquee 3"); ?></h2>
            <table class="form-table">
                <!--- Text Area Field 3 --->
                <tr>
                    <th scope="row"><label for="mrtext_text_field_3"><?php print esc_attr(
                "Text Area "
            ); ?></label></th>
                    <td><textarea id="mrtext_text_field_3" name="mrtext_text_field_3" rows="5" cols="50"><?php print esc_textarea(
                        get_option("mrtext_text_field_3", "Text 3")
                    ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mrtext_text_field_3_link" name="mrtext_text_field_3_link"><?php print esc_attr(
                        "Link"
                    ); ?></label></th>
                    <td><input type="text" id="mrtext_text_field_3_link" name="mrtext_text_field_3_link" value="<?php print esc_attr(
                        get_option("mrtext_text_field_3_link", "#")
                    ); ?>" class="color-picker">
                    </td>
                </tr>
            </table>
            <hr>
            <h2><?php print esc_attr("Marquee 4"); ?></h2>
            <table class="form-table">
                <!--- Text Area Field 4 --->
                <tr>
                    <th scope="row"><label for="mrtext_text_field_4"><?php print esc_attr(
                "Text Area "
            ); ?></label></th>
                    <td><textarea id="mrtext_text_field_4" name="mrtext_text_field_4" rows="5" cols="50"><?php print esc_textarea(
                        get_option("mrtext_text_field_4", "Text 4")
                    ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mrtext_text_field_4_link" name="mrtext_text_field_4_link"><?php print esc_attr(
                        "Link"
                    ); ?></label></th>
                    <td><input type="text" id="mrtext_text_field_4_link" name="mrtext_text_field_4_link" value="<?php print esc_attr(
                        get_option("mrtext_text_field_4_link", "#")
                    ); ?>" class="color-picker">
                    </td>
                </tr>
            </table>
            <hr>
            <h2><?php print esc_attr("Marquee 5"); ?></h2>
            <table class="form-table">
                <!--- Text Area Field 5 --->
                <tr>
                    <th scope="row"><label for="mrtext_text_field_5"><?php print esc_attr(
                "Text Area "
            ); ?></label></th>
                    <td><textarea id="mrtext_text_field_5" name="mrtext_text_field_5" rows="5" cols="50"><?php print esc_textarea(
                        get_option("mrtext_text_field_5", "Text 5")
                    ); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="mrtext_text_field_5_link" name="mrtext_text_field_5_link"><?php print esc_attr(
                        "Link"
                    ); ?></label></th>
                    <td><input type="text" id="mrtext_text_field_5_link" name="mrtext_text_field_5_link" value="<?php print esc_attr(
                        get_option("mrtext_text_field_5_link", "#")
                    ); ?>" class="color-picker">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="page_options"
                value="mrtext_text_field_1,mrtext_text_field_2,mrtext_text_field_3,mrtext_text_field_4,mrtext_text_field_5,mrtext_text_field_1_link,mrtext_text_field_2_link,mrtext_text_field_3_link,mrtext_text_field_4_link,mrtext_text_field_5_link">
            <p class="submit"><input type="submit" name="submit" class="button-primary"
                    value="<?php _e("Save Changes", "mrtext"); ?> "></p>
        </form>
    </div>
    <div class="right-column">
        <div class="bio-data">
            <h1><?php print esc_attr("About Author"); ?></h1>
        </div>
        <div class="round-image-container">
            <div class="round-image">
                <img src="<?php print plugin_dir_url(__FILE__) .
                    "assets/img/jahid_hasan.jpg"; ?>" alt="Jahid Hasan">
            </div>
        </div>
        <div class="bio-data">
            <h2><?php print esc_attr("Jahid Hasan"); ?></h2>
            <p><?php print esc_attr(
                "As a WordPress developer, you have the ability to create custom themes, plugins, and customize websites to meet specific requirements. Your expertise enables you to shape engaging designs and implement powerful functionality, enhancing the WordPress experience for users. Keep evolving your skills, staying up-to-date with trends, and embracing the WordPress community to make a significant impact in the field of development."
            ); ?></p>
            <h2><?php print esc_attr("Buy Me a Coffee"); ?></h2>
            <div class="bmc-button">
                <script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js"
                    data-name="bmc-button" data-slug="hasanjahid" data-color="#40DCA5" data-emoji="" data-font="Bree"
                    data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#ffffff"
                    data-coffee-color="#FFDD00"></script>
            </div>
            <h2><?php print esc_attr("QR"); ?></h2>
            <div class="round-image-container">
                <div class="round-image">
                    <img src="<?php print plugin_dir_url(__FILE__) .
                    "assets/img/bmc_qr.png"; ?>" alt="Jahid Hasan">
                </div>
            </div>
        </div>
    </div>
<?php
}

// Callback function to render the Marquee Style page
function mrtext_style_page()
{
    ?>
<div class="wrap">
    <h1><?php print esc_attr("Marquee Style"); ?></h1>
</div>
<!-- Tab -->
<h2 class="nav-tab-wrapper">
    <a href="?page=mrtext-settings" class="nav-tab"><?php print esc_attr(
        "Add Marquee"
    ); ?></a>
    <a href="?page=mrtext_style" class="nav-tab nav-tab-active"><?php print esc_attr(
        "Marquee Style"
    ); ?></a>
</h2>
<!-- Style Form -->
<div class="container">
    <div class="left-column">
        <form method="post" action="options.php">
            <?php wp_nonce_field("update-options"); ?>
            <div>
                <h2><?php print esc_attr("Color Options"); ?></h2>
                <table class="form-table">
                    <!--- Background Color Option --->
                    <tr>
                        <th scope="row"><label for="mrtext_bg_color_option" name="mrtext_bg_color_option"><?php print esc_attr(
                            "Background Color"
                        ); ?></label></th>
                        <td><input type="color" id="mrtext_bg_color_option" name="mrtext_bg_color_option" value="<?php print esc_attr(
                            get_option("mrtext_bg_color_option", "#000000")
                        ); ?>" class="color-picker"></td>
                    </tr>
                    <!--- Text Color Option --->
                    <tr>
                        <th scope="row"><label for="mrtext_color_option" name="mrtext_color_option"><?php print esc_attr(
                            "Text Color"
                        ); ?></label></th>
                        <td><input type="color" id="mrtext_color_option" name="mrtext_color_option" value="<?php print esc_attr(
                            get_option("mrtext_color_option", "#ffffff")
                        ); ?>" class="color-picker"></td>
                    </tr>
                    <!--- Text Hover Color Option --->
                    <tr>
                        <th scope="row"><label for="mrtext_hover_color_option" name="mrtext_hover_color_option"><?php print esc_attr(
                            "Text Hover Color"
                        ); ?></label></th>
                        <td><input type="color" id="mrtext_hover_color_option" name="mrtext_hover_color_option" value="<?php print esc_attr(
                            get_option("mrtext_hover_color_option", "#ffffff")
                        ); ?>" class="color-picker"></td>
                    </tr>
                </table>
            </div>
            <hr>
            <div>
                <h2><?php print esc_attr("Typography & Others"); ?></h2>
                <table class="form-table">                    
                    <!--- Font Size --->
                    <tr>
                        <th scope="row"><label for="mrtext_font_size" name="mrtext_font_size"><?php print esc_attr(
                            "Font Size"
                        ); ?></label></th>
                        <td><input type="text" id="mrtext_font_size" name="mrtext_font_size" value="<?php print esc_attr(
                            get_option("mrtext_font_size", "16px")
                        ); ?>" class="color-picker">
                        </td>
                    </tr>
                    <!--- Font Weight --->
                    <tr>
                        <th scope="row"><label for="mrtext_font_weight" name="mrtext_font_weight"><?php print esc_attr(
                            "Font Weight"
                        ); ?></label></th>
                        <td><input type="text" id="mrtext_font_weight" name="mrtext_font_weight" value="<?php print esc_attr(
                            get_option("mrtext_font_weight", "500")
                        ); ?>" class="color-picker">
                        </td>
                    </tr>
                    <!--- Font Direction --->
                    <tr>
                        <th scope="row"><label for="mrtext_font_direction" name="mrtext_font_direction"><?php print esc_attr(
                            "Font Direction"
                        ); ?></label></th>
                        <td><input type="text" placeholder="right or left" id="mrtext_font_direction" name="mrtext_font_direction" value="<?php print esc_attr(
                            get_option("mrtext_font_direction")
                        ); ?>" class="color-picker"></td>
                    </tr>
                    <!--- Scroll Delay --->
                    <tr>
                        <th scope="row"><label for="mrtext_scroll_delay" name="mrtext_font_scroll_delay"><?php print esc_attr(
                            "Scroll Delay"
                        ); ?></label></th>
                        <td><input type="text" id="mrtext_font_scroll_delay" name="mrtext_font_scroll_delay" value="<?php print esc_attr(
                            get_option("mrtext_font_scroll_delay", "85")
                        ); ?>" class="color-picker"></td>
                    </tr>
                </table>
            </div>
            <!--- Top Header Show/Hide --->

            <?php $current_value = get_option("mrtext_radio", "show"); ?>
            <hr>
            <div>
                <h2><?php print esc_attr("Top Header Marquee"); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrtext_radio_show">Show</label></th>
                        <td><input id="mrtext_radio_show" type="radio" name="mrtext_radio" value="show"
                                <?php checked("show", $current_value); ?>></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="mrtext_radio_show">Hide</label></th>
                        <td><input id="mrtext_radio_hide" type="radio" name="mrtext_radio" value="Hide"
                                <?php checked("Hide", $current_value); ?>></td>
                    </tr>
                </table>
            </div>

            <input type="hidden" name="action" value="update">
            <input type="hidden" name="page_options"
                value="mrtext_bg_color_option,mrtext_color_option,mrtext_hover_color_option,mrtext_font_direction,mrtext_font_size,mrtext_font_weight,mrtext_font_scroll_delay,mrtext_radio">
            <p class="submit"><input type="submit" name="submit" class="button-primary"
                    value="<?php _e("Save Changes", "mrtext"); ?> "></p>
        </form>
    </div>
    <div class="right-column">
        <div class="bio-data">
            <h1><?php print esc_attr("About Author"); ?></h1>
        </div>
        <div class="round-image-container">
            <div class="round-image">
                <img src="<?php print plugin_dir_url(__FILE__) .
                    "assets/img/jahid_hasan.jpg"; ?>" alt="Jahid Hasan">
            </div>
        </div>
        <div class="bio-data">
            <h2><?php print esc_attr("Jahid Hasan"); ?></h2>
            <p><?php print esc_attr(
                "As a WordPress developer, you have the ability to create custom themes, plugins, and customize websites to meet specific requirements. Your expertise enables you to shape engaging designs and implement powerful functionality, enhancing the WordPress experience for users. Keep evolving your skills, staying up-to-date with trends, and embracing the WordPress community to make a significant impact in the field of development."
            ); ?></p>
            <h2><?php print esc_attr("Buy Me a Coffee"); ?></h2>
            <div class="bmc-button">
                <script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js"
                    data-name="bmc-button" data-slug="hasanjahid" data-color="#40DCA5" data-emoji="" data-font="Bree"
                    data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#ffffff"
                    data-coffee-color="#FFDD00"></script>
            </div>
            <h2><?php print esc_attr("QR"); ?></h2>
            <div class="round-image-container">
                <div class="round-image">
                    <img src="<?php print plugin_dir_url(__FILE__) .
                    "assets/img/bmc_qr.png"; ?>" alt="Jahid Hasan">
                </div>
            </div>
        </div>
    </div>

<?php
}
