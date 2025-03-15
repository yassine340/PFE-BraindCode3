<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Util\Http\Downloader;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function upload(Request $request)
{
    // Validate the request
    $request->validate([
        'titre' => 'required|string|max:255',
        'document' => 'required|file|mimes:pdf,docx,jpg,png|max:10240', // Adjust the MIME types and max size as needed
    ]);

    // Get the uploaded file
    $file = $request->file('document');

    // Generate a unique filename using current timestamp
    $filename = time() . '.' . $file->getClientOriginalExtension();

    // Move the file to the 'public/documents' folder
    $file->move(public_path('documents'), $filename);

    // Create a new document record
    $document = new Document();
    $document->titre = $request->titre;
    $document->url = 'documents/' . $filename;  // Store the relative file path
    $document->save();

    // Return a success response
    return response()->json(['success' => 'Document uploaded successfully']);
}

    

public function showFiles()
{
    $data = Document::all(); // Retrieve all documents from the database
    return view('documents.list', compact('data')); // Pass the data to the view
}


   

public function download($url)
{
    // Construct the full file path
    $filePath = public_path('documents/' . $url);

    // If file exists, return it for download
    if (file_exists($filePath)) {
        return Response::download($filePath);
    } else {
        // If file doesn't exist, return an error
        return response()->json(['error' => 'File not found'], 404);
    }
}


    
}
