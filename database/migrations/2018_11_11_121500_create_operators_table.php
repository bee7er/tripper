<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('operators', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->integer('seq')->unsigned();
			$table->char('operator', 2);
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
		Schema::drop('operators');
	}
}
