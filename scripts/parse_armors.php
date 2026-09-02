<?php

$lines = file('armors.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$output = [];

for ($i = 0; $i < count($lines); $i += 4) {

	// 1行目：名前・価格
	preg_match('/【\s*(.+?)\s*】\\\(\d+)/u', $lines[$i], $m1);
	$name = $m1[1] ?? '';
	preg_match('/\\\(\d+)/u', $lines[$i], $mPrice);
	$price = (int)($mPrice[0] ?? 0);

	// 2行目：属性・装甲値・回避値補正
	preg_match('/属性：【\s*(.+?)\s*】装甲値：【\s*(\d+)\s*】回避値補正：【\s*([\-0-9]+)\s*】/u', $lines[$i + 1], $m2);
	$armor_type = $m2[1] ?? '';
	$armor = (int)($m2[2] ?? 0);
	$evasion_modifier = (int)($m2[3] ?? 0);

	// 3行目：移動力修正・隠密性
	preg_match('/移動力修正：【\s*([\-0-9]+)\s*】隠密性：【\s*(.+?)／([\-0-9]+)\s*】/u', $lines[$i + 2], $m3);
	$movement_modifier = (int)($m3[1] ?? 0);
	$stealth = $m3[2] ?? '';
	$stealth_modifier = (int)($m3[3] ?? 0);

	// 4行目：技能修正・効果
	preg_match('/技能修正：【\s*([\-0-9]+)\s*】効果：(.+?)／参照P/u', $lines[$i + 3], $m4);
	$skill_modifier = (int)($m4[1] ?? 0);
	$effect = trim($m4[2] ?? '');
	$reference_page = '参照P';

	$output[] = [
		'name' => $name,
		'reading' => '',
		'armor_type' => $armor_type,
		'weight_type' => '',
		'weight' => 0,
		'encumbrance_limit' => 0,
		'price' => $price,
		'attribute' => $armor_type,
		'armor' => $armor,
		'evasion_modifier' => $evasion_modifier,
		'movement_modifier' => $movement_modifier,
		'stealth' => $stealth,
		'stealth_modifier' => $stealth_modifier,
		'skill_modifier' => $skill_modifier,
		'effect' => $effect,
		'reference_page' => $reference_page,
	];
}

file_put_contents(
	'armors.json',
	json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
