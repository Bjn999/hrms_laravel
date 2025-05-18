<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_File extends Model
{
    use HasFactory;
    protected $table = "employees_files";
    protected $guarded = [];

    
    public function employee(){
        return $this->BelongsTo('\App\Models\Employee', 'employee_id');
    }
    public function added(){
        return $this->BelongsTo('\App\Models\Admins', 'added_by');
    }
    public function updatedby(){
        return $this->BelongsTo('\App\Models\Admins', 'updated_by');
    }
}
