<?php

use Illuminate\Database\Seeder;
use App\Model\Question;

class QuestionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('questions')->delete();

        $question = new Question();
        $question->type = Question::QUESTION_TYPE_TEXT;
        $question->label = 'Loan amount';
        $question->question = 'How much do you want to borrow?';
        $question->required = true;
        $question->save();

        $question = new Question();
        $question->type = Question::QUESTION_TYPE_TEXT;
        $question->label = 'Repayment type';
        $question->question = 'Do you want a repayment or interest only mortgage?';
        $question->required = true;
        $question->save();
    }
}
