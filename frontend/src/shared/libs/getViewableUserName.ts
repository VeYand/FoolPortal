import {UserData} from 'shared/types'

const getViewableUserName = (user: UserData) => [user.firstName, user.lastName, user.patronymic].filter(Boolean).join(' ')

export {
	getViewableUserName,
}