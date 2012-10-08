<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @desc Simple function to check if current page is equal to nav list element.
 *       This case return "active" as class name for the list element.
 * @param String $pageID
 * @param String $linkID
 * @return String/Null
 */
function isActive($pageID, $linkID) {
    if ($pageID == $linkID) {
        return "active";
    }
}

function isIconActive($pageID, $linkID,$used_icon) {
    if ($pageID == $linkID) {
        return '<icon class="'.$used_icon.' icon-white"></icon>';
    } else {
        return '<icon class="'.$used_icon.' icon-grey"></icon>';
    }
}

?>