<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 17-10-11
 * Time: 下午1:31
 */
namespace app\lib;
class CORS
{
    public function appInit(&$params)
    {
        header('Access-control-Allow-Origin: *');
        header('Access-control-Allow-Headers: Content-Type, token, Origin, X-Requested-With, Accept');
        header('Access-control-Allow-Methods: POST,GET');
        if(request()->isOptions()){
            exit();
        }
    }
}