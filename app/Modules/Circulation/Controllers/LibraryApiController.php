<?php
namespace Modules\Circulation\Controllers;
use Illuminate\Http\Request;use Illuminate\Routing\Controller;use Modules\Catalog\Models\Book;use Modules\Membership\Models\Member;use Modules\Circulation\Models\Borrowing;use Modules\Circulation\Services\CirculationService;
class LibraryApiController extends Controller {
 public function __construct(private CirculationService $circulation){}
 public function dashboard(){return response()->json(['books'=>Book::count(),'available_books'=>Book::sum('available_stock'),'members'=>Member::count(),'active_borrowings'=>Borrowing::where('status','borrowed')->count(),'recent_borrowings'=>Borrowing::with(['member','details'])->latest()->limit(10)->get()]);}
 public function books(Request $r){$q=Book::with(['authors','publisher','categories']);if($r->filled('search'))$q->search($r->string('search')->toString());if($r->filled('category_id'))$q->inCategory((int)$r->input('category_id'));if($r->boolean('available'))$q->available();return $q->paginate(15);}
 public function members(){return Member::with('roles')->paginate(15);}
 public function borrow(Request $r){$data=$r->validate(['member_id'=>'required|integer|exists:library_members,id','items'=>'required|array|min:1','items.*.book_id'=>'required|integer|exists:library_books,id','items.*.quantity'=>'required|integer|min:1']);return response()->json($this->circulation->borrow($data['member_id'],$data['items']),201);}
 public function returnBook(Request $r,Borrowing $borrowing){$data=$r->validate(['returned_at'=>'nullable|date']);return response()->json($this->circulation->returnBorrowing($borrowing,$data['returned_at']??null));}
 public function deleteBook(int $id){$book=Book::findOrFail($id);$book->delete();return response()->json(['message'=>'Buku di-soft delete']);}
 public function deleteMember(int $id){$member=Member::findOrFail($id);$member->delete();return response()->json(['message'=>'Anggota di-soft delete']);}
 public function deleteBorrowing(int $id){$borrowing=Borrowing::findOrFail($id);$borrowing->delete();return response()->json(['message'=>'Peminjaman di-soft delete']);}
 public function restoreBook(int $id){$book=Book::withTrashed()->findOrFail($id);$book->restore();return response()->json(['message'=>'Buku berhasil dipulihkan','book'=>$book]);}
 public function restoreMember(int $id){$member=Member::withTrashed()->findOrFail($id);$member->restore();return response()->json(['message'=>'Anggota berhasil dipulihkan','member'=>$member]);}
 public function restoreBorrowing(int $id){$b=Borrowing::withTrashed()->findOrFail($id);$b->restore();return response()->json(['message'=>'Peminjaman berhasil dipulihkan','borrowing'=>$b]);}
}
