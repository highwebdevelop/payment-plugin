<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
           $table->bigInteger('user_id')->nullable()->change();
           $table->foreignId('payment_method_id')
               ->nullable()
               ->references('id')
               ->on('payment_methods')
               ->cascadeOnDelete()
               ->cascadeOnUpdate();
           $table->nullableMorphs('payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
