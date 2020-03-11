<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('icon')->nullable();
            $table->string('avatar')->nullable();
            $table->string('twitter_id')->unique()->nullable();
            $table->string('twitter_name')->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('facebook_name')->nullable();
            $table->string('google_id')->unique()->nullable();
            $table->string('google_name')->nullable();
            $table->string('github_id')->unique()->nullable();
            $table->string('github_name')->nullable();

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
        Schema::dropIfExists('users');
    }
}
