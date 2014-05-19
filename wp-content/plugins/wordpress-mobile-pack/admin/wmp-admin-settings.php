<?php 


global $wmobile_pack; 
?>

<script src="http://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
	$('.custom-upload input[type=file]').change(function(){
		$(this).next().find('input').val($(this).val());
	});
</script>

<div id="wmpack-admin">
	<div class="spacer-20"></div>
    <!-- set title -->
    <h1>SETTINGS</h1>
	<div class="spacer-20"></div>
	<div class="settings">
        <div class="left-side">
            <!-- add nav menu -->
            <nav class="menu">
                <ul>
                    <li><a href="#">Look & Feel</a></li>
                    <li><a href="#">Content</a></li>
                    <li class="selected"><a href="#">Settings</a></li>
                    <li><a href="#">Upgrade</a></li>
                </ul>
            
            </nav>
          <div class="spacer-0"></div>
            <!-- add settings -->
            <div class="details">
            	<div class="spacer-10"></div>
            	<p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Vis an solet ocurreret, sit laudem semper perfecto ex, vix an nibh tacimates. Ne usu duis ignota oblique.</p>
            	<div class="spacer-20"></div>
            </div>
            <div class="spacer-15"></div>
            <div class="details offline">
            	<div class="offline-mode">
                 	<p>Offline mode </p>
                    <div class="spacer-20"></div>
                 	<!-- add radio buttons -->
                    <input type="radio" name="offline" id="on" disabled="disabled" /><label for="on">On</label>
                    <div class="spacer-10"></div>
                    <input type="radio" name="offline" id="off" checked="checked" disabled="disabled" /><label for="off">Off</label>
                </div>
                <div class="waitlist">
                	<div class="spacer-20"></div>
                    <div class="spacer-20"></div>
                	 <a class="btn blue smaller" href="#">Join Waitlist</a>
                     <div class="spacer-0"></div>
                     <p>and get notified when available</p>
                </div>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-15"></div>
            <div class="details">
            	<div class="display-mode">
                 	<p>Display mode</p>
                    <div class="spacer-20"></div>
                    <form name="display_form" action="" method="post">
                        <!-- add radio buttons -->
                        <input type="radio" name="display_mode" id="display_mode_normal" value="normal" /><label for="display_mode_normal">Normal(visible to all users)</label>
                        <div class="spacer-10"></div>
                        <input type="radio" name="display_mode" id="display_mode_preview" value="preview" /><label for="display_mode_preview">Preview(visible to admin only)</label>
                        <div class="spacer-10"></div>
                        <input type="radio" name="display_mode" id="display_mode_disabled" value="disabled" /><label for="display_mode_disabled">Disabled(not visible)</label>
                		<div class="spacer-20"></div>
                        <a class="btn green smaller" href="#">Save</a>
                    </form>
                </div>
                <div class="spacer-20"></div>
            </div>
            <div class="spacer-15"></div>
            <div class="details branding">
                <h2 class="title">Custom Branding</h2>
                <div class="spacer_15"></div>
                <div class="grey-line"></div>
                <div class="spacer-15"></div>
                <div class="spacer-20"></div>
               	<form name="branding_form" action="" method="post">
                    <label for="branding_logo">Upload your logo</label>
                   <div class="custom-upload">
                        <input type="file" id="branding_logo" name="branding_logo" />
                        <div class="fake-file">
                            <input disabled="disabled" />
                            <a href="#" class="btn grey smaller">Browse</a>
                        </div>
                    </div>
                   
                   <div class="spacer-20"></div>
                   <!-- if image is added display second box type -->
                   <div class="display-logo">
                   	   <label for="branding_icon">App icon</label>
                       <img src="resources/images/app-icon.png" />
                       <a href="#" class="btn grey smaller">Change</a>
                   </div>
                   <div class="spacer-20"></div>
                   <a class="btn green smaller" href="#">Save</a>
                   
                </form>
            </div>
        </div>
    
        <div class="right-side">
            <!-- add news and updates -->
            <div class="updates">
                <h2>News & Updates</h2> 
                <div class="spacer-20"></div>
                <div class="details">
                    <!-- start news and updates -->
                    <p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Ne usu duis ignota oblique. <a href="#" target="_blank" title="read more">read more</a></p> 
                    <div class="spacer-20"></div>
                    <div class="grey-dotted-line"></div>
                    <div class="spacer-20"></div>
                    <p>Lorem ipsum dolor sit amet, nec accusamus assentior in, per ea probo percipit ullamcorper. An mel animal menandri vituperata. Ne usu duis ignota oblique. <a href="#" target="_blank" title="read more">read more</a></p> 
                        
                </div>
            </div>
            <div class="spacer-5"></div>
            <!-- add appticles social -->
            <div class="appticles-updates">
                <!-- add content -->
            	<div><p>Appticles Updates</p></div>
                <div class="social">
                	<a class="facebook"></a>
                    <a class="twitter"></a>
                    <a class="google-plus"></a>
                </div>
            </div>
            <div class="spacer-15"></div>
            <!-- add whitepaper -->
            <div class="white-paper"><p>White paper</p></div>
            <div class="spacer-15"></div>
			<!-- add newsletter box -->
      <div class="form-box">
                <h2>Join our newsletter</h2>
                <div class="spacer-10"></div>
                <p>Receive monthly freebies, Special Offers & Access to Exclusive Subscriber Content.</p>
                <div class="spacer-0"></div>
				<form id="newsletter" name="" action="" method="post">
                    <input type="hidden" name="" id="" placeholder="the-email address of the admin" class="small" />
                    <a class="btn green smaller" href="#">Subscribe</a>
                </form>
            </div>
		    <div class="spacer-15"></div>
        <!-- add feedback form -->
            <div class="form-box">
                <h2>Give us your feedback</h2>
                <div class="spacer-20"></div>
                <form id="" name="" action="" method="post">
                    <input type="hidden" name="" id="" placeholder="the-email address of the admin" class="small" />
                    <textarea name="feedback_message" id="feedback_message" placeholder="Your message" class="small"></textarea>
                    <div class="spacer-5"></div>
                    <a class="btn green smaller" href="#">Send</a>
                </form>
            </div>
        </div>
	</div>


</div>

