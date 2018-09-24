<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company')->nullable(false);
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('department')->nullable(false);
            $table->string('company_type', 50)->nullable(false)->index();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable(false);
            $table->string('password')->nullable();
            $table->boolean('is_verified_by_admin')->default(false);
            $table->boolean('is_email_verified')->default(false);
            $table->boolean('is_inactive')->default(false);
            $table->string('token')->nullable()->unique();
            $table->dateTime('token_expiry')->nullable();
            $table->integer('address_id')->index()->nullable();
            $table->string('role', 20)->nullable(false);
            $table->jsonb('permissions')->nullable();
            $table->timestamp('recent_login_time')->nullable();
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
        Schema::dropIfExists('app_users');
    }
}
