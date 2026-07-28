<?php

namespace App\Http\Controllers;

abstract class Controller
{
	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string',
			'race' => 'required|string',
			'abilities' => 'required|array',
			'abilities.strength' => 'required|integer',
			'abilities.spirit' => 'required|integer',
			'abilities.dexterity' => 'required|integer',
			'abilities.intellect' => 'required|integer',
			'abilities.concentration' => 'required|integer',
			'abilities.endurance' => 'required|integer',
			'abilities.reflex' => 'required|integer',
		]);

		Character::create([
			'name' => $validated['name'],
			'race' => $validated['race'],
			'abilities' => json_encode($validated['abilities']),
		]);

		return redirect()->route('characters.index');
	}
}
