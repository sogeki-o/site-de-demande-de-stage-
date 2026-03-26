<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequiredDocument;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class RequiredDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $documents = RequiredDocument::orderBy('sort_order')->orderBy('name')->paginate(15);
        return view('admin.required_documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.required_documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $document = RequiredDocument::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLogger::log('admin.required_document.create', 'RequiredDocument', $document->id, 'Creation d\'un document requis', [
            'name' => $document->name,
        ]);

        return redirect()->route('admin.required-documents.index')->with('success', 'Document requis cree avec succes.');
    }

    public function edit(RequiredDocument $requiredDocument)
    {
        return view('admin.required_documents.edit', compact('requiredDocument'));
    }

    public function update(Request $request, RequiredDocument $requiredDocument)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $requiredDocument->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLogger::log('admin.required_document.update', 'RequiredDocument', $requiredDocument->id, 'Mise a jour d\'un document requis', [
            'name' => $requiredDocument->name,
        ]);

        return redirect()->route('admin.required-documents.index')->with('success', 'Document requis mis a jour.');
    }

    public function destroy(RequiredDocument $requiredDocument)
    {
        $documentId = $requiredDocument->id;
        $documentName = $requiredDocument->name;
        $requiredDocument->delete();

        AuditLogger::log('admin.required_document.delete', 'RequiredDocument', $documentId, 'Suppression d\'un document requis', [
            'name' => $documentName,
        ]);

        return redirect()->route('admin.required-documents.index')->with('success', 'Document requis supprime.');
    }
}
