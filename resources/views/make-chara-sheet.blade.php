<!DOCTYPE html>
<html lang="jp">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<div id="app">
		<male-chara-sheet></male-chara-sheet>
	</div>

	@vite(['resources/js/app.js'])
	<select v-model="race" @change="applyRaceBonus">
		<option value="human">只人</option>
		<option value="dwarf">鉱人</option>
		<option value="elf">森人</option>
		<option value="lizardman">蜥蜴人</option>
		<option value="halfling">圃人</option>
	</select>

	<!-- 割り振りUI -->
	<div v-for="(val, key) in abilities">
		<label>{{ key }}：</label>
		<select v-model="abilities[key]">
			<option v-for="d in dice" :value="d">{{ d }}</option>
		</select>
	</div>

	<!-- 初心者救済 -->
	<div v-if="isBeginnerReliefAvailable()">
		<label>初心者救済：1つの能力値を3に補正</label>
		<select v-model="beginnerReliefTarget">
			<option v-for="(val, key) in abilities" :value="key">{{ key }}</option>
		</select>
		<button @click="checkBeginnerRelief">補正する</button>
	</div>

	<!-- +1処理 -->
	<div v-if="!bonusApplied">
		<label>好きな能力値を+1：</label>
		<select v-model="bonusTarget">
			<option value="strength">体力点</option>
			<option value="spirit">魂魄点</option>
			<option value="dexterity">技量点</option>
			<option value="intellect">知力点</option>
		</select>
		<button @click="applyBonus">+1する</button>
	</div>
	```
	<p>現在の能力値合計：{{ totalAbility() }}</p>

</body>

</html>