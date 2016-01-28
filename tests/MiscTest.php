<?php

/**
 * Class MiscTest
 *
 * @group integrationTests
 */
class MiscTest extends WP_Ajax_UnitTestCase {

    protected $old_current_user;

    function test_feedback_form(){

        // Become an administrator
        $this->_setRole( 'administrator' );

        $_POST['wmp_feedback_page'] = 'Settings';
        $_POST['wmp_feedback_name'] = 'Test user';
        $_POST['wmp_feedback_email'] = 'anghel.ac@gmail.com';
        $_POST['wmp_feedback_message'] = 'Unit testing for feedback form';

        // Make the request
        try {
            $this->_handleAjax( 'wmp_send_feedback' );
        } catch ( WPAjaxDieContinueException $e ) {
            unset( $e );
        }
    }
}
