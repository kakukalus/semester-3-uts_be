<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    use HasFactory;
    protected $table = 'patients';
    protected $fillable = ['name','phone','address','status_id','in_date_at','out_date_at'];
    protected $primaryKey = 'id';
    
    public $timestamps = false;

    public function status_patient()
    {
        // return $this->belongsTo(Status::class);
        return $this->belongsTo('App\Models\Status','status_id','id_status');
    }


}
