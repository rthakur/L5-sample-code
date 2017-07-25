<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $properties = Property::paginate(10);
        return view('admin.property.index', [ 'properties' => $properties]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $property = Property::find($id);
        return view('admin.property.edit', [ 'property' => $property ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = validator($request->all(), [
            'subject' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'address' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
      
        if ($validate->fails())
            return back()->withErrors($validate)->withInput();
        
        $property = Property::find($id);
        $language = $request->lang;
        
        $propertyTexts = PropertyTexts::firstOrCreate(['property_id' => $property->id]);
        $propertyTexts->{'subject_' . $language} = $request->subject;
        $propertyTexts->{'description_' . $language} = $request->description;
        $propertyTexts->save();
        
        $property->price = $request->price;
        $property->address = $request->address;
        $property->geo_lat =  $request->latitude;
        $property->geo_lng = $request->longitude;
        $property->save();
        return redirect($language.'/admin/property');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Property::find($id)->delete();
        return redirect('/admin/property');
    }
}
