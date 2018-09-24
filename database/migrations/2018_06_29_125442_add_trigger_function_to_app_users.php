<?php

use Illuminate\Database\Migrations\Migration;

class AddTriggerFunctionToAppUsers extends Migration
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
            'CREATE TRIGGER app_users_update_search_doc',
            'BEFORE INSERT OR UPDATE',
            'ON app_users',
            'FOR EACH ROW',
            "EXECUTE PROCEDURE tsvector_update_trigger(fts_doc, 'pg_catalog.english', first_name, last_name, email)"
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
        DB::statement('DROP TRIGGER IF EXISTS app_users_update_search_doc ON app_users');
    }
}
