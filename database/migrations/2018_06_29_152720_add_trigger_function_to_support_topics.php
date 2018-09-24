<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTriggerFunctionToSupportTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the function that will be bound to update search index document
        $trigger_creation_query = [
            'CREATE TRIGGER support_topics_update_search_doc',
            'BEFORE INSERT OR UPDATE',
            'ON support_topics',
            'FOR EACH ROW',
            "EXECUTE PROCEDURE tsvector_update_trigger(fts_doc, 'pg_catalog.english', topic, content)"
        ];

        DB::statement(implode("\n", $trigger_creation_query));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS support_topics_update_search_doc ON support_topics');
    }
}
