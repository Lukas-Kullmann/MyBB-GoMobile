<?php

// Uninstall MyBB GoMobile
// Not that anyone would want to do that, right? ;P

function gomobile_uninstall(){
    global $db;
    // Smarter uninstall, same as install function's cleanup
    // MyBB tables cleanup

    if($db->field_exists("mobile", "posts"))
    {
        $db->query("ALTER TABLE ".TABLE_PREFIX."posts DROP COLUMN mobile");
    }

    if($db->field_exists("mobile", "threads"))
    {
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

    // Undo the template edits we made earlier
    require_once MYBB_ROOT . "inc/adminfunctions_templates.php";

    find_replace_templatesets(
        "postbit_posturl",
        '#' . preg_quote(
                '<img src="{$mybb->settings[\'bburl\']}/images/mobile/posted_{$post[\'mobile\']}.gif" alt="" width="{$post[\'mobile\']}8" height="{$post[\'mobile\']}8" title="{$lang->gomobile_posted_from}" style="vertical-align: middle;" /> '.''
            ) . '#',
        '',
        0
    );
}