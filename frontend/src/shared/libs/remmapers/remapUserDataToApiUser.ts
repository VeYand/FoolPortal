import {
	UserDataRoleEnum as ApiUserRole,
} from 'shared/api'
import {USER_ROLE} from 'shared/types'

const remapUserRoleToApiUserRole = (role: USER_ROLE): ApiUserRole => {
	switch (role) {
		case USER_ROLE.OWNER:
			return ApiUserRole.NUMBER_1
		case USER_ROLE.ADMIN :
			return ApiUserRole.NUMBER_2
		case USER_ROLE.TEACHER:
			return ApiUserRole.NUMBER_3
		case USER_ROLE.STUDENT:
			return ApiUserRole.NUMBER_4
		default:
			throw new Error('Invalid user role')
	}
}

export {
	remapUserRoleToApiUserRole,
}