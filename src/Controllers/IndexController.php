<?php
namespace Src\Controllers;

use function array_flip;
use DateTime;
use GuzzleHttp\Client;
use function json_encode;
use MongoDB\BSON\ObjectID;
use Slim\Http\Request;
use Slim\Http\Response;
use stdClass;
use function var_dump;

class IndexController extends Controller {

    public function getVolByVille(Request $request, Response $response, array $args){

        $collection = $this->getMongo()->selectDatabase('vols')->selectCollection('velo');
        if (isset($args['city'])) {
            $list = $collection->find(['0.city'=>$args['city']]);
        }else{
            $list = $collection->find();
        }
        $data = [];
        foreach ($list as $val){
            $data[] = $val[0];
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

    public function newVol (Request $request, Response $response, array $args)
    {

        //$db = $this->getMongo()->selectDatabase('vols');
        $db = $this->getMongo()->selectDatabase('vols');
        $collection = $db->selectCollection('velo');
        $result = $collection->find()->toArray();
        $result = array_reverse ($result);
        $newVol = null;
        foreach ($result as $vol){

            if(isset($args['lastVol'])){
                if($args['lastVol'] != get_object_vars($vol['_id'])['oid']){
                    $newVol['id'] = get_object_vars($vol['_id'])['oid'];
                    $newVol['name'] = $vol[0]['name'];
                    $newVol['date'] = $vol[0]['date'];
                    $newVol['description'] = $vol[0]['description'];
                    $newVol['city'] = $vol[0]['city'];
                }
            }else{
                $newVol['id'] = get_object_vars($vol['_id'])['oid'];
                $newVol['name'] = $vol[0]['name'];
                $newVol['date'] = $vol[0]['date'];
                $newVol['description'] = $vol[0]['description'];
                $newVol['city'] = $vol[0]['city'];
            }
            break;
        }
        return $response->withJson($newVol, 200);
    }

}
