<?php

namespace App\Model\Instances;

use App\Model\Block;
use App\Model\ContextMenu;
use App\Model\Instance;

abstract class InstanceBase implements InstanceInterface
{
    /**
     * The instance object
     * @var object
     */
    public $obj;

    /**
     * The instance object
     * @var array
     */
    public $entries;

    /**
     * InstanceBase constructor.
     * @param Instance $instance
     */
    public function __construct(Instance $instance)
    {
        $this->obj = $instance;
        $this->entries = [];
    }

    /**
     * @param $name
     */
    public function setEntries($array)
    {
        return $this->entries = $array;
    }

    /**
     * Get the current prefix, comprising the depth and colors of previous levels
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    protected function getPrefix($depth, $colors)
    {
        $prefix = '';
        for ($i=0; $i<($depth - 1); $i++) {
            $prefix .= ("<span style='color: #{$colors[$i]}'>▎</span>");
        }

        return $prefix;
    }

    /**
     * Build and return the string representing the opening line
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    public function getOpeningLine($depth, $colors)
    {
        $prefix = $this->getPrefix($depth, $colors);

        // Insert before for containers but after for non-containers
        $title = 'Insert after';
        $insertAction = ContextMenu::INSERT_AFTER;
        $collapsed = $incomplete = '';
        if ($this->obj->container) {
            $title = 'Insert before';
            $insertAction = ContextMenu::INSERT_BEFORE;
            if ($this->obj->collapsed) {
                $collapsed = " - <span class='emphatic'>*collapsed</span>";
            }
        }

        if (Block::BLOCK_TYPE_ELSE == $this->obj->type) {
            $title = 'Insert inside';
            $insertAction = ContextMenu::INSERT_INSIDE;
        }

        $incomplete = $this->isComplete() ? '': " - <span class='emphatic'>*please select a snippet</span>";

        return (
            "<div class='row-selected' id='{$this->obj->id}_$insertAction'>"
            . $prefix
            . "<span style='color: #{$this->obj->color}' title='$title'>"
            . $this->obj->top1
            . $this->obj->top2
            . '&nbsp;&nbsp;'
            . $this->obj->type . ($this->obj->subtype ? " {$this->obj->subtype}: " : '')
            . ($this->obj->container ? '' : ': ' . $this->obj->title) . $collapsed . $incomplete
            . "</span></div>"
        );
    }

    /**
     * Build and return the string representing the first line of a container
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    public function getContainerLine($depth, $colors)
    {
        $prefix = $this->getPrefix($depth, $colors);

        return (
            "<div class='row-selected' id='{$this->obj->id}_" . ContextMenu::INSERT_INSIDE . "'>"
            . $prefix
            . "<span style='color: #{$this->obj->color}' title='Insert inside'>"
            . $this->obj->side
            . '-&nbsp;&nbsp;'
            . $this->obj->title
            . "</span></div>"
        );
    }

    /**
     * Build and return the string representing the closing line
     *
     * @param $depth
     * @param $colors
     * @param $blockType - overrides the instance block type
     * @param $title - overrides the instance title
     * @return string
     */
    public function getClosingLine($depth, $colors, $blockType = null, $title = null)
    {
        $prefix = $this->getPrefix($depth, $colors);

        if (null === $blockType) {
            $blockType = $this->obj->type;
        }

        if (null === $title) {
            $title = $this->obj->title;
        }

        return (
            "<div class='row-selected' id='{$this->obj->id}_" . ContextMenu::INSERT_AFTER . "'>"
            . $prefix
            . "<span style='color: #{$this->obj->color}' title='Insert after'>"
            . $this->obj->bottom1
            . $this->obj->bottom2
            . '&nbsp;&nbsp;'
            . $blockType
            . ': End '
            . $title
            . "</span></div>"
        );
    }

    /**
     * Checks that the instance is not missing anything vital
     *
     * @return bool
     */
    public function isComplete()
    {
        return true;
    }

    /**
     * Checks what is missing and returns an appropriate ContextMenu option
     * for each additional action that is needed
     *
     * @return bool
     */
    public function getMissingOptions()
    {
        return [];
    }
}