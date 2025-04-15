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
        $domain = $_POST['domain'] ?? null;
        $error = null;
        $website = null;
        $author = null;
        if ($domain) {
          try {
            $domain = preg_replace('/^(https?:\/\/)?(www\.)?/', '', rtrim($domain, '/'));
            $website = Website::findByDomain($domain);
            $author = $website->author();
          } catch (\Exception $e) {
            $error = 'Website not found or invalid domain.';
          }
        } else {
            $website = null;
        }

        $data = [
          'fullname' => $author ? $author->getFullname() : null,
          'error' => $error,
          'domain' => $website ? $website->getDomain() : null,
          'name' => $website ? $website->getName() : null,
          'email' => $author ? $author->getEmail() : null,
          'website_id' => $website ? $website->getId() : null,
        ];

        $this->render('websites/index', compact('data'));
    }

    public function contact()
    {
        $this->render('website/contact');
    }

    public function services()
    {
        $this->render('website/services');
    }
}