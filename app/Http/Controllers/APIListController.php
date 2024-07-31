<?php

namespace App\Http\Controllers;

use App\Models\APIList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class APIListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $response = Http::withHeaders([
                'X-Api-Key' => config('app.postman_api_key'),
            ])->get("https://api.getpostman.com/collections/24712439-fdacd7a2-c358-4564-bd5f-403287c7abb4");

            $data = $response->json()['collection']['item'];
            $apiList = [];
            foreach ($data as $collection) {
                foreach ($collection['item'] as $item) {
                    $apiList[] = [
                        'collection' => $collection['name'],
                        'name' => $item['name'],
                        'request' => $item['request'],
                    ];
                }
            }
            return DataTables::of($apiList)
                ->addColumn('collection', function ($item) {
                    return $item['collection'];
                })
                ->addColumn('url', function ($item) {
                    $url = [];
                    foreach ($item['request']['url']['path'] as $path) {
                        $url[] = $path;
                    }
                    $url = implode("/", $url);
                    return '/api/' . $url;
                })
                ->addColumn('method', function ($item) {
                    $method = $item['request']['method'];
                    if ($method == 'POST') {
                        return '<span class="badge badge-lg badge-light-warning">POST</span>';
                    } else if ($method == 'GET') {
                        return '<span class="badge badge-lg badge-light-success">GET</span>';
                    }
                })
                ->addColumn('auth', function ($item) {
                    if (isset($item['request']['auth'])) {
                        return '<span class="badge badge-lg badge-light-primary">Auth&nbsp;<b>' . $item['request']['auth']['type'] . '</b></span>';
                    } else {
                        return '<span class="badge badge-lg badge-light-danger">No Auth</span>';
                    }
                })
                ->addColumn('body', function ($item) {
                    $body = [];
                    if (isset($item['request']['body'])) {
                        foreach ($item['request']['body']['formdata'] as $b) {
                            $key = 'Key : <b>' . $b['key'] . '</b>';
                            $description = isset($b['description']) ? '<br>Desc : ' . $b['description'] : '';
                            $body[] = '<div class="bg-gray-100 p-2 rounded border fs-7">' . $key . $description . '</div>';
                        }
                    }
                    return implode("", $body);
                })
                ->addColumn('description', function ($item) {
                    if (isset($item['request']['description'])) {
                        return $item['request']['description'];
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['auth', 'method', 'header', 'body'])
                ->addIndexColumn()
                ->make();
        }
        return view('admin.api.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\APIList  $aPIList
     * @return \Illuminate\Http\Response
     */
    public function show(APIList $aPIList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\APIList  $aPIList
     * @return \Illuminate\Http\Response
     */
    public function edit(APIList $aPIList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\APIList  $aPIList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, APIList $aPIList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\APIList  $aPIList
     * @return \Illuminate\Http\Response
     */
    public function destroy(APIList $aPIList)
    {
        //
    }
}
