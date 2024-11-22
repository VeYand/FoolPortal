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

export {
	type UserData,
	USER_ROLE,
}