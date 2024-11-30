import {USER_ROLE} from 'shared/types'

const roleLabels: Record<USER_ROLE, string> = {
	STUDENT: 'Студент',
	TEACHER: 'Преподаватель',
	ADMIN: 'Админ',
	OWNER: 'Владелец',
}

const getViewableUserRole = (role: USER_ROLE) => roleLabels[role]

export {
	getViewableUserRole,
}