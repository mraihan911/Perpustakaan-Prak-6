<?php
namespace Modules\Membership\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\SoftDeletes;use Illuminate\Database\Eloquent\Relations\BelongsToMany;use Illuminate\Database\Eloquent\Relations\HasMany;
class Member extends Model {use SoftDeletes;protected $table='library_members';protected $fillable=['member_number','name','email','phone','joined_at','expires_at','status','active_borrowings'];protected $casts=['joined_at'=>'date','expires_at'=>'date','active_borrowings'=>'integer'];public function roles():BelongsToMany{return $this->belongsToMany(LibraryRole::class,'library_member_role','member_id','role_id');}public function borrowings():HasMany{return $this->hasMany(\Modules\Circulation\Models\Borrowing::class,'member_id');}}
