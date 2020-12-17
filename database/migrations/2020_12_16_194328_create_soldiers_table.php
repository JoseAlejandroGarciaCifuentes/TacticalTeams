<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soldiers', function (Blueprint $table) {
            $table->id();
            $table->string('name', '100');
            $table->string('surname', '100');
            $table->date('date_of_birth');
            $table->date('incorporation_date');
            $table->integer('badge_number')->unique();
            $table->enum('state', ['Active', 'Retired', 'Sick_leave']);
            $table->enum('rank', ['Trooper', 'Sergeant', 'Captain']);
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
        Schema::dropIfExists('soldiers');
    }
}
