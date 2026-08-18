
export function calculateVitality(abilities, assignedParameter) {
	const base = abilities.strength + abilities.spirit + abilities.endurance;
	const dice = assignedParameter.vitality || 0;
	return base + dice;
}

export function calculateVitality2(vitality) {
	return vitality * 2;
}

export function calculateMove(assignedParameter, raceBonusTable, race) {
	const dice = assignedParameter.move || 0;
	const bonus = raceBonusTable[race]?.moveBonus || 1;
	return dice * bonus;
}

export function calculateSpellUses(assignedParameter, spellSkillBonus) {
	const dice = assignedParameter.spell || 0;
	let base = 0;
	if (dice <= 6) base = 0;
	else if (dice <= 9) base = 1;
	else if (dice <= 11) base = 2;
	else base = 3;
	return base + spellSkillBonus;
}

export function calculateResistBase(abilities, adventurerLevel, skillBonus) {
	return abilities.spirit + abilities.reflex + adventurerLevel + skillBonus;
}