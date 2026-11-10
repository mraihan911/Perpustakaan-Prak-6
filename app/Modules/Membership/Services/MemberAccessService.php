<?php
namespace Modules\Membership\Services;
use Modules\Membership\Models\Member;use Modules\Shared\Contracts\MemberAccessInterface;
class MemberAccessService implements MemberAccessInterface {public function canBorrow(int $id):bool{$m=Member::find($id);return $m!==null&&$m->status==='active'&&(!$m->expires_at||!$m->expires_at->lt(today()))&&$m->active_borrowings<5;}public function incrementBorrowings(int $id):void{Member::whereKey($id)->increment('active_borrowings');}public function decrementBorrowings(int $id):void{Member::withTrashed()->whereKey($id)->where('active_borrowings','>',0)->decrement('active_borrowings');}}
