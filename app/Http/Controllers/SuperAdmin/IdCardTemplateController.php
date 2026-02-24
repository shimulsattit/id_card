<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\IdCardTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IdCardTemplateController extends Controller
{
    public function index()
    {
        $templates = IdCardTemplate::whereNull('school_id')->latest()->get();
        return view('admin.id_card_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.id_card_templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'background_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'background_image_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'text_color' => 'nullable|string|max:7',
            'name_color' => 'nullable|string|max:7',
            'data_color' => 'nullable|string|max:7',
            'photo_border_color' => 'nullable|string|max:7',
            'type' => 'required|in:student,teacher',
        ]);

        $data = $request->except(['background_image', 'background_image_back']);
        $data['school_id'] = null; // Global template

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('id_card_templates', 'public');
        }
        if ($request->hasFile('background_image_back')) {
            $data['background_image_back'] = $request->file('background_image_back')->store('id_card_templates', 'public');
        }

        IdCardTemplate::create($data);

        return redirect()->route('admin.id-card-templates.index')->with('success', 'Template created successfully.');
    }

    public function edit(IdCardTemplate $idCardTemplate)
    {
        return view('admin.id_card_templates.edit', compact('idCardTemplate'));
    }

    public function update(Request $request, IdCardTemplate $idCardTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'background_image_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'text_color' => 'nullable|string|max:7',
            'name_color' => 'nullable|string|max:7',
            'data_color' => 'nullable|string|max:7',
            'photo_border_color' => 'nullable|string|max:7',
            'type' => 'required|in:student,teacher',
        ]);

        $data = $request->except(['background_image', 'background_image_back']);

        if ($request->hasFile('background_image')) {
            if ($idCardTemplate->background_image) {
                Storage::disk('public')->delete($idCardTemplate->background_image);
            }
            $data['background_image'] = $request->file('background_image')->store('id_card_templates', 'public');
        }
        if ($request->hasFile('background_image_back')) {
            if ($idCardTemplate->background_image_back) {
                Storage::disk('public')->delete($idCardTemplate->background_image_back);
            }
            $data['background_image_back'] = $request->file('background_image_back')->store('id_card_templates', 'public');
        }

        $idCardTemplate->update($data);

        return redirect()->route('admin.id-card-templates.index')->with('success', 'Template updated successfully.');
    }

    public function destroy(IdCardTemplate $idCardTemplate)
    {
        if ($idCardTemplate->background_image) {
            Storage::disk('public')->delete($idCardTemplate->background_image);
        }
        if ($idCardTemplate->background_image_back) {
            Storage::disk('public')->delete($idCardTemplate->background_image_back);
        }
        $idCardTemplate->delete();
        return redirect()->route('admin.id-card-templates.index')->with('success', 'Template deleted.');
    }
}
