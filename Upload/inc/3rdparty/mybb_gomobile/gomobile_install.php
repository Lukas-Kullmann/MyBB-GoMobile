<?php
// Installation functions

function gomobile_install(){
    global $db, $mybb;
    // Clean up the database before installing
    // MyBB tables cleanup

    if($db->field_exists("mobile", "posts")){
        $db->query("ALTER TABLE ".TABLE_PREFIX."posts DROP COLUMN mobile");
    }


    if($db->field_exists("mobile", "threads")){
        $db->query("ALTER TABLE ".TABLE_PREFIX."threads DROP COLUMN mobile");
    }


    if($db->field_exists("usemobileversion", "users")){
        $db->query("ALTER TABLE ".TABLE_PREFIX."users DROP COLUMN usemobileversion");
    }

    // Settings cleanup
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='gomobile'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='gomobile_header_text'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='gomobile_theme_id'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='gomobile_permstoggle'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='gomobile_homename'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='gomobile_homelink'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='gomobile_strings'");
    // Add a column to the posts & threads tables for tracking mobile posts
    $db->query("ALTER TABLE ".TABLE_PREFIX."posts ADD mobile int NOT NULL default '0'");
    $db->query("ALTER TABLE ".TABLE_PREFIX."threads ADD mobile int NOT NULL default '0'");
    // And another to the users table for options
    $db->query("ALTER TABLE ".TABLE_PREFIX."users ADD usemobileversion int NOT NULL default '1'");
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
    // Edit existing templates (shows when posts are from GoMobile)
    require_once MYBB_ROOT."inc/adminfunctions_templates.php";
    find_replace_templatesets("postbit_posturl", '#'.preg_quote('<span').'#', '<img src="{$mybb->settings[\'bburl\']}/images/mobile/posted_{$post[\'mobile\']}.gif" alt="" width="{$post[\'mobile\']}8" height="{$post[\'mobile\']}8" title="{$lang->gomobile_posted_from}" style="vertical-align: middle;" /> '.'<span');
    // Prepare to insert the settings
    $setting_group = array("gid" => 0,"name" => "gomobile","title" => "GoMobile Settings","description" => "Options, settings and strings used by MyBB GoMobile.","disporder" => 1,"isdefault" => 0,);
    $gid = $db->insert_query("settinggroups", $setting_group);
    $dispnum = 0;
    global $lang;
    $lang->load("../gomobile");
    $settings = array("gomobile_mobile_name" => array("title"=> $lang->gomobile_settings_mobile_name_title,"description"=> $lang->gomobile_settings_mobile_name,"optionscode"=> "text","value"=> $db->escape_string($mybb->settings['bbname']),"disporder"=> ++$dispnum),"gomobile_theme_id" => array("title"=> $lang->gomobile_settings_theme_id_title,"description"=> $lang->gomobile_settings_theme_id,"optionscode"=> "text","value"=> $theme,"disporder"=> ++$dispnum),"gomobile_permstoggle" => array("title"=> $lang->gomobile_settings_permstoggle_title,"description"=> $lang->gomobile_settings_permstoggle,"optionscode"=> "yesno","value"=> 0,"disporder"=> ++$dispnum),"gomobile_homename" => array("title"=> $lang->gomobile_settings_homename_title,"description"=> $lang->gomobile_settings_homename,"optionscode"=> "text","value"=> $db->escape_string($mybb->settings['homename']),"disporder"=> ++$dispnum),"gomobile_homelink" => array("title"=> $lang->gomobile_settings_homelink_title,"description"=> $lang->gomobile_settings_homelink,"optionscode"=> "text","value"=> $db->escape_string($mybb->settings['homeurl']),"disporder"=> ++$dispnum),"gomobile_strings" => array("title"=> $lang->gomobile_settings_strings_title,"description"=> $lang->gomobile_settings_strings,"optionscode"=> "textarea","value"=> $db->escape_string($strings),"disporder"=> ++$dispnum));
    // Insert the settings listed above
    foreach($settings as $name => $setting){
        $setting['gid'] = $gid;
        $setting['name'] = $name;
        $db->insert_query("settings", $setting);
    }

    rebuild_settings();
}
