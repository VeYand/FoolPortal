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

type CourseData = {
	courseId: string,
	teacherSubjectId: string,
	groupId: string,
}

type LessonData = {
	lessonId: string,
	date: Date,
	startTime: number,
	duration: number,
	teacherId: string,
	subjectId: string,
	groupId: string,
	courseId: string,
	attachmentIds: string[],
	locationId: string,
	description?: string,
}

type AttachmentData = {
	attachmentId: string,
	name: string,
	extension: string,
	description?: string,
}

export {
	type UserData,
	USER_ROLE,
	type GroupData,
	type SubjectData,
	type LocationData,
	type TeacherSubjectData,
	type CourseData,
	type LessonData,
	type AttachmentData,
}