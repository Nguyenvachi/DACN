<?php

// FamilyController removed. The family module was intentionally removed from the project.
// If this file is invoked unexpectedly, return HTTP 410 (Gone).

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function __call($method, $args)
    {
        abort(410, 'Family module removed');
    }
}
