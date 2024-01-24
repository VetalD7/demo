<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class IndexController
 * @package Modules\Report\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * Display listing of the resource.
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('report::index');
    }
}
