<?php

use Illuminate\Database\Seeder;
use App\Model\Block;

class BlockTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('blocks')->delete();

        $block = new Block();
        $block->type = Block::BLOCK_TYPE_ACTION;
        $block->label = 'Action';
        $block->top1 = '-';
        $block->top2 = '-';
        $block->side = '▎';
        $block->bottom1 = '-';
        $block->bottom2 = '-';
        $block->color = 'c2c200';
        $block->container = false;
        $block->contextMenuMap = 1277;
        $block->save();

        $block = new Block();
        $block->type = Block::BLOCK_TYPE_COMMENT;
        $block->label = 'Comment';
        $block->top1 = '-';
        $block->top2 = '-';
        $block->side = '▎';
        $block->bottom1 = "-";
        $block->bottom2 = '-';
        $block->color = 'a2a2a2';
        $block->container = false;
        $block->contextMenuMap = 1277;
        $block->save();

        $block = new Block();
        $block->type = Block::BLOCK_TYPE_CONDITION;
        $block->label = 'Case';
        $block->top1 = '▞';
        $block->top2 = '▝';
        $block->side = '▎';
        $block->bottom1 = "▚";
        $block->bottom2 = '▗';
        $block->color = '00c2c2';
        $block->container = true;
        $block->contextMenuMap = 1947;
        $block->save();

        $block = new Block();
        $block->type = Block::BLOCK_TYPE_ELSE;
        $block->label = 'Otherwise';
        $block->top1 = '▎';
        $block->top2 = '-';
        $block->side = '▎';
        $block->bottom1 = "▚";
        $block->bottom2 = '▗';
        $block->color = '00c2c2';
        $block->container = true;
        $block->contextMenuMap = 11947;
        $block->save();

        $block = new Block();
        $block->type = Block::BLOCK_TYPE_ITERATION;
        $block->label = 'Repeat while';
        $block->top1 = '▞';
        $block->top2 = '▝';
        $block->side = '▎';
        $block->bottom1 = "▚";
        $block->bottom2 = '▗';
        $block->color = '0000c2';
        $block->container = true;
        $block->contextMenuMap = 1947;
        $block->save();

        $block = new Block();
        $block->type = Block::BLOCK_TYPE_SEQUENCE;
        $block->label = 'Sequence';
        $block->top1 = '▞';
        $block->top2 = '▝';
        $block->side = '▎';
        $block->bottom1 = "▚";
        $block->bottom2 = '▗';
        $block->color = 'c200c2';
        $block->container = true;
        $block->contextMenuMap = 1947;
        $block->save();
    }
}
