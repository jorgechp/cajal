<!DOCTYPE
 html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
{CONTENT_HEAD}

</head>

<body>

<div id="wrapper">
	<div id="content">
    
    	<div id="header">
            <h1>
                <img src="{LOGO_URL}" alt="logo">
        	<a href="index.php">{SYSTEM_NAME}</a>
            </h1>            
        </div><!-- header -->
        
        <div id="menu">
        	<ul>	
		{CONTENT_NAVMENU}
            </ul>
        </div><!--menu-->
        
       <div id="container">
       
        <div id="sidebar">
            <div>
        
{CONTENT_NAVMENUFILTERS}
            </div>
        </div><!--sidebar-->
        
        <div id="main">
 {CONTENT_MAIN}
        </div><!--main-->

     
        
        	<div style="clear:both;"></div>
       </div><!--container-->
       

        
        <div id="footer">

        {CONTENT_FOOTER}
        </div><!--footer-->
        <div>
            {CHANGE_ROL}
        </div>
	 </div><!-- content -->

	</div><!-- wrapper -->




 </body>
</html>
