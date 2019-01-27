<?php

use Illuminate\Database\Seeder;
use App\Model\Block;
use App\Model\Subtype;

class SubtypeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('subtypes')->delete();

        // Displays a message to the user, warning, error or completion
        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_INSTRUCTION;
        $subtype->label = 'Instruction';
        $subtype->save();

        // Presents a question and receives a response
        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_QUESTION;
        $subtype->label = 'Question';
        $subtype->save();

        // Branches to another action diagram
        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_SNIPPET;
        $subtype->label = 'Snippet';
        $subtype->save();

        // Enables a value to be recorded
        $subtype = new Subtype();
        $subtype->type = Block::BLOCK_TYPE_ACTION;
        $subtype->subtype = Subtype::SUBTYPE_TEXT;
        $subtype->label = 'Text';
        $subtype->save();
    }
}
