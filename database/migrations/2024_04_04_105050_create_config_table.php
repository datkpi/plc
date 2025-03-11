<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_education', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 50)->nullable()->index();
            $table->string('branch')->nullable();
            $table->string('branch_value')->nullable(); //Hệ đào tạo
            $table->string('level')->nullable();
            $table->string('level_value')->nullable(); //Trình độ học vấn
            $table->string('university_id')->nullable(); //Trường đào tạo
            $table->string('majors')->nullable(); //Ngành đào tạo
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->char('created_by', 50)->nullable();
            $table->char('updated_by', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_education');
    }
};
