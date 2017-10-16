<?php
header('Content-Type: text/html; charset=utf-8');
require 'parameters.php';

// exclude the dots from the directory  scanning

$array_dots = array(
	'..',
	'.'
);

// $array_exclude is an array contained in parameters.php
// $files in the directory without dots and excluded

$files = array_diff(scandir($dir) , array_merge($array_dots, $array_exclude));

// enter the directoty where the files exist

$changed_dir = chdir($dir);

// charset of the filenames as POSTed by python

$in_charset = 'ISO-8859-7';

// the array containing the filenames  + timestamps

$full_info_files_array = array();

// foreach filename enter in the full_info_files_array array the last time modification along with the filename

if (!empty($files))
{
	foreach($files as $key => $value)
	{
		array_push($full_info_files_array, array(
			urlencode($value) ,
			iconv($in_charset, "UTF-8", $value) ,
			filemtime($value)
		));
	}

	// sort the files array based on the modification time - usort is used

	usort($full_info_files_array,
	function ($a, $b)
	{
		if (($a[2]) == ($b[2]))
		{
			return 0;
		}
		else
		if (($a[2]) > ($b[2]))
		{
			return -1;
		}
		else
		{
			return 1;
		}
	}); //usort end
} // if !empty end


$page_directory = dirname($_SERVER["PHP_SELF"]);
?>
<html>
   <head><meta charset="utf-8" />
       <link href="http://mottie.github.io/tablesorter/css/theme.default.css" rel="stylesheet">
       <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 
       <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.1/js/jquery.tablesorter.min.js"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.1/js/extras/jquery.tablesorter.pager.min.js"></script> 
    <script>
        $(function(){
          $("#files_list").tablesorter({widthFixed: true, widgets: ['zebra']}) 
             .tablesorterPager({container: $("#pager")}); });
    </script>
       
     <style>
    table, th, td {border: 1px solid black;}
         .tablesorter  {width: auto;}
    </style>
       
    </head>
   
    <body>
   <h1>
       Ανακοινώσεις σχολείου 2016-2017
        </h1>
           <table id='files_list' class="tablesorter"> 
               
               <thead>
                 <tr> 
                   <th>Σύνδεσμος</th> 
                    <th>Όνομα Αρχείου</th> 
                    <th>Ημερομηνία Αλλαγής</th> 
    
                  </tr>   
                   
                   
               </thead>
               <tbody>
                  <?php
			   for  ($x=0 ; $x< count($full_info_files_array); $x++)
			   {
			   
			   ?>
               <tr>
                 <td>
                     <a href="<?php 
			$encoded_filename= ($full_info_files_array[$x][0]);
			$link = $page_directory . $path_of_files . ($encoded_filename);
                        echo $link;						?>"> Download </a>
						
                 </td>
                
                <td>
                     <?php echo ($full_info_files_array[$x][1]);  ?>
                    
                </td>
                   <td>
                      
			       <?php echo date ("F d Y H:i:s.", $full_info_files_array[$x][2]);  ?>
                   </td>
               </tr>
			   
			    <?php }?>
			   
           </tbody></table>
           
          <div id="pager" class="pager">
	<form>
		  <img src="http://mottie.github.com/tablesorter/addons/pager/icons/first.png" class="first"/> 
          <img src="http://mottie.github.com/tablesorter/addons/pager/icons/prev.png" class="prev"/> 
		<input type="text" class="pagedisplay"/>
		  <img src="http://mottie.github.com/tablesorter/addons/pager/icons/next.png" class="next"/> 
        <img src="http://mottie.github.com/tablesorter/addons/pager/icons/last.png" class="last"/> 
		<select class="pagesize">
			<option selected="selected"  value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option  value="40">40</option>
		</select>
	</form>
</div> 
         
      
<script type="text/javascript">

$( document ).ready(function() {
   
   var itemsCount = $("#files_list tbody tr").length;
  if (itemsCount==0) {
      $('#files_list').html('<h3> Δεν υπάρχουν αρχεία προς δημοσίευση </h3>');
      $('#pager').hide();
    
  }
   
  
});
	  


	  </script>

   Δημιουργία: <a href="http://users.sch.gr/chertour/" target="new">Kωνσταντίνος Χερτούρας </a> - Καθηγητής Πληροφορικής - ΕΠΑΛ Ροδόπολης Σερρών --- chertour at sch.gr  
   </body>
</html>
