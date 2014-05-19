<?php get_header(); ?>
<div class="main_body_mobile">           
	<div class="wrapper">
		<div class="ui-body ui-body-c">
              		
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                        
                            <td valign="top" style="width:100%;">
                                <div id="container">
                                    <br />
                                    <h2 style="padding-left:20px;">404 Error</h2>
                                    <div class="entry">
                                        <p style="padding-left:20px;"> This page does not exist.</p>
                                        <p style="padding-left:20px;"> Please try one of the following:</p>
                                        <ul>
                                            <li>Hit the "back" button on your browser.</li>
                                            <li>Head on over to the <a href="<?php bloginfo('url'); ?>">front page</a>.</li>
                                            <li>Try searching using the form in the sidebar.</li>
                                            <li>Click on a link in the sidebar.</li>
                                            <li>Use the navigation menu at the top of the page.</li>
                                        </ul>
                                    </div>
                                </div>
                            </td>    
						</tr><tr>                        
								
        					<td valign="top" style="width:100%;"><?php get_sidebar(); ?></td>
                                        	
                         </tr>
                    </tbody>
                    </table>    
		</div>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>        