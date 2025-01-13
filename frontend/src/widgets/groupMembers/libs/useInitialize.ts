import {useCallback, useEffect, useState} from 'react'
import {
	useLazyListGroups,
	useLazyListUsers,
} from 'shared/libs/query'
import {GroupData, UserData} from 'shared/types'
import {remapApiGroupsToGroupsList, remapApiUsersToUsersList} from '../../../shared/libs/remmapers'

type UseInitializeReturns = {
	loading: boolean,
	users: UserData[],
	groups: GroupData[],
}

const useInitialize = (): UseInitializeReturns => {
	const [listUsers] = useLazyListUsers()
	const [listGroups] = useLazyListGroups()

	const [loading, setLoading] = useState(true)
	const [users, setUsers] = useState<UserData[]>([])
	const [groups, setGroups] = useState<GroupData[]>([])

	const fetchData = useCallback(async () => {
		try {
			setLoading(true)
			const [usersResponse, groupsResponse]
				= await Promise.all([
					listUsers({}),
					listGroups({}),
				])

			setUsers(remapApiUsersToUsersList(usersResponse.data?.users.users ?? []))
			setGroups(remapApiGroupsToGroupsList(groupsResponse.data?.groups ?? []))
		}
		catch (error) {
			console.error(`Error fetching data: ${error}`)
		}
		finally {
			setLoading(false)
		}
	}, [listUsers, listGroups])

	useEffect(() => {
		fetchData()
	}, [fetchData])

	return {
		loading,
		users,
		groups,
	}
}

export {
	useInitialize,
}