<?php

namespace App\Model;

class ContextMenu
{
    // Bit map definitions
    const CONTEXT_MENU_EDIT = 1;
    const CONTEXT_MENU_COLLAPSE = 2;
    const CONTEXT_MENU_INSERT_ACTION = 4;
    const CONTEXT_MENU_INSERT_COMMENT = 8;
    const CONTEXT_MENU_INSERT_CASE = 16;
    const CONTEXT_MENU_INSERT_ELSE = 32;
    const CONTEXT_MENU_INSERT_ITERATION = 64;
    const CONTEXT_MENU_INSERT_SEQUENCE = 128;
    const CONTEXT_MENU_ZOOM = 256;
    const CONTEXT_MENU_UNZOOM = 512;
    const CONTEXT_MENU_DELETE = 1024;

    /**
     * Build and return the context menu for an instance
     *
     * @param $instance
     * @return string
     */
    public static function getContextMenu($instance)
    {
        $map = $instance->contextMenuMap;
        $formHtml = '<ul class="menu-options">';
        $formHtml .= ($map & self::CONTEXT_MENU_EDIT ? '<li class="menu-option" id="edit">Edit</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_COLLAPSE ? '<li class="menu-option" id="edit">Collapse/Expand</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_INSERT_ACTION ? '<li class="menu-option" id="edit">Insert Action</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_INSERT_COMMENT ? '<li class="menu-option" id="edit">Insert Comment</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_INSERT_CASE ? '<li class="menu-option" id="edit">Insert Case</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_INSERT_ELSE ? '<li class="menu-option" id="edit">Insert Else</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_INSERT_ITERATION ? '<li class="menu-option" id="edit">Insert Iteration</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_INSERT_SEQUENCE ? '<li class="menu-option" id="edit">Insert Sequence</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_ZOOM ? '<li class="menu-option" id="edit">Zoom</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_UNZOOM ? '<li class="menu-option" id="edit">Unzoom</li>' : '');
        $formHtml .= ($map & self::CONTEXT_MENU_DELETE ? '<li class="menu-option" id="edit">Delete</li>' : '');
        $formHtml .= '</ul>';

        return $formHtml;
    }
}
