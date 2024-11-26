import {UserData} from 'shared/types'
import {USER_ROLE} from 'shared/types/types'

type ViewableData = {
	groupNamesTitle: string,
	groupNames: string,
	role: string,
	name: string,
}

const useViewableData = (user: UserData): ViewableData => {
	const groupNamesTitle = useViewableGroupNamesTitle(user.groupIds)
	const groupNames = useViewableGroupNames(user.groupIds)
	const role = useViewableUserRole(user.role)
	const name = useViewableName(user)

	return {
		groupNamesTitle,
		groupNames,
		role,
		name,
	}
}

const useViewableGroupNames = (groupIds: string[]) => groupIds.join(', ')

const useViewableGroupNamesTitle = (groupIds: string[]) => {
	switch (groupIds.length) {
		case 0:
			return 'Вы не состоите ни в одной группе'
		case 1:
			return 'Вы состоите в группе:'
		default:
			return 'Вы состоите в группах:'
	}
}

const roleLabels: Record<USER_ROLE, string> = {
	STUDENT: 'Студент',
	TEACHER: 'Преподаватель',
	ADMIN: 'Админ',
	OWNER: 'Владелец',
}

const useViewableUserRole = (role: USER_ROLE) => roleLabels[role]

const useViewableName = (user: UserData) => [user.firstName, user.lastName, user.patronymic].filter(Boolean).join(' ')

export {
	useViewableData,
}