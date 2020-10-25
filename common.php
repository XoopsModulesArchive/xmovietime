<?php
/*
 * Modularized (sort of) for Xoops By John Mordo from the YahooMovieTimeRipper Script Written
 *by Tony Ranieri of
 * www.woogu.com With his Permission.
 * This file was created to break-up the original script for easier usage.
 *I took the code in this file out of the mymovies.php file and replaced it with an include     *statement.
 *Replace the URL below with the URL that represents the theater you want to list.
 *get it from Yahoo(see included readme.txt file for directions)
 * obtain the info for the script
 */
require_once 'functions.php';

   // include the YahooMovieTimeRipper file into your code
   require_once __DIR__ . '/functions.php';

   // create a new instance of the YahooMovieTimeRipper
   $a = new YahooMovieTimeRipper('http://movies.yahoo.com/showtimes/showtimes.html?z=06489&sim#T1');

   // get the date, movie, and theater info
   $dates = $a->getDateInfo();
   $movies = $a->getMovieInfo();
   $theaters = $a->getTheaterInfo();

   // display


