<?php

namespace App\Controller;

class MyController extends MainController
{
    public function index()
    {
        echo "Hello world!";
    }

    public function rendered_index()
    {
        $this->set(
            [
                "title" => "My rendered page",
                "nofooter" => 0
            ]);

        $this->render();
    }
}