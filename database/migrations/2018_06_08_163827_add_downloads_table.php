<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('path');
            $table->string('name')->nullable();
            $table->integer('size');
            $table->date('expires')->nullable();
            $table->string('password')->nullable();
            $table->integer('download_count')->default(0);
            $table->unsignedInteger('template_id');
            $table->foreign('template_id')->references('id')->on('templates');
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
        Schema::drop('downloads');
    }
}
