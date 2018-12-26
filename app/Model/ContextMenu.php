<?php

namespace App\Model;

use App\Model\Instances\InstanceInterface;

class ContextMenu
{
    // Bit map definitions
    const CM_EDIT = 1;
    const CM_COLLAPSE = 2;
    const CM_INSERT_ACTION = 4;
    const CM_INSERT_COMMENT = 8;
    const CM_INSERT_CONDITION = 16;
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
    const CM_ACTION_INSERT_CONDITION = 'insert-condition';
    const CM_ACTION_INSERT_ELSE = 'insert-else';
    const CM_ACTION_INSERT_ITERATION = 'insert-iteration';
    const CM_ACTION_INSERT_SEQUENCE = 'insert-sequence';
    const CM_ACTION_SELECT_SNIPPET = 'select-snippet';
    const CM_ACTION_ZOOM = 'zoom';
    const CM_ACTION_UNZOOM = 'unzoom';
    const CM_ACTION_DELETE = 'delete';

    const INSERT_AFTER = 'insert-after';
    const INSERT_INSIDE = 'insert-inside';
    const INSERT_BEFORE = 'insert-before';

    /**
     * Build and return the context menu for an instance
     *
     * @param $instance
     * @return string
     */
    public static function getContextMenu(InstanceInterface $instance)
    {
        $message = null;
        $collapse = 'Collapse';
        if ($instance->obj->collapsed) {
            $collapse = 'Expand';
        }

        // Check for any missing actions; they go first
        $missingOptions = $instance->getMissingOptions();

        // NB Using a bit map to see which options are appropriate for each block type
        $map = $instance->obj->contextMenuMap;
        $formHtml = '<ul class="menu-options">';
        if ($missingOptions) {
            foreach ($missingOptions as $missingOption) {
                $formHtml .= ('<li class="menu-option" id="'.$missingOption.'">' . self::tidy($missingOption) . '</li>');
            }
        }

        $isComplete = false;
        if (Block::BLOCK_TYPE_ACTION == $instance->obj->type
            && Subtype::SUBTYPE_SNIPPET == $instance->obj->subtype
        ) {
            $isComplete = $instance->isComplete();
        }

        $formHtml .= ($map & self::CM_EDIT ? '<li class="menu-option" id="'.self::CM_ACTION_EDIT.'">Edit</li>' : '');
        $formHtml .= ($map & self::CM_COLLAPSE ? '<li class="menu-option" id="'.self::CM_ACTION_COLLAPSE.'">' . $collapse . '</li>' : '');
        $formHtml .= ($map & self::CM_ZOOM && $isComplete ? '<li class="menu-option" id="'.self::CM_ACTION_ZOOM.'">Zoom</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_ACTION ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_ACTION.'">Insert Action</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_COMMENT ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_COMMENT.'">Insert Comment</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_CONDITION ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_CONDITION.'">Insert Condition</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_ELSE ? '<li class="menu-option inactive" id="'.self::CM_ACTION_INSERT_ELSE.'">Insert Else</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_ITERATION ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_ITERATION.'">Insert Iteration</li>' : '');
        $formHtml .= ($map & self::CM_INSERT_SEQUENCE ? '<li class="menu-option" id="'.self::CM_ACTION_INSERT_SEQUENCE.'">Insert Sequence</li>' : '');
        $formHtml .= ($map & self::CM_DELETE ? '<li class="menu-option" id="'.self::CM_ACTION_DELETE.'">Delete</li>' : '');
        $formHtml .= '</ul>';

        return [$formHtml, $message];
    }

    /**
     * Prepare an action string for external use
     *
     * @param $action
     */
    private static function tidy($action) {
        return ucwords(str_replace('-', ' ', $action));
    }
}
