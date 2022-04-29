---
extends: _layouts.post
title: Postmark API Wrapper for CodeIgniter
date: 2011-04-22
categories: [codeigniter]
alert_message: This project is no longer maintained.
alert_type: warning
---
I've been using [Postmark](http://postmarkapp.com/) and it's API for quite a while. If you're still sending out emails through PHP's [mail()](http://php.net/mail) function, you need to check out Postmark! With Postmark you can off-load those transactional emails and not have to worry about it yourself.

While there is a Postmark API Wrapper for [CodeIgniter](http://codeigniter.com/) already, I felt it was unnecessary to create an entirely different Email class just to send off API requests to Postmark. Instead, this library extends the Core CI_Email class, retaining the small footprint philosophy of CodeIgniter.

## Installation

1.  Download the library from [Github](http://github.com/jrtashjian/postmark-codeigniter).
2.  Copy `config/postmark.php` to your `application/config/` folder
3.  Copy `libraries/Postmark.php` to your `application/libraries/` folder

## Configuration

There is only one setting you need to update in the config file (`application/config/postmark.php`) and that is your Postmark API key. You can find your API key from the Server Details -> Credentials page in your Postmark Account.

```
$config['postmark_api_key'] = "YOUR_API_KEY_HERE";
```

## Loading the Library

To use the library, you will need to load it along with the Core CI_Email library (because we extend it).

```
$this->load->library( 'email' );
$this->load->library( 'postmark' );
```

OR

```
$this->load->library( array( 'email', 'postmark' ) );
```

Just make sure to load the Core CI_Email (email) class first.

## Sending an Email

The great thing about extending the Core CI_Email class is the ability to not have to change the way you use the class! The only difference is that you will be calling functions as `$this->postmark->function_name()` instead of `$this->email->function_name()`.

```
$this->load->library( 'email' );
$this->load->library( 'postmark' );

$this->postmark->from( 'your_example.com', 'Your Name' );
$this->postmark->to( 'someone@example.com' );
$this->postmark->cc( 'another@another-example.com' );
$this->postmark->bcc( 'them@their-example.com' );

$this->postmark->subject( 'Email Test' );
$this->postmark->message( 'Testing the email class.' );

$this->postmark->send();
```

## Other Information

When calling `$this->postmark->from()`, you will have to use your Postmark API Sender Signature located on the Signatures page in your Postmark Account.

## Changelog

Version 1.0

April 22nd, 2011 - Initial Release