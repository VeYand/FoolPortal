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
	password?: string,
}

type GroupData = {
	groupId: string,
	name: string,
}

type SubjectData = {
	subjectId: string,
	name: string,
}

type LocationData = {
	locationId: string,
	name: string,
}

type TeacherSubjectData = {
	teacherSubjectId: string,
	subjectId: string,
	teacherId: string,
}

export {
	type UserData,
	USER_ROLE,
	type GroupData,
	type SubjectData,
	type LocationData,
	type TeacherSubjectData,
}