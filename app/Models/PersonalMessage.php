<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonalMessage extends Model
{
    protected $table = 'personal_message';

    protected $fillable = ['message', 'to_user_id', 'from_user_id', 'start_date', 'end_date', 'delete_flg'];

    public function addInformation($message, $startDate, $endDate, $to, $from){
        try{
            $result = DB::transaction(function() use ($message, $startDate, $endDate, $to, $from){    
                return $this->create([
                    'message' => $message,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'to_user_id' => $to,
                    'from_user_id' => $from
                    ]);
                });
                
        }catch(\Exception $e){
            \Log::debug($e);
            $result = false;
        }
        return $result;
    }

    public function deleteInformation($id){
        try{

            $info = $this->findOrFail($id);
            \Log::debug($info);
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

    public function editInformation($id, $message, $startDate, $endDate, $to){
        try{
            $info = $this->findOrFail($id);

            $result = DB::transaction(function() use ($info, $message, $startDate, $endDate, $to){
                $info->message = $message;
                $info->start_date = $startDate;
                $info->end_date = $endDate;
                $info->to_user_id = $to;
                return $info->save();
            });

        }catch(\Exception $e){
            \Log::debug($e);
            return false;
        }
        return $result;
    }
}
