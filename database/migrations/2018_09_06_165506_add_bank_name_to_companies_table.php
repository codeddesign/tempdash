<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankNameToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('bank_routing_number')->nullable();
            $table->string('bank_account_number_hash')->nullable();
            $table->string('payout_method')->nullable();
            $table->integer('billing_address_id')->index()->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_routing_number');
            $table->dropColumn('bank_account_number_hash');
            $table->dropColumn('payout_method');
            $table->dropColumn('billing_address_id');
        });
    }
}
