<?php
namespace App\Mailers;

class ApplicationMailer
{
  protected $to;
  protected $subject;
  protected $body;
  protected $headers = Array('Content-Type: text/html; charset=UTF-8', 'From: <support@nexoragh.com>');

  public function __construct($to, $subject, $body)
  {
    $this->to = $to;
    $this->subject = $subject;
    $this->body = $body;
  }

  // public function __call() {

  // }

  // public function send()
  // {
  //       // Logic to send the email
  //       echo "Sending email to {$this->to} with subject '{$this->subject}' and body '{$this->body}'";
  // }
}