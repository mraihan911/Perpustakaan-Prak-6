<?php
namespace Modules\Circulation\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BorrowingDetail extends Model {protected $table='library_borrowing_details';protected $fillable=['borrowing_id','book_id','book_title','book_isbn','quantity'];public function borrowing():BelongsTo{return $this->belongsTo(Borrowing::class,'borrowing_id');}public function book():BelongsTo{return $this->belongsTo(\Modules\Catalog\Models\Book::class,'book_id');}}
