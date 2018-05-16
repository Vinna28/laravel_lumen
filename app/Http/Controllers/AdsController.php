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


        //cek waktu data terakhir diinput
        $lastData = Ads::latest()->first();
        // return $lastData;
        $lastInput = $lastData['created_at'];
        $lastInput = strtotime($lastInput);
        // echo($lastNginput);
        //cek waktu saat nginput
            //get datetime php
        $now = date('Y-m-d H:i:s');
        $now = strtotime($now);
        // echo $now;

        // return response()->json([
        //     'last input'=>$lastInput,
        //     'now'=>$now
        //     ]);
        //if > 1 minutes then
        ////create
        if($lastInput > $now - 60){
            return response()->json(['status'=>'Forbidden','messages'=>'gak bisa nginput sebelum 1 menit'], 403);
        }else{
            // echo "bisa nginput";
            try{
                $ads = Ads::create($request->all());
                return response()->json([
                'status'=>'Succes',
                'messages'=>'bisa nginput'
                ], 200);
            }
            catch (\Exception $e){
                $error_code = $e->errorInfo[1];
                if($error_code == 1062){
                    // self::deleteAds($ads['id']);
                    return response()->json([
                'status'=>'Forbidden',
                'messages'=>'judul sudah ada'
                ], 403);
                }
            }  
        }  
    }

    public function deleteAds($id){
        $ads  = Ads::find($id);
        
        if($ads->view_counter > 0){
            return response()->json([
                'status'=>'Forbidden',
                'message'=>'Iklan sudah ada View Counter-nya'
                ],403);
        }else{
            $ads->delete();
            return response()->json([
                'status'=>'Success',
                'message'=>'Hapus iklan berhasil'
                ],200);
        }
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
    // Get costum request 
    public function getCustomAds(Request $request) {

        $create_at = $request->input('date');
        $category = $request->input('category');
        $view_counter = $request->input('view_counter');


        $ads = Ads::query();
        if (!is_null($category)) {
            $ads = $ads->where('category', $category);
        }
        if (!is_null($view_counter)) {
            $ads = $ads->where('view_counter', $view_counter);
        }
        if (!is_null($create_at)) {
            $ads = $ads->whereRaw('date(created_at) = ?', [$create_at]);
        }     

        return response()->json(['result'=>$ads->get()]);
    }

    // get popular
    public function getPopularAds() {

        $ads = Ads::orderByDesc('view_counter')->take(5)->get();

        return response()->json(["result"=>$ads]);
    }

    //Pakpo
    public function approveAds(Request $request, $id){
        $ads  = Ads::find($id);

        $ads->status = $request->input('status');
        if($ads->status == 0){
            return response()->json([
                'status'=>'Forbidden',
                'message'=>'Status cannot unapproved again'
                ], 403);
        }
            $ads->save();
            return response()->json([
                'status'=>'Success',
                'message'=>'Status Approved'
                ], 200);
    }

    public function updateVcAds(Request $request, $id){
        $ads  = Ads::find($id);

        $ads->view_counter = $request->input([
            'status'=>'Forbidden',
            'message'=>'view_counter'
            ], 403);

        $ads->save();

        return response()->json([
            'status'=>'Success',
            'message'=>'View count has been updated'
            ], 200);
    }
 
}