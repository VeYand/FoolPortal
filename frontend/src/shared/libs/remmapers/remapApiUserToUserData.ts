import {UserData as ApiUserData, UserDataRoleEnum as ApiUserRole} from 'shared/api'
import {UserData} from 'shared/types'
import {USER_ROLE} from '../../types/types'

const remapApiUserRoleToUserRole = (role: ApiUserRole): USER_ROLE => {
	switch (role) {
		case ApiUserRole.Owner:
			return USER_ROLE.OWNER
		case ApiUserRole.Admin:
			return USER_ROLE.ADMIN
		case ApiUserRole.Teacher:
			return USER_ROLE.TEACHER
		case ApiUserRole.Student:
			return USER_ROLE.STUDENT
		default:
			throw new Error('Invalid user role')
	}
}

const remapApiUserToUserData = (user: ApiUserData): UserData => ({
	userId: user.userId,
	firstName: user.firstName,
	lastName: user.lastName,
	patronymic: user.patronymic ?? undefined,
	role: remapApiUserRoleToUserRole(user.role),
	imageSrc: user.imageSrc ?? undefined,
	email: user.email,
	groupIds: user.groupIds,
})

export {
	remapApiUserToUserData,
}