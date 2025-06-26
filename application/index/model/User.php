<?php

namespace app\index\model;


use think\Model;

class User extends Model
{       

    public function myExecute(){
        $this->execute('insert into think_user (id, name) values (?, ?)',[8,'thinkphp']);
    }


}