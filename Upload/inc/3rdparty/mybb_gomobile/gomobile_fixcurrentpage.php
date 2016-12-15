<?php

// Undo the slight change we may have made earlier to get around per-forum themes
function gomobile_fixcurrentpage(){
    global $current_page;

    $current_page = my_strtolower(basename(THIS_SCRIPT));
}
