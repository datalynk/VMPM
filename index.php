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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>	
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<link rel="shortcut icon" type="image/ico" href="favicon.ico">
		
		<title>Virtuemart Product Manager In place updater</title>
		<style type="text/css" title="currentStyle">
			@import "media/css/page.css";
			@import "media/css/table.css";
			@import "media/css/themes/base/jquery-ui.css";
			@import "media/css/themes/smoothness/jquery-ui-1.7.2.custom.css";
		</style>
        <script src="media/js/jquery-1.4.4.min.js" type="text/javascript"></script>
        <script src="media/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="media/js/jquery.jeditable.js" type="text/javascript"></script>
        <script src="media/js/jquery.jeditable.checkbox.js" type="text/javascript"></script>
        <script src="media/js/jquery-ui.js" type="text/javascript"></script>
        <script src="media/js/jquery.validate.js" type="text/javascript"></script>
        <script src="media/js/jquery.dataTables.editable.js" type="text/javascript"></script>
		<script type="text/javascript" charset="utf-8">

/* Formating function for product details */
function fnFormatDetails ( oTable, nTr,ID )
{
var aData = oTable.fnGetData( nTr );
var sOut = '<div id="pdetails'+ID+'">Άνοιγμα καρτέλας του "'+aData[4]+'"<br /> Παρακαλώ περιμένετε...';
$.get('productform.php?pid='+ID, function(data) { 
var pd = data+"<a href='#' id='pr"+ID+"'>VM PRODUCT FORM</a>";
$('#pdetails'+ID).html(pd);
$( '#pr'+ID ).click(function() {
 url = "/index.php?page=product.product_form&tmpl=component&option=com_virtuemart&product_id="+ID;
                    $("#modalDiv").dialog("open");
                    $("#modalIFrame").attr('src',url);
$("#modalIFrame").contents().find("#vmMenu").hide();

                    return false;
    });
});
	return sOut;
}



/*Helper for Dropdown Filters*/
//TODO make this function syncronous?
function create_json_drop_down(Box){ 
var current_selected_size = $('select#'+Box+'_dropdown option:selected').val();  
$.getJSON('GetDropDown.php?w='+Box, create_dropdown); 
  function create_dropdown(data, textStatus) {  
 $('select#'+Box+'_dropdown').find('option').each(function()  
   {  
    $(this).remove();  
   });  
   $.each(data, function() {  
    var is_selected = false;  
    if(current_selected_size == this.id)is_selected = true;  
    var option = new Option(this.text, this.id, false, is_selected);  
    var options = $('#'+Box+'_dropdown').attr('options');  
    options[options.length] = option;  
   });  
  }  
}  


/*Hide VM menu on loaded frame*/
function frame_loaded(){
$("#modalIFrame").contents().find("body #vmMenu").hide();
}

////////////////////////////////////////////////////////////////////////////////////////////

