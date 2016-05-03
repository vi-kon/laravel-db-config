<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateConfigTable
 *
 * @author Kovács Vince <vincekovacs@hotmail.com>
 */
class CreateConfigTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('config', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('namespace')
                  ->default('');
            $table->string('key');
            $table->string('value');
            $table->enum('type', ['int', 'bool', 'string']);
            $table->unsignedInteger('modified_by_user_id');
            $table->timestamp('modified_at');

            $table->unique(['namespace', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('config');
    }
}
