<?php

use Illuminate\Database\Seeder;
use App\Model\Block;
use App\Model\Subtype;

class SubtypeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('subtypes')->delete();

        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_IMAGE;
        $subtype->label = 'Image';
        $subtype->save();

        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_INSTRUCTION;
        $subtype->label = 'Instruction';
        $subtype->save();

        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_QUESTION;
        $subtype->label = 'Question';
        $subtype->save();

        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_TEXT;
        $subtype->label = 'Text';
        $subtype->save();

        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_VIDEO;
        $subtype->label = 'Video';
        $subtype->save();
    }
}
