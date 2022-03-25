<?php

use Illuminate\Database\Migrations\Migration;

class ProfilesFulltextSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE FULLTEXT INDEX `profile_ftindex` ON `profiles`(`name`, `description`, `content`);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `profiles` DROP INDEX `profile_ftindex`;');
    }
}
