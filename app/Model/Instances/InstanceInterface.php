<?php

namespace App\Model\Instances;

interface InstanceInterface
{
    /**
     * Returns the action diagram opening line for this instance type
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    public function getOpeningLine($depth, $colors);

    /**
     * Returns the action diagram container line for this instance type
     *
     * @param $depth
     * @param $colors
     * @return string
     */
    public function getContainerLine($depth, $colors);

    /**
     * Returns the action diagram closing line for this instance type
     *
     * @param $depth
     * @param $colors
     * @param $blockType
     * @param $title
     * @return string
     */
    public function getClosingLine($depth, $colors, $blockType = null, $title = null);
}
