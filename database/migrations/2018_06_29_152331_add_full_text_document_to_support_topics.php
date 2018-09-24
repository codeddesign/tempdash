<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFullTextDocumentToSupportTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE "support_topics" ADD "fts_doc" tsvector');
        DB::statement('CREATE INDEX "idx_support_topics_fts_doc" ON "support_topics" USING gin("fts_doc")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP INDEX "idx_support_topics_fts_doc"');
        DB::statement('ALTER TABLE "support_topics" DROP COLUMN "fts_doc"');
    }
}
