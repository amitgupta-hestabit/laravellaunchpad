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
            $table->string('email');
            $table->string('password');
            $table->enum('user_type', ['ADMIN','STUDENT', 'TEACHER']);
            $table->string('address');
            $table->string('profile_picture');
            $table->string('current_school')->nullable();
            $table->string('previous_school')->nullable();
            $table->integer('experience')->nullable();
            $table->text('expertise_in_subjects')->nullable();
            $table->json('parent_details')->nullable();
            $table->integer('assigned_teacher')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->integer('is_approved')->default(0);
            $table->timestamp('approved_date_time')->nullable();
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
