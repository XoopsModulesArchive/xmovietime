<?php
//*******************************************************************************************
//  YahooMovieTimeRipper.inc.php
//
// Author:  Tony Ranieri - April 09, 2004
// Version: 2.0
// Email:   tony@woogu.com  - Send me an email if you use this or have questions/comments :)
//
// Most recent version available at: http://www.woogu.com/
//
// Step 1:  Go to http://movies.yahoo.com/
//
// Step 2:  Enter zip code for area where you'd like to find movie data
//          for into the "Browse by Location" text box and press "Go!"
//
// Step 3:  Select the ONE theater you'd like to use to retrieve the
//          movie data from, and copy the URL from it (ie right click,
//          copy shortcut/copy link location)
//
// Step 4:  Create a new instance of the YahooMovieTimeRipper class as follows:
//          $a = new YahooMovieTimeRipper("http://movies.yahoo.com/showtimes/theater?id=976&date=20040410");
//
// Step 5:  Extract the date, movie, or theater info from that page using one
//          of the following three functions:
//             getDateInfo()
//             getMovieInfo()
//             getTheaterInfo()
//
//          To use one of these functions with the instance previously created,
//          simply do the following:
//             $movies = $a->getMovieInfo();
//
//          The above returned an array of all the movie data into the $movies array.
//
// Step 6:  Format the data in the array in your web page.
//
// For an actual example, please refer to the "demo.php" file included in this package.
//
// This is free to use/modify/whatever, but please give me credit :)
//
// Once again, I take no responsibility for anything stupid done with it, or that
// happens as a result of your own stupidity, ever.
//*******************************************************************************************

//**********************************************************************************************
// MovieInfo class
//    - stores movie data - title, url, rating, run time, showings...
//**********************************************************************************************
class MovieInfo
{
    public $title = 'N/A';        // Title of movie
   public $URL = 'N/A';          // URL to detailed movie data, on movies.yahoo.com
   public $rating = 'N/A';       // MPAA rating of the movie
   public $runTime = 'N/A';      // Length of movie
   public $showings = 'N/A';     // Times the movie plays at

   //*******************************************************************************************

    // Function:   movieInfo()

    // Purpose:    constructor

    //*******************************************************************************************

    public function __construct($title, $URL, $rating, $runTime, $showings)
    {
        $this->title = $title;

        $this->URL = $URL;

        $this->rating = $rating;

        $this->runTime = $runTime;

        $this->showings = $showings;
    }
}

//**********************************************************************************************
// TheaterInfo class
//    - stores theater info - theater name, address, map url, additional info url
//**********************************************************************************************
class TheaterInfo
{
    public $name = 'N/A';      // Name of the theater
   public $address = 'N/A';   // Address line one of the theater
   public $mapItURL = 'N/A';  // Map it URL, to display the yahoo map of the theater location
   public $infoURL = 'N/A';   // "Theater Info" link URL

   //*******************************************************************************************

    // Function:   theaterInfo()

    // Purpose:    constructor

    //*******************************************************************************************

    public function __construct($name, $addr1, $mapItURL, $infoURL)
    {
        $this->name = $name;

        $this->address = $addr1;

        $this->mapItURL = $mapItURL;

        $this->infoURL = $infoURL;
    }
}

//**********************************************************************************************
// DateInfo class
//    - stores dates to preview upcoming showtimes
//    - stores the URL to preview that info on Yahoo's site
//**********************************************************************************************
class DateInfo
{
    public $dateURL = 'N/A';   // date string appended to URL
   public $dateStr = 'N/A';   // string date representation

   //*******************************************************************************************

    // Function:   dateInfo()

    // Purpose:    constructor

    //*******************************************************************************************

    public function __construct($URL, $string)
    {
        $this->dateURL = $URL;

        $this->dateStr = $string;
    }
}

