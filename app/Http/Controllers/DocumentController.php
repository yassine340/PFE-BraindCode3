<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx|max:2048',
            'formation_id' => 'required|exists:formations,id'
        ]);

        // Get the file
        $file = $request->file('file');

        // Generate a unique name for the file
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Move the file to the storage directory
        $file->move(storage_path('app/public/documents'), $fileName);

        // Create a new document record
        $document = Document::create([
            'titre' => $file->getClientOriginalName(),
            'url' => 'documents/' . $fileName,
            'formation_id' => $request->formation_id
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Document uploaded successfully.',
            'document' => $document
        ]);
    }
    public function getDocuments($formation_id)
    {
        // Get the documents for the given formation
        $documents = Document::where('formation_id', $formation_id)->get();

        // Return the documents
        return response()->json($documents);
    }
    public function getAllDocuments()
{
    $documents = Document::all();

    return response()->json($documents);
}

}
