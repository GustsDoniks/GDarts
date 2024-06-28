<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('game_type');
            $table->integer('legs')->nullable();
            $table->string('set_type');
            $table->integer('legs_in_set')->nullable();
            $table->integer('sets')->nullable();
            $table->integer('target_score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('game_type');
            $table->dropColumn('legs');
            $table->dropColumn('set_type');
            $table->dropColumn('legs_in_set');
            $table->dropColumn('sets');
            $table->dropColumn('target_score');
        });
    }
}
