<?php

// Uninstall MyBB GoMobile
// Not that anyone would want to do that, right? ;P

function gomobile_uninstall(){
    global $db;
    // Smarter uninstall, same as install function's cleanup
    // MyBB tables cleanup

    if($db->field_exists("mobile", "posts"))
    {
        // older version cleanup
        $db->query("ALTER TABLE ".TABLE_PREFIX."posts DROP COLUMN mobile");
    }

    if($db->field_exists("mobile", "threads"))
    {
        // older version cleanup
        $db->query("ALTER TABLE ".TABLE_PREFIX."threads DROP COLUMN mobile");
    }

    if($db->field_exists("usemobileversion", "users"))
    {
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
}