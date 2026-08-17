export function rollDice() {
	return Array.from(
		{ length: 7 },
		() => Math.floor(Math.random() * 3) + 1
	);
}

export function rollOriginDice() {
	return [
		Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
		Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
		Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1
	];
}

export function rollParameterDice() {
	return [
		Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
		Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1,
		Math.floor(Math.random() * 6) + 1 + Math.floor(Math.random() * 6) + 1
	];
}