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

$link='';
////OPEN DATABASE
function openDB(){
global $link;
include '../configuration.php';
$joomlaConf= new JConfig();
$link = mysql_connect($joomlaConf->host,$joomlaConf->user,$joomlaConf->password);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($joomlaConf->db) or die('Could not select database');
mysql_set_charset('utf8');
}
///CLOSE DATABASE
function closeDB(){
global $link;
mysql_close($link);
}

openDB();


// Performing SQL query
$query='SELECT 
       `jos_vm_product`.`product_id` AS ID,
       `jos_vm_product`.`product_desc` AS DESCRIPTION,
       `jos_vm_product`.`product_weight` AS WEIGHT,
       `jos_vm_product`.`attribute` AS ATTRIBUTE
       FROM `jos_vm_product` 
LEFT JOIN `jos_vm_product_price` ON `jos_vm_product`.`product_id` = `jos_vm_product_price`.`product_id` 
LEFT JOIN `jos_vm_product_category_xref` ON `jos_vm_product`.`product_id` = `jos_vm_product_category_xref`.`product_id` 
LEFT JOIN `jos_vm_product_mf_xref` ON `jos_vm_product`.`product_id`=`jos_vm_product_mf_xref`.`product_id` 
LEFT JOIN `jos_vm_category` ON `jos_vm_category`.`category_id`= `jos_vm_product_category_xref`.`category_id` 
LEFT JOIN `jos_vm_manufacturer` ON `jos_vm_manufacturer`.`manufacturer_id` = `jos_vm_product_mf_xref`.`manufacturer_id`
WHERE `jos_vm_product`.`product_id` = '.(int)$_REQUEST['pid'];


$result = mysql_query($query) or die('Query failed: ' . mysql_error());
// Printing results in HTML

//product_desc
//product_weight
//attribute
//product_length	
//product_width	
//product_height

$row= mysql_fetch_row($result);
$r= rand ();
?>

<a href="#" onclick="javascript:tinyMCE.execCommand('mceToggleEditor', false, 'product_desc<?=$row['0'].$r?>');return false;" title="Toggle editor">Επεξεργαστης</a>


 <FORM action="" method="post">
    <P>
    <LABEL for="description">Περιγραφή: </LABEL><TEXTAREA NAME="product_desc<?=$row['0'].$r?>" id="product_desc<?=$row['0'].$r?>" COLS=90 ROWS=10><?=$row['1']?></TEXTAREA><BR />
    <LABEL for="weight">Βάρος: </LABEL><INPUT type="text" id="weight" value="<?=$row['2']?>"><BR>
    <LABEL for="attribute">Χαρακτιριστικά: </LABEL><INPUT type="text" id="attribute" value="<?=$row['3']?>"><BR>
    <INPUT type="submit" value="Ενημέρωση"> <INPUT type="reset">
    </P>
 </FORM>
<?php


// Free resultset
mysql_free_result($result);
closeDB();


?>
