data() {
	return {
		dice: Array(7).fill(1), // 出目（1〜3）
		abilities: {
			strength: 0,
			spirit: 0,
			dexterity: 0,
			intellect: 0,
			concentration: 0,
			endurance: 0,
			reflex: 0
		},
		race: '', // 種族選択
		raceBonusTable: {
			human: { strength: 1, spirit: 0, dexterity: 0, intellect: 1, concentration: 0, endurance: 2, reflex: 0 },
			dwarf: { strength: 2, spirit: 0, dexterity: 2, intellect: -1, concentration: 1, endurance: 1, reflex: -1 },
			elf: { strength: -1, spirit: 1, dexterity: 1, intellect: 1, concentration: 0, endurance: -1, reflex: 1 },
			lizardman: { strength: 2, spirit: 1, dexterity: 0, intellect: 0, concentration: 1, endurance: 0, reflex: 0 },
			halfling: { strength: -1, spirit: 2, dexterity: 1, intellect: 0, concentration: -1, endurance: 0, reflex: 2 },
			darkElf: { strength: 0, spirit: 2, dexterity: 1, intellect: 1, concentration: 0, endurance: -1,  reflex: 1 },         // 闇人（ダークエルフ）
			lycanthrope: { strength: 1, spirit: 0, dexterity: 1, intellect: 0, concentration: 0, endurance: 1, reflex: 0 },     // 獣憑き（ライカンスロープ）
			martialBeastman: { strength: 2, spirit: 0, dexterity: 1, intellect: 0, concentration: 1, endurance: 0, reflex: -1 }, // 格闘獣人
			bruteBeastman: { strength: 1, spirit: 1, dexterity: 0, intellect: 0, concentration: 1, endurance: 2, reflex: -1 },   // 剛力獣人
			agileBeastman: { strength: 1, spirit: -1, dexterity: 1, intellect: 0, concentration: 0, endurance: 0, reflex: 1 },   // 俊敏獣人
			sensoryBeastman: { strength: -1, spirit: 0, dexterity: 1, intellect: 1, concentration: 1, endurance: 0, reflex: 1 }  // 知覚獣人

		},
		bonusTarget: '', // +1対象
		beginnerReliefTarget: '', // 初心者救済対象
		bonusApplied: false,
		reliefApplied: false
	};
},
methods: {
	totalAbility() {
		return Object.values(this.abilities).reduce((sum, val) => sum + val, 0);
	},
	isBeginnerReliefAvailable() {
		return this.totalAbility() <= 15 && !this.reliefApplied;
	},
	rollDice() {
		this.dice = Array.from({ length: 7 }, () => Math.floor(Math.random() * 3) + 1);
	},
	applyRaceBonus() {
		const bonus = this.raceBonusTable[this.race];
		for (let key in this.abilities) {
			this.abilities[key] += bonus[key] || 0;
		}
	},
	checkBeginnerRelief() {
		const total = Object.values(this.abilities).reduce((sum, val) => sum + val, 0);
		if (total <= 15 && this.beginnerReliefTarget && !this.reliefApplied) {
			this.abilities[this.beginnerReliefTarget] = 3;
			this.reliefApplied = true;
		}
	},
	applyBonus() {
		if (this.bonusTarget && !this.bonusApplied) {
			this.abilities[this.bonusTarget]++;
			this.bonusApplied = true;
		}
	}
}
