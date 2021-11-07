<?php
use Cake\Core\Configure;

    function getUserStatuses(){
        return Configure::read('AsaUserStatus');
    }


    function userStatusToString($status, $separator = '; ', $prefix = '') {
        $statuses = getUserStatuses();

        $status   = (int)$status;

        $str = '';

        foreach($statuses as $key => $val) {
            if($key & $status) {
                $str .= $prefix . $val . $separator;
            }
        }

        return rtrim($str, $separator);
    }


    function multiSelectToBitMask($masks) {
        $bit = 0;

        foreach($masks as $val) {
            $val = (int)$val;
            $bit = $bit + $val;
        }

        return $bit;
    }


    function bitMaskToOptions($bit) {
        $options = [];

        for($i=1; $i<=HIGHEST_BIT; $i = ($i*2)) {
            if($i & $bit) {
                $options[$i] = $i;
            }
        }

        return $options;
    }


    function popi_email($email) {
        $em   = explode("@", $email);
        $name = implode(array_slice($em, 0, count($em) - 1), '@');
        $len  = floor(strlen($name) / 2);

        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }



