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
        Schema::create('main_salary_employee_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_salary_employee_id')->references('id')->on('main_salary_employees')->onUpdate('cascade');
            $table->foreignId('finance_months_periods_id')->references('id')->on('finance_months_periods')->onUpdate('cascade');
            $table->bigInteger('employee_code');
            $table->decimal('day_price', 10, 2)->comment('الراتب اليومي للموظف');
            $table->foreignId('discounts_type')->comment('نوع الخصم')->references('id')->on('discount_sal_types')->onUpdate('cascade');
            $table->decimal('total', 10, 2)->comment('اجمالي الخصم');
            $table->integer('is_archived')->default(0)->comment('هل تم الارشفة');
            $table->foreignId('archived_by')->nullable()->comment('من الذي ارشفه')->references('id')->on('admins')->onUpdate('cascade');
            $table->dateTime('archived_at')->nullable()->comment('تاريخ الارشفة');
            $table->integer('is_auto')->default(0)->comment('هل الخصم يتم تلقائي من النظام ام بشكل يدوي');
            $table->string('notes', 100)->nullable();
            $table->integer('com_code');
            $table->integer('active')->default(1);
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employee_discounts');
    }
};
