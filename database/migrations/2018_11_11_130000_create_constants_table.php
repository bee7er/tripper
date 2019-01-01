<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstantsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('clists', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('label');
			$table->timestamps();
            $table->softDeletes();
		});

		Schema::create('constants', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('label');
			$table->string('value');
			$table->timestamps();
            $table->softDeletes();
		});

		Schema::create('clist_constants', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->unsignedInteger('clist_id')->nullable();
			$table->unsignedInteger('constant_id')->nullable();
			$table->foreign('clist_id')->references('id')->on('clists')->onDelete('set null');
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
		Schema::drop('clists');
		Schema::drop('constants');
		Schema::drop('clist_constants');
	}
}
