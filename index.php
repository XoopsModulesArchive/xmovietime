<?php
// Hacked By John Mordo from the YahooMovieTimeRipper Script Written by Tony Ranieri of
// www.woogu.com With his Permission.
//This makes it wotrk in Xoops.
// let start including the header of xoops
include '../../mainfile.php'; // including top functions of xoops
//$GLOBALS['xoopsOption']['template_main'] = "movies.html"; // this line must be defined BEFORE //header.php, otherwise module will get threader like a x1 module (old style)
require XOOPS_ROOT_PATH . '/header.php'; // including the rest
// done, now your own files comes!

include 'mymovies.php';

// including footer of xoops
require XOOPS_ROOT_PATH . '/footer.php';
// done
