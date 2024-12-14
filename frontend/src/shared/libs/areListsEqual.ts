const areListsEqual = <T extends string>(list1: T[], list2: T[]) => {
	const set1 = new Set(list1)
	const set2 = new Set(list2)

	if (set1.size !== set2.size) {
		return false
	}

	for (const item of set1) {
		if (!set2.has(item)) {
			return false
		}
	}

	return true
}

export {
	areListsEqual,
}