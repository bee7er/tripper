<?php

namespace App\Model\Instances;

use App\Model\Block;
use App\Model\ContextMenu;

class ElseInstance extends InstanceBase
{
    /**
     * Build and return the string representing the closing line
     * It is effectively the close line of a condition
     *
     * @param $depth
     * @param $colors
     * @param $blockType
     * @param $title
     * @return string
     */
    public function getClosingLine($depth, $colors, $blockType = null, $title = null)
    {
        // Override the block type to condition, as that is where the else occurs
        return parent::getClosingLine($depth, $colors, Block::BLOCK_TYPE_CONDITION, '');
    }

    /**
     * Where do we insert new instances?  It depends.
     *
     * @return array
     */
    public function getInsertAction()
    {
        return ['Insert inside', ContextMenu::INSERT_INSIDE];
    }
}
