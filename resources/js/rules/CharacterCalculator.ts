export const assignedParameter= {
	vitality: null,
	move: null,
	spell: null
    }

export function calculateVitality() {
	const base = abilities.strength + abilities.spirit + abilities.endurance;
	const dice = assignedParameter.vitality || 0;
	return base + dice;
}

export function calculateVitality2() {
	return vitality * 2;
}

export function calculateMove() {
	const dice = assignedParameter.move || 0;
	const bonus = raceBonusTable[race]?.moveBonus || 1;
	return dice * bonus;
}

export function calculateSpellUses() {
	const dice = assignedParameter.spell || 0;
	let base = 0;
	if (dice <= 6) base = 0;
	else if (dice <= 9) base = 1;
	else if (dice <= 11) base = 2;
	else base = 3;
	return base + spellSkillBonus;
}

export function calculateResistBase() {
	return abilities.spirit + abilities.reflex + adventurerLevel + skillBonus;
}