<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    /** @use HasFactory<\Database\Factories\VersionFactory> */
    use HasFactory;

    protected $guarded = false;

    public function fileSize()
    {
        $kb = 1024;
        $mb =1048576;
        $gb =1073741824;
        $tb = 1099511627776;
        if ($this->file_size < 10*$mb){
            return round($this->file_size/$mb).'KB';
        }
        if ($this->file_size < $gb){
            return round($this->file_size/$mb).'MB';
        }
        if ($this->file_size < $tb){
            return round($this->file_size/$gb).'GB';
        }
       return round($this->file_size/$tb).'TB';
    }
}
