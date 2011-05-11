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

  $id = $_REQUEST['id'] ;
  $value = $_REQUEST['value'] ;
  $column = $_REQUEST['columnName'] ;
  $columnPosition = $_REQUEST['columnPosition'] ;
  $columnId = $_REQUEST['columnId'] ;
  $rowId = $_REQUEST['rowId'] ;

  /* Update a record using information about id, columnName (property
     of the object or column in the table) and value that should be
     set */ 
  echo "id=$id\nvalue=$value\ncolumn=$column\ncolumnPosition=$columnPosition\ncolumnId=$columnId\nrowId=$rowId\n";
?>
Δεν εχω ακομα φτιαξει το update
