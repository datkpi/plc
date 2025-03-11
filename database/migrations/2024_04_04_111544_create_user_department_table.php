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
        Schema::create('user_work', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 50)->nullable()->index();
            $table->char('department_id', 50)->nullable();
            $table->char('position_id', 50)->nullable();
            $table->string('dispatch_number')->nullable(); //Số công văn đi kèm
            $table->text('note')->nullable();
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
        Schema::dropIfExists('user_department');
    }
};
