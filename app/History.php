<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class History extends Model{
    protected $table = 'history';
    protected $fillable = [
        'responsable', 'notes', 'quantity', 'action'
    ];
    public function historical(){
        return $this->morphTo();
    }
}