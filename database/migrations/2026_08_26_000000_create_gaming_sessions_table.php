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
        Schema::create('gaming_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('station_id'); // Matches computers.cid
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('expected_end_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('status')->default('ACTIVE'); // ACTIVE, COMPLETED, CANCELLED
            $table->integer('duration_minutes')->nullable();
            $table->integer('base_amount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('station_id')->references('cid')->on('computers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gaming_sessions');
    }
};
