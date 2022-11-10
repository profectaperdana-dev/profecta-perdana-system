<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->integer('user_id');
            $table->string('name');
            $table->string('phone');
            $table->string('emergency_phone');
            $table->boolean('gender');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('email');
            $table->string('province');
            $table->string('district');
            $table->string('sub_district');
            $table->text('address');
            $table->enum('last_edu_first', ['SHS', 'Associate', 'Bachelor', 'Master', 'Doctoral']);
            $table->string('school_name_first');
            $table->date('from_first');
            $table->date('to_first');
            $table->enum('last_edu_sec', ['SHS', 'Associate', 'Bachelor', 'Master', 'Doctoral']);
            $table->string('school_name_sec');
            $table->date('from_sec');
            $table->date('to_sec');
            $table->string('mom_name');
            $table->string('father_name');
            $table->string('mom_phone');
            $table->string('father_phone');
            $table->integer('salary');
            $table->date('work_date');
            $table->string('photo');
            $table->integer('created_by');
            $table->softDeletes();
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
        Schema::dropIfExists('employees');
    }
}