$(document).ready( function () {


//Load dropdown menus
create_json_drop_down("Categorys");
create_json_drop_down("Group");

//Prepare VM  dialog
$("#modalDiv").dialog({
                hide: 'slide',
                position: 'top',
                modal: true,
                autoOpen: false,
                height: $(window).height()-10,
                width: $(window).width()-10,
                draggable: false,
                resizeable: false,   
                title: 'VM product Dialog'
            });

//$("#modalDiv").css( { "left":"0px", "top":"0px" } );
$("#modalIFrame").ready(function () {
$("#modalIFrame").contents().find("body #vmMenu").hide();
$("#modalIFrame").contents().find("table tbody tr td").width('0%');
//$("#modalIFrame").contents().find("body.table.td.td").width('100%');
});


var oTable = $('#products').dataTable({
	//"oSearch": {"sSearch": " Init Search"},
        "aoSearchCols": [null, {"sSearch": "-default-" }],//TODO na valo na pernei to proto apo to dropdwon giati to -default- mporei na alaksei
                                                          //Normaly this array must be of the same size as the number of columns 
                                                          //but because we use the sSearch_0 and sSearch_1 outside from Datatables
                                                          //we can use this trick to send at serverside script the filter value for
                                                          //group  

         	"sCookiePrefix": "vmpm_",
		"sScrollX": "100%",
	//	"sScrollXInner": "110%",
		"bScrollCollapse": true,
                                                            
		"bServerSide": true,
		"sAjaxSource": "AjaxServer.php",
	//	"bStateSave": true, //TODO na to kano enable sto telos?
               	"bJQueryUI": true,
		"bProcessing": true,
       		"sPaginationType": "two_button",
	//	"sDom":'<"toolbar">ftrip',
		"oLanguage": { 
	"sProcessing": "Παρακαλώ περιμένετε...",
	"sLengthMenu": "Εμφάνιση _MENU_ προϊώντα ανα σελίδα",
	"sZeroRecords": "Δεν βρέθηκαν προϊώντα.",
	"sInfo": "Εμφάνιση _START_ εώς _END_ απο _TOTAL_ προϊώντα",
	"sInfoEmpty": "Εμφάνιση 0 εώς 0 απο 0 προϊώντα.",
	"sInfoFiltered": "(Φιλτρό σε _MAX_  προϊώντα.)",
	"sInfoPostFix": "",
       // "oSearch": {"sSearch": "long"},
        "sSearch": "Αναζήτηση",
	"sUrl": "",//TODO media/language/el_GR.txt
	"oPaginate": {
		"sFirst":    "Πρώτο",
		"sPrevious": "Προηγούμενο",
		"sNext":     "Επόμενο",
		"sLast":     "Τελευτέο"
                    }
           },
                     
            "aoColumnDefs": [
                  {"bSearchable": false, "aTargets": [ 0,1 ] },//no search for columns
                  {"bVisible":    false, "aTargets": [ 0 ] },  //hide columns                       
                  {"bSortable": false, "aTargets": [ 0,1 ] },//no sorting for columns
          //       { "fnRender": function ( oObj ) {return "<img src='media/details_open.png'>"},"aTargets": [ 0 ] },
	  //DEL	 { "sAddNewRowFormId": function ( oObj ) {return oObj.aData[0];},"aTargets": [ 0 ] },

                 //      {"fnRender": function ( oObj ) {return oObj.aData[4].substr(0, 15);},"aTargets": [ 4 ]},
		        {"fnRender": function ( oObj ) {return oObj.aData[7].replace(/<.*?>/g, '').substr(0, 7)+"...";},"aTargets": [ 7 ]}, //SDESC
                 //	{ "bVisible": false,  "aTargets": [ 6 ] },
                        
			{ "sClass": "read_only", "aTargets": [ 2,3 ] } //Auto den fenetai na doulevei,,Ta ekana read only vazontas null parakato sto aoColumns
		]//,"aaSorting": [[1, 'asc']] 
               						}).makeEditable({
								        sUpdateURL: "UpdateData.php",
                    							sAddURL: "AddData.php",
									sAddHttpMethod: "GET", 
                    							sDeleteURL: "DeleteData.php",
									sAddNewRowFormId: function ( oObj ) {return oObj.aData[0];},
									sAddDeleteToolbarSelector: ".dataTables_length",	
        "aoColumns": [
                     null,null,null,{},{},{},
                         {     
                                loadurl  : 'Load.php', 
                                 loaddata : function (value,setings) { 
                                    return  {column: "sdesc",pid: this.parentNode.getAttribute('id')}; //To id tou row
                                 },
                                indicator: '...',
                                tooltip: 'Κανε διπλό κλικ για επεξεργασία',
                                type: 'textarea',
                                rows: 6,
				cols: 8,  
                                submit: 'Ενημέρωση'
                        }
, 
                        {type: 'checkbox'}//Edo prepei na einai osa kai ta columns
                      ]

		});		

/*Setup Dropdown Filters*/
$("#Categorys_dropdown").change( function() {
 var str = "";
          $("#Categorys_dropdown option:selected").each(function () {
                str += $(this).text();
              });
oTable.fnFilter( str, 0 );
}); 

$("#Group_dropdown").change( function() {
 var str = "";
          $("#Group_dropdown option:selected").each(function () {
                str += $(this).text();
              });
oTable.fnFilter( str, 1 );
}); 

/* Add event listener for opening and closing details
	 * Note that the indicator for showing which row is open is not controlled by DataTables,
	 * rather it is done here
	 */
	$('#products tbody td .details').live('click', function () {
		var nTr = this.parentNode.parentNode;
		if ( this.src.match('details_close') )
		{
			/* This row is already open - close it */
			this.src = "media/details_open.png";
			oTable.fnClose( nTr );
		}
		else
		{
			/* Open this row */
			this.src = "media/details_close.png";
			oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr,nTr.getAttribute('id')), 'details' );
		}
	});


});
</script>

<!-- Init TinyMCE editor -->
 <script type="text/javascript" src="http://www.virtuemart.gr/plugins/editors/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript"> 
                                tinyMCE.init({
                                        // General
                                        directionality: "ltr",
                                       // editor_selector : "mce_editable",
                                        mode : "textareas",
                                        // Cleanup/Output
                                        inline_styles : true,
                                        gecko_spellcheck : true,
                                        cleanup : true,
                                        cleanup_on_startup : false,
                                        entity_encoding : "raw",
                                        extended_valid_elements : "hr[id|title|alt|class|width|size|noshade|style],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],a[id|class|name|href|target|title|onclick|rel|style]",
                                        force_br_newlines : false, force_p_newlines : true, forced_root_block : 'p',
                                        invalid_elements : "applet",
                                        // URL
                                        relative_urls : true,
                                        remove_script_host : false,
                                      //  document_base_url : "",
                                        // Layout
                                       // content_css : "/templates/system/css/editor.css",
                                });
                                </script>
</head>

<body id="dt_products">
	<div id="container">
Με διπλό κλίκ ανοίγουν τα πεδία για Edit, η ενημέρωση ολοκληρώνεται πατώντας enter.
<div id="demo">
<div id="filters">
<p>Φίλτρα προβολής</p>
Επιλογή Κατηγορίας:<select name="Categorys_dropdown" id="Categorys_dropdown"></select>  
Επιλογή Ομάδας<select name="Group_dropdown" id="Group_dropdown"></select>
</div>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="products">

	<thead>
		<tr>                
                        <th class="hidden">Hideme</th>
                        <th>DI</th> 
		 	<th>ID</th>
			<th>THUMBNAIL</th>
			<th>NAME</th>
			<th>SKU</th>
                        <th>PRICE</th>
			<th>SDESC</th> 
			<th>PUBLISH</th> 
	         </tr>
	</thead>
	<tfoot>
		<tr> 
                        <th class="hidden">Hideme</th>
                        <th>DI</th>
                        <th>ID</th>
                        <th>THUMBNAIL</th>
                        <th>NAME</th>
                        <th>SKU</th>
                        <th>PRICE</th>
                        <th>SDESC</th>
                        <th>PUBLISH</th> 
                 </tr>

	</tfoot>
</table>
</div>
</div>
 <div id="modalDiv" ><iframe id="modalIFrame" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="yes" title="VM Dialog" onload="if (modalIFrame.location.href != 'about:blank') frame_loaded()" ></iframe></div>

</body>
</html>
