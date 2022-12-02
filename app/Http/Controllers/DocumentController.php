<?php

namespace App\Http\Controllers;

use App\Consumers\PanagoraConsumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Get a paginated list of routes to generate documents
     *
     * @param Request $request
     * @param mixed $event
     *
     * @return response
     */
    public function get(Request $request, $event)
    {
        $request->merge(['event' => $event]);

        $validation = Validator::make($request->all(), [
            'voter_ids' => 'required',
            'event' => 'required|integer',
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
        ]);

        $page = $request->page ?? 1;
        $per_page = $request->per_page ?? 10;

        if ($validation->fails()) {
            return response([
                'success' => false,
                'errors' => $validation->errors()
            ], 400);
        }

        $voters = collect($request->voter_ids)
            ->unique()
            ->skip(($page - 1) * $per_page)
            ->take($per_page);

        $response = [];
        $panagora_request_paths = [];

        foreach ($voters as $voter_id) {
            $panagora_request_paths[] = [
                'path' => "/eventos/$event/votante/$voter_id",
                'key' => $voter_id,
            ];
        }

        $response = $this->panagora_api->makeConcurrentRequests($panagora_request_paths, 'GET');
        //Criar uma lista com os links para a geracao dos documentos
        return response([
            'success' => true,
            'actual_page' => $page,
            'total_pages' => ceil(count($request->voter_ids) / $per_page),
            'data' => $response,
        ], 200);
    }
}
