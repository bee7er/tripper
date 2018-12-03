<?php

namespace App\Model;

class ContextMenu
{
    // Bit map definitions
    const CM_EDIT = 1;
    const CM_COLLAPSE = 2;
    const CM_INSERT_ACTION = 4;
    const CM_INSERT_COMMENT = 8;
    const CM_INSERT_CASE = 16;
    const CM_INSERT_ELSE = 32;
    const CM_INSERT_ITERATION = 64;
    const CM_INSERT_SEQUENCE = 128;
    const CM_ZOOM = 256;
    const CM_UNZOOM = 512;
    const CM_DELETE = 1024;

    // Action definitions
    const CM_ACTION_EDIT = 'edit';
    const CM_ACTION_COLLAPSE = 'collapse';
    const CM_ACTION_INSERT_ACTION = 'insert-action';
    const CM_ACTION_INSERT_COMMENT = 'insert-comment';
    const CM_ACTION_INSERT_CASE = 'insert-case';
    const CM_ACTION_INSERT_ELSE = 'insert-else';
    const CM_ACTION_INSERT_ITERATION = 'insert-iteration';
    const CM_ACTION_INSERT_SEQUENCE = 'insert-sequence';
    const CM_ACTION_ZOOM = 'zoom';
    const CM_ACTION_UNZOOM = 'unzoom';
    const CM_ACTION_DELETE = 'delete';

    /**
     * Build and return the context menu for an instance
     *
     * @param $instance
     * @return string
     */
    public static function getContextMenu($instance)
    {
        // NB Using a bit map to see which options are appropriate for each block type
        $map = $instance->contextMenuMap;
        $formHtml = '<ul class="menu-options">';
        $formHtml .= ($map & self::CM_EDIT ? '<li class="menu-option" id="'.self::CM_ACTION_EDIT.'">Edit</li>' : '');
        $formHtml .= ($map & self::CM_COLLAPSE ? '<li class="menu-option" id="'.self::CM_ACTION_COLLAPSE.'">Collapse/Expand</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_ACTION ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_ACTION.'">Insert Action</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_COMMENT ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_COMMENT.'">Insert Comment</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_CASE ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_CASE.'">Insert Case</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_ELSE ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_ELSE.'">Insert Else</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_ITERATION ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_ITERATION.'">Insert Iteration</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_SEQUENCE ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_SEQUENCE.'">Insert Sequence</li>' : '');
        $formHtml .= ($map & self::CM_ZOOM ? '<li class="menu-option" id="'.self::CM_ACTION_ZOOM.'">Zoom</li>' : '');
        $formHtml .= ($map & self::CM_UNZOOM ? '<li class="menu-option" id="'.self::CM_ACTION_UNZOOM.'">Unzoom</li>' : '');
        $formHtml .= ($map & self::CM_DELETE ? '<li class="menu-option" id="'.self::CM_ACTION_DELETE.'">Delete</li>' : '');
        $formHtml .= '</ul>';

        return $formHtml;
    }
}
