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
        Schema::create('patients', function (Blueprint $table) {
            $table->mediumIncrements('id',11);
            $table->string('name',100)->nullable();
            $table->string('phone',100)->nullable();
            $table->text('address')->nullable();
            $table->foreignId('status_id')->nullable();
            $table->date('in_date_at')->nullable();
            $table->date('out_date_at')->nullable();
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
        Schema::dropIfExists('patients');
    }
};
