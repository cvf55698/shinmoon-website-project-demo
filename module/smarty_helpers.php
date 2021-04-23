<?php

if(!function_exists('load_smarty')){
    function load_smarty()
    {
        $smarty_config = require CONFIG_PATH."smarty.php";
        $smarty = new \SmartyBC();
        $smarty->left_delimiter = "{{"; 
        $smarty->right_delimiter = "}}";
        $smarty->setTemplateDir(VIEW_PATH); 
        $smarty->setCompileDir(BASEPATH.$smarty_config['compile_dir']);
        $smarty->setConfigDir(BASEPATH.$smarty_config['config_dir']);
        $smarty->setCacheDir(BASEPATH.$smarty_config['cache_dir']);
        if (APP_DEBUG) {
            $smarty->debugging      = false;
            $smarty->caching        = false;
            $smarty->cache_lifetime = 0;
        } else {
            $smarty->debugging      = false;
            $smarty->caching        = true;
            $smarty->cache_lifetime = 0;
        }
        
        return $smarty;
    }
}

?>