//**********************************************************************************************
// YahooMovieTimeRipper class
//    - does the main work getting source, parsing urls
//    - has functions for user to get the movie, theater, and date info
//**********************************************************************************************
class YahooMovieTimeRipper
{
    public $URL = 'N/A';       // the URL to get the data from
   public $rootURL = 'N/A';   // base url
   public $tableID = 'N/A';   // table id (after the # at the end of the URL)

   public $source = 'N/A';    // source retrieved from the URL - used in data extraction

    //*******************************************************************************************

    // Function:   YahooMovieTimeRipper

    // Purpose:    constructor for the class

    // YahooMovieTimeRipper(string url)

    //    - string URL for the table to use

    //*******************************************************************************************

    public function __construct($URL)
    {
        $this->URL = $URL;

        $this->initURL();

        $f = fopen($URL, 'rb');

        if ($f) {
            while ($pre = fread($f, 1000)) {
                $this->source .= $pre;
            }
        } else {
            echo 'Unable to open ' . $URL . '.';

            return false;
        }
    }

    //*******************************************************************************************

    // Function:   initURL()

    // Purpose:    take the URL and extact the table number and the root url

    //*******************************************************************************************

    public function initURL()
    {
        $length = mb_strlen($this->URL);

        $this->rootURL = mb_substr($this->URL, 0, ($length - 9));

        $this->tableID = mb_substr($this->URL, ($length - 2), $length);

        //$this->tableID = "T4";
    }

    //*******************************************************************************************

    // Function:   MovieInfo Array getMovieInfo()

    // Purpose:    extract movie data from give page

    //*******************************************************************************************

    public function getMovieInfo()
    {
        $showings = $this->source;

        // spilt each individual table

        $showings = preg_split('<!--  Print the name of the theater. -->', $showings);

        // find table for the specified theater

        //    remove the 'T' and take the number as array index

        $showings = $showings[$this->tableID[1]];

        // parse out just the show times, movie title, and ratings

        $showings = preg_match('<!-- /Address Row -->(.*)<!-- /Theater Table -->', $showings, $content);

        $showings = $content[0];

        // early cleanup of data

        $showings = preg_replace('<b>', '<br><br>', $showings);

        $showings = preg_replace('</b>', '', $showings);

        $showings = preg_replace('Click here for Showtimes', '', $showings);

        // split into individual movies

        $showings = preg_split('<td width="50%" class=ygfa>', $showings);

        // set the size of showings to var to prevent recalc'ing every loop iteration

        $size = count($showings);

        for ($i = 1; $i < $size; $i++) {
            // parse movie url

            $foobar = preg_match('<a href="(.*)"><br><br>', $showings[$i], $url);

            $foobar = preg_match('^http://(.*)?', $url[1], $url);

            // check that a url for the movie was found

            if ($foobar) {
                $url = $url[0];
            } else {
                $url = '';
            }

            $foo = strip_tags($showings[$i]);

            // parse movie title

            $foobar = eregi('^(.*)(rated|starts|buy)', $foo, $title);

            $title = $title[1];

            // parse rating and runtime info

            $foobar = eregi('rated(.*)(buy tickets:|&nbsp;)', $foo, $rating);

            if ($foobar) {
                $rating = $rating[1];
            } else {
                $rating = '';
            }

            // seperate runtime and rating

            $rating = preg_split(',', $rating);

            $runtime = $rating[1];

            $rating = $rating[0];

            // remove &nbsp; at start of runtime and the Buy Tickets: from the end

            if (mb_stristr($runtime, 'Buy Tickets:')) {
                $runtime = mb_substr($runtime, 6, (mb_strlen($runtime) - 18));
            }

            // remove &nbsp; at start of runtime and the Showtimes: from the end

            elseif (mb_stristr($runtime, 'Showtimes:')) {
                $runtime = mb_substr($runtime, 6, (mb_strlen($runtime) - 16));
            }

            // else runtime = nothing

            else {
                $runtime = '';
            }

            // extract show times - handle the different types differently...

            //    - different types are the options for buying tickets or not

            if (mb_stristr($foo, 'buy tickets:')) {
                $foobar = eregi('buy tickets:(.*)?', $foo, $showtimes);
            } elseif (mb_stristr($foo, 'showtimes:')) {
                $foobar = eregi('showtimes:(.*)?', $foo, $showtimes);
            } else {
                $showtimes = '';
            }

            $showtimes = $showtimes[1];

            // remove &nbsp; at start of showtimes list

            $showtimes = mb_substr($showtimes, 8, mb_strlen($showtimes));

            $movieInfoAr[$i] = new MovieInfo($title, $url, $rating, $runtime, $showtimes);
        }

        return $movieInfoAr;
    }

