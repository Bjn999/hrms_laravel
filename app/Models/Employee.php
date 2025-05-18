<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $guarded = [];

    public function branch(){
        return $this->BelongsTo('\App\Models\Branche', 'branch_id');
    }
    public function department(){
        return $this->BelongsTo('\App\Models\Department', 'emp_departments_id');
    }
    public function qualification(){
        return $this->BelongsTo('\App\Models\Qualification', 'qualifications_id');
    }
    public function nationality(){
        return $this->BelongsTo('\App\Models\Nationality', 'emp_nationality_id');
    }
    public function language(){
        return $this->BelongsTo('\App\Models\Language', 'emp_lang_id');
    }
    public function social_status(){
        return $this->BelongsTo('\App\Models\Social_Status_Type', 'emp_social_status_id');
    }
    public function religion(){
        return $this->BelongsTo('\App\Models\religion_id', 'religion_id');
    }
    public function job(){
        return $this->BelongsTo('\App\Models\Jobs_categories', 'emp_job_id');
    }
    public function shift_type(){
        return $this->BelongsTo('\App\Models\shifts_type', 'shift_type_id');
    }
    public function resignation(){
        return $this->BelongsTo('\App\Models\Resignation', 'resignation_id');
    }
    public function added(){
        return $this->BelongsTo('\App\Models\Admins', 'added_by');
    }
    public function updatedby(){
        return $this->BelongsTo('\App\Models\Admins', 'updated_by');
    }
}
