<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blocks', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->enum('type', array('act', 'cnd', 'els', 'itr', 'seq'))->unique();
			$table->string('label');
			$table->char('top1', 1);
			$table->char('top2', 1);
			$table->char('side', 1);
			$table->char('bottom1', 1);
			$table->char('bottom2', 1);
			$table->char('color', 6);
			$table->boolean('container');
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
		Schema::drop('blocks');
	}
}
