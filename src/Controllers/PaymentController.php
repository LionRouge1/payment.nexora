<?php
namespace App\Controllers;

use App\Controllers\ApplicationController;

use App\Models\Payment;

class PaymentController extends ApplicationController
{
  public function index()
  {
    
    $this->render('payments/index');
  }

  public function show($id)
  {
    $payment = Payment::find($id);

    if(!$payment) {
      $this->notFound();
      return;
    }

    $this->render('payments/show', campact('user'));
  }

  // protected function render($view, $data=[])
  // {
  //   extract($data);
  //   $viewPath = __DIR__ . '/../Views/'.$view.'.php';
  //   if(!file_exists($viewPath)) {
  //     throw new \Exception("View file {$viewPath} not found");
  //   }

  //   ob_start();
  //   include $viewPath;
  //   $content = ob_get_clean();
  //   include __DIR__ . '/../Views/layouts/main.php';
  //   // include __DIR__ . '/../Views/layout.php';
  // }

  // public function notFound()
  // {
  //   http_response_code(404);
  //   $this->render('errors/404');
  //   exit();
  // }
}