



<?php
include 'common.php';
?>   
   <center><h3>Southington Movie Times</h3></center>
         <table border="1">
      <tr>
         <td colspan="6">
            <a href="<?php echo $theaters->infoURL; ?>" target="_new"><?php echo $theaters->name; ?></a> -- <?php echo $theaters->address; ?> (<a href="<?php echo $theaters->mapItURL; ?>" target="_new">map it</a>)
         </td>
      </tr>
         </table>
	<table width="100%" border="1" colspan="6">
	 <tr>
    
    <td>Title</td>
    <td>MPAA Rating</td>
    <td>Run Time</td>
    <td>Showings</td>
  </tr>
	 
   
<?php
   for ($i = 0, $iMax = count($movies); $i < $iMax; $i++) {
       ?>
      <tr>
         <?php
         // prevent putting links on when no url exists
         if ('' != $movies[$i]->URL) {
             ?>
         <td><a href="<?php echo $movies[$i]->URL; ?>" target="_new"><?php echo $movies[$i]->title; ?></a></td>
         <?php
         } else {
             ?>
         <td><?php echo $movies[$i]->title; ?></td>
         <?php
         } ?>
         <td><?php echo $movies[$i]->rating; ?></td>
         <td><?php echo $movies[$i]->runTime; ?></td>
         <td><?php echo $movies[$i]->showings; ?></td>
      </tr>
<?php
   }
?>
   </table>
   <table>
  <tr>
    <td width="100%" align="center" colspan="6"><a href="http://movies.yahoo.com/showtimes/showtimes.html?z=06489&r=sim">Other Area Theaters</a><br><font size="1"></font></td>
  </tr>
</table>
<table>

<tr>
    <td width="100%" align="center" colspan="6"><br>Original Script by <a href="http://www.woogu.com">Tony Ranieri</a><br><font size="1"></font></td></tr></table>

