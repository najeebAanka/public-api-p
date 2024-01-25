<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\HelpTopic;
use App\Resources\FAQ;

class GeneralController extends Controller
{
    public function faq()
    {
        return response()->json(
            ['message' => 'Data Got!',
                'data' => FAQ::collection(HelpTopic::where('status', 1)->orderBy('ranking')->get())]);
    }
}
