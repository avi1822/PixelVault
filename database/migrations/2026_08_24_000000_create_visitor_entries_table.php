<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_entries', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name');
            $table->string('phone_number')->nullable();
            $table->integer('hours_played');
            $table->string('game_played');
            $table->string('food_item')->default('None');
            $table->string('zone_location'); // 'Upper Floor (PS5 Lounge)' or 'Lower Floor (PC Arena)'
            $table->date('entry_date');
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
        Schema::dropIfExists('visitor_entries');
    }
};
