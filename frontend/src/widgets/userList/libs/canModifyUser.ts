import {USER_ROLE, UserData} from 'shared/types'

const canModifyUser = (currentUser: UserData, userToModify: UserData) => {
	if (currentUser.role === USER_ROLE.STUDENT || currentUser.role === USER_ROLE.TEACHER) {
		return false
	}

	if (currentUser.role === USER_ROLE.ADMIN) {
		if (userToModify.role === USER_ROLE.STUDENT || userToModify.role === USER_ROLE.TEACHER) {
			return true
		}
		return currentUser.userId === userToModify.userId && userToModify.role === USER_ROLE.ADMIN
	}

	return currentUser.role === USER_ROLE.OWNER
}

const canDeleteUser = (currentUser: UserData, userToDelete: UserData) => {
	if (currentUser.role === USER_ROLE.STUDENT || currentUser.role === USER_ROLE.TEACHER) {
		return false
	}

	if (currentUser.role === USER_ROLE.ADMIN) {
		return userToDelete.role === USER_ROLE.STUDENT || userToDelete.role === USER_ROLE.TEACHER
	}

	return currentUser.role === USER_ROLE.OWNER && userToDelete.userId !== currentUser.userId
}

export {
	canModifyUser,
	canDeleteUser,
}
