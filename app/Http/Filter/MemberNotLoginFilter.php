<?php

namespace App\Http\Filter;

use App\Auth\MemberAuth;

class MemberNotLoginFilter{
    
    public function handle()
    {
        if(MemberAuth::check()){
            return redirect('/');
        }
        
        return null;
    }

}

?>