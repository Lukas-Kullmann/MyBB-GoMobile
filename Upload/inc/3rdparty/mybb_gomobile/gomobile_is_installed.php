<?php

// Checks to see if the plugin is installed already
function gomobile_is_installed(){
    global $db;
    // Is the cache [the last installation step performed] ready for use?
    $installed = $db->simple_select("settings", "*", "name='gomobile_strings'");

    return $db->num_rows($installed) > 0;
}
