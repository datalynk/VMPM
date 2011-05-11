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

/*Server side script for Datatables Jquery Component*/
/*It's heavily modified version of server side example*/

         include '../configuration.php';
        $joomlaConf= new JConfig();
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * DB variables
	 */

 	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "cid";

	/* Database connection information */
	$gaSql['user']       = $joomlaConf->user;
	$gaSql['password']   = $joomlaConf->password;
	$gaSql['db']         = $joomlaConf->db;
	$gaSql['server']     = $joomlaConf->host;
	
	
$aColumns = array('ROWID','DETAILSIMG','ID','THUMBNAIL','NAME','SKU','PRICE','SDESC','PUBLISH');

$dbColumns = array('`jos_vm_product`.`product_id`','`product_thumb_image`','`product_name`','`product_sku`','`product_price`','`product_s_desc`','`product_publish`');

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	
	mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
		die( 'Could not select database '. $gaSql['db'] );
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($dbColumns) ; $i++ ) //IGNORE ROWID,DETAILSIMG

		{
			$sWhere .= $dbColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual filtering For Category*/ 
	if ( $_GET['sSearch_0'] != ''&& $_GET['sSearch_0'] != '-' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= "`jos_vm_category`.`category_name` LIKE '%".mysql_real_escape_string($_GET['sSearch_0'])."%' ";
		}
	

        /* Individual filtering For Group*/ 
        if ( $_GET['sSearch_1'] != '' )
                {
                        if ( $sWhere == "" )
                        {
                                $sWhere = "WHERE ";
                        }
                        else
                        {
                                $sWhere .= " AND ";
                        }
                        $sWhere .= "`jos_vm_shopper_group`.`shopper_group_name` LIKE '%".mysql_real_escape_string($_GET['sSearch_1'])."%' ";
                }
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
SELECT   
      `jos_vm_product`.`product_id` AS ID,
       `product_thumb_image` AS THUMBNAIL,
       `product_name` AS NAME,
       `product_sku` AS SKU,
       `product_price` AS PRICE,
       `product_s_desc` AS SDESC,
       `product_publish` AS PUBLISH
       FROM `jos_vm_product` 
LEFT JOIN `jos_vm_product_price` ON `jos_vm_product`.`product_id` = `jos_vm_product_price`.`product_id` 
LEFT JOIN `jos_vm_product_category_xref` ON `jos_vm_product`.`product_id` = `jos_vm_product_category_xref`.`product_id` 
LEFT JOIN `jos_vm_product_mf_xref` ON `jos_vm_product`.`product_id`=`jos_vm_product_mf_xref`.`product_id` 
LEFT JOIN `jos_vm_category` ON `jos_vm_category`.`category_id`= `jos_vm_product_category_xref`.`category_id` 
LEFT JOIN `jos_vm_manufacturer` ON `jos_vm_manufacturer`.`manufacturer_id` = `jos_vm_product_mf_xref`.`manufacturer_id`
LEFT JOIN `jos_vm_tax_rate` ON `jos_vm_product`.`product_tax_id`=`jos_vm_tax_rate`.`tax_rate_id`
LEFT JOIN  `jos_vm_shopper_group` ON `jos_vm_product_price`.`shopper_group_id`=`jos_vm_shopper_group`.`shopper_group_id`
		$sWhere
		$sOrder
		$sLimit
	";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error()."<br />".$sQuery);
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	


	/* Total data set length */
	$sQuery = "
SELECT COUNT(`product_sku`) AS COUNTER FROM `jos_vm_product` 
LEFT JOIN `jos_vm_product_price` ON `jos_vm_product`.`product_id` = `jos_vm_product_price`.`product_id` 
LEFT JOIN `jos_vm_product_category_xref` ON `jos_vm_product`.`product_id` = `jos_vm_product_category_xref`.`product_id` 
LEFT JOIN `jos_vm_product_mf_xref` ON `jos_vm_product`.`product_id`=`jos_vm_product_mf_xref`.`product_id` 
LEFT JOIN `jos_vm_category` ON `jos_vm_category`.`category_id`= `jos_vm_product_category_xref`.`category_id` 
LEFT JOIN `jos_vm_manufacturer` ON `jos_vm_manufacturer`.`manufacturer_id` = `jos_vm_product_mf_xref`.`manufacturer_id`
LEFT JOIN `jos_vm_tax_rate` ON `jos_vm_product`.`product_tax_id`=`jos_vm_tax_rate`.`tax_rate_id`
LEFT JOIN  `jos_vm_shopper_group` ON `jos_vm_product_price`.`shopper_group_id`=`jos_vm_shopper_group`.`shopper_group_id`
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => intval($iTotal),
		"iTotalDisplayRecords" => intval($iFilteredTotal),
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "THUMBNAIL" ) //TODO ti kano edo? An exei mikrografia thn dixno an den exei mikreno thn kanonikh?
			{
		
				$row[] = "<img src='/components/com_virtuemart/shop_image/product/".$aRow[ $aColumns[$i] ]."' width=80px; height=80px; />";
			}

                        else if ( $aColumns[$i] == "PUBLISH" ) {

                             if ($aRow[ $aColumns[$i] ]=="Y") $row[]="Yes"; else $row[]="No"; //TODO Edv ti kano me tin glosa? An einai Ellinioka na leo "Nai" "Oxi"?  
                   
                        }
			else if ( $aColumns[$i] == 'DETAILSIMG' )
			{
				$row[] = "<img src='media/details_open.png' class='details'>";
			} 
                        else if ( $aColumns[$i] == 'ROWID' )
                         {
                        $row[] = $aRow[ 'ID' ];
                        }
                         else $row[] = $aRow[ $aColumns[$i] ];
		}

		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>
