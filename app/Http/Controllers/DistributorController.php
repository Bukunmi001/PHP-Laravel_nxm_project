<?php

namespace App\Http\Controllers;

use App\Services\DistributorService;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    protected $distributorService;

    public function __construct(DistributorService $distributorService)
    {
        $this->distributorService = $distributorService;
    }

    public function topDistributors(Request $request)
    {
        $distributors = $this->distributorService->getTopDistributors();

        return view('distributors.top', compact('distributors'));
    }
}
