<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
			$table->string('firstname');
			$table->string('lastname');
			$table->string('nickname');
			$table->integer('gender_id')->unsigned()->nullable();
			$table->integer('country_id')->unsigned();
			$table->string('other_country');
			$table->integer('confession_id')->unsigned();
			$table->date('birthday');
			$table->date('joined_at');
			$table->boolean('keepdata');
			$table->boolean('sendnewspaper');
			$table->string('address');
			$table->string('further_address');
			$table->string('zip');
			$table->string('city');
			$table->string('region_id');
			$table->string('phone')->nullable();
			$table->string('mobile')->nullable();
			$table->string('business_phone')->nullable();
			$table->string('fax')->nullable();
			$table->string('email')->nullable();
			$table->string('email_parents')->nullable();
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
        Schema::dropIfExists('members');
    }
}
