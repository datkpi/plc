<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\TestRepository;

class TestController  extends Controller
{

    public function __construct(TestRepository $testRepo) {
        $this->testRepo = $testRepo;
    }

    public function index()
    {
        $data = $this->testRepo->all();
        return view('backend/index', compact('product_count', 'news_count', 'contact_count'));
    }


}
