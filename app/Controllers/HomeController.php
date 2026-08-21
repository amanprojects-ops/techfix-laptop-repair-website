<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;
use App\Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        $services  = Service::all(true);
        $settings  = $this->getSettings();
        $this->render('frontend/home', compact('services', 'settings'), 'main');
    }

    public function services(): void
    {
        $services = Service::all(true);
        $this->render('frontend/home', compact('services'), 'main');
    }

    public function pricing(): void
    {
        $services = Service::all(true);
        $this->render('frontend/pricing', compact('services'), 'main');
    }

    private function getSettings(): array
    {
        $rows = Database::fetchAll('SELECT `key`, `value` FROM site_settings');
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
}
