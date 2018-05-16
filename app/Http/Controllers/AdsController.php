<?php
 
namespace App\Http\Controllers;
 
use App\Ads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
 
class AdsController extends Controller{
 
 
    public function index(){
 
        $adsapis  = Ads::all();

        return response()->json([
            'result'=>$adsapis
        ]);
 
    }
 
    public function getAds($id){
        
        $ads  = Ads::find($id);

        if ($ads==null) {
            # code...
            return response()->json([
                'status'=>404,
                'message'=>'Forbidden, id is not exist'
            ]);

        } else {
            # code...
            $ads->view_counter = $ads['view_counter']+1;
            $ads->save();

            return response()->json([
                'result'=>$ads
            ]); 
        }
    }

    public function getPendingAds(){

        $ads = Ads::where('status', 0)->get();
        
        return response()->json([
            'result'=>$ads
        ]);
    }
 
    public function saveAds(Request $request){
 
        $input = $request->all();
        // var_dump($input['title']);
        // $ads = Ads::where('title', $input['title'])->first();
        // // var_dump(count($ads));
        // //if ads name exist, dont save and response 403
        // if ($ads) {
        //     # code...
        //     // echo "udah ada datanya";
        //     return response()->json([
        //         "status"=>"Failed",
        //         "message"=>"Ads Exist",
        //         "data"=>$ads
        //     ]);
        // } else {
        //     # code...
        //     // echo "belum ada datanya";
        //     $ads = Ads::create($request->all());
        //     return response()->json([
        //         "status"=>"Success",
        //         "message"=>"Ads Added",
        //         "data"=>$ads
        //     ]);
        // }
        
        try{
            $ads = Ads::create($request->all());
            return response()->json([
                'result'=>$ads
            ]);
        }
        catch (\Exception $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                //var_dump($e);
                // self::deleteAds($ads['id']);
                return response()->json([
                    'status'=>500,
                    'message'=>'Error! We have a duplicate entry problem'
                ]);
            }
        }
    }

    public function deleteAds($id){
        $ads  = Ads::find($id);
 
        $ads->delete();
 
        return response()->json([
            'status'=>200,
            'message'=>'Success! Ads deleted'
        ]);
    }
 
    public function updateAds(Request $request,$id){
        $ads  = Ads::find($id);

        if ($ads==null) {
            # code...
            return response()->json([
                'status'=>404,
                'message'=>'Forbidden, id is not exist'
            ]);

        } else {
            # code...
            $ads->title = $request->input('title');
            $ads->content = $request->input('content');
            $ads->category = $request->input('category');
     
            $ads->save();
     
            return response()->json([
                'status'=>200,
                'message'=>'Success',
                'result'=>$ads
            ]);
        } 
 
    }

    //Pakde

    //Pakpo
 
}