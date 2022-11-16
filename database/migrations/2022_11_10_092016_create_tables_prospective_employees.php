<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesProspectiveEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospective_employees', function (Blueprint $table) {
            $table->id();
            //* CODE
            $table->string('code')->unique();
            $table->string('link')->unique();

            //* Data Pribadi
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('house_phone_number')->nullable();
            $table->string('email')->nullable();
            $table->integer('birth_order')->nullable();
            $table->integer('from_order')->nullable();
            $table->string('formal_education_1')->nullable();
            $table->string('formal_education_from_1')->nullable();
            $table->string('formal_education_to_1')->nullable();
            $table->string('formal_education_2')->nullable();
            $table->string('formal_education_from_2')->nullable();
            $table->string('formal_education_to_2')->nullable();

            //* Data Keluarga
            $table->string('marital_status')->nullable();
            $table->string('couple_name')->nullable();
            $table->string('couple_occupation')->nullable();
            $table->string('couple_education')->nullable();
            $table->integer('number_of_children')->nullable();
            $table->integer('child_1_age')->nullable();
            $table->integer('child_2_age')->nullable();
            $table->integer('child_3_age')->nullable();
            $table->integer('child_4_age')->nullable();

            //* Data Orang Tua
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_address')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_address')->nullable();

            //* Number Phone
            $table->string('related_name_1')->nullable();
            $table->string('related_number_phone_1')->nullable();
            $table->string('related_name_2')->nullable();
            $table->string('related_number_phone_2')->nullable();

            //* Experience
            $table->string('company_name_1')->nullable();
            $table->string('position_1')->nullable();
            $table->string('length_of_work_1')->nullable();
            $table->string('last_salary_1')->nullable();
            $table->string('reason_stop_1')->nullable();
            $table->string('company_name_2')->nullable();
            $table->string('position_2')->nullable();
            $table->string('length_of_work_2')->nullable();
            $table->string('last_salary_2')->nullable();
            $table->string('reason_stop_2')->nullable();

            //* Skill
            $table->string('language_skill_1')->nullable();
            $table->string('language_skill_2')->nullable();
            $table->string('language_skill_3')->nullable();
            $table->string('computer_skill')->nullable();

            //* dll
            $table->string('placement')->nullable();

            //* Salary
            $table->string('salary_expected')->nullable();

            //* agreement
            // $table->string('agreement')->nullable();
            // $table->date('date_agreement')->nullable();
            //* status
            $table->integer('status')->default(0);


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
        Schema::dropIfExists('prospective_employees');
    }
}
