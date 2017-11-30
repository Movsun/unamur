<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gateway;
use App\Node;

class GatewayController extends Controller
{
    public function index(){

      $gateways = Gateway::all();
      $nodes = Node::all();
      return view('gateways.index', compact('gateways', 'nodes'));
    }

    public function addGateway(){
      $gateways = Gateway::all();
      return view('gateways.add_gateway', compact('gateways'));
    }

    public function storeGateway(Request $request){
      $eui = $request['eui'];
      $name = $request['name'];

      if ($eui && $name){
        $g = Gateway::create(['eui'=> $eui, 'name'=>$name]);
      }
      return back();
    }
    public function removeGateway($id){
      $g = Gateway::find($id);
      if ($g){
        $g->delete();
      }
      return back();
    }

    public function addNode(){
      $nodes = Node::all();
      return view('gateways.add_node', compact('nodes'));
    }
    public function storeNode(Request $request){
      $eui = $request['eui'];
      $name = $request['name'];

      if ($eui && $name){
        $n = Node::create(['eui'=> $eui, 'name'=>$name]);
      }
      return back();
    }
    public function removeNode($id){
      $n = Node::find($id);
      if ($n){
        $n->delete();
      }
      return back();
    }
}
