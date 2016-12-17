<?php

/**
 * Get an array of all valid mobile style's ids
 * @return int[]
 */
function gomobile_validstyles()
{
    global $mybb;

    // gomobile_theme_id contains a CSV-list of valid styles
    $styles = explode(',', $mybb->settings['gomobile_theme_id']);

    // parse style ids to integers
    foreach($styles as &$style)
        $style = (int) $style;

    return $styles;
}