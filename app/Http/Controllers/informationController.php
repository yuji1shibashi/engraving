<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralMessage;
use App\Models\PersonalMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InformationController extends Controller
{
    protected $generalMessage;
    protected $personalMessage;

    public function __construct(GeneralMessage $generalMessage, PersonalMessage $personalMessage){
        $this->generalMessage = $generalMessage;
        $this->personalMessage = $personalMessage;
    }

    public function index(){
        
        $users = DB::table('users')
            ->select('users.name', 'users.id')
            ->get();

        $msg = $this->generalMessage->select([
            'id'
            ,'message'
            , 'start_date'
            , 'end_date'
            ,DB::raw("'' as to_user_id")
            ,DB::raw("'' as user_nm")
            ,'created_at'
            ])
            ->where([
                'general_message.deleted_flg' => 0
            ]);
        $msgList = $this->personalMessage->select([
            'personal_message.id'
            ,'personal_message.message'
            ,'personal_message.start_date'
            ,'personal_message.end_date'
            ,'personal_message.to_user_id'
            ,'users.name'
            ,'personal_message.created_at'
        ])
        ->where([
            'personal_message.deleted_flg' => 0
        ])
        ->join('users', 'users.id', '=', 'personal_message.to_user_id')
        ->union($msg);
        $msgList = $msgList->orderby('created_at')->get();
        foreach($msgList as $val){
            $stdt = new Carbon($val->start_date);
            $edt = new Carbon($val->end_date);
            $val->start_date = $stdt->format('Y-m-d');
            $val->end_date = $edt->format('Y-m-d');
        }

        $errorMsg = session('errorMsg', '');
        $msg = session('msg', '');

        $viewData = [
            'msgList' => $msgList,
            'user' => $users
        ];
        
        return view('Information', $viewData);
    }

    /**
     * インフォメーション 登録
     * [addPublicMessage description]
     * @param Request $request [description]
     */
    public function addInformation(Request $request){
        $message = $request->input('message');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $to = $request->input('to_send');
        if(empty($to)){
            $result = $this->generalMessage->addGeneralInfo($message, $startDate, $endDate);            
        }else{
            $from = session('login_user_id');
            $result = $this->personalMessage->addInformation($message, $startDate, $endDate, $to, $from);
        }

        if(!$result){
            $message = '登録に失敗しました。';
        }else{
            $message = 'インフォメーションを登録しました。';
        }
        return response()->json(['message' => $message]);
    }

    /**
     * インフォメーション　削除
     *
     * @param Request $request
     * @return void
     */
    public function delInformation(Request $request){

        $id = $request->input('message_id');
        $to = $request->input('to_send');
        if( empty($to)){
            $result = $this->generalMessage->delGeneralInfo($id);
        }else{
            $result = $this->personalMessage->deleteInformation($id);
        }

        if(!$result){
            $message = '削除に失敗しました。';
        }else{
            $message = 'インフォメーションを削除しました。';
        }
        return response()->json(['message' => $message]);
    }

    /**
     * インフォメーション　編集
     *
     * @param Request $request
     * @return void
     */
    public function editInformation(Request $request){
        $message = $request->input('message');
        $id = $request->input('message_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $to = $request->input('to_send');
        $originalId = $request->input('original_user_id');
        \Log::debug($request->input('original_user_id'));
        if( empty($originalId)){
            if( empty($to)){
                $result = $this->generalMessage->editGeneralInfo($id, $message, $startDate, $endDate);
            }else{
                $this->generalMessage->delGeneralInfo($id);
                $from = session('login_user_id');
                $result = $this->personalMessage->addInformation($message, $startDate, $endDate, $to, $from);
            }
            
        }else{
            if( empty($to)){
                $res = $this->personalMessage->deleteInformation($id);
                if(!$res){
                    return false;
                }
                $result = $this->generalMessage->addGeneralInfo($message, $startDate, $endDate);
            }else{
                $result = $this->personalMessage->editInformation($id, $message, $startDate, $endDate, $to);
            }
        }

        if(!$result){
            $message = '編集に失敗しました。';
        }else{
            $message = 'インフォメーションを編集しました。';
        }
        return response()->json(['message' => $message]);
    }
}
