---
extends: _layouts.post
title: CodeIgniter Form with Text CAPTCHA
date: 2011-01-14
categories: [codeigniter]
---
> "A CAPTCHA (Completely Automated Public Turing Test To Tell Computers and Humans Apart) is a program that protects websites against bots by generating and grading tests that humans can pass but current computer programs cannot."
>
> - [captcha.net](http://captcha.net)

The current CAPTCHA system I started using is [Text CAPTCHA](http://textcaptcha.com). The Text CAPTCHA web service generates text-based CAPTCHAs which is a single question that can be easily solved by humans but cannot be solved by a robot. Since I added comments functionality to ThePollPlace a few weeks ago, I've been noticing lots of spam. Lots meaning, thousands of spam comments. So I implemented Text CAPTCHA because it was simple and easy to do. Now, I'll show you how to implement it into a form using [CodeIgniter](http://codeigniter.com). First I'll show you the code, then I'll explain it.

```
// application/controllers/example.php
class Example extends Controller {

    function Example() {
        parent::Controller();
    }

    function index() {
		// load libraries
		$this->load->library( array( 'session', 'form_validation' ) );

		// load helper
		$this->load->helper( 'form' );

        // setup form validation
        $this->form_validation->set_rules( 'name', 'name', 'required' );
        $this->form_validation->set_rules( 'email', 'email', 'valid_email' );
        $this->form_validation->set_rules( 'url', 'url', 'prep_url' );
        $this->form_validation->set_rules( 'captcha', 'captcha', 'required|callback_check_captcha' );
        $this->form_validation->set_rules( 'comment', 'comment', 'required' );

        if ( $this->form_validation->run() ) {
            // create comment
            die('Validated!');
        }

        // setup textCAPTCHA
        try {
            $xml = @new SimpleXMLElement( 'http://textcaptcha.com/api/your_api_key', NULL, TRUE) ;
        } catch ( Exception $e ) {
            $fallback = '';
            $fallback .= 'Is ice hot or cold?';
            $fallback .= '' . md5( 'cold' ) . '';
            $fallback .= '';
            $xml = new SimpleXMLElement( $fallback );
        }

        // store answers in session for use later
        $answers = array();
        foreach ( $xml->answer as $hash ) {
            $answers[] = (string)$hash;
        }
        $this->session->set_userdata( 'captcha_answers', $answers );

        // load vars into view
        $this->load->vars( array( 'captcha' => (string)$xml->question ) );

        // load the view
        $this->load->view( 'example' );
    }

    function check_captcha( $string ) {
        $user_answer = md5( strtolower( trim( $string ) ) );
        $answers = $this->session->userdata( 'captcha_answers' );

        if( in_array( $user_answer, $answers ) ) {
            return TRUE;
        } else {
            $this->form_validation->set_message( 'check_captcha', 'Your answer was incorrect!' );
            return FALSE;
        }
    }
}
```

```
// application/views/example.php
<h2>Leave a Comment</h2>
<?php echo form_open( '/example/index/' ); ?>

<div class="textfield">
    <?php echo form_label( 'Name', 'name' ); ?>
    <?php echo form_error( 'name' ); ?>
    <?php echo form_input( 'name' ); ?>
</div>

<div class="textfield">
    <?php echo form_label( 'Email', 'email' ); ?>
    <?php echo form_error( 'email' ); ?>
    <?php echo form_input( 'email' ); ?>
</div>

<div class="textfield">
    <?php echo form_label( 'Url', 'url' ); ?>
    <?php echo form_error( 'url' ); ?>
    <?php echo form_input( 'url' ); ?>
</div>

<div class="textfield">
    <?php echo form_label( $captcha, 'captcha' ); ?>
    <?php echo form_error( 'captcha' ); ?>
    <?php echo form_input( 'captcha' ); ?>
</div>

<div class="textarea">
    <?php echo form_label( 'Comment', 'comment' ); ?>
    <?php echo form_error( 'comment' ); ?>
    <?php echo form_textarea( 'comment' ); ?>
</div>

<div class="buttons">
    <button type="submit" name="submit" value="submit">Submit Comment</button>
</div>
<?php echo form_close(); ?>
```

In order for Text CAPTCHA to do its job, we need to add a new form field for the question to be answered. I pass the question to the view as `$captcha`.

```
<div class="textfield">
    <?php echo form_label( $captcha, 'captcha' ); ?>
    <?php echo form_error( 'captcha' ); ?>
    <?php echo form_input( 'captcha' ); ?>
</div>
```

Using CodeIgniter's [Form Validation library](http://codeigniter.com/user_guide/libraries/form_validation.html), we need to add a new rule for the CAPTCHA. Notice we also have defined a call back: `callback_check_captcha`. We will use this callback to write a custom validation function to make sure our CAPTCHA was answered correctly

```
$this->form_validation->set_rules( 'captcha', 'captcha', 'required|callback_check_captcha' );
```

Next we need to call the Text CAPTCHA service to get our random question. You will need to [register](http://textcaptcha.com/register) to receive your api key. Be sure to replace `your_api_key` with the key you receive. This try-catch statement will attempt to communicate with the Text CAPTCHA web service. If it fails, we fallback to a predefined question seamlessly. We then store all of the accepted answers into an array which is also stored in the session so we can use it for validation later.

```
// setup textCAPTCHA
try {
    $xml = @new SimpleXMLElement('http://textcaptcha.com/api/your_api_key', NULL, TRUE);
} catch ( Exception $e ) {
    $fallback = '<captcha>';
    $fallback .= '<question>Is ice hot or cold?</question>';
    $fallback .= '<answer>' . md5( 'cold' ) . '<answer>';
    $fallback .= '</captcha>';
    $xml = new SimpleXMLElement( $fallback );
}

// store answers in session for use later
$answers = array();
foreach ( $xml->answer as $hash ) {
    $answers[] = (string)$hash;
}
$this->session->set_userdata( 'captcha_answers', $answers );
```

After Text CAPTCHA is setup and initialized, we need to pass the question to our view for use in the form.

```
// load vars into view
$this->load->vars( array( 'captcha' => (string)$xml->question ) );
```

CodeIgniter's Form Validation library makes custom validation functions easy. All we need to do is check if the answer submitted is in the list of acceptable answers. If not, we fail the test and return our error message.

```
function check_captcha( $string ) {
    $user_answer = md5( strtolower( trim( $string ) ) );
    $answers = $this->session->userdata( 'captcha_answers' );

    if ( in_array( $user_answer, $answers ) ) {
        return TRUE;
    } else {
        $this->form_validation->set_message( 'check_captcha', 'Your answer was incorrect!' );
        return FALSE;
    }
}
```

That's it! Simple enough, right? You can [download the demo](https://jrtashjian.com/wp-content/uploads/2011/01/codeigniter-form-with-text-captcha.zip) for the complete code.