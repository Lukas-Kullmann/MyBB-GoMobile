<?php
// Installation functions

function gomobile_install(){
    global $db, $mybb, $lang;

    // Clean up the database before installing
    gomobile_uninstall();

    // And another to the users table for options
    $db->query("ALTER TABLE ".TABLE_PREFIX."users ADD usemobileversion tinyint(1) NOT NULL default 1");
    $db->query("ALTER TABLE ".TABLE_PREFIX."users ADD mobilestyle smallint(5) unsigned NOT NULL default 0");
    // First, check that our theme doesn't already exist
    $query = $db->simple_select("themes", "tid", "LOWER(name) LIKE '%mybb gomobile%'");

    if($db->num_rows($query)){
        // Theme is already installed
        $theme = $db->fetch_field($query, "tid");
    } else {
        // Import the theme for our users
        $theme = MYBB_ROOT."inc/plugins/gomobile_theme.xml";

        if(!file_exists($theme)){
            flash_message("Upload the GoMobile Theme XML to the plugin directory (./inc/plugins/) before continuing.", "error");
            admin_redirect("index.php?module=config/plugins");
        }

        $contents = @file_get_contents($theme);

        if($contents){
            $options = array('no_stylesheets' => 0,'no_templates' => 0,'version_compat' => 1,'parent' => 1,'force_name_check' => true,);
            require_once MYBB_ADMIN_DIR."inc/functions_themes.php";
            $theme = import_theme_xml($contents, $options);
        }

    }

    // Default strings
    $strings = "
iPhone
iPod
iPhone
iPad
Mobile
Android
Presto
Opera Mini
Opera Mobi
IEMobile
Windows Phone
HTC
Nokia
Netfront
SmartPhone
Symbian
SonyEricsson
AvantGo
DoCoMo
Pre/
UP.Browser
Playstation Vita
Blazer
Bolt
Doris
Dorothy";

    // Prepare to insert the settings
    $setting_group = array(
        "gid"         => 0,
        "name"        => "gomobile",
        "title"       => "GoMobile Settings",
        "description" => "Options, settings and strings used by MyBB GoMobile.",
        "disporder"   => 1,
        "isdefault"   => 0
    );
    
    $gid = $db->insert_query("settinggroups", $setting_group);
    $dispnum = 0;
    
    gomobile_load_language();
    
    $settings = array(
        "gomobile_mobile_name" => array(
            "title"       => $lang->setting_gomobile_mobile_name,
            "description" => $lang->setting_gomobile_mobile_name_desc,
            "optionscode" => "text",
            "value"       => $db->escape_string($mybb->settings['bbname']),
            "disporder"   => ++$dispnum
        ),
        "gomobile_theme_id" => array(
            "title"       => $lang->setting_gomobile_theme_id,
            "description" => $lang->setting_gomobile_theme_id_desc,
            "optionscode" => "text",
            "value"       => $theme,
            "disporder"   => ++$dispnum
        ),
        "gomobile_permstoggle" => array(
            "title"       => $lang->setting_gomobile_permstoggle,
            "description" => $lang->setting_gomobile_permstoggle_desc,
            "optionscode" => "yesno",
            "value"       => 0,
            "disporder"   => ++$dispnum
        ),
        "gomobile_homename" => array(
            "title"       => $lang->setting_gomobile_homename,
            "description" => $lang->setting_gomobile_homename_desc,
            "optionscode" => "text",
            "value"       => $db->escape_string($mybb->settings['homename']),
            "disporder"   => ++$dispnum
        ),
        "gomobile_homelink" => array(
            "title"       => $lang->setting_gomobile_homelink,
            "description" => $lang->setting_gomobile_homelink_desc,
            "optionscode" => "text",
            "value"       => $db->escape_string($mybb->settings['homeurl']),
            "disporder"   => ++$dispnum
        ),
        "gomobile_strings" => array(
            "title"       => $lang->setting_gomobile_strings,
            "description" => $lang->setting_gomobile_strings_desc,
            "optionscode" => "textarea",
            "value"       => $db->escape_string($strings),
            "disporder"   => ++$dispnum
        )
    );
    
    // Insert the settings listed above
    foreach($settings as $name => $setting){
        $setting['gid'] = $gid;
        $setting['name'] = $name;
        $db->insert_query("settings", $setting);
    }

    rebuild_settings();
}
