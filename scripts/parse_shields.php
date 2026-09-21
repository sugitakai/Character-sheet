<?php

$lines = file('database\data\盾.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$output = [];

for ($i = 0; $i < count($lines); $i += 3) {

	// 1行目：名前・価格
	preg_match('/【\s*(.+?)\s*】￥(\d+)/u', $lines[$i], $m1);
	$name = $m1[1] ?? '';
	$price = (int)($m1[2] ?? 0);

	// 2行目：属性・装甲値・受け流し
	preg_match('/属性：【\s*(.+?)\s*】装甲値：【\s*(\d+)\s*】受け流し：【\s*([\-0-9]+)\s*】/u', $lines[$i + 1], $m2);
	$armor_type = $m2[1] ?? '';
	$armor = (int)($m2[2] ?? 0);
	$block_modifier = (int)($m2[3] ?? 0);

	// 3行目：効果
	preg_match('/効果：(.+?)／参照P/u', $lines[$i + 2], $m3);
	$effect = trim($m3[1] ?? '');
	$reference_page = '参照P';

	$output[] = [
		'name' => $name,
		'reading' => '',
		'armor_type' => $armor_type,
		'armor' => $armor,
		'block_modifier' => $block_modifier,
		'price' => $price,
		'effect' => $effect,
		'reference_page' => $reference_page,
	];
}

file_put_contents(
	'shields.json',
	json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
