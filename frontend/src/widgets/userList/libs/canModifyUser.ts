import {USER_ROLE, UserData} from 'shared/types'

const canModifyUser = (currentUser: UserData, userToModify: UserData) => (
	(currentUser.role < userToModify.role || currentUser.userId === userToModify.userId) && currentUser.role !== USER_ROLE.STUDENT && currentUser.role !== USER_ROLE.TEACHER
)

const canDeleteUser = (currentUser: UserData, userToDelete: UserData) => (
	currentUser.role < userToDelete.role && currentUser.role !== USER_ROLE.STUDENT && currentUser.role !== USER_ROLE.TEACHER
)

export {
	canModifyUser,
	canDeleteUser,
}
