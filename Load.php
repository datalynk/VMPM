<?php
/*
    VMPM - Web Based Script for easy management of Virtuemart Products
    Copyright (C) 2011  Panagioris Skarvelis (sl45sms@yahoo.gr)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
        include '../configuration.php';
        $joomlaConf= new JConfig();

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * DB variables
         */

        /* Database connection information */
        $gaSql['user']       = $joomlaConf->user;
        $gaSql['password']   = $joomlaConf->password;
        $gaSql['db']         = $joomlaConf->db;
        $gaSql['server']     = $joomlaConf->host;

        /* 
         * MySQL connection
         */
        $gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
                die( 'Could not open connection to server' );

        mysql_select_db( $gaSql['db'], $gaSql['link'] ) or
                die( 'Could not select database '. $gaSql['db'] );


// Set internal character encoding to UTF-8 for mysql Need this for Greek Support
   $rSn= mysql_query("SET NAMES 'UTF8'",$gaSql['link']) or die(mysql_error()."SetNames");
   $rSc= mysql_query("SET CHARACTER SET 'utf8'") or die(mysql_error()."Set Charset");


if ($_REQUEST['column']=="sdesc"){
$sQuery = "SELECT `product_s_desc` FROM `jos_vm_product` WHERE `product_id`  = '".(int)$_REQUEST['pid']."'";
} else $sQuery = "SELECT 'YEA' 'U' 'R' 'A' 'BIG' 'HACKER'";


$result = mysql_query($sQuery);
if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
$row = mysql_fetch_row($result);

echo $row[0];

?>
