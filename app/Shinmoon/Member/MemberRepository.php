<?php

namespace App\Shinmoon\Member;

use App\Database\DatabaseUtility;
use App\Result\ResultData;
use App\Hash\HashUtility;
use App\Shinmoon\Traits\RepositoryTrait;

class MemberRepository{

    use RepositoryTrait;

    public function select_member_by_account($account)
    {
        $select_account_sql = "select * from member where account = :account ;";
        $query_result = $this->db->query($select_account_sql,[':account'=>$account,]);
        if($query_result->getSuccess()){
            $member = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['member'=>$member]);
        }else{
            return new ResultData(false);
        }
    }

    public function select_member_by_email($email)
    {
        $select_email_sql = "select * from member where email = :email ;";
        $query_result = $this->db->query($select_email_sql,[':email'=>$email,]);
        if($query_result->getSuccess()){
            $member = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['member'=>$member]);
        }else{
            return new ResultData(false);
        }
    }

    public function select_member_by_web_login($account_or_email)
    {
        $select_member_sql = "select * from member where account = :account_or_email or email = :account_or_email ;";
        $query_result = $this->db->query($select_member_sql,[':account_or_email'=>$account_or_email]);
        if($query_result->getSuccess()){
            $member = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['member'=>$member]);
        }else{
            return new ResultData(false);
        }
    }

    public function select_member_by_oauth_login($oauth_type,$oauth_id)
    {
        $select_member_sql = "select * from member where oauth_type = :oauth_type and oauth_id = :oauth_id ;";
        $query_result = $this->db->query($select_member_sql,[':oauth_type'=>$oauth_type,':oauth_id'=>$oauth_id]);
        if($query_result->getSuccess()){
            $member = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['member'=>$member]);
        }else{
            return new ResultData(false);
        }
    }

    public function select_member_by_reset_password($reset_password)
    {
        $select_member_sql = "select * from member where reset_password = :reset_password  ;";
        $query_result = $this->db->query($select_member_sql,[':reset_password'=>$reset_password]);
        if($query_result->getSuccess()){
            $member = $query_result->getData()['rows'][0];
            return new ResultData(true,null,['member'=>$member]);
        }else{
            return new ResultData(false);
        }
    }

    public function insert_member_by_web_register($account,$password,$email)
    {
        $password_hash = HashUtility::bcrypt_hash($password);
        $insert_member_sql = "insert into member (account,password,email) values (:account,:password,:email);";
        $this->db->exec($insert_member_sql,[':account'=>$account,':password'=>$password_hash,':email'=>$email]);
    }

    public function insert_member_by_oauth_login($oauth_type,$oauth_id,$email)
    {
        $insert_member_sql = "insert into member (oauth_type,oauth_id,email) values (:oauth_type,:oauth_id,:email);";
        $this->db->exec($insert_member_sql,[':oauth_type'=>$oauth_type,':oauth_id'=>$oauth_id,':email'=>$email]);
    }

    public function update_member_password($member_id,$new_password_hash)
    {
        $update_member_password_sql = "update member set password = :password where id = :id ;";
        $this->db->exec($update_member_password_sql,[':password'=>$new_password_hash,':id'=>$member_id]);
    }

    public function update_member_email($member_id,$new_email)
    {
        $update_member_email_sql = "update member set email = :email where id = :id ;";
        $this->db->exec($update_member_email_sql,[':email'=>$new_email,':id'=>$member_id]);
    }

    public function update_member_reset_passowrd($member_id,$reset_password_token)
    {
        $update_member_reset_password_sql = "update member set reset_password = :reset_password where id = :id ;";
        $this->db->exec($update_member_reset_password_sql,[':reset_password'=>$reset_password_token,':id'=>$member_id]);
    }

    public function update_member_password_by_reset_password_email($member_id,$new_password_hash)
    {
        $update_member_password_sql = "update member set password = :password , reset_password = NULL where id = :id ;";
        $this->db->exec($update_member_password_sql,[':password'=>$new_password_hash,':id'=>$member_id]);
    }

    public function update_member_profile_by_id($member_id,$member_data)
    {
        $update_member_password_sql = "update member set name = :name , telephone_number = :telephone_number where id = :id ;";
        $this->db->exec($update_member_password_sql,[':name'=>$member_data['name'],'telephone_number'=>$member_data['telephone_number'],':id'=>$member_id]);
    }

    public function update_member_cart_order_id($member_id,$cart_order_id)
    {
        $update_member_cart_order_id_sql = "update member set cart_order_id = :cart_order_id where id = :id ;";
        $this->db->exec($update_member_cart_order_id_sql,[':cart_order_id'=>$cart_order_id,':id'=>$member_id]);
    }

    public function lock_member_by_id($member_id)
    {
        $select_member_sql = "select id from member where id = :id for update;";
        $this->db->exec($select_member_sql,[':id'=>$member_id]);
    }

    public function lock_member_by_email($email)
    {
        $select_member_sql = "select email from member where email = :email for update;";
        $this->db->exec($select_member_sql,[':email'=>$email]);
    }

    public function lock_member_by_reset_password($reset_password)
    {
        $select_member_sql = "select reset_password from member where reset_password = :reset_password for update;";
        $this->db->exec($select_member_sql,[':reset_password'=>$reset_password]);
    }

    public function lock_member_by_oauth($oauth_type,$oauth_id)
    {
        $select_member_sql = "select oauth_type,oauth_id,account from member where oauth_type = :oauth_type and oauth_id = :oauth_id and account = '' for update;";
        $this->db->exec($select_member_sql,[':oauth_type'=>$oauth_type,':oauth_id'=>$oauth_id]);
    }

}

?>