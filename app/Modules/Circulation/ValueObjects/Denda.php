<?php
namespace Modules\Circulation\ValueObjects;
use InvalidArgumentException;
final readonly class Denda {public function __construct(public int $hariTerlambat,public int $tarifPerHari=1000){if($hariTerlambat<0||$tarifPerHari<0)throw new InvalidArgumentException('Hari terlambat dan tarif denda tidak boleh negatif.');}public function hitung():int{return $this->hariTerlambat*$this->tarifPerHari;}public function __toString():string{return 'Rp'.number_format($this->hitung(),0,',','.');}}
