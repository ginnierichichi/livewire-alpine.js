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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('username')->nullable();
            $table->text('about')->nullable();
            $table->date('birthday')->nullable();
            $table->string('photo')->nullable();
//            $table->foreignId('current_team_id')->nullable();
//            $table->text('profile_photo_path')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'name' => 'Ginnie Richichi',
            'email' => 'ginnie@scolmore.com',
            'password' => Hash::make('password'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
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
