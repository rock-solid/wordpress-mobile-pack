<?php


/**
 * Based on: http://sachachua.com/blog/2011/08/drupal-html-purifier-embedding-iframes-youtube/
 * Iframe filter that does some primitive whitelisting in a somewhat recognizable and tweakable way
 */
class HTMLPurifier_Filter_Iframe extends HTMLPurifier_Filter
{
    public $name = 'Iframe';

    /**
     *
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function preFilter($html, $config, $context)
    {
        
		$html = preg_replace('#<iframe#i', '<img class="Iframe"', $html);
        $html = preg_replace('#</iframe>#i', '', $html);
	  
        return $html;
    }

    /**
     *
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function postFilter($html, $config, $context)
    {
       
		$post_regex = '#<img class="Iframe"([^>]+?)>#';
        return preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
    }

     /**
     * @param array $matches
     * @return string
     */
    protected function postFilterCallback($matches)
    {
        // Domain Whitelist
        $Match = preg_match('%src="(https?:)?(http?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player.vimeo.com|www\.dailymotion.com|w.soundcloud.com|fast.wistia.net|fast.wistia.com|wi.st|'.$_SERVER['HTTP_HOST'].')%', $matches[1]);
        
		if ($Match) {
            $extra = ' frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen';
            
            return '<iframe ' . $matches[1] . $extra . '></iframe>';
        } else {
            return '';
        }
    }
}

