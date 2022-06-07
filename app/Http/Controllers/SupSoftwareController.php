<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportSoftware;
use App\Services\SupSoftwareService;


class SupSoftwareController extends Controller
{

        //View List 
        public function index()
        {
            return view('index.support_software');
        }
        
        //View Create
        public function create()
        {
            $support_software = new SupportSoftware();

            return view ('index.edit_support_software', compact('support_software'));
        }

        //View Edit By ID 
        public function edit(Request $request, $id)
        {
            $support_software = SupportSoftware::find($id);

            $support_software = (object)$support_software;

            return view ('index.edit_support_software', compact('support_software','id'));
        }
        
        //Method POST - Update Database
        public function update(Request $request, $id)
        {        
            $support_software = SupportSoftware::find($id);
    
            $validator = [];
            $validator['software'] = 'required';
            $validator['lable'] = 'required';
            $validator['value'] = 'required';
            $validator['order_version'] = 'required';

            $this -> validate($request, $validator);

            $data = [];
            $data['software'] = $request -> get('software');
            $data['lable'] = $request -> get('lable');
            $data['value'] = $request -> get('value');
            $data['order_version'] = $request -> get('order_version');
            $data['support_software_id'] = $support_software->id;
            
                
            $services = new SupSoftwareService(); 

            $services -> updateDatabase($data);

            return redirect('/supported-software');
        }


        //Method POST - Insert Database
        public function store(Request $request)
        { 
            $validator = [];
            $validator['software'] = 'required';
            $validator['lable'] = 'required';
            $validator['value'] = 'required';
            $validator['order_version'] = 'required';
    
            $this -> validate($request, $validator);
    
            $data = [];
            $data['software'] = $request -> get('software');
            $data['lable'] = $request -> get('lable');
            $data['value'] = $request -> get('value');
            $data['order_version'] = $request -> get('order_version');
                

            $services = new SupSoftwareService(); 

            $services -> insertDatabase($data);

            return redirect('/supported-software');           
        }

        
        //Method DELETE - Delete Database 
        public function destroy($id)
        {
            $services = new SupSoftwareService(); 

            $services -> deleteDatabase($id);

            return view ('index.support_software');
        }

}

