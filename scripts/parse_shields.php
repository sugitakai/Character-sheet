<?php

$file = 'database\data\盾.txt';

echo "読み込みファイル: " . realpath($file) . PHP_EOL;

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$output = [];

for ($i = 0; $i < count($lines); $i += 4) {

	// 1行目：名前・価格
	preg_match(
		'/【\s*(.+?)\s*】\s*[¥\\\\](\d+|非売品)/u', 
		$lines[$i], 
		$m1
	);

	$name = $m1[1] ?? '';
	$price = isset($m1[2]) && is_numeric($m1[2])
	? (int)$m1[2]
	: 0;

    // 2行目：属性・盾受け修正・盾受け値
	preg_match(
		'/属性：【\s*(.+?)\s*】盾受け修正：【\s*(\d+)\s*】盾受け値：【\s*([\-0-9]+)\s*】/u',
		$lines[$i + 1],
		$m2
	);

	$armor_type = $m2[1] ?? '';
	$block_modifier = (int)($m2[2] ?? 0);
	$armor = (int)($m2[3] ?? 0);

    // 3行目：隠密性・技能修正
    preg_match(
        '/隠密性：【\s*(.+?)\s*／\s*([\-0-9]+)\s*】\s*技能修正：【\s*([\-0-9]+)\s*】/u',
        $lines[$i + 2],
        $m3
    );

	$stealth = $m3[1] ?? '';
	$stealth_modifier = (int)($m3[2] ?? 0);
	$skill_modifier = (int)($m3[3] ?? 0);

	// 4行目：効果
	preg_match(
		'/効果：(.*?)(?:／参照P+)?\s*$/u',
		$lines[$i + 3],
		$m4
	);
	
	$effect = trim($m4[1] ?? '');

	$output[] = [
		'name' => $name,
		'reading' => '',
		'armor_type' => $armor_type,
		'armor' => $armor,
		'block_modifier' => $block_modifier,
		'price' => $price,
        'stealth' => $stealth,
        'stealth_modifier' => $stealth_modifier,
        'skill_modifier' => $skill_modifier,
		'effect' => $effect,
        'reference_page' => '参照P',
	];
}

file_put_contents(
	'shields.json',
	json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
