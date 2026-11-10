<?php
namespace Modules\Catalog\Services;
use Illuminate\Support\Facades\DB;use Modules\Catalog\Models\Book;use Modules\Shared\Contracts\BookCatalogInterface;
class BookCatalogService implements BookCatalogInterface {public function snapshot(int $id):?array{$b=Book::find($id);return $b?['id'=>$b->id,'title'=>$b->title,'isbn'=>$b->isbn,'available_stock'=>$b->available_stock]:null;}public function reserve(int $id,int $qty):bool{$b=Book::query()->lockForUpdate()->find($id);if(!$b||$qty<1||$b->available_stock<$qty)return false;$b->decrement('available_stock',$qty);return true;}public function release(int $id,int $qty):void{$b=Book::withTrashed()->lockForUpdate()->find($id);if($b)$b->increment('available_stock',$qty);}}
