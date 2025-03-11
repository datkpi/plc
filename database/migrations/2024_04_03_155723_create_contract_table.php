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
        Schema::create('user_contract', function (Blueprint $table) {
            $table->id();

            //Thong tin hợp đồng
            $table->char('contract_code', 50)->nullable()->index(); //Mã hợp đồng
            $table->char('user_id', 50)->nullable()->index();
            $table->char('contract_type', 50)->nullable(); //Loại hợp đồng ()
            $table->char('contract_duration', 50)->nullable(); //Thời hạn hợp đồng (năm, ngày)
            $table->char('department_id', 50)->nullable(); //Phòng ban
            $table->char('position_id', 50)->nullable(); //Vị trí
            $table->date('start_date')->nullable();
            $table->date('expired_date')->nullable(); //Hết hạn HĐ
            $table->string('status')->nullable(); //Trạng thái
            $table->text('note')->nullable();
            $table->char('created_by', 50)->nullable();
            $table->char('updated_by', 50)->nullable();

            //Chấm dứt hợp đồng
            $table->string('decision_number')->nullable(); //Số quyết định
            $table->date('decision_date')->nullable(); //Ngày quyết định
            $table->char('end_type', 50)->nullable(); //Loại chấm dứt
            $table->text('end_reason')->nullable();
            $table->date('end_date')->nullable();
            $table->date('retirement_date')->nullable();
            $table->date('official_retirement_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract');
    }
};
