<?php

	// Include dbtoolkit layout core
	include_once "libs/layout.php";
	include_once "libs/utils.php";


?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>DB-Toolkit Layout Engine Demo</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap-dropdown.js"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link href="bootstrap/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
      }
    </style>

  </head>

  <body>
      <div class="container">
      <?php

        //$str = '480[480|240:240]:240[120:120|240|80:80:80|200:40]|720|720[120:120:120:240:120]';

        $para = '<h2>Heading <small>sub-heading</small></h2>
          <p>Etiam porta sem malesuada magna mollis euismod. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>';
        //$str = '960|240:720';

        $title = '<div class="hero-unit">
        <h1>DB-Toolkit</h1>
        <p>Build database interfaces.</p>
        <p><a class="btn primary large">Learn more &raquo;</a></p>
      </div>';

        
        $page = new Layout();
        
        $page->html($para, 2);
        $page->html($title, 0);

        //add some tabs
        $page->html('<ul class="tabs">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Messages</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="#">Contact</a></li>
        </ul>', 4);

        $page->html("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent quis lorem eget massa malesuada euismod. Cras dolor purus, semper eu lobortis eu, egestas non lacus. Morbi eget eros ligula, id imperdiet dolor. Nullam nisi erat, egestas vitae faucibus quis, tempus ac lectus. Aenean tellus lectus, ornare sed faucibus vel, porta nec nunc. Morbi enim diam, placerat quis posuere in, dictum ac massa. Donec quis quam nunc. Curabitur rutrum, nulla eu rhoncus hendrerit, erat velit blandit libero, eget egestas ante felis eget arcu. Integer tincidunt tincidunt velit eu pharetra. Curabitur et aliquam nisl. Aenean interdum mauris non enim congue quis condimentum massa dictum.

Aliquam erat volutpat. Quisque eleifend rutrum sollicitudin. Sed et ipsum turpis, vitae laoreet lacus. Fusce eget semper nulla. Fusce aliquet est et enim vulputate ac molestie lorem dictum. Integer vitae ipsum sit amet tortor ultrices vulputate. Etiam elementum, eros vel suscipit ornare, urna nisi dignissim ipsum, a vestibulum leo quam a mauris. Pellentesque feugiat, elit a ultrices euismod, nisl nulla sagittis orci, vel elementum ipsum quam nec ligula. Integer ultricies tempus pharetra. ", 5);

        // Reset the layout so we can add another row for the nav and footer
        $page->setLayout('960|960|240:720[720:720]|960|960');

        // Add a footer
        $page->html('<hr /><div>DB-Toolkit Layout Generator</div>', 8);

        // Add the top navigation menu
        $page->html('<div style="z-index: 5;" class="topbar-wrapper">
    <div data-dropdown="dropdown" class="topbar">
      <div class="topbar-inner">
        <div class="container">
          <h3><a href="#">Project Name</a></h3>
          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" href="#">Dropdown</a>
              <ul class="dropdown-menu">
                <li><a href="#">Secondary link</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Another link</a></li>
              </ul>
            </li>
          </ul>
          <form action="" class="pull-left">
            <input type="text" placeholder="Search">
          </form>
          <ul class="nav secondary-nav">
            <li class="dropdown">
              <a class="dropdown-toggle" href="#">Dropdown</a>
              <ul class="dropdown-menu">
                <li><a href="#">Secondary link</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Another link</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div><!-- /topbar-inner -->
    </div><!-- /topbar -->
  </div>', 7);




        // Render page layout
        echo $page->renderLayout();




      ?>

      </div>
      
    <script>
        $(function() {
              $().dropdown()
        });

    </script>
  </body>
</html>