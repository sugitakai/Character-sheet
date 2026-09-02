<?php

$lines = file('weapons.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$output = [];

for ($i = 0; $i < count($lines); $i += 6) {

	// 1行目：名前・読み・価格
	preg_match('/【\s*(.+?)\((.+?)\)\s*】￥(\d+)/u', $lines[$i], $m1);
	$name = $m1[1] ?? '';
	$reading = $m1[2] ?? '';
	$price = (int)($m1[3] ?? 0);

	// 2行目：武器種／重量種
	preg_match('/【\s*(.+?)／(.+?)\s*】/u', $lines[$i + 1], $m2);
	$weapon_type = $m2[1] ?? '';
	$weight_type = $m2[2] ?? '';

	// 3行目：用途／属性／射程
	preg_match('/【\s*(.+?)／(.+?)／(.+?)\s*】/u', $lines[$i + 2], $m3);
	$usage = $m3[1] ?? '';
	$attribute = $m3[2] ?? '';
	$range = $m3[3] ?? '';

	// 4行目：命中値修正
	preg_match('/命中値修正：【\s*([\-0-9]+)\s*】/u', $lines[$i + 3], $m4);
	$hit_modifier = (int)($m4[1] ?? 0);

	// 5行目：基本ダメージ／職業修正
	preg_match('/基本ダメージ\s*(.+?)\s*／職業修正：【\s*(\d+)\s*】/u', $lines[$i + 4], $m5);
	$base_damage = $m5[1] ?? '';
	$job_modifier = (int)($m5[2] ?? 0);

	// 6行目：効果／参照P
	preg_match('/効果：(.+?)／参照P/u', $lines[$i + 5], $m6);
	$effect = trim($m6[1] ?? '');
	$reference_page = '参照P';

	$output[] = [
		'name' => $name,
		'reading' => $reading,
		'weapon_type' => $weapon_type,
		'weight_type' => $weight_type,
		'weight' => 0,
		'encumbrance_limit' => 0,
		'price' => $price,
		'attribute' => $attribute,
		'usage' => $usage,
		'range' => $range,
		'hit_modifier' => $hit_modifier,
		'base_damage' => $base_damage,
		'job_modifier' => $job_modifier,
		'effect' => $effect,
		'reference_page' => $reference_page,
	];
}

file_put_contents(
	'weapons.json',
	json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
