<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportSoftware;
use App\Services\SupSoftwareService;
use App\Http\Requests\StoreSupportSoftwareRequest;

class SupSoftwareController extends Controller
{

    //View List 
    public function index()
    {
        $data = SupportSoftware::all();
        return view('supported_software.index', compact('data'));
    }

    //View Create
    public function create()
    {
        $support_software = new SupportSoftware();

        return view('index.edit_support_software', compact('support_software'));
    }

    //View Edit By ID 
    public function edit(Request $request, $id)
    {
        $support_software = SupportSoftware::find($id);

        $support_software = (object)$support_software;

        return view('index.edit_support_software', compact('support_software', 'id'));
    }

    //Method POST - Update Database
    public function update(StoreSupportSoftwareRequest $request, $id)
    {

        $services = new SupSoftwareService();

        $services->updateDatabase($request->all(), $id);

        return redirect('/supported-software');
    }

    //Method POST - Insert Database
    public function store(StoreSupportSoftwareRequest $request)
    {

        $services = new SupSoftwareService();

        $services->insertDatabase($request->all());

        return redirect('/supported-software');
    }

    //Method DELETE - Delete Database 
    public function destroy($id)
    {
        $services = new SupSoftwareService();

        $services->deleteDatabase($id);

        return redirect()->route('supported-software.index');
    }
}
