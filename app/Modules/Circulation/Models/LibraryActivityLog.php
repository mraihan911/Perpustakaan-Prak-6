<?php
namespace Modules\Circulation\Models;
use Illuminate\Database\Eloquent\Model;
class LibraryActivityLog extends Model {protected $table='library_activity_logs';protected $fillable=['event','payload'];protected $casts=['payload'=>'array'];}
