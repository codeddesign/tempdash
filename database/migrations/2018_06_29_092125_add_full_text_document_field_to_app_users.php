<?php

use Illuminate\Database\Migrations\Migration;

class AddFullTextDocumentFieldToAppUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement('ALTER TABLE "app_users" ADD "fts_doc" tsvector');
       DB::statement('CREATE INDEX "idx_app_users_fts_doc" ON "app_users" USING gin("fts_doc")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP INDEX "idx_app_users_fts_doc"');
        DB::statement('ALTER TABLE "app_users" DROP COLUMN "fts_doc"');
    }
}
