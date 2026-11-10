<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{
 Schema::create('library_roles',function(Blueprint $t){$t->id();$t->string('name')->unique();$t->timestamps();});
 Schema::create('library_members',function(Blueprint $t){$t->id();$t->string('member_number')->unique();$t->string('name');$t->string('email')->unique();$t->string('phone')->nullable();$t->date('joined_at');$t->date('expires_at')->nullable();$t->string('status')->default('active');$t->unsignedInteger('active_borrowings')->default(0);$t->timestamps();$t->softDeletes();});
 Schema::create('library_member_role',function(Blueprint $t){$t->foreignId('member_id')->constrained('library_members')->cascadeOnDelete();$t->foreignId('role_id')->constrained('library_roles')->cascadeOnDelete();$t->primary(['member_id','role_id']);});
}public function down():void{Schema::dropIfExists('library_member_role');Schema::dropIfExists('library_members');Schema::dropIfExists('library_roles');}};
