<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * 全体インフォメーション　モデル
 */
class GeneralMessage extends Model
{

    protected $table = 'general_message';

    protected $fillable = [
        'message',
        'start_date',
        'end_date',
        'deleted_flg'
    ];
    
    /**
     * 全体インフォメーション登録
     * [addInfo description]
     * @param [string] $message   [description]
     * @param [date] $startDate [description]
     * @param [date] $endDate   [description]
     */
    public function addGeneralInfo($message, $startDate, $endDate){

        try{

            $result = DB::transaction(function() use ($message, $startDate, $endDate){
                
                return $this->create([
                    'message' => $message,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    ]);
                });
                
        }catch(\Exception $e){
            \Log::debug($e);
            $result = false;
        }
        return $result;
    }

    /**
     * 全体インフォメーション削除
     *
     * @param [int] $id
     * @return void
     */
    public function delGeneralInfo($id){
        
        try{

            $info = $this->findOrFail($id);
            
            $result = DB::transaction(function() use ( $info, $id ){
                $info->deleted_flg = 1;
                return $info->save();
            });
                
        }catch(\Exception $e){
            \Log::debug($e);
            return false;
        }
        return $result;
    }

    public function editGeneralInfo($id, $message, $startDate, $endDate){
        try{

            $info = $this->findOrFail($id);
            
            $result = DB::transaction(function() use ( $info, $id, $message, $startDate, $endDate){
                $info->message = $message;
                $info->start_date = $startDate;
                $info->end_date = $endDate;
                return $info->save();
            });
                
        }catch(\Exception $e){
            \Log::debug($e);
            return false;
        }
        return $result;
    }
}
