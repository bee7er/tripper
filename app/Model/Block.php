<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use SoftDeletes;

    const BLOCK_TYPE_ACTION = 'act';
    const BLOCK_TYPE_COMMENT = 'cmt';
    const BLOCK_TYPE_CONDITION = 'cnd';
    const BLOCK_TYPE_ELSE = 'els';
    const BLOCK_TYPE_ITERATION = 'itr';
    const BLOCK_TYPE_SEQUENCE = 'seq';

    const CONTEXT_MENU_MAP_ACTION = '';

    protected $dates = ['deleted_at'];

    protected $guarded  = array('id');

    /**
     * Get the block
     *
     * @return Block
     */
    public static function getBlock($blockId)
    {
        $block = Block::select()
            ->where("id", $blockId)
            ->get();

        return $block[0];
    }
}
