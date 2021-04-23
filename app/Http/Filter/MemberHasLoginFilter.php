<?php

namespace App\Http\Filter;

use App\Auth\MemberAuth;

class MemberHasLoginFilter{
    
    public function handle()
    {    
        if(!MemberAuth::check()){
            return redirect('/login');
        }
        
        return null;
    }

}

?>