import {UserData as ApiUserData, UserDataRoleEnum as ApiUserRole} from 'shared/api'
import {UserData, USER_ROLE} from 'shared/types'

const remapApiUserRoleToUserRole = (role: ApiUserRole): USER_ROLE => {
	switch (role) {
		case ApiUserRole.NUMBER_1:
			return USER_ROLE.OWNER
		case ApiUserRole.NUMBER_2:
			return USER_ROLE.ADMIN
		case ApiUserRole.NUMBER_3:
			return USER_ROLE.TEACHER
		case ApiUserRole.NUMBER_4:
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

const remapApiUsersToUsersList = (users: ApiUserData[]): UserData[] => (
	users.map(remapApiUserToUserData, users)
)

export {
	remapApiUserToUserData,
	remapApiUsersToUsersList,
}