    //*******************************************************************************************

    // Function:   TheaterInfo Array getTheaterInfo()

    // Purpose:    extract theater info from a page

    //                - name, address, phone, map url, and more

    //*******************************************************************************************

    public function getTheaterInfo()
    {
        $theater = $this->source;

        // spilt each individual table

        $theater = preg_split('<!--  Print the name of the theater. -->', $theater);

        // find table for the specified theater

        //    remove the 'T' and take the number as array index

        $theater = $theater[$this->tableID[1]];

        // split off the top portion with the theater name and address

        $theater = preg_split('<!-- /Icon/Links Table -->', $theater);

        $theater = $theater[0];

        $address = preg_split('<!-- Address Row -->', $theater);

        $theater = $address[0];

        $address = $address[1];

        // split the address row into address and links

        $address = preg_split('<!-- Icon/Links Table -->', $address);

        $links = $address[1];

        $address = $address[0];

        // set the name and address

        $fooBar = preg_match('<b>(.*)</b>', $theater, $theater);

        $theater = $theater[1];

        $address = strip_tags($address);

        // extract links

        $foobar = preg_match('<a href="(.*)">Theater Info</a>', $links, $theaterInfo);

        $theaterInfoURL = 'http://movies.yahoo.com' . $theaterInfo[1];

        $foobar = preg_match("\| <a href=\"(.*)\">Map It</a>", $links, $mapIt);

        $mapItURL = $mapIt[1];

        $theaterInfo = new TheaterInfo($theater, $address, $mapItURL, $theaterInfoURL);

        return $theaterInfo;
    }

    //*******************************************************************************************

    // Function:   DateInfo array getDateInfo()

    // Purpose:    extract date from page as well as future days movie showtime data

    //*******************************************************************************************

    public function getDateInfo()
    {
        $dates = $this->source;

        // spilt each individual table

        $dates = preg_split('<!--  Print the name of the theater. -->', $dates);

        // find table for the specified theater

        //    remove the 'T' and take the number as array index

        $dates = $dates[0];

        // split in two to allow easy access of the drop down list

        $dates = preg_split('<!-- /End theater name table -->', $dates);

        $dates = $dates[1];

        // extract the select code

        $foobar = preg_match("<select name=\"date\" size=\"1\" onchange=dateselector.submit\(\)>(.*)</select>", $dates, $dates);

        $dates = $dates[1];

        // split dates into arrary elements by the HTML <option> tag(s)

        $dates = preg_split('<option', $dates);

        // for each array element:

        //    (EXCLUDING CURRENT DAY -- YOU ARE ALREADY VIEWING TODAY!!)

        //    make the date field = the value attribute of the <option>

        //    make the day/text field = the text (ie <option>TEXT</option>

        $size = count($dates);

        for ($i = 2; $i < $size; $i++) {
            // parse out the dateURL, and the date string

            $foobar = preg_match('value="(.*)"', $dates[$i], $url);

            $foobar = preg_match('>(.*)</option>', $dates[$i], $string);

            $url = $url[1];

            $string = $string[1];

            // append date string to the url

            $url = $this->rootURL . '&date=' . $url . '&nt=10#' . $this->tableID;

            $dateInfo[$i] = new dateInfo($url, $string);
        }

        return $dateInfo;
    }
}

   
   
