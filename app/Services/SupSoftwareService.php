<?php

    namespace App\Services;

    use Illuminate\Support\Facades\DB; 
    use App\Models\SupportSoftware;

    class SupSoftwareService 
    {

        public function __construct()
        {       
        }

        function insertDatabase($data)
        {
        
            $query = SupportSoftware::create($data);

            return $query;
        }

        function updateDatabase($data, $id)
        {
            $record = SupportSoftware::find($id);

            $record->update($data);

            return $record;
        }


        function deleteDatabase($id){

                $query = DB::table('support_software')
                    ->where('id', $id)
                    ->delete();

                return $query;     
        }

    }

?>