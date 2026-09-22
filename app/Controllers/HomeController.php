<?php declare(strict_types=1);

namespace App\Controllers;

use Skim\Core\Response;

class HomeController {
    public function index(): Response {
        return Response::html(<<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>SKIM App</title>
            <style>body { font-family: sans-serif; padding: 2rem; }</style>
        </head>
        <body>
            <h1>Hello from SKIM!</h1>
            <p>Your application is running. Edit <code>app/Controllers/HomeController.php</code> to get started.</p>
        </body>
        </html>
        HTML);
    }
}
