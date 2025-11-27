<?php
namespace App\Helpers;
use App\User;

class Helper 
{

    public static function getUser($val)
    {
        $g=User::find($val);
        if($g) {
            $n=$g->name;
        } else {
            $n='';
        }
        return $n;
    }

}

?>