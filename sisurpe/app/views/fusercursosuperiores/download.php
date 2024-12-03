<?php
$filename = $data->file_name;
$mimetype = $data->file_type;
$filedata = ($data->file); 
header( "Content-Type: $data->file_type" );
header( "Content-Disposition: attachment; filename=$filename" );
header( 'Content-Transfer-Encoding: Binary' );
header( 'Connection: Keep-Alive' );
header( 'Expires: 0' );
header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
header( 'Pragma: public' );
echo $filedata;
?>