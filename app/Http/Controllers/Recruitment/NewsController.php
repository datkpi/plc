<?php

namespace App\Http\Controllers\Recruitment;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Recruitment\NewsRepository;

class NewsController extends Controller
{

    public function __construct(NewsRepository $newsRepo) {
        $this->newsRepo = $newsRepo;
    }

    public function index()
    {
        $data = $this->newsRepo->all();
        return view('recruitment/news/index');
    }


}
