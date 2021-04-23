<?php 

namespace App\Shinmoon\Traits;

use App\Session\SessionUtility;

trait TestTrait{

    private $test_log_path;
    private $common_password;
    private $test_member1;
    private $test_member2;
    private $test_member3;
    private $test_facebook_oauth_member;
    private $test_google_oauth_member;
    private $test_product1;
    private $test_order_setting_param_arr_1;

    public function load_test_config()
    {
        load_const();
        load_env();
        load_app_config();

        $this->common_password = "Aa11111";
        $this->test_member1 = ['id'=>1,'account'=>'testAccount','password'=>'Aa11111','email'=>'testMail@mail.com','oauth_type'=>0,'oauth_id'=>''];
        $this->test_member2 = ['id'=>2,'account'=>'testAccount2','password'=>'Aa11111','email'=>'testMail2@mail.com','oauth_type'=>0,'oauth_id'=>''];
        $this->test_member3 = ['id'=>3,'account'=>'testAccount3','password'=>'Aa11111','email'=>'testMail3@mail.com','oauth_type'=>0,'oauth_id'=>''];
        $this->test_facebook_oauth_member = ['id'=>4,'oauth_type'=>1,'oauth_id'=>'facebook_oauth_id_1'];
        $this->test_google_oauth_member = ['id'=>5,'oauth_type'=>2,'oauth_id'=>'google_oauth_id_1'];
        $this->test_product1 = ['id'=>1];
        $this->test_order_setting_param_arr_1 = [
            'name'=>'Customer1',
            'telephone_number'=>$this->generate_random_telephone_number(),
            'invoice_type'=>'second_invoice',
            'donate_invoice_type'=>'1',
            'choose_donate'=>'',
            'other_donate'=>'',
            'second_invoice_type'=>'1',
            'member_carrier'=>'',
        ];
    }

    public function generate_random_string($len)
    {
        if($len<=0){
            $len = 0;
        }
        
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        return "Aa1".substr(str_shuffle($str),3,$len-3);
    }

    public function generate_random_telephone_number()
    {
        $str="0123456789";
        str_shuffle($str);
        return "09".substr(str_shuffle($str),0,8);
    }

    public function generate_random_email()
    {
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        return substr(str_shuffle($str),0,10)."@mail.com";
    }

    public function generate_random_member_carrier()
    {
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890";
        str_shuffle($str);
        return substr(str_shuffle($str),0,8);
    }

    public function generate_random_other_donate()
    {
        $str="123456789";
        str_shuffle($str);
        return substr(str_shuffle($str),0,7);
    }

    public function start_session()
    {
        session_start();
        SessionUtility::init_flash();
    }

    public function close_session()
    {
        session_destroy();
    }

    public function set_login_member_session($test_member)
    {
        $_SESSION['member'] = $test_member;
    }

    public function get_test_member_id($test_member) : int
    {
        return (int) $test_member['id'];
    }

    public function get_test_member_account($test_member) : string
    {
        return $test_member['account'];
    }

    public function get_test_member_password($test_member) : string
    {
        return $test_member['password'];
    }

    public function get_test_member_email($test_member) : string
    {
        return $test_member['email'];
    }

    public function get_test_member_oauth_type($test_member) : int
    {
        return (int) $test_member['oauth_type'];
    }

    public function get_test_member_oauth_id($test_member) : string
    {
        return $test_member['oauth_id'];
    }

    public function get_test_product_id($test_product) : int
    {
        return (int) $test_product['id'];
    }

    public function set_test_log_path($path)
    {
        $this->test_log_path = $path;
    }

    public function write_to_log($service_result)
    {
        foreach($service_result->getErrorMessage() as $error_message){
            file_put_contents($this->test_log_path, $error_message."\n", FILE_APPEND);
        }
    }

    public function write_to_file($path,$content)
    {
        file_put_contents($path, $content."\n", FILE_APPEND);
    }

}

?>