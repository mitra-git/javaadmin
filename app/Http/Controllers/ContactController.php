<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index(){
        $contact = Contact::all();
        $contact = Contact::paginate(8);
        return view('contact.index', compact('contact'));
    }

    public function create(){
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'subject' => 'required',
            'handphone' => 'required',
            'message' => 'required|string',
        ], [
            'name.required' => 'Name is required.',
            'subject.required' => 'Subject is required.',
            'handphone.required' => 'Handphone is required.',
            'message.required' => 'Message is required.',
            'message.string' => 'Message must be a string.',
        ]);
    
        $input = $request->all();
    
        Contact::create($input);
    
        return redirect()->route('contact.index')->with(['success' => 'Data successfully saved!']);
    }

    public function show(string $id){
        $contact = Contact::findOrFail($id);

        return view('contact.show', compact('contact'));
    }

    public function edit(Contact $contact){
        return view('contact.update', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'handphone' => 'required',
            'message' => 'required|string',
        ]);

        $input = $request->all();

        $contact->update($input);

        return redirect()->route('contact.index')
            ->with('success', 'Contact updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contact.index')
            ->with('success', 'Contact deleted successfully');
    }
}
