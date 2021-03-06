<?php 

/*

VS SCORM - CAMreader.php 
Rev 1.0 - Friday, October 02, 2009
Copyright (C) 2009, Addison Robson LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, 
Boston, MA 02110-1301, USA.

*/

// ------------------------------------------------------------------------------------
// Preparations
// ------------------------------------------------------------------------------------

// load the imsmanifest.xml file
$dom = new DomDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->load('imsmanifest.xml');

// adlcp namespace
$manifest = $dom->getElementsByTagName('manifest');
$adlcp = $manifest->item(0)->getAttribute('xmlns:adlcp');

// ------------------------------------------------------------------------------------
// Read the Resources (Assets) List
// ------------------------------------------------------------------------------------

// output table header row
$resListTable  = "<table cellpadding=3 cellspacing=0 border=1>\n";
$resListTable .= "<tr>\n";
$resListTable .= "\t<td valign=top align=left><b>Identifier</b></td>\n";
$resListTable .= "\t<td valign=top align=left><b>Type</b></td>\n";
$resListTable .= "\t<td valign=top align=left><b>SCORMType</b></td>\n";
$resListTable .= "\t<td valign=top align=left><b>HREF</b></td>\n";
$resListTable .= "\t<td valign=top align=left><b>Files</b></td>\n";
$resListTable .= "</tr>\n";

// get the resources element
$resourcesList = $dom->getElementsByTagName('resources');

// iterate over each of the resources
foreach ($resourcesList as $resourcesListRow) {

  $resourceList = $resourcesListRow->getElementsByTagName('resource');
  $allHref = array();
  foreach ($resourceList as $resourceListRow) {

    // decode the attributes
    // e.g. <resource identifier="A001" type="webcontent" adlcp:scormtype="sco" href="a001index.html">
    $identifier = $resourceListRow->getAttribute('identifier');
    $type = $resourceListRow->getAttribute('type');
    $scormtype = $resourceListRow->getAttribute('adlcp:scormtype');
    $href = $resourceListRow->getAttribute('href');

    // make safe for display
    $identifier = cleanVar($identifier);
    $type = cleanVar($type);
    $scormtype = cleanVar($scormtype);
    $href = cleanVar($href);
    $allHref[] = $href;
    // list of files
    $files = array();
    $fileList = $resourceListRow->getElementsByTagName('file');
    foreach ($fileList as $fileListRow) {
      $files[] = cleanVar($fileListRow->getAttribute('href'));
    }
    $filesText = implode('<br>',$files);

    // table row
    $resListTable .= "<tr>\n";
    $resListTable .= "\t<td valign=top align=left>$identifier</td>\n";
    $resListTable .= "\t<td valign=top align=left>$type</td>\n";
    $resListTable .= "\t<td valign=top align=left>$scormtype</td>\n";
    $resListTable .= "\t<td valign=top align=left><a href='$href'>$href</a></td>\n";
    $resListTable .= "\t<td valign=top align=left>$filesText</td>\n";
    $resListTable .= "</tr>\n";

    // resource array
    $resource[$identifier]['type'] = $type;
    $resource[$identifier]['scormtype'] = $scormtype;
    $resource[$identifier]['href'] = $href;

  }
}

$resListTable .= "</table>\n";

// ------------------------------------------------------------------------------------
// Functions
// ------------------------------------------------------------------------------------
function cleanVar($value) {
  $value = (trim($value) == "") ? " " : htmlentities(trim($value));
  return $value;
}

?>
<html>
<head>
  <title></title>
  <style type="text/css">
  p,td,li,body,input,select,textarea {
    font-family: verdana, sans-serif;
    font-size: 10pt;
  }
  h1 {
    font-weight: bold;
    font-size: 12pt;
  }
  h2 {
    font-weight: bold;
    font-size: 11pt;
  }
  a:link, a:active, a:visited {
    color: blue;
    text-decoration: none;
  }
  a:hover {
    color: blue;
    text-decoration: underline;
  }
  </style>
</head>
<body bgcolor="#ffffff">

<h2>Resources = Assets</h2>
<p><?php print $resListTable; ?>

</body>
</html>
