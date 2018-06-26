<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('language')->default('de');
            $table->multiLineString('description')->nullable();
            $table->multiLineString('head')->nullable();
            $table->multiLineString('body');
            $table->multiLineString('footer')->nullable();
            $table->boolean('locked')->default(false);
            $table->string('slot')->nullable();
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
        Schema::drop('templates');
    }
}
