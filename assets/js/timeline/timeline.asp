<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Client Timeline</title>
    <meta charset="utf-8">
    <meta name="description" content="Project Timeline">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
 	<link href='https://fonts.googleapis.com/css?family=Advent+Pro:400,100,200,500,600' rel='stylesheet' type='text/css'>
    <script src="/js/jquery-1.8.0.min.js" type="text/javascript"></script>
	<script src="/js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>
    <!-- Style-->
    <style>
      html, body {
      height:100%;
      padding: 0px;
      margin: 0px;
      }
    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML elements--><!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  </head>
</html>
<body>
  <!-- BEGIN Timeline Embed -->
  <div id="timeline-embed"></div>
  <script type="text/javascript">
    var timeline_config = {
     width: "100%",
     height: "100%",
	 css:    '/css/timeline.css',     //OPTIONAL PATH TO CSS
     source: '/inc/client-timeline-json.asp'
    }
  </script>
  <script type="text/javascript" src="/js/timeline/storyjs-embed.js"></script>
  <!-- END Timeline Embed-->
</body>