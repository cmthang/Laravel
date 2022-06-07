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
            // $data = (array)$data;

            // $query = DB::table('support_software')->insert([
            //     'software' => $data['software'],
            //     'lable' => $data['lable'],
            //     'value' => $data['value'],
            //     'order_version' => $data['order_version']
            // ]);
        
            $query = SupportSoftware::create($data);

            return $query;
        }

        function updateDatabase($data)
        {
            $data = (array)$data;

            $query = DB::table('support_software')->where('id', $data['support_software_id'])
            ->update([
                'software' => $data['software'],
                'lable' => $data['lable'],
                'value' => $data['value'],
                'order_version' => $data['order_version']
            ]);

            return $query;
        }


        function deleteDatabase($id){

                $query = DB::table('support_software')
                    ->where('id', $id)
                    ->delete();

                return $query;     
        }

    }

?>