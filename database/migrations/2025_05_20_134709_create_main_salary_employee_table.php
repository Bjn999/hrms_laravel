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
        Schema::create('main_salary_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_month_id')->comment('كود الشهر المالي')->references('id')->on('finance_months_periods')->onUpdate('cascade');
            $table->integer('employee_code')->comment('كود الموضف');
            $table->string('emp_name', 300)->comment('اسم الموضف لحظة الراتب');
            $table->integer('is_sensitive_manager_data')->nullable()->default(0)->comment('هل الموظف ادارة عليا اي بيانات حساسة');
            $table->integer('branch_id')->nullable()->comment('فرع الموضف لحظة الراتب');
            $table->integer('emp_departments_id')->nullable()->comment('إدارة الموضف لحظة الراتب');
            $table->integer('emp_job_id')->nullable()->comment('وظيفة الموضف لحظة الراتب');
            $table->integer('functional_status')->nullable()->default(0)->comment('الحالة الوظيفية للموضف لحظة الراتب');
            $table->decimal('additions', 10, 2)->nullable()->default(0)->comment('اجمالي الاضافي لراتب الموضف');
            $table->decimal('motivation', 10, 2)->nullable()->default(0)->comment('اجمالي الحافز للموضف مع العلم ممكن يكون ثابت او متغير');
            $table->decimal('additional_days_counter', 10, 2)->nullable()->default(0)->comment('اجمالي الايام الاضافي لراتب الموضف');
            $table->decimal('additional_days', 10, 2)->nullable()->default(0)->comment('اجمالي قيمة الايام الاضافي لراتب الموضف');
            $table->decimal('absence_days_counter', 10, 2)->nullable()->default(0)->comment('اجمالي ايام الغياب لراتب الموضف');
            $table->decimal('absence_days', 10, 2)->nullable()->default(0)->comment('اجمالي قيمة ايام الغياب لراتب الموضف');
            $table->decimal('monthly_loan', 10, 2)->nullable()->default(0)->comment('اجمالي قيم السلف الشهرية المستقطعة من راتب الموضف');
            $table->decimal('permanent_loan', 10, 2)->nullable()->default(0)->comment('اجمالي قيم السلف المستديمة المستقطعة من راتب الموضف');
            $table->decimal('discount', 10, 2)->nullable()->default(0)->comment('اجمالي قيم الخصومات لراتب الموضف');
            $table->decimal('phones', 10, 2)->nullable()->default(0)->comment('اجمالي قيم خصومات الهاتف لراتب الموضف');
            $table->decimal('socialinsurancecutmonthly', 10, 2)->nullable()->default(0)->comment('اجمالي قيمة خصوم التأمين الاجتماعي للموضف');
            $table->decimal('medicalinsurancecutmonthly', 10, 2)->nullable()->default(0)->comment('اجمالي قيمة خصوم التأمين الطبي للموضف');
            $table->decimal('fixed_allowances', 10, 2)->nullable()->default(0)->comment('قيمة البدلات الثابتة الموظف');
            $table->decimal('changable_allowances', 10, 2)->nullable()->default(0)->comment('قيمة البدلات المتغيرة الموظف');
            $table->decimal('sanctions_days_counter_type1', 10, 2)->nullable()->default(0)->comment('عدد ايام الجزاء للموضف');
            $table->decimal('sanctions_days_type1', 10, 2)->nullable()->default(0)->comment('قيمة ايام الجزاء للموضف');
            $table->decimal('sanctions_days_counter_type2', 10, 2)->nullable()->default(0)->comment('عدد ايام جزاء البصمة للموضف');
            $table->decimal('sanctions_days_type2', 10, 2)->nullable()->default(0)->comment('قيمة جزاء البصمة للموضف');
            $table->decimal('sanctions_days_counter_type3', 10, 2)->nullable()->default(0)->comment('عدد ايام جزاء الغياب للموضف');
            $table->decimal('sanctions_days_type3', 10, 2)->nullable()->default(0)->comment('قيمة جزاء الغياب للموضف');
            $table->decimal('sanctions_days_total_type_1_type2', 10, 2)->nullable()->default(0)->comment('اجمالي ايام الجزاء بدون جزاء البصمة');
            $table->decimal('emp_sal', 10, 2)->nullable()->default(0)->comment('قيمة راتب الموظف');
            $table->decimal('day_price', 10, 2)->default(0)->nullable()->default(0)->comment('قيمة يوم الموظف لهذا الراتب');
            $table->decimal('last_salary_remain_balance', 10, 2)->nullable()->default(0)->comment('قيمة الراتب المرحل من الشهر السابق');
            $table->decimal('last_main_salary_record_id', 10, 2)->nullable()->default(0)->comment('رقم الراتب للشهر السابق');
            $table->decimal('final_the_net', 10, 2)->nullable()->default(0)->comment('صافي راتب للموظف');
            $table->string('year_and_month', 10)->nullable()->default(0)->comment('السنة والشهر');
            $table->integer('finance_yr')->nullable()->default(0)->comment('السنة المالية للراتب');
            $table->integer('sal_cash_or_visa')->nullable()->default(0)->comment('استلم الراتب نقداً او فيزا');
            $table->integer('is_stoped')->nullable()->default(0)->comment('استلم الراتب نقداً او فيزا');
            $table->decimal('total_benefits', 10, 2)->nullable()->default(0)->comment('اجمالي المستحق للموظف');
            $table->decimal('total_deduction', 10, 2)->nullable()->default(0)->comment('اجمالي الخصم للموظف');

            $table->integer('is_archived')->nullable()->default(0)->comment('هل تم ارشفة الراتب');
            $table->foreignId('archived_by')->nullable()->comment('من قام بأرشفة الراتب')->references('id')->on('admins')->onUpdate('cascade');
            $table->dateTime('archived_date')->nullable()->comment('تاريخ ارشفة الراتب');
            $table->foreignId('added_by')->references('id')->on('admins')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('admins')->onUpdate('cascade');
            $table->integer('com_code')->comment('كود الشركة التابع لها الموضف لحظة الراتب');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employees');
    }
};
