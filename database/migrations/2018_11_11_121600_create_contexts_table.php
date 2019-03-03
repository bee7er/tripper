<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contexts', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('seq')->unsigned();
			$table->char('context', 3);
			$table->string('tooltip');
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
		Schema::drop('contexts');
	}
}
