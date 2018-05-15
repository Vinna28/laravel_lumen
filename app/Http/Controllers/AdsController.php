<?php
 
namespace App\Http\Controllers;
 
use App\Ads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
 
class AdsController extends Controller{
 
 
    public function index(){
 
        $adsapis  = Ads::all();

        return response()->json([
            'results'=>$adsapis,
            'message'=>'List all ads'
        ]);
 
    }
 
    public function getAds($id){
 
        $ads  = Ads::find($id);
        $ads->view_counter = $ads['view_counter']+1;
 
        $ads->save();

        if ($ads) {
            # code...
            return response()->json([
                "data"=>$ads
                ]); 
        }
    }

    public function getPendingAds(){

        $ads = Ads::where('status', 0)->get();
        return response()->json($ads);
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
            return $ads;
        }
        catch (\Exception $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                var_dump($e);
                // self::deleteAds($ads['id']);
                return 'We have a duplicate entry problem';
            }
        }
    }

    public function deleteAds($id){
        $ads  = Ads::find($id);
 
        $ads->delete();
 
        return response()->json('success');
    }
 
    public function updateAds(Request $request,$id){
        $ads  = Ads::find($id);
 
        $ads->title = $request->input('title');
        $ads->content = $request->input('content');
        $ads->category = $request->input('category');
 
        $ads->save();
 
        return response()->json($ads);
    }
 
}