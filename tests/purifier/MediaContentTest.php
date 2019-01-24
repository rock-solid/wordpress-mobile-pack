<?php

require_once(PWA_PLUGIN_PATH.'inc/class-wmp-formatter.php');

class MediaContentTest extends WP_UnitTestCase
{

    public $purifier;

    public function setUp()
    {
        parent::setUp();

        $this->purifier = WMobilePack_Formatter::init_purifier();
    }

    public function test_paragraph(){

        $input = '<p width="100" style="color:red; width: 100%;" class="paragraph">This is a text inside a paragraph.</p><br><br/>';
        $output = '<p style="color:#FF0000;width:100%;">This is a text inside a paragraph.</p><br /><br />';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_image(){

        $input = '<img src="test.jpg" width="100" height="100" class="imagestyling" style="color:red" />';
        $output = '<img src="test.jpg" width="100" height="100" style="color:#FF0000;" alt="test.jpg" />';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_responsive_image(){

        $input = '<img width="300" height="580" src="http://dummyblog.appticles.com/wp-content/uploads/2013/03/featured-image-vertical.jpg" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="Vertical Featured Image" srcset="http://dummyblog.appticles.com/wp-content/uploads/2013/03/featured-image-vertical.jpg 300w, http://dummyblog.appticles.com/wp-content/uploads/2013/03/featured-image-vertical-155x300.jpg 155w" sizes="(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px" />';
        $output = '<img width="300" height="580" src="http://dummyblog.appticles.com/wp-content/uploads/2013/03/featured-image-vertical.jpg" srcset="http://dummyblog.appticles.com/wp-content/uploads/2013/03/featured-image-vertical.jpg 300w, http://dummyblog.appticles.com/wp-content/uploads/2013/03/featured-image-vertical-155x300.jpg 155w" sizes="(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px" alt="featured-image-vertical.jpg" />';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_links(){

        $input = '<a href="http://www.appticles.com" target="_blank" style="display:none; color:green;" class="linkstyling">link here</a><a href="tel:234234324" target="_blank" class="linkstyling" style="display:none; color:green;">link here</a>';
        $output = '<a href="http://www.appticles.com" target="_blank" style="display:none;color:#008000;">link here</a><a href="tel:234234324" target="_blank" style="display:none;color:#008000;">link here</a>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }


    public function test_tables(){

        $input = '<table class="divclass" style="background-color:red"><tr><td>test table</td></tr></table>';
        $output = 'test table';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_script(){

        $input = '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>
        StackExchange.ready(function () {

                StackExchange.using("snippets", function () {
                    StackExchange.snippets.initSnippetRenderer();
                });
        })
		</script>';

        $output = '';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_divs(){

        $input = '<div class="divclass" style="background-color:red">red text</div>';
        $output = '<div style="background-color:#FF0000;">red text</div>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }


    public function test_formatting(){

        $input = '<blockquote> this is a quote</blockquote><span class="divclass" style="background-color:red"> this is a span</span><h1> this is a span</h1><em class="divclass" style="background-color:red"> this is a span</em><i class="divclass" style="background-color:red"> this is a span</i><strong class="divclass" style="background-color:red"> this is a span</strong><b class="divclass" style="background-color:red"> this is a span</b>';

        $output = '<blockquote> this is a quote</blockquote><span style="background-color:#FF0000;"> this is a span</span><h1> this is a span</h1><em style="background-color:#FF0000;"> this is a span</em><i style="background-color:#FF0000;"> this is a span</i><strong style="background-color:#FF0000;"> this is a span</strong><b style="background-color:#FF0000;"> this is a span</b>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_youtube(){

        $input = '<iframe width="560" height="315" src="https://www.youtube.com/embed/MYSVMgRr6pw" frameborder="0" allowfullscreen=""></iframe>';
        $output = '<iframe width="560" height="315" src="https://www.youtube.com/embed/MYSVMgRr6pw" frameborder="0" allowfullscreen=""></iframe>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_vimeo(){

        $input = '<iframe src="https://player.vimeo.com/video/119119605" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="https://vimeo.com/119119605">Appticles.com - Join Us!</a> from <a href="https://vimeo.com/appticles">Appticles</a> on <a href="https://vimeo.com">Vimeo</a>.</p>';
        $output = '<iframe src="https://player.vimeo.com/video/119119605" width="500" height="281" frameborder="0" allowfullscreen=""></iframe> <p><a href="https://vimeo.com/119119605">Appticles.com - Join Us!</a> from <a href="https://vimeo.com/appticles">Appticles</a> on <a href="https://vimeo.com">Vimeo</a>.</p>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_flickrit(){

        $input = '<iframe frameborder="0" scrolling="no" src="http://flickrit.com/slideshowholder.php?height=100&size=big&setId=72157650960639108&theme=1&thumbnails=0&transition=0&layoutType=responsive&sort=0" style="width: 100%; height: 100%; min-width: 100%; min-height: 150px;" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        $output = '<iframe frameborder="0" scrolling="no" src="http://flickrit.com/slideshowholder.php?height=100&amp;size=big&amp;setId=72157650960639108&amp;theme=1&amp;thumbnails=0&amp;transition=0&amp;layoutType=responsive&amp;sort=0" style="width:100%;height:100%;min-width:100%;min-height:150px;" allowfullscreen=""></iframe>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_soundcloud(){

        $input = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/197166150&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>';
        $output = '<iframe width="100%" height="450" scrolling="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/197166150&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_dailymotion(){

        $input = '<iframe frameborder="0" width="480" height="270" src="//www.dailymotion.com/embed/video/x2k42wg?autoPlay=1&start=300" allowfullscreen></iframe><br /><a href="http://www.dailymotion.com/video/x2k42wg_arsenal-stars-take-on-saracens_sport" target="_blank">Arsenal stars take on Saracens!</a> <i>de <a href="http://www.dailymotion.com/WorldRugby" target="_blank">WorldRugby</a></i>';
        $output = '<iframe frameborder="0" width="480" height="270" src="//www.dailymotion.com/embed/video/x2k42wg?autoPlay=1&amp;start=300" allowfullscreen=""></iframe><br /><a href="http://www.dailymotion.com/video/x2k42wg_arsenal-stars-take-on-saracens_sport" target="_blank">Arsenal stars take on Saracens!</a> <i>de <a href="http://www.dailymotion.com/WorldRugby" target="_blank">WorldRugby</a></i>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_wistia(){

        $input = '<iframe frameborder="0" width="480" height="270" src="//www.dailymotion.com/embed/video/x2k42wg?autoPlay=1&start=300" allowfullscreen></iframe><br /><a href="http://www.dailymotion.com/video/x2k42wg_arsenal-stars-take-on-saracens_sport" target="_blank">Arsenal stars take on Saracens!</a> <i>de <a href="http://www.dailymotion.com/WorldRugby" target="_blank">WorldRugby</a></i>';
        $output = '<iframe frameborder="0" width="480" height="270" src="//www.dailymotion.com/embed/video/x2k42wg?autoPlay=1&amp;start=300" allowfullscreen=""></iframe><br /><a href="http://www.dailymotion.com/video/x2k42wg_arsenal-stars-take-on-saracens_sport" target="_blank">Arsenal stars take on Saracens!</a> <i>de <a href="http://www.dailymotion.com/WorldRugby" target="_blank">WorldRugby</a></i>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_spreaker(){

        $input = '<iframe src="//www.spreaker.com/embed/player/standard?autoplay=true&amp;episode_id=7523544" style="width:100%;height:131px;min-width:250px;" frameborder="0" scrolling="no"></iframe>';

        $this->assertEquals($this->purifier->purify($input), $input);
    }

    public function test_instagram(){

        $input = '<iframe src="http://instagram.com/p/a1wDZKopa2/embed" width="400" height="480" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
        $output = '<iframe src="http://instagram.com/p/a1wDZKopa2/embed" width="400" height="480" frameborder="0" scrolling="no"></iframe>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_spotify(){

        $input = '<iframe src="https://embed.spotify.com/?uri=spotify%3Aalbum%3A09wBB2tZNMY9WhIhvx5OSx" width="300" height="380" frameborder="0" allowtransparency="true"></iframe>';
        $output = '<iframe src="https://embed.spotify.com/?uri=spotify%3Aalbum%3A09wBB2tZNMY9WhIhvx5OSx" width="300" height="380" frameborder="0"></iframe>';

        $this->assertEquals($this->purifier->purify($input), $output);

        $input = '<iframe src="https://embed.spotify.com/?uri=spotify:track:7Hms7xBu3FJqbruhShB2zt?play=true&#038;utm_source=open.spotify.com&#038;utm_medium=open" width="300" height="380" frameborder="0" allowtransparency="true"></iframe>';
        $output = '<iframe src="https://embed.spotify.com/?uri=spotify:track:7Hms7xBu3FJqbruhShB2zt?play=true&amp;utm_source=open.spotify.com&amp;utm_medium=open" width="300" height="380" frameborder="0"></iframe>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_youko()
    {
        $input = '<iframe height = 498 width = 510 src = "http://player.youku.com/embed/XMTUzMzc2MjE2MA==" frameborder = 0 allowfullscreen ></iframe>';
        $output = '<iframe height="498" width="510" src="http://player.youku.com/embed/XMTUzMzc2MjE2MA==" frameborder="0" allowfullscreen=""></iframe>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

	public function test_giggle_tourism()
	{
		$input = '<iframe height = 498 width = 510 src = "http://app.giggle-tourism.com/locations" frameborder = 0 allowfullscreen ></iframe>';
		$output = '<iframe height = 498 width = 510 src = "http://app.giggle-tourism.com/locations" frameborder = 0 allowfullscreen ></iframe>';
	}

    public function test_video(){

        $input = '<video width="320" height="240" controls><source src="movie.mp4" type="video/mp4"><source src="movie.ogg" type="video/ogg">Your browser does not support the video tag.</video>';
        $output = '<video width="320" height="240" controls=""><source src="movie.mp4" type="video/mp4"><source src="movie.ogg" type="video/ogg">Your browser does not support the video tag.</source></source></video>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_audio(){

        $input = '<audio controls><source src="horse.ogg" type="audio/ogg"><source src="horse.mp3" type="audio/mpeg">Your browser does not support the audio tag.</audio>';
        $output = '<audio controls=""><source src="horse.ogg" type="audio/ogg"><source src="horse.mp3" type="audio/mpeg">Your browser does not support the audio tag.</source></source></audio>';

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_min_width_height_and_css_important(){

        $input = '<p style="min-width:100%; min-height:50% !important;" class="paragraph">This is a text inside a paragraph.</p><br><br/>';
        $output = '<p style="min-width:100%;min-height:50%;">This is a text inside a paragraph.</p><br /><br />';

        $this->assertEquals($this->purifier->purify($input), $output);
    }
}
