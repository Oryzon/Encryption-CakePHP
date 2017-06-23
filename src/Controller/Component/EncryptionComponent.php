<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class EncryptionComponent extends Component {

     static $_key = "AZERTYUIOPQSDFGHJKLMWXCVBNazertyuiopqsdfghjklmwxcvbn0123456789-_";

    public static function Crypt($password, $salt) {
        if($password == "" || $salt == "") return $password;

        $e=self::$_key;
        $u=$e{(int)self::Seed(0,63,microtime(true).$salt)};
        $f=md5($salt.$u,true);
        $l=self::Unorder($e,$f,64);
        $t=$g="";
        $c=strlen($password);
        $s=$c-$c%3;
        $x=$l{(int)self::Seed(0,63,$f.microtime(true))};
        $n=ord($x)+ord($u);
        $v=self::Unorder("0123",$f.$n,4);
        for($r=$i=0;$i<$s;$i+=3,$r++){
            $g=(ord(($password{$i}^$l{($n+$r+1)%64}))<<16)+
                (ord(($password{$i+1}^$l{($n+$r+2)%64}))<<8)+
                (ord(($password{$i+2}^$l{($n+$r+3)%64})));
            $g=array($v{0}=>$l{$g>>18},
                $v{1}=>$l{($g>>12)&63},
                $v{2}=>$l{($g>>6)&63},
                $v{3}=>$l{$g&63});
            ksort($g);
            $t.=join($g);}
        switch($c-$s){
            case 1:
                $g=ord(($password{$i}^$l{($n+$r+4)%64}))<<16;
                $v=self::Unorder("01",$f,2);
                $g=array($v{0}=>$l{$g>>18},
                    $v{1}=>$l{($g>>12)&63});
                ksort($g);
                $t.=join($g);
                break;
            case 2:
                $g=(ord(($password{$i}^$l{($n+$r+4)%64}))<<16)+
                    (ord(($password{$i+1}^$l{($n+$r+5)%64}))<<8);
                $v=self::Unorder("012",$f,3);
                $g=array($v{0}=>$l{$g>>18},
                    $v{1}=>$l{($g>>12)&63},
                    $v{2}=>$l{($g>>6)&63});
                ksort($g);
                $t.=join($g);
                break;}
        $c=strlen($t);
        $r=$c-self::Seed(0,$c-1,$salt.$c);
        return substr_replace($t,$x.$u,-$r,-$r);
    }

    public static function Decrypt($password, $salt) {
        if ($password == '' || $salt == "") return $password;
        $c = strlen($password) - 2;
        $mm = self::Seed(0, $c - 1, $salt . $c);
        $u = $password{(int)$mm + 1};
        $m = ord($password{(int)$mm}) + ord($u);
        $password = substr($password, 0, $mm) . substr($password, -($c - $mm));
        $e = self::$_key;
        $ff = md5($salt . $u, true);
        $l = self::Unorder($e, $ff, 64);
        $v = self::Unorder("0123", $ff . $m, 4);
        $k = array(
            "0123", "0132",
            "0213", "0312",
            "0231", "0321");
        switch ($v{0}) {
            case 0:
                $v = $v == "0231" ? $k[3] : ($v == "0312" ? $k[4] : $v);
                break;
            case 1:
                $v = $v == "1203" ? self::ReverseA($k[2]) :
                    ($v == "1230" ? self::ReverseA($k[3]) :
                        ($v == "1302" ? self::ReverseA($k[4]) :
                            (($v == "1320" ? self::ReverseA($k[5]) : $v))));
                break;
            case 2:
                $v = $v == "2013" ? self::ReverseB($k[0]) :
                    ($v == "2031" ? self::ReverseB($k[1]) : (
                    $v == "2130" ? self::ReverseB($k[3]) :
                        ($v == "2310" ? self::ReverseB($k[5]) : $v)));
                break;
            case 3:
                $v = $v == "3012" ? self::ReverseC($k[0]) :
                    ($v == "3021" ? self::ReverseC($k[1]) :
                        ($v == "3102" ? self::ReverseC($k[2]) :
                            ($v == "3201" ? self::ReverseC($k[4]) : $v)));
                break;
        }
        $d = $g = "";
        $f = 0;
        while ($c % 4 !== 0) {
            $password .= "=";
            $c = strlen($password);
            $c = $c - 4;
            $f++;
        };
        for ($r = $i = 0; $i < $c; $i += 4, $r++) {
            $q = array($v{0} => $e{strpos($l, $password{$i})},
                $v{1} => $e{strpos($l, $password{$i + 1})},
                $v{2} => $e{strpos($l, $password{$i + 2})},
                $v{3} => $e{strpos($l, $password{$i + 3})});
            ksort($q);
            $g = (strpos($e, $q[0]) << 18) +
                (strpos($e, $q[1]) << 12) +
                (strpos($e, $q[2]) << 6) +
                (strpos($e, $q[3]));
            $d .= (chr($g >> 16) ^ $l{($m + $r + 1) % 64}) .
                (chr(($g >> 8) & 255) ^ $l{($m + $r + 2) % 64}) .
                (chr($g & 255) ^ $l{($m + $r + 3) % 64});
        }
        switch ($f) {
            case 1:
                $v = self::Unorder("012", $ff, 3);
                $v = $v == "120" ? "201" : ($v == "201" ? "120" : $v);
                $q = array($v{0} => $e{strpos($l, $password{$i})},
                    $v{1} => $e{strpos($l, $password{$i + 1})},
                    $v{2} => $e{strpos($l, $password{$i + 2})});
                ksort($q);
                $g = (strpos($e, $q[0]) << 18) +
                    (strpos($e, $q[1]) << 12) +
                    (strpos($e, $q[2]) << 6);
                $d .= (chr($g >> 16) ^ $l{($m + $r + 4) % 64}) .
                    (chr(($g >> 8) & 255) ^ $l{($m + $r + 5) % 64});
                break;
            case 2:
                $v = self::Unorder("01", $ff, 2);
                $q = array($v{0} => $e{strpos($l, $password{$i})},
                    $v{1} => $e{strpos($l, $password{$i + 1})});
                $g = (strpos($e, $q[0]) << 18) +
                    (strpos($e, $q[1]) << 12);
                $d .= (chr($g >> 16) ^ $l{($m + $r + 4) % 64});
                break;
        }
        return $d;
    }

    private static function Unorder($x, $b, $c) {
        $w = 0;
        $y = strlen($b);
        for ($i = 0; $i < $c; $i++) {
            $w = ($w + ord($x[$i]) +
                    ord($b[$i % $y])) % $c;
            $j = $x[$i];
            $x[$i] = $x[$w];
            $x[$w] = $j;
        }
        return $x;
    }

    private static function ReverseA($a) {
        return strrev(substr($a, 0, 2))
            . substr($a, -2);
    }

    private static function ReverseB($a) {
        return substr(self::ReverseA($a), 0, 1)
            . strrev(substr(self::ReverseA($a), 1, 2))
            . substr(self::ReverseA($a), -1);
    }

    private static function ReverseC($a) {
        return substr(self::ReverseB($a), 0, 2)
            . strrev(substr(self::ReverseB($a), 2, 3));
    }

    private static function Seed($a, $b, $c) {
        $d = unpack("Na", hash("crc32", $c, true));
        return round((($d['a'] & 2147483647) / 2147483647.0) * ($b - $a)) + $a;
    }
}