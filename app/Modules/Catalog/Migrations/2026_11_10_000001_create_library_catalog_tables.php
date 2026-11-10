<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('library_authors', function(Blueprint $t){$t->id();$t->string('name');$t->text('bio')->nullable();$t->timestamps();});
  Schema::create('library_publishers', function(Blueprint $t){$t->id();$t->string('name');$t->string('city')->nullable();$t->timestamps();});
  Schema::create('library_categories', function(Blueprint $t){$t->id();$t->string('name');$t->string('slug')->unique();$t->foreignId('parent_id')->nullable()->constrained('library_categories')->nullOnDelete();$t->timestamps();$t->softDeletes();});
  Schema::create('library_books', function(Blueprint $t){$t->id();$t->string('title');$t->string('isbn',20)->unique();$t->foreignId('publisher_id')->nullable()->constrained('library_publishers')->nullOnDelete();$t->unsignedInteger('stock')->default(0);$t->unsignedInteger('available_stock')->default(0);$t->unsignedInteger('published_year')->nullable();$t->timestamps();$t->softDeletes();$t->index(['title','isbn']);});
  Schema::create('author_book', function(Blueprint $t){$t->foreignId('author_id')->constrained('library_authors')->cascadeOnDelete();$t->foreignId('book_id')->constrained('library_books')->cascadeOnDelete();$t->primary(['author_id','book_id']);});
  Schema::create('book_category', function(Blueprint $t){$t->foreignId('book_id')->constrained('library_books')->cascadeOnDelete();$t->foreignId('category_id')->constrained('library_categories')->cascadeOnDelete();$t->timestamp('added_at')->useCurrent();$t->primary(['book_id','category_id']);});
 }
 public function down(): void { Schema::dropIfExists('book_category');Schema::dropIfExists('author_book');Schema::dropIfExists('library_books');Schema::dropIfExists('library_categories');Schema::dropIfExists('library_publishers');Schema::dropIfExists('library_authors'); }
};
