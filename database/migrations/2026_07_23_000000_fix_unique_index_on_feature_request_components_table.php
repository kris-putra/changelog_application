<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Disable transaction wrapping so SET FOREIGN_KEY_CHECKS=0 works.
     */
    public function withoutApplyingTransactions(): bool
    {
        return true;
    }

    public function up(): void
    {
        // Step 1: Drop the FK constraint that depends on the old index
        DB::statement('ALTER TABLE feature_request_components DROP FOREIGN KEY feature_request_components_feature_request_id_foreign');

        // Step 2: Drop the wrong single-column unique index
        DB::statement('ALTER TABLE feature_request_components DROP INDEX feature_request_components_feature_request_id_component_unique');

        // Step 3: Add the correct composite unique index (short name to stay under MySQL's 64-char limit)
        DB::statement('ALTER TABLE feature_request_components ADD UNIQUE INDEX frc_frcid_tcid_unique (feature_request_id, technical_component_id)');

        // Step 4: Re-add the FK constraint
        DB::statement('ALTER TABLE feature_request_components ADD CONSTRAINT feature_request_components_feature_request_id_foreign FOREIGN KEY (feature_request_id) REFERENCES feature_requests (id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE feature_request_components DROP FOREIGN KEY feature_request_components_feature_request_id_foreign');
        DB::statement('ALTER TABLE feature_request_components DROP INDEX frc_frcid_tcid_unique');
        DB::statement('ALTER TABLE feature_request_components ADD UNIQUE INDEX feature_request_components_feature_request_id_component_unique (feature_request_id)');
        DB::statement('ALTER TABLE feature_request_components ADD CONSTRAINT feature_request_components_feature_request_id_foreign FOREIGN KEY (feature_request_id) REFERENCES feature_requests (id) ON DELETE CASCADE');
    }
};