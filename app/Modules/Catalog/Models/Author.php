<?php
namespace Modules\Catalog\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Author extends Model {protected $table='library_authors';protected $fillable=['name','bio'];public function books():BelongsToMany{return $this->belongsToMany(Book::class,'author_book','author_id','book_id');}}
