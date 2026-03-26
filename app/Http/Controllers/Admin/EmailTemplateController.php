<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $templates = EmailTemplate::orderBy('name')->paginate(15);
        return view('admin.email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.email_templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:email_templates,code',
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $template = EmailTemplate::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLogger::log('admin.email_template.create', 'EmailTemplate', $template->id, 'Creation d\'un modele d\'email', [
            'code' => $template->code,
            'name' => $template->name,
        ]);

        return redirect()->route('admin.email-templates.index')->with('success', 'Modele d\'email cree avec succes.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email_templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:email_templates,code,' . $emailTemplate->id,
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $emailTemplate->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLogger::log('admin.email_template.update', 'EmailTemplate', $emailTemplate->id, 'Mise a jour d\'un modele d\'email', [
            'code' => $emailTemplate->code,
            'name' => $emailTemplate->name,
        ]);

        return redirect()->route('admin.email-templates.index')->with('success', 'Modele d\'email mis a jour.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $templateId = $emailTemplate->id;
        $templateCode = $emailTemplate->code;
        $emailTemplate->delete();

        AuditLogger::log('admin.email_template.delete', 'EmailTemplate', $templateId, 'Suppression d\'un modele d\'email', [
            'code' => $templateCode,
        ]);

        return redirect()->route('admin.email-templates.index')->with('success', 'Modele d\'email supprime.');
    }
}
