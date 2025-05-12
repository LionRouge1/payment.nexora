<?php
namespace App\Controllers;
use App\Controllers\ApplicationController;
use App\Models\Website;

class WebsiteController extends ApplicationController
{
    public function index()
    {
        $data = null;
        $this->render('websites/index', compact('data'));
    }

    public function search()
    {
        $error = null;
        $curl = 'https://api.currencyfreaks.com/v2.0/rates/latest?apikey=3d68979846b346cf9374ef309b793d78&symbols=GHS';
        $response = file_get_contents($curl);
        $response = json_decode($response, true);
        $rate = $response['rates']['GHS'] ?? null;
        if ($rate) {
            $rate = number_format(250/$rate, 2);
        } else {
            $error = 'Unable to fetch exchange rate.';
        }

        if(!isset($_POST['domain']) || empty($_POST['domain'])) {
            $error = 'Domain field cannot be empty.';
        }

        $domain = $_POST['domain'] ?? null;
        $website = null;
        $author = null;
        if ($domain) {
          try {
            $domain = preg_replace('/^(https?:\/\/)?(www\.)?/', '', rtrim($domain, '/'));
            $domain = strtolower($domain);
            $website = Website::findByDomain($domain);
            $author = $website->author();
          } catch (\Exception $e) {
            $error = 'Website not found or invalid domain.';
          }
        } else {
            $website = null;
        }



        $website_id = $website ? $website->id : null;
        $email = $author ? $author->email : null;
        $fullname = $author ? $author->fullname : null;
        $name = $website ? $website->name : null;
        $domain = $website ? $website->domain : null;
        $author_id = $author ? $author->id : null;

        $data = [
          'fullname' => $fullname,
          'error' => $error,
          'domain' => $domain,
          'name' => $name,
          'email' => $email,
          'website_id' => $website_id,
          'rate' => $rate,
        ];
        $_SESSION['website_id'] = $website_id;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['domain'] = $domain;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['author_id'] = $author_id;

        $this->render('websites/index', compact('data'));
    }
}