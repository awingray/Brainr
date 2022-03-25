<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImprovementsRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('profile_profile_relation', 'related');

        Schema::table('profile_relations', function (Blueprint $table) {
            $table->boolean('visible')
                  ->index();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('related', 'profile_profile_relation');

        Schema::table('profile_relations', function (Blueprint $table) {
            $table->dropColumn('visible');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
