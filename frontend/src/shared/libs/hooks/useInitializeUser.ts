import {userEntitySlice} from 'entities/user'
import {useEffect} from 'react'
import {useLazyGetLoggedUser} from 'shared/libs/query'
import {remapApiUserToUserData} from 'shared/libs/remmapers'
import {useAppDispatch} from 'shared/redux'

const useInitializeUser = () => {
	const dispatch = useAppDispatch()
	const [getLoggedUser, {isLoading, isFetching}] = useLazyGetLoggedUser()

	useEffect(() => {
		const initializeUser = async () => {
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
		}

		initializeUser()
	}, [dispatch, getLoggedUser])

	return {isLoading: isLoading || isFetching}
}


export {
	useInitializeUser,
}