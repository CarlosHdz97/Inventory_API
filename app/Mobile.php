<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Mobile extends Model{
    protected $table = 'mobile';
    protected $fillable = [
        'mobile', 'serie', 'emei', 'accesorios', 'status'
    ];
}