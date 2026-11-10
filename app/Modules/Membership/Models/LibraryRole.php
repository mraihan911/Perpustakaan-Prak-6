<?php
namespace Modules\Membership\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class LibraryRole extends Model {protected $table='library_roles';protected $fillable=['name'];public function members():BelongsToMany{return $this->belongsToMany(Member::class,'library_member_role','role_id','member_id');}}
