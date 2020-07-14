<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use Illuminate\Support\Facades\Storage;
use Session;



class ImgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $log = '123';
    
        // dd('123 ');

        return view('img.index')->with('log', $log);
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
        $err = [];
        $tru = [];

        $validatedData = $request->validate([
            'check' => 'required',
        ]);
        
        $imgs = $request['check'];
        $url = $request['urlOutput'];


        if(isset($imgs)){

            ini_set('max_execution_time', 300);

            foreach($imgs as $img){
                
                if(strpos($img, 'http') === false){
                    $img = str_replace('//', '', $img);
                    $img = 'https://'. $img;
                }
        
                $path = 'images/'.$this->basenameUrl($url).'/'.$this->basenameUrl($img);

                $content = @file_get_contents($img, false, $context);
        
                if ($content === false) {
                    array_push($err, $img);
                }
                else{
                    array_push($tru, $img);
                    Storage::disk('local')->put($path, $content, 'public');
                }
            }
        }

        Session::put('errorImg', $err);
        Session::put('trueImg', $tru);

        return redirect('img')->with('message', 'Zdjęcia zostały zapisane w folderze storage/images/'.$this->basenameUrl($url));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getImg(Request $request)
    {
        $tab = [];

        $client = new Client();
        $response = $client->request('GET', $request['url']);
        $status = $response->getStatusCode(); 

        $base_url = parse_url($request['url'], PHP_URL_HOST);

        if($status === 200)
        {
            $body = $response->getBody()->getContents();
            $dom = new Dom;
            $dom->load($body);
            $contents = $dom->find('img');
            foreach ($contents as $content)
            {
                $src = $content->getAttribute('src');
                
                $file_headers = @get_headers($src);

                if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    $src = 'https://'.$base_url.$src;
                    $file_h = @get_headers($src);
                    if(!$file_h || $file_h[0] != 'HTTP/1.1 404 Not Found') {
                        array_push($tab, $src);
                    }
                }
                else {
                    array_push($tab, $src);
                }
            }
        }        

        return $tab;

    }

    public function basenameUrl($path) {
        $basename = pathinfo($path);
        if(isset($basename['basename']))
            return $basename['basename'];
    }
}
