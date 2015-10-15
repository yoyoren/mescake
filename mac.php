<?php
function GetMacAddr()
{
    $return_array = array();
    $temp_array = array();
    $mac_addr = "";
    
    @exec("arp -a",$return_array);
    
    foreach($return_array as $value)
    {
        if(strpos($value,$_SERVER["REMOTE_ADDR"]) !== false &&
        preg_match("/(:?[0-9a-f]{2}[:-]){5}[0-9a-f]{2}/i",$value,$temp_array))
        {
            $mac_addr = $temp_array[0];
            break;
        }
    }
    
    return ($mac_addr);
}
echo $_SERVER["REMOTE_ADDR"];
echo GetMacAddr();
?>
