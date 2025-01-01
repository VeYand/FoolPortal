import {UserData} from 'shared/types'

const getViewableUserName = (user: UserData, shouldShowPatronymic: boolean = true) => (
	[user.firstName, user.lastName, shouldShowPatronymic && user.patronymic].filter(Boolean).join(' ')
)

export {
	getViewableUserName,
}