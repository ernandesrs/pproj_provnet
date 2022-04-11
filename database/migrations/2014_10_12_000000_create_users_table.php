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
            $table->string('first_name')->max(30);
            $table->string('last_name')->max(50);
            $table->string('username')->max(20)->unique();
            $table->string('email')->unique();
            $table->string('photo')->nullable();
            $table->string('password');
            $table->integer("level")->default(1);
            $table->string("status")->max(8)->default("active");
            $table->string("gender")->max(8);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
