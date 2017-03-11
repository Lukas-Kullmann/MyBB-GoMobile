<?php
/*
* MyBB GoMobile - 1.3.2
* Licensed under GNU/GPL v3
*/

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB")){
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// Theme overriding
$plugins->add_hook("global_start", "gomobile_forcetheme");
$plugins->add_hook("global_end",   "gomobile_fixcurrentpage");

// Page numbers
$plugins->add_hook("showthread_end", "gomobile_showthread");

// UCP options
$plugins->add_hook("usercp_options_end",    "gomobile_usercp_options");
$plugins->add_hook("usercp_do_options_end", "gomobile_usercp_options");

// Misc. hooks
$plugins->add_hook("misc_start", "gomobile_switch_version");

// Settings
$plugins->add_hook("admin_config_settings_change", "gomobile_load_language");

// Require functions
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_fixcurrentpage.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_forcefooter.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_forcetheme.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_info.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_install.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_load_language.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_is_installed.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_showthread.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_switch_version.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_uninstall.php';
require_once __DIR__ . '/../3rdparty/mybb_gomobile/gomobile_usercp_options.php';
