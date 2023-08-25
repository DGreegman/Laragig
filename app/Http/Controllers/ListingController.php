<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // show all listings here 

    public function index() {
        
        return view('listings.index', [
            
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(2)
            
           ]);
    }


    // show a particular listing
    public function show(Listing $listing) {
        
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    public function create(){
        return view('listings.create');
    }
    public function store(Request $request){
        // dd($request->file('logo'));
        $formFields = $request->validate([
            'title' => 'required',
            'logo' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'email' => 'required',
            'tags' => 'required',
            'description' => 'required',
            'website' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);
        return redirect('/')->with('message', 'Listing Created Successfully');
    }

    // edit function of my listing

    public function edit(Listing $listing) {
        return view('listings.edit', ['listing'=>$listing]);
    }

    public function update(Listing $listing, Request $request){

        // Allowing Logged User to only Perform any action on the Listings
        if ($listing->user_id != auth()->id()) {
           abort(403, 'Unathorized User Can not Perform Any Action...');
        }
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'email' => 'required',
            'tags' => 'required',
            'description' => 'required',
            'website' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        

        $listing->update($formFields);
        return back()->with('message', 'Listing Updated Successfully');

    }

    public function destroy(Listing $listing){
        
        // Allowing Logged User to only Perform any action on the Listings
        if ($listing->user_id != auth()->id()) {
                abort(403, 'Unathorized User Can not Perform Any Action...');
            }
        $listing->delete();
        return redirect('/')->with('message', 'Listing Deleted Successfully');
    }

    // funciton for display of listings
    public function manage(){
        return view('listings.manage', ['listings'=>auth()->user()->listings()->get()]);
    }
}
