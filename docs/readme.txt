Version 2.1 fixes xmovietime to work with Yahoo format change.
couldn't get the "future dates" part to work properly so I removed it.
Trying to separate title and ratings and runtime, but not there yet.
I also included the original YahooMovieTimewRipper script.






1. Go to Yahoo.com
2. Click on Movies
3. Enter your zipcode in the right box and click Go (You will need this URL later in step 9)
4. Find the theater you want to list and click on it.
5. Copy that URL
6. Go to the common.php file and replace 
   
   ("http://movies.yahoo.com/showtimes/showtimes.html?z=06489&sim#T1");
   
   on line 18

   with the URL you copied.

7. go to the mymovies.php file
8. find line 56 or so
9. replace

http://movies.yahoo.com/showtimes/showtimes.html?z=06489&r=sim

with the URL of the page you got when you typed in your zipcode on Yahoo (step 3)
10. That's It.

The original Author has asked that a link back to him be placed in the site you are using it on yours.
