<?php

namespace App\Result;

class ResultData{

    private $success; 
    private $data;
    private $error_message; 

    public function __construct($success = false,$error_message = null,$data = null)
    {
        if($error_message == null){$error_message = [];}
        if($data == null){$data = [];}
        $this->success = $success;
        $this->error_message = $error_message;
        $this->data = $data;
    }

    public function getSuccess() : bool
    {
        return $this->success;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

}

?>