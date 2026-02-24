<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\IdCardTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IdCardTemplateController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        // Fetch school specific and system default (school_id is null) templates
        $templates = IdCardTemplate::where('school_id', $schoolId)
            ->orWhereNull('school_id')
            ->get();

        return view('school.id_cards.templates', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'background_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required|in:student,teacher',
            'text_color' => 'nullable|string',
            'name_color' => 'nullable|string',
            'data_color' => 'nullable|string',
            'photo_border_color' => 'nullable|string'
        ]);

        $data = $request->except(['background_image']);
        $data['school_id'] = Auth::user()->school_id;

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('id_cards/templates', 'public');
        }

        IdCardTemplate::create($data);

        return redirect()->back()->with('success', 'Template uploaded successfully.');
    }

    public function update(Request $request, IdCardTemplate $idCardTemplate)
    {
        if ($idCardTemplate->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'background_image_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'text_color' => 'nullable|string',
            'name_color' => 'nullable|string',
            'data_color' => 'nullable|string',
            'photo_border_color' => 'nullable|string'
        ]);

        $data = $request->only(['name', 'text_color', 'name_color', 'data_color', 'photo_border_color']);

        if ($request->hasFile('background_image')) {
            // Delete old
            if ($idCardTemplate->background_image) {
                Storage::disk('public')->delete($idCardTemplate->background_image);
            }
            $data['background_image'] = $request->file('background_image')->store('id_cards/templates', 'public');
        }

        if ($request->hasFile('background_image_back')) {
            // Delete old
            if ($idCardTemplate->background_image_back) {
                Storage::disk('public')->delete($idCardTemplate->background_image_back);
            }
            $data['background_image_back'] = $request->file('background_image_back')->store('id_cards/templates', 'public');
        }

        $idCardTemplate->update($data);

        return redirect()->back()->with('success', 'Template updated successfully.');
    }

    public function destroy(IdCardTemplate $idCardTemplate)
    {
        if ($idCardTemplate->school_id !== Auth::user()->school_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($idCardTemplate->background_image) {
            Storage::disk('public')->delete($idCardTemplate->background_image);
        }

        if ($idCardTemplate->background_image_back) {
            Storage::disk('public')->delete($idCardTemplate->background_image_back);
        }

        $idCardTemplate->delete();

        return redirect()->back()->with('success', 'Template deleted.');
    }
}
