<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Http\Requests\StoreDomain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['domains'] = Domain::latest()->paginate(10);
        return view('domain.index', $data);
    }

    public function create()
    {
        $data['domain'] = null;
        return view('domain.create', $data);
    }

    public function store(StoreDomain $request)
    {
        $domain = new Domain();
        $domain->domain = $request->domain;
        $domain->key = $request->key;
        $domain->coefficient = $request->coefficient;
        $domain->main_button_color = $request->main_button_color;
        $domain->main_button_color_hover = $request->main_button_color_hover;
        $domain->main_button_color_active = $request->main_button_color_active;
        $domain->panel_text_color = $request->panel_text_color;
        $domain->add_button_color = $request->add_button_color;
        $domain->bg_color = $request->bg_color;
        $domain->label_text_color = $request->label_text_color;
        $domain->save();

        return redirect()->route('domain');
    }

    public function edit($id)
    {
        $data['domain'] = Domain::findOrFail($id);
        return view('domain.edit', $data);
    }

    public function update(StoreDomain $request, $id)
    {
        $domain = Domain::findOrFail($id);
        $domain->domain = $request->domain;
        $domain->key = $request->key;
        $domain->coefficient = $request->coefficient;
        $domain->main_button_color = $request->main_button_color;
        $domain->main_button_color_hover = $request->main_button_color_hover;
        $domain->main_button_color_active = $request->main_button_color_active;
        $domain->panel_text_color = $request->panel_text_color;
        $domain->add_button_color = $request->add_button_color;
        $domain->bg_color = $request->bg_color;
        $domain->label_text_color = $request->label_text_color;
        $domain->save();

        return redirect()->route('domain');
    }

    public function delete($id)
    {
        Domain::destroy($id);
        return redirect()->route('domain');
    }
}
