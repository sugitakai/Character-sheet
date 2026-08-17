export const assignedParameter= {
	vitality: null,
	move: null,
	spell: null
    }
export function vitality() {
	const base = this.abilities.strength + this.abilities.spirit + this.abilities.endurance;
	const dice = this.assignedParameter.vitality || 0;
	return base + dice;
},

export function vitality2() {
	return this.vitality * 2;
},
export function move() {
	const dice = this.assignedParameter.move || 0;
	const bonus = this.raceBonusTable[this.race]?.moveBonus || 1;
	return dice * bonus;
},

export function spellUses() {
	const dice = this.assignedParameter.spell || 0;
	let base = 0;
	if (dice <= 6) base = 0;
	else if (dice <= 9) base = 1;
	else if (dice <= 11) base = 2;
	else base = 3;
	return base + this.spellSkillBonus;
},
export function resistBase() {
	return this.abilities.spirit + this.abilities.reflex + this.adventurerLevel + this.skillBonus;
}