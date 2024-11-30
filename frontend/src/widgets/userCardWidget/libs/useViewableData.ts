import {getViewableUserName} from 'shared/libs'
import {UserData} from 'shared/types'

type ViewableData = {
	groupNamesTitle: string,
	groupNames: string,
	name: string,
}

const useViewableData = (user: UserData): ViewableData => {
	const groupNamesTitle = useViewableGroupNamesTitle(user.groupIds)
	const groupNames = useViewableGroupNames(user.groupIds)
	const name = getViewableUserName(user)

	return {
		groupNamesTitle,
		groupNames,
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

export {
	useViewableData,
}