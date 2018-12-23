<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstancesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('instances', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->decimal('seq', 5, 2);
			$table->text('title')->nullable();
			$table->unsignedInteger('trip_id')->nullable();
			$table->unsignedInteger('block_id')->nullable();
			$table->unsignedInteger('subtype_id')->nullable();
			$table->unsignedInteger('question_id')->nullable();
			$table->unsignedInteger('condition_id')->nullable();
			$table->unsignedInteger('parent_id')->nullable();
			$table->unsignedInteger('snippetTrip_id')->nullable();
			$table->boolean('collapsed');
			$table->boolean('protected');
			$table->boolean('controller');
			$table->boolean('template');
			$table->foreign('trip_id')->references('id')->on('trips')->onDelete('set null');
			$table->foreign('block_id')->references('id')->on('blocks')->onDelete('set null');
			$table->foreign('subtype_id')->references('id')->on('subtypes')->onDelete('set null');
			$table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
			$table->foreign('condition_id')->references('id')->on('conditions')->onDelete('set null');
			$table->foreign('parent_id')->references('id')->on('instances')->onDelete('set null');
			$table->foreign('snippetTrip_id')->references('id')->on('trips')->onDelete('set null');
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
		Schema::drop('instances');
	}
}
