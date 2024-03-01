<?php 
namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class Image extends Facade {

    /**
     * @method make()
     * 
     * @method delete()
     * 
     */
    public static function getFacadeAccessor() {
        return 'Image';
    }
}