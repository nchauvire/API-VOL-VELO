<?php
namespace Src\Controllers;

use DateTime;
use GuzzleHttp\Client;
use function json_encode;
use Slim\Http\Request;
use Slim\Http\Response;
use stdClass;
use function var_dump;

class IndexController extends Controller {

    public function getVolByVille(Request $request, Response $response, array $args){

        if (! $args['city']) {
            $args['city'] = 'nantes';
        }

        $client = new Client();
        $request = $client->request(
            'GET',
            "http://api.waqi.info/feed/{$args['city']}/?token=61d3602af69176305a80cefdc676225612d38d6a"
        );
        $iaqi = json_decode($request->getBody()->getContents(),true)['data']['iaqi'];
        $data = [];
        foreach ($iaqi as $key => $val){
            $data[$key] = $val['v'];
        }



        // Render index view
        return $response->withJson($data);

    }

    public function addVol (Request $request, Response $response, array $args)
    {
        $entite = new stdClass();
        $date = new DateTime('now');
        $entite->date = $date->format("Y-m-d");
        $entite->name = $request->getParam('name');
        $entite->phone = $request->getParam('phone');
        $entite->description = $request->getParam('description');
        $entite->city = $request->getParam('city');

        $db = $this->getMongo()->selectDatabase('vols');
        $collection = $db->selectCollection('velo');

        $result = $collection->insertOne([$entite]);

        if($result->getInsertedCount() >= 1){
            return $response->withJson("insertion réalisé avec succès", 200);
        }else{
            return $response->withJson("Une erreur c'est produite lors de la creation du vol de velo", 500);
        }
    }

}
