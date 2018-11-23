<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');


            $table->integer('user_id')->unsigned();
            $table->integer('employee_id')->unsigned();

            $table->text('description')->nullable();

            $table->decimal('amount', 10, 3);
            $table->decimal('amount_topay', 10, 3);
//            $table->decimal('balance', 10, 3);

            $table->text('term');
            $table->enum('frequency', ['Day', 'Week','Month', 'Year'])->default('Day');

            $table->date('start_date');
            $table->date('released_date');

            $table->enum('status', ['pending','approved','on going','paid'])->default('pending');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
