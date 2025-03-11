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
        Schema::table('user', function (Blueprint $table) {
            $table->char('bank_id', 50);
            $table->char('bank_code', 50)->nullable();;
            $table->string('bank_address')->nullable();;
            $table->string('bank_name')->nullable();;
            $table->char('code', 50)->nullable()->index();
            $table->string('status')->nullable(); //Trạng thái

            //CCCD
            $table->date('cccd_date')->nullable();
            $table->date('cccd_issued')->nullable(); //Ngày cấp cccd
            $table->char('cccd_at', 50)->nullable(); //Nơi cấp
            $table->char('domicile', 50)->nullable(); //Nguyên quán
            $table->char('birthplace', 50)->nullable(); //Nơi sinhbha

            //Địa chỉ thường trú
            $table->char('address_province', 50)->nullable();
            $table->char('address_district', 50)->nullable();
            $table->char('address_ward', 50)->nullable();

            //Hộ khẩu
            $table->string('household')->nullable();
            $table->char('household_province', 50)->nullable();
            $table->char('household_district', 50)->nullable();
            $table->char('household_ward', 50)->nullable();
            $table->char('updated_by', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            //
        });
    }
};
