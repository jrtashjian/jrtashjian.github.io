---
extends: _layouts.post
title: Simple Login Form With CodeIgniter
date: 2009-02-10
categories: [code]
---
**Notice:** This tutorial is dated. It is important you take care how you store and check a user's password, they should always be stored with one-way encryption. Read more about [Password Hashing](http://www.phptherightway.com/#web_application_security).

One of the most basic but, most important part of a web application is the login form. The login form is the first stage of securing your web application. I will show you how I construct one using the [Encryption Class](http://codeigniter.com/user_guide/libraries/encryption.html), the [Form Validation Class](http://codeigniter.com/user_guide/libraries/form_validation.html) and the [Session Class](http://codeigniter.com/user_guide/libraries/sessions.html) of [CodeIgniter](http://codeigniter.com).

To begin, we will need to create our view and controller files as well as load the libraries we will be using. I will not be using a database for this tutorial but, rather an array containing the username and password of out user.

For the views, I created a view for the header, the footer, and the login form:

**header.php**
```
<html>
<head>
<title>Welcome to CodeIgniter</title>

<style type="text/css">
body { background-color:#fff; margin:40px; font-family:Lucida Grande,Verdana,Sans-serif; font-size:14px; color:#4F5155; }
h1 { color:#444; background-color:transparent; border-bottom:1px solid #D0D0D0; font-size:16px; font-weight:bold; margin:24px 0 2px 0; padding:5px 0 6px 0; }
</style>
</head>
<body>
```

**footer.php**
```
</body>
</html>
```

**login_form.php**
```
<h1>Tutorial: Simple Login Form</h1>
```

Thats it for the views, so far. Next we will create the controller:

**main.php**
```
class Main extends Controller {

    function Main() {
        parent::Controller();
    }

    function index() {
        // LOAD LIBRARIES
        $this->load->library( array( 'encrypt', 'form_validation', 'session' ) );

        // LOAD HELPERS
        $this->load->helper( array( 'form' ) );

        // VALID USER CREDENTIALS
        $user_credentials = array();
        $user_credentials['testuser1'] = array(
            'user_name' => 'testuser1',
            'user_pass' => 'Gag/R6LlMz8JKhjd+pkMrL+MUIHn86vjs/ZJ31uH+QRCh1eRxxA0Fve6FXfE7rmFqgqsiwe2ZFrFT8ylZs050A==' // password
        );
        $user_credentials['testuser2'] = array(
            'user_name' => 'testuser2',
            'user_pass' => 'Gag/R6LlMz8JKhjd+pkMrL+MUIHn86vjs/ZJ31uH+QRCh1eRxxA0Fve6FXfE7rmFqgqsiwe2ZFrFT8ylZs050A==' // password
        );
        $user_credentials['testuser3'] = array(
            'user_name' => 'testuser3',
            'user_pass' => 'Gag/R6LlMz8JKhjd+pkMrL+MUIHn86vjs/ZJ31uH+QRCh1eRxxA0Fve6FXfE7rmFqgqsiwe2ZFrFT8ylZs050A==' // password
        );

        $this->load->view( 'header' );
        $this->load->view( 'login_form' );
        $this->load->view( 'footer' );
    }

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
```

As you can see in the above code, I have already encrypted a password. To create an encrypted password from a string all you need to do is call `encode()` from the encryption class, like so:

```
echo $this->encrypt->encode( 'password' );
```

## Building the Form

After setting up the files as described above, we now need to create the form so our users can login. I will be using the [Form Helper](http://codeigniter.com/user_guide/helpers/form_helper.html) that CodeIgniter provides us with instead of writing out the html. I will be creating this form inside the `login_form.php` view:

**login_form.php**
```
<h1>Tutorial: Simple Login Form</h1>

<?php if ( $this->session->flashdata( 'message' ) ) : ?>
    <p><?php echo $this->session->flashdata( 'message' ); ?></p>
<?php endif; ?>

<?php echo form_open( 'main/index/' ); ?>
    <?php echo form_fieldset( 'Login Form' ); ?>

        <div class="textfield">
            <?php echo form_label( 'username', 'user_name' ); ?>
            <?php echo form_input( 'user_name' ); ?>
        </div>

        <div class="textfield">
            <?php echo form_label( 'password', 'user_pass' ); ?>
            <?php echo form_password( 'user_pass' ); ?>
        </div>

        <div class="buttons">
            <?php echo form_submit( 'login', 'Login' ); ?>
        </div>

    <?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>
```

You probably noticed I am using PHP [short tag syntax](http://codeigniter.com/user_guide/general/alternative_php.html) which, I prefer to use. If you choose not to use short tag syntax instead of writing this:

```
<?php echo form_input( 'user_name' ); ?>
```

you would write this:

```
<?php echo form_input( 'user_name' ); ?>
```

Whatever suits your coding style, stick to it and be consistent throughout your code. I have also added a check to see if the [Flashdata](http://codeigniter.com/user_guide/libraries/sessions.html) 'message' has been set. I like to use flashdata for temporary messages because any flashdata vars set will only be available for the next server request, and are then deleted or cleared. I will be using this to notify the user of an incorrect password, on successful login, and if a user has not been found.

## Submitting the Form

Now that we have our form created, we need to make it work! So, open up that controller and lets put some code in there.

The first thing we must do upon submitting a form is to validate the data. CodeIgniter's Form Validation Class makes this really easy, all we have to do is set some rules:

```
// SET VALIDATION RULES
$this->form_validation->set_rules( 'user_name', 'username', 'required' );
$this->form_validation->set_rules( 'user_pass', 'password', 'required' );
$this->form_validation->set_error_delimiters( '<em>','</em>' );
```

The first parameter of `$this->form_validation->set_rules()` is the field name we set in html. The second parameter is what you would call the field in the error message (eg. The `username` field is a required field). The third parameter is the rule reference and defines what the field data should contain. We will not be doing anything special so, 'required' works for what we need to accomplish because all we need to check is if the field was filled in. I have also set the error message delimiters which again, is just personal preference. You can keep them as default or use different delimiters.

In order for us to validate the form we need to check to see if it has been submitted and then validate the form. If the form validates, we continue processing the submitted credentials if not, we display the error messages and the user can try to submit the form again.

```
// SET VALIDATION RULES
$this->form_validation->set_rules( 'user_name', 'username', 'required' );
$this->form_validation->set_rules( 'user_pass', 'password', 'required' );
$this->form_validation->set_error_delimiters( '<em>','</em>' );

// has the form been submitted and with valid form info (not empty values)
if ( $this->input->post( 'login' ) ) {
    if ( $this->form_validation->run() ) {
        // form submitted and validated continue processing
    }
}
```

If the form does not validate, `$this->form_validation->run()` will return FALSE and terminate the if-statement. The controller will then finish executing but, we still need to notify the user of any errors! When a form fails to validate, CodeIgniters Form Validation Class generates the error messages or messages and we can display them the way we choose on the view. CodeIgniter allows us to output these messages either in a block format (all messages in one place) or individually. I prefer to display them individually and right where the form element that had the error. To do that, we need to update out `login_form.php` view. We will be inserting the `form_error()` function which only needs one parameter; the name of our form element. We also will need to re-populate the form with the submitted data so the user can see what went wrong and also so the user does not have to re-type all the info back into the form. To do this, we will place the `set_value()` funtion inside the `form_input()` function

```
<div class="textfield">
    <?php echo form_label( 'username', 'user_name' ); ?>
    <?php echo form_error( 'user_name' ); ?>
    <?php echo form_input( 'user_name', set_value( 'user_name' ) ); ?>
</div>

<div class="textfield">
    <?php echo form_label( 'password', 'user_pass' ); ?>
    <?php echo form_error( 'user_pass' ); ?>
    <?php echo form_password( 'user_pass' ); ?>
</div>
```

Now when a user submits a form with no information or with missing information, they will be denied access and informed about the error. They can then fix the errors and re-submit until they get it right.

## Validating The User

Once the user has submitted a valid form we then need to check if the user is a valid user or not. If the user is a valid user, we will then continue processing if not, an error message will be displayed and the user will be returned to the login form. We will be displaying this error message using Flashdata.

```
// has the form been submitted and with valid form info (not empty values)
if ( $this->input->post( 'login' ) ) {
    if ( $this->form_validation->run() ) {
        $user_name = $this->input->post( 'user_name' );
        $user_pass = $this->input->post( 'user_pass' );

        if ( array_key_exists( $user_name, $user_credentials ) ) {
            // continue processing form (validate password)
        } else {
            $this->session->set_flashdata( 'message', 'A user does not exist for the username specified.' );
            redirect( 'main/index/' );
        }
    }
}
```

Once the user has submitted credentials for a valid user, we will then check the submitted password against the stored password to see if they match we will allow the user to login and use the application. If the passwords do not match, we will display another message notifying the user of the error and then return the user back to the login form.

```
// has the form been submitted and with valid form info (not empty values)
if ( $this->input->post( 'login' ) ) {
    if ( $this->form_validation->run() ) {
        $user_name = $this->input->post( 'user_name' );****
        $user_pass = $this->input->post( 'user_pass' );

        if ( array_key_exists( $user_name, $user_credentials ) ) {
            if ( $user_pass == $this->encrypt->decode( $user_credentials[ $user_name ]['user_pass'] ) ) {
                // user has been logged in
                die( "USER LOGGED IN!" );
            } else {
                $this->session->set_flashdata( 'message', 'Incorrect password.' );
                redirect( 'main/index/' );
            }
        } else {
            $this->session->set_flashdata( 'message', 'A user does not exist for the username specified.' );
            redirect( 'main/index/' );
        }
    }
}
```

Well, there you have it. A simple and easy login form created using CodeIgniter. Even though I used an array to store user credentials, it would be really easy to modify the code for use with a database.

You can [download the demo](https://jrtashjian.com/wp-content/uploads/2009/02/simple-login-form-with-codeigniter.zip) here.

Remember, you still need to update the variable `$config['base_url']` inside the `./application/config/config.php` file.