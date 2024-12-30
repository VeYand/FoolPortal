const getColorByLessonType = (type: string) => {
	switch (type) {
		case 'lecture':
			return '#FFD700'
		case 'lab':
			return '#98FB98'
		case 'practice':
			return '#F08080'
		default:
			return '#FFFFFF'
	}
}

export {
	getColorByLessonType,
}