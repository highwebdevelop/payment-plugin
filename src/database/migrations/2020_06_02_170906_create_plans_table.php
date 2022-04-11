<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('planId');
            $table->string('name');
            $table->enum('type',
                [
                    \Payment\System\App\Services\PlanService::TYPE_MONTHLY,
                    \Payment\System\App\Services\PlanService::TYPE_ANNUAL,
                    \Payment\System\App\Services\PlanService::TYPE_WEEKLY
                ]
            );
            $table->float('price');
            $table->string('currency');
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
        Schema::dropIfExists('plans');
    }
}
