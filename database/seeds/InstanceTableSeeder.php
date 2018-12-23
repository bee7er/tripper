<?php

use Illuminate\Database\Seeder;

use App\Model\Block;
use App\Model\Condition;
use App\Model\Constant;
use App\Model\Context;
use App\Model\Instance;
use App\Model\Operator;
use App\Model\Question;
use App\Model\Status;
use App\Model\Subtype;
use App\Trip;

class InstanceTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('instances')->delete();

        $trip = Trip::where('title', 'First trip')->firstOrFail();

        $actBlock = Block::where('type', Block::BLOCK_TYPE_ACTION)->firstOrFail();
        $cmtBlock = Block::where('type', Block::BLOCK_TYPE_COMMENT)->firstOrFail();
        $cndBlock = Block::where('type', Block::BLOCK_TYPE_CONDITION)->firstOrFail();
        $elsBlock = Block::where('type', Block::BLOCK_TYPE_ELSE)->firstOrFail();
        $itrBlock = Block::where('type', Block::BLOCK_TYPE_ITERATION)->firstOrFail();
        $seqBlock = Block::where('type', Block::BLOCK_TYPE_SEQUENCE)->firstOrFail();

        $imgSubtype = Subtype::where('subtype', Subtype::SUBTYPE_IMAGE)->firstOrFail();
        $insSubtype = Subtype::where('subtype', Subtype::SUBTYPE_INSTRUCTION)->firstOrFail();
        $qusSubtype = Subtype::where('subtype', Subtype::SUBTYPE_QUESTION)->firstOrFail();
        $txtSubtype = Subtype::where('subtype', Subtype::SUBTYPE_TEXT)->firstOrFail();

        $loanAmountQuestion = Question::where('label', 'Determine loan amount')->firstOrFail();
        $repaymentTypeQuestion = Question::where('label', 'Determine repayment type')->firstOrFail();

        // Template objects.  One of each type, used when creating a new instance of that type.
        $templateInstance = new Instance();
        $templateInstance->block_id = $actBlock->id;
        $templateInstance->title = 'New action';
        $templateInstance->protected = true;
        $templateInstance->template = true;
        $templateInstance->save();

        $templateInstance = new Instance();
        $templateInstance->block_id = $cmtBlock->id;
        $templateInstance->title = 'New comment';
        $templateInstance->protected = true;
        $templateInstance->template = true;
        $templateInstance->save();

        $templateInstance = new Instance();
        $templateInstance->block_id = $cndBlock->id;
        $templateInstance->title = 'New condition';
        $templateInstance->protected = true;
        $templateInstance->template = true;
        $templateInstance->save();

        $templateInstance = new Instance();
        $templateInstance->block_id = $elsBlock->id;
        $templateInstance->title = 'New else';
        $templateInstance->protected = true;
        $templateInstance->template = true;
        $templateInstance->save();

        $templateInstance = new Instance();
        $templateInstance->block_id = $itrBlock->id;
        $templateInstance->title = 'New iteration';
        $templateInstance->protected = true;
        $templateInstance->template = true;
        $templateInstance->save();
        
        $templateInstance = new Instance();
        $templateInstance->block_id = $seqBlock->id;
        $templateInstance->title = 'New sequence';
        $templateInstance->protected = true;
        $templateInstance->template = true;
        $templateInstance->save();

        // Default action diagram for testing
        $ctrlSeqInstance = new Instance();
        $ctrlSeqInstance->block_id = $seqBlock->id;
        $ctrlSeqInstance->trip_id = $trip->id;
        $ctrlSeqInstance->parent_id = null;
        $ctrlSeqInstance->seq = 0;
        $ctrlSeqInstance->title = 'Controller';
        $ctrlSeqInstance->protected = true;
        $ctrlSeqInstance->controller = true;
        $ctrlSeqInstance->save();

        $seqInstance = new Instance();
        $seqInstance->block_id = $seqBlock->id;
        $seqInstance->trip_id = $trip->id;
        $seqInstance->parent_id = $ctrlSeqInstance->id;
        $seqInstance->seq = 1;
        $seqInstance->title = 'Mortgage Enquiry';
        $ctrlSeqInstance->protected = true;
        $seqInstance->save();

        $cmtInstance = new Instance();
        $cmtInstance->block_id = $cmtBlock->id;
        $cmtInstance->trip_id = $trip->id;
        $cmtInstance->parent_id = $seqInstance->id;
        $cmtInstance->seq = 1.2;
        $cmtInstance->title = 'A conversation between an adviser and a mortgage applicant';
        $cmtInstance->save();

        // Question to determine loan amount
        $actInstance = new Instance();
        $actInstance->block_id = $actBlock->id;
        $actInstance->trip_id = $trip->id;
        $actInstance->parent_id = $seqInstance->id;
        $actInstance->seq = 1;
        $actInstance->subtype_id = $qusSubtype->id;
        $actInstance->question_id = $loanAmountQuestion->id;
        $actInstance->title = 'Ask for loan amount';
        $actInstance->save();

        $response = new \App\Model\Response();
        $response->instance_id = $actInstance->id;
        $response->response = '110000';

        $gtOperator = Operator::where('operator', Operator::OPERATOR_GT)->firstOrFail();
        $conContext = Context::where('context', Context::CONTEXT_CONSTANT)->firstOrFail();
        $loanLimitConstant = Constant::where('label', 'Loan limit')->firstOrFail();

        $cmpCondition = new Condition();
        $cmpCondition->operator_id = $gtOperator->id;
        $cmpCondition->context_id = $conContext->id;
        $cmpCondition->status_id = null;
        $cmpCondition->constant_id = $loanLimitConstant->id;
        $cmpCondition->save();

        // TODO Comparison condition
        $cmpInstance = new Instance();
        $cmpInstance->block_id = $cndBlock->id;
        $cmpInstance->trip_id = $trip->id;
        $cmpInstance->parent_id = $seqInstance->id;
        $cmpInstance->condition_id = $cmpCondition->id;
        $cmpInstance->seq = 2;
        $cmpInstance->title = 'Is loan > 100,000';
        $cmpInstance->save();

        $actInstance = new Instance();
        $actInstance->block_id = $actBlock->id;
        $actInstance->trip_id = $trip->id;
        $actInstance->parent_id = $cmpInstance->id;
        $actInstance->seq = 1;
        $actInstance->subtype_id = $txtSubtype->id;
        $actInstance->title = 'Explain that an Equifax search will be conducted';
        $actInstance->save();

        // Question to determine repayment type
        $actInstance = new Instance();
        $actInstance->block_id = $actBlock->id;
        $actInstance->trip_id = $trip->id;
        $actInstance->parent_id = $cmpInstance->id;
        $actInstance->seq = 2;
        $actInstance->subtype_id = $qusSubtype->id;
        $actInstance->question_id = $repaymentTypeQuestion->id;
        $actInstance->title = 'Ask for repayment type';
        $actInstance->save();

        $response = new \App\Model\Response();
        $response->instance_id = $actInstance->id;
        $response->response = 'repayment';

        $eqOperator = Operator::where('operator', Operator::OPERATOR_EQ)->firstOrFail();
        $stsContext = Context::where('context', Context::CONTEXT_STATUS)->firstOrFail();
        $repaymentTypeStatus = Status::where('label', 'Repayment')->firstOrFail();

        $cmpCondition = new Condition();
        $cmpCondition->operator_id = $eqOperator->id;
        $cmpCondition->context_id = $stsContext->id;
        $cmpCondition->status_id = $repaymentTypeStatus->id;
        $cmpCondition->constant_id = null;
        $cmpCondition->save();

        // TODO Status condition
        $stsInstance = new Instance();
        $stsInstance->block_id = $cndBlock->id;
        $stsInstance->trip_id = $trip->id;
        $stsInstance->parent_id = $cmpInstance->id;
        $stsInstance->seq = 2;
        $stsInstance->title = 'Is repayment type REPAYMENT';
        $stsInstance->save();

        $actInstance = new Instance();
        $actInstance->block_id = $actBlock->id;
        $actInstance->trip_id = $trip->id;
        $actInstance->parent_id = $stsInstance->id;
        $actInstance->seq = 1;
        $actInstance->subtype_id = $txtSubtype->id;
        $actInstance->title = 'A repayment mortgage means that you repay both interest and capital each month.';
        $actInstance->save();

        $elsInstance = new Instance();
        $elsInstance->block_id = $elsBlock->id;
        $elsInstance->trip_id = $trip->id;
        $elsInstance->parent_id = $cmpInstance->id;
        $elsInstance->seq = 2;
        $elsInstance->title = 'Else';
        $elsInstance->save();

        $actInstance = new Instance();
        $actInstance->block_id = $actBlock->id;
        $actInstance->trip_id = $trip->id;
        $actInstance->parent_id = $elsInstance->id;
        $actInstance->seq = 1;
        $actInstance->subtype_id = $txtSubtype->id;
        $actInstance->title= 'In interest only mortgages, interest is paid each month and the balance is repaid at the end';
        $actInstance->save();

        $itrInstance = new Instance();
        $itrInstance->block_id = $itrBlock->id;
        $itrInstance->trip_id = $trip->id;
        $itrInstance->parent_id = $cmpInstance->id;
        $itrInstance->seq = 3;
        $itrInstance->title = 'Enter the security properties';
        $itrInstance->save();

        $actInstance = new Instance();
        $actInstance->block_id = $actBlock->id;
        $actInstance->trip_id = $trip->id;
        $actInstance->parent_id = $itrInstance->id;
        $actInstance->seq = 1;
        $actInstance->subtype_id = $txtSubtype->id;
        $actInstance->title= 'Enter security property details';
        $actInstance->save();

        $actInstance = new Instance();
        $actInstance->block_id = $actBlock->id;
        $actInstance->trip_id = $trip->id;
        $actInstance->parent_id = $ctrlSeqInstance->id;
        $actInstance->seq = 2;
        $actInstance->subtype_id = $insSubtype->id;
        $actInstance->title= 'Completion message';
        $actInstance->save();

    }
}
