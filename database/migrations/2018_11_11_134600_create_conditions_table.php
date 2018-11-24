<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conditions', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->unsignedInteger('operator_id')->nullable();
			$table->unsignedInteger('context_id')->nullable();
			$table->unsignedInteger('status_id')->nullable();
			$table->unsignedInteger('constant_id')->nullable();
			$table->foreign('operator_id')->references('id')->on('operators')->onDelete('set null');
			$table->foreign('context_id')->references('id')->on('contexts')->onDelete('set null');
			$table->foreign('status_id')->references('id')->on('statuses')->onDelete('set null');
			$table->foreign('constant_id')->references('id')->on('constants')->onDelete('set null');
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
		Schema::drop('conditions');
	}
}
