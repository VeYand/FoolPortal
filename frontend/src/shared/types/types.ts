enum USER_ROLE {
	OWNER = 'OWNER',
	ADMIN = 'ADMIN',
	TEACHER = 'TEACHER',
	STUDENT = 'STUDENT',
}

type UserData = {
	userId: string,
	firstName: string,
	lastName: string,
	patronymic?: string,
	role: USER_ROLE,
	imageSrc?: string,
	email: string,
	groupIds: string[],
}

type SubjectData = {
	subjectId: string,
	name: string,
}

export {
	type UserData,
	USER_ROLE,
	type SubjectData,
}