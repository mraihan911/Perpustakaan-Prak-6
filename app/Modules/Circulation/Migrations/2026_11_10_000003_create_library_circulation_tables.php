<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{
 Schema::create('library_borrowings',function(Blueprint $t){$t->id();$t->string('borrowing_code')->unique();$t->foreignId('member_id')->constrained('library_members');$t->date('borrowed_at');$t->date('due_at');$t->date('returned_at')->nullable();$t->string('status')->default('borrowed');$t->unsignedBigInteger('fine_amount')->default(0);$t->timestamps();$t->softDeletes();});
 Schema::create('library_borrowing_details',function(Blueprint $t){$t->id();$t->foreignId('borrowing_id')->constrained('library_borrowings')->cascadeOnDelete();$t->foreignId('book_id')->constrained('library_books');$t->string('book_title');$t->string('book_isbn',20);$t->unsignedInteger('quantity');$t->timestamps();});
 Schema::create('library_activity_logs',function(Blueprint $t){$t->id();$t->string('event');$t->json('payload')->nullable();$t->timestamps();});
}public function down():void{Schema::dropIfExists('library_activity_logs');Schema::dropIfExists('library_borrowing_details');Schema::dropIfExists('library_borrowings');}};
