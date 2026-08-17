export const raceBonusTable =  {
	human: { strength: 1, spirit: 0, dexterity: 0, intellect: 1, concentration: 0, endurance: 2, reflex: 0, moveBonus: 3 },
	dwarf: { strength: 2, spirit: 0, dexterity: 2, intellect: -1, concentration: 1, endurance: 1, reflex: -1, moveBonus: 2 },
	elf: { strength: -1, spirit: 1, dexterity: 1, intellect: 1, concentration: 0, endurance: -1, reflex: 1, moveBonus: 4 },
	lizardman: { strength: 2, spirit: 1, dexterity: 0, intellect: 0, concentration: 1, endurance: 0, reflex: 0, moveBonus: 2 },
	halfling: { strength: -1, spirit: 2, dexterity: 1, intellect: 0, concentration: -1, endurance: 0, reflex: 2, moveBonus: 3 },
	darkElf: { strength: 0, spirit: 2, dexterity: 1, intellect: 1, concentration: 0, endurance: -1, reflex: 1, moveBonus: 3 },         // 闇人（ダークエルフ）
	lycanthrope: { strength: 1, spirit: 0, dexterity: 1, intellect: 0, concentration: 0, endurance: 1, reflex: 0, moveBonus: 3 },     // 獣憑き（ライカンスロープ）
	martialBeastman: { strength: 2, spirit: 0, dexterity: 1, intellect: 0, concentration: 1, endurance: 0, reflex: -1, moveBonus: 3 }, // 格闘獣人
	bruteBeastman: { strength: 1, spirit: 1, dexterity: 0, intellect: 0, concentration: 1, endurance: 2, reflex: -1, moveBonus: 2 },   // 剛力獣人
	agileBeastman: { strength: 1, spirit: -1, dexterity: 1, intellect: 0, concentration: 0, endurance: 0, reflex: 1, moveBonus: 4 },   // 俊敏獣人
	sensoryBeastman: { strength: -1, spirit: 0, dexterity: 1, intellect: 1, concentration: 1, endurance: 0, reflex: 1, moveBonus: 3 }  // 知覚獣人
}