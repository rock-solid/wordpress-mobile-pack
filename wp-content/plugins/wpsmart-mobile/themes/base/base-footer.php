<!-- Analytics code -->
<?php if( wps_get_option( 'analytics_type' ) == 'google_analytics' ) : wps_google_analytics_script( wps_get_option( 'google_analytics_code' ) );  ?>
<?php elseif( wps_get_option( 'analytics_type' ) == 'custom_analytics' ) : echo wps_html_unclean( wps_get_option( 'custom_analytics_code' ) ); ?>
<?php endif; ?>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-35007573-2', 'wpsmart.com');
  ga('send', 'pageview');
  ga('set', 'dimension1', document.domain);
</script>

</body>
</html>