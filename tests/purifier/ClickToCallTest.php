<?php

require_once(PWA_PLUGIN_PATH.'inc/class-wmp-formatter.php');

class ClickToCallTest extends WP_UnitTestCase
{

    public $purifier;


    public function setUp()
    {
        parent::setUp();

        $this->purifier = WMobilePack_Formatter::init_purifier();
    }


    public function test_callto(){

        $input = '<a href="callto:+0722659181">test click to call</a>';
        $output = $input;

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_tel(){

        $input = '<a href="tel:+0722659181">test click to call</a>';
        $output = $input;

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_skype(){

        $input = '<a href="skype:alexandra.ac?call">test click to call</a>';
        $output = $input;

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_sms(){

        $input = '<a href="sms:+0722659171">test click to call</a>';
        $output = $input;

        $this->assertEquals($this->purifier->purify($input), $output);
    }

    public function test_whatsapp(){

        $input = '<a href="whatsapp://send?text=Hello%20World!">test click to call</a>';
        $output = $input;

        $this->assertEquals($this->purifier->purify($input), $output);
    }
}