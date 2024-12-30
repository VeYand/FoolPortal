const formatDateToISO = (date: Date) => {
	const isoString = date.toISOString()
	const [datePart, timePart] = isoString.split('T')
	// @ts-expect-error
	const [timeWithoutMillis] = timePart.split('.')
	return `${datePart}T${timeWithoutMillis}Z`
}

export {
	formatDateToISO,
}