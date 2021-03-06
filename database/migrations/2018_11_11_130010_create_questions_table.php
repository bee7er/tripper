<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// NB The label is how the response is identified in the action diagram
		// NB The type is the response type, cur=currency, lst=list option selection
		// NB The clist_id is the corresponding list for the lst type question
		Schema::create('questions', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->enum('type', array('cur','dte','dtm','lst','nbr','per','txt'));
			$table->unsignedInteger('clist_id')->nullable();
			$table->string('label');
			$table->string('question');
			$table->boolean('required');
			$table->foreign('clist_id')->references('id')->on('clists')->onDelete('set null');
			$table->timestamps();
            $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('questions');
	}
}
