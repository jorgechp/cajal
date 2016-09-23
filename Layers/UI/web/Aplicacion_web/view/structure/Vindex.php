    <!DOCTYPE html>
    <html lang="en">
    <head>
{CONTENT_HEAD}
    </head>
    <body>
	
    <div class="navbar">
      <div class="navbar-inner">	
          <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="index.php?myProfile"><img class="logo_image" src="{AVATAR_URL}" alt="#PROFILE#"/></a><img src="{IMG_FOLDER_ROOT}/../icons/navIcons/role_{USER_ROL}.png" alt="{USER_ROL}" title="#CURRENT_ROLE_{USER_ROL}#">
          <ul class="nav nav-collapse pull-right">
		{CONTENT_NAVMENU}
          </ul>

          <!-- Everything you want hidden at 940px or less, place within here -->

        </div>
		
                    {CONTENT_NAVMENUFILTERS}
		
      </div>
	<div  >
		<a class="help_modal_button" id="help_modal_button" href="#help_modal">?</a>
	</div>
	
    <div class="help_modal_mask" id="help_modal">
	<div class="help_modal_box">
		<a href="#help_modal_close" title="Close" class="help_modal_close">X</a>
                <h2>{HELP_CONTEXT_TITLE}</h2>
                <p>{HELP_CONTEXT}</p>
	</div>	
    </div>
    </div>

    <!--Profile container-->
    <div class="container profile">  
	{CONTENT_MAIN}
    </div>

    <!--END: Profile container-->

<!-- Help menu -->

<!-- END: Help menu -->
    <!-- Footer -->
    <div class="footer">
        {CONTENT_FOOTER}
    </div>
    <!-- Contact form in Modal -->
    <!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><i class="icon-mail"></i> Contact Me</h3>
      </div>
      <div class="modal-body">
        <form action="index.php?contact" method="POST" class="form">

          <input type="text" placeholder="#SUBJECT#" name="CONTACT_SUBJECT">
           <textarea rows="6" cols="470" name="CONTACT_TEXT" placeholder="#MESSAGE#">  
            
           </textarea>
        <p>
                     <select name="CONTACT_TYPE_NOTIFICATION">
        <option value="0">Motivo...</option>                 
        <option value="1">Sugerencia</option>
        <option value="2">Problema</option>
        </select>
        </p>
          <br/>
          <button type="submit" class="btn btn-large"><i class="icon-paper-plane"></i> SUBMIT</button>
        </form>
      </div>
    </div>

	
    </body>
    </html>
