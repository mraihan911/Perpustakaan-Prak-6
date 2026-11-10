<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;use Modules\Catalog\Models\{Author,Book,Category,Publisher};use Modules\Membership\Models\{LibraryRole,Member};
class LibrarySeeder extends Seeder {public function run():void{
 $publisher=Publisher::firstOrCreate(['name'=>'Penerbit Nusantara'],['city'=>'Bandung']);$author=Author::firstOrCreate(['name'=>'Andi Pratama'],['bio'=>'Penulis contoh untuk data praktikum.']);
 $parent=Category::firstOrCreate(['slug'=>'teknologi'],['name'=>'Teknologi','parent_id'=>null]);$child=Category::firstOrCreate(['slug'=>'pemrograman'],['name'=>'Pemrograman','parent_id'=>$parent->id]);$fiction=Category::firstOrCreate(['slug'=>'fiksi'],['name'=>'Fiksi','parent_id'=>null]);
 $books=[['title'=>'Belajar Laravel Modular','isbn'=>'9786021234567','stock'=>8,'available_stock'=>8],['title'=>'Dasar Domain Driven Design','isbn'=>'9786021234568','stock'=>5,'available_stock'=>5],['title'=>'Cerita Perpustakaan','isbn'=>'9786021234569','stock'=>3,'available_stock'=>3]];
 foreach($books as $i=>$data){$book=Book::firstOrCreate(['isbn'=>$data['isbn']],$data+['publisher_id'=>$publisher->id,'published_year'=>2025]);$book->authors()->syncWithoutDetaching([$author->id]);$book->categories()->syncWithoutDetaching([($i===2?$fiction:$child)->id=>['added_at'=>now()]]);}
 $role=LibraryRole::firstOrCreate(['name'=>'Anggota']);Member::firstOrCreate(['email'=>'anggota@example.com'],['member_number'=>'AGT-0001','name'=>'Budi Santoso','phone'=>'081234567890','joined_at'=>today(),'expires_at'=>today()->addYear(),'status'=>'active','active_borrowings'=>0])->roles()->syncWithoutDetaching([$role->id]);
 Member::firstOrCreate(['email'=>'anggota2@example.com'],['member_number'=>'AGT-0002','name'=>'Siti Aminah','joined_at'=>today(),'expires_at'=>today()->addYear(),'status'=>'active','active_borrowings'=>0])->roles()->syncWithoutDetaching([$role->id]);
}}
