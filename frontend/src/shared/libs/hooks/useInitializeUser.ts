import {userEntitySlice} from 'entities/user'
import {useCallback} from 'react'
import {useLazyGetLoggedUser} from 'shared/libs/query'
import {remapApiUserToUserData} from 'shared/libs/remmapers'
import {useAppDispatch, useAppSelector} from 'shared/redux'

type UseInitializeUserReturnType = {
	isLoading: boolean,
	initialize: () => void,
}

const useInitializeUser = (): UseInitializeUserReturnType => {
	const dispatch = useAppDispatch()
	const loadingState = useAppSelector(state => state.userEntity.loading)
	const [getLoggedUser, {isLoading, isFetching}] = useLazyGetLoggedUser()

	const initialize = useCallback(async () => {
		dispatch(userEntitySlice.actions.setLoading(true))

		const response = await getLoggedUser({})

		if (response.isError) {
			dispatch(userEntitySlice.actions.setLoading(false))
			return
		}

		if (response.data) {
			const user = remapApiUserToUserData(response.data)
			dispatch(userEntitySlice.actions.setUser(user))
		}
	}, [dispatch, getLoggedUser])

	return {isLoading: isLoading || isFetching || loadingState, initialize}
}


export {
	useInitializeUser,